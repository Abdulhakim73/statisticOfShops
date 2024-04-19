<?php

namespace App\Console\Commands;

use App\Book;
use App\Recent;
use Illuminate\Console\Command;

class AutoDeleteRecent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recent:auto_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-10 day" ));
        Recent::query()->where('created_at','<', $myDate)->delete();

        return 0;
    }
}
