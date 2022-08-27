<?php

namespace App\Http\Controllers\API;

use App\Actions\RandomProcessSleepAction;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessingTestCpuJob;
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
            $sleep_time = (new RandomProcessSleepAction())->run(1,15, 1);
            ProcessingTestCpuJob::dispatch();
            Log::info( "RequestTestController send response with  ($sleep_time) seconds");
            return response()->json(['sleep_time' => $sleep_time ]);
        } catch (\Exception $exception) {
            Log::error( "Error RequestTestController ". $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
