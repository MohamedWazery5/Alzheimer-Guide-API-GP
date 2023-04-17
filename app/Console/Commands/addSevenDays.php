<?php

namespace App\Console\Commands;

use App\Models\CustomRepeat;
use App\Models\TaskScheduler;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class addSevenDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customTasks:addSevenDays';

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
        $CustomTasks = CustomRepeat::where('date', date('Y-m-d', strtotime('-1 days')))->get();
        foreach ($CustomTasks as $task) {
            $task->date = date('Y-m-d', strtotime(' + 6 days'));
            $task->save();
        }
    }
}
