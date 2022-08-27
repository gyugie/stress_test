<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;

class RandomProcessSleepAction
{
    public function run() {
        $sleep_time = 0;
        $rand = rand(2000000, 6000000);
        $sleep_time = round(($rand / 100000),2);
        flush();
        usleep($rand);
        return $sleep_time;
    }
}
