<?php

namespace App\Console\Commands;

use App\Jobs\CountQuizContestResults;
use App\Models\Contest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateContestStatuses extends Command
{
    protected $signature = 'contests:update-statuses';

    protected $description = 'Update contest statuses and delete unverified contests after start time';

    public function handle(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $this->updateComingToActive($now);

        $this->UpdateActiveToEnded($now);

        $this->info('Contest statuses updated and unverified contests deleted.');
    }

    public function updateComingToActive(string $now): void
    {
        Contest::where('status', 'coming')->where('start_at', '<=', $now)
            ->update(['status' => 'active']);
    }

    public function UpdateActiveToEnded(string $now): void
    {
        $contests = Contest::where('status', 'active')
            ->whereRaw('DATE_ADD(start_at, INTERVAL time MINUTE) <= ?', [$now])
            ->get();

        foreach ($contests as $contest) {
            if($contest->type=='quiz'){
                CountQuizContestResults::dispatch($contest);
            }
            else {

            }
        }

    }
}
