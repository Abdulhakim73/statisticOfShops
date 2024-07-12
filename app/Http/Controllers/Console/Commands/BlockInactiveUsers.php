<?php

namespace App\Http\Controllers\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class BlockInactiveUsers extends Command
{
    protected $signature = 'block-inactive-users';
    protected $description = 'Block users who have not logged in within 3 days';

    public function handle(): void
    {
        $threeDaysAgo = Carbon::now()->subDays(3);
        $users = User::all();
        foreach ($users as $user) {
            if ($user->auth >= $threeDaysAgo) {
                $this->info("User ID {$user->id} has a logged-in token created within the last three days.");
            } else {
                $this->info("User ID {$user->id} does not have a logged-in token created within the last three days.");
                $user->status = "blocked";
                $user->update();
            }
        }
    }
}
