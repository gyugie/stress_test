<?php

namespace App\Jobs;

use App\Actions\RandomProcessSleepAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessingTestCpuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $posts = Storage::path('wp') . '/wp_posts.csv';
            $URI = Arr::get(Arr::random($this->csvToArray($posts)), 'post_name');
            $URL = "http://lp.fattah.id/$URI";

            $response = Http::get($URL);

            $sleep = (new RandomProcessSleepAction())->run();
            $message = "ProcessingTestCpuJob Call ($URL) status (". $response->status() .") Sleep ($sleep) seconds";
            Log::info($message);
            echo "$message";
        } catch (\Exception $exception) {
            Log::error("Error ProcessingTestCpuJob:: ". $exception->getMessage());
        }
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
