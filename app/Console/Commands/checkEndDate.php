<?php

namespace App\Console\Commands;

use App\Models\TaskScheduler;
use Illuminate\Console\Command;

class checkEndDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:checkEndDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = TaskScheduler::active()->get();
        $collection=collect($tasks);
        $collection->each(function ($item, $key) {
            $today = date('Y-m-d');
            if ($item->end_date<$today) {
                $item->status = 0;
                $item->save();
            }
        });
        // if ($tasks) {
        //     $today = date('Y-m-d');
        //     foreach ($tasks as $task) {
        //         if ($task->end_date < $today) {
        //             $task->status = 0;
        //             $task->save();
        //         }
        //     }
        // }
    }
}
