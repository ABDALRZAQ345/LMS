<?php

namespace App\Jobs;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public function backoff(): int|array
    {
        return [60, 120, 240,60*60*12,60*60*24 ];
    }

    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function handle(): void
    {
        $langMap = [
            'cpp' => 54,
            'python' => 71,
            'java' => 62,
            'csharp' => 51,
        ];

        $problem = $this->submission->problem;

        $sourceCode = base64_encode($this->submission->code);
        $stdin = base64_encode($problem->test_input);

        $response = Http::timeout(60 * 5)->withHeaders([
            'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
            'Accept' => 'application/json',
            'X-RapidAPI-Host' => 'judge0-ce.p.rapidapi.com',
        ])->post('https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=true&wait=true', [
            'language_id' => $langMap[$this->submission->language],
            'source_code' => $sourceCode,
            'stdin' => $stdin,
            'cpu_time_limit' => $problem->time_limit,
            'memory_limit' => $problem->memory_limit * 1024,
        ]);
        Log::channel('verification_code')->info($response);

        $result = $response->json();


        $output = isset($result['stdout']) ? trim(base64_decode($result['stdout'])) : '';
        $expected = trim($problem->expected_output);


        $normalizedOutput = preg_replace('/\s+/', '', $output);
        $normalizedExpected = preg_replace('/\s+/', '', $expected);

        $statusId =isset($result['status']) ? $result['status']['id'] : null;

        Log::channel('verification_code')->info($result);
        if ($statusId === 5) {
            $status = 'time_limit_exceeded';
        }
        elseif ($statusId === 6) {
            $status = 'compile_error';
        } elseif ($statusId === 11) {
            $status = 'runtime_error';
        } elseif (isset($result['stderr'])) {
            $status = 'error';
            $output = 'some thing wrong from our side';
        } elseif ($normalizedOutput === $normalizedExpected) {
            $status = 'accepted';
            $this->updateUserResult($problem);
        } else {
            $status = 'wrong_answer';
        }

        $this->submission->update([
            'status' => $status,
            'output' => $output,
        ]);
    }

    /**
     * @param mixed $problem
     * @return void
     */
    public function updateUserResult(mixed $problem): void
    {
        \DB::table('contest_user')
            ->where('user_id', $this->submission->user_id)
            ->where('contest_id', $problem->contest_id)
            ->update([
                'correct_answers' => \DB::raw('correct_answers + 1'),
                'end_time' => now(),//end time in programming contest is the time for the last submission
            ]);

    }
}
