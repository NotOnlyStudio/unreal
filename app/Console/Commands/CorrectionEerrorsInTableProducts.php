<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CorrectionEerrorsInTableProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CorrectErrorsInTable:products';

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
        $product = Product::all();
        foreach ($product as $item){
            $item->update([
                'category_id' =>collect(explode(',',$item->category_id))->unique()->implode(','),
                'subcategory_id' =>collect(explode(',',$item->subcategory_id))->unique()->implode(',')
            ]);
        }
        return 0;
    }
}
