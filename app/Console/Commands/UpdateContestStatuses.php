<?php

namespace App\Console\Commands;


use App\Jobs\CountContestResults;
use App\Models\Contest;
use App\Repositories\Contest\ContestsRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateContestStatuses extends Command
{
    protected $signature = 'contests:update-statuses';

    protected $description = 'Update contest statuses and delete unverified contests after start time';

    protected ContestsRepository $contestsRepository;

    public function handle(): void
    {
        $this->contestsRepository = new ContestsRepository();


        $this->updateToActive();
        $this->UpdateToEnded();

        $this->info('Contest statuses updated ');
    }

    public function updateToActive(): void
    {
      $this->contestsRepository->getAllActiveContests()
            ->where('status','!=','active')
            ->update(['status' => 'active']);
    }

    public function UpdateToEnded(): void
    {
        $contests = $this->contestsRepository->getAllEndedContests()->where('status','!=','ended')->get();

        foreach ($contests as $contest) {
                CountContestResults::dispatch($contest);
        }

    }
}
