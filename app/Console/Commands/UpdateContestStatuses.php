<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contest;
use Carbon\Carbon;

class UpdateContestStatuses extends Command
{
    protected $signature = 'contests:update-statuses';
    protected $description = 'Update contest statuses and delete unverified contests after start time';

    public function handle()
    {
        $now = Carbon::now();

        Contest::where('status', 'coming')
            ->where('start_at', '<=', $now)
            ->update(['status' => 'active']);


        Contest::where('status', 'active')
            ->whereRaw("DATE_ADD(start_at, INTERVAL time MINUTE) <= ?", [$now])
            ->update(['status' => 'ended']);

        Contest::where('verified', false)->where('start_at', '<=', $now)
            ->delete();

        \Log::channel('verification_code')->info('Running contest status update at ' . now());

        $this->info('Contest statuses updated and unverified contests deleted.');
    }
}
