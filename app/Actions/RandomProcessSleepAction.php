<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;

class RandomProcessSleepAction
{
    public function run($start_number = 2000000, $end_number = 6000000, $dividen = 100000) {
        $sleep_time = 0;
        $rand = rand($start_number, $end_number);
        $sleep_time = round(($rand / $dividen),2);
        flush();
        usleep($rand);
        return $sleep_time;
    }
}
