<?php

namespace App\Http\Controllers\API;

use App\Actions\RandomProcessSleepAction;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessingTestCpuJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestTestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $at = Carbon::now();
            $memory = memory_get_usage() / 1024;
            $formatted = number_format($memory, 3) . 'K';
//            $sleep_time = (new RandomProcessSleepAction())->run(1,15, 1);
            ProcessingTestCpuJob::dispatch();
            Log::info( "RequestTestController Current memory usage : {$formatted}\n");
            return response()->json("OK");
        } catch (\Exception $exception) {
            Log::error( "Error RequestTestController ". $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
