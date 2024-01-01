<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FreeModelsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'free-models:reset';
    protected $signature = 'free:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset to every human field `free_models` in DB';

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
        \App\Models\User::where("id","!=",null)->update([
            "free_models"=>3
        ]);

        \App\Models\User::where("id","!=",null)->update([
            "free_models_downloaded"=>null
        ]);
    }
}
