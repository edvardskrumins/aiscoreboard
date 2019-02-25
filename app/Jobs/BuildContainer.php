<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Docker\Docker;
use Docker\Context\Context;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Docker\API\Model\BuildInfo;
use \Docker\API\Exception\ImageBuildInternalServerErrorException;
use Docker\API\Exception\ContainerCreateInternalServerErrorException;
use Docker\API\Exception\ContainerStartInternalServerErrorException;
use Docker\API\Model\ContainerConfig;
use App\TestRun;
use Docker\API\Model\ContainersCreatePostBody;
use App\TestData;
use League\Csv\Reader;
use Docker\API\Model\HostConfig;


class BuildContainer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $algorithm_id;
    protected $test_id;
    protected $stream_text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($algorithm_id, $test_id)
    {
        $this->algorithm_id = $algorithm_id;
        $this->test_id = $test_id;
        $this->stream_text = "";
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    private function saveError($test_run, $err) {
        dd($err);
        $test_run->status = 3;
        $test_run->info = $err->getMessage();
        $test_run->save();
    }

    public function handle()
    {
        $zip = new ZipArchive;
        $success = $zip->open(storage_path("app/algorithms/".$this->algorithm_id.".zip"));
        if ($success === TRUE) {
            if(!file_exists(storage_path("app/algorithms/".$this->algorithm_id))) {
                $zip->extractTo(storage_path("app/algorithms/" . $this->algorithm_id));
            }
            $zip->close();
        } else {
            return;
        }
        $test_run = TestRun::where("test_data_id", "=", $this->test_id, "and")->where("algorithm_id", "=", $this->algorithm_id)->get();
        if($test_run->isEmpty()) {
            $test_run = new TestRun;
            $test_run->status = 1;
            $test_run->algorithm_id = $this->algorithm_id;
            $test_run->test_data_id = $this->test_id;
            $test_run->score = 0.0;
            $test_run->info = "";
            $test_run->save();
        } else {
            $test_run = $test_run->first();
        }
        $context = new Context(storage_path("app/algorithms/".$this->algorithm_id));
        $inputStream = $context->toStream();
        $docker = Docker::create();
        //$containerManager = $docker->getContainerManager();

        // PĀRLIKU 

        $test_data = TestData::find($this->test_id);
        $csv_ads = Reader::createFromString($test_data->campaigns);
        $csv_slots = Reader::createFromString($test_data->spots);
   
        Storage::disk('local')->put("/algorithms/".$this->algorithm_id."/ads.csv", $csv_ads->__toString());
        Storage::disk('local')->put("/algorithms/".$this->algorithm_id."/slots.csv", $csv_slots->__toString());

        try {
            $buildStream = $docker->imageBuild($inputStream, ["t" => "algorithm-".$this->algorithm_id]);            
        //    if image already exists - do not build
        
        } catch(ImageBuildInternalServerErrorException $err) {
            $this->saveError($test_run, $err);
            //output the exception
        }
         
     

        $buildStream->wait();
        //Set a longer timeout
        $test_run->status = 2;
        $test_run->save();
       
        $containerConfig = new ContainersCreatePostBody();
        $containerConfig->setImage('algorithm-'.$this->algorithm_id); 
//             $myfile = fopen(storage_path("app/algorithms/".$this->algorithm_id)."/slots.csv", "r") or die("Unable to open file!");
// dd(fread($myfile,filesize(storage_path("app/algorithms/".$this->algorithm_id)."/slots.csv")));
// fclose($myfile);
        // $containerConfig->setVolumes(new \ArrayObject([storage_path("/app/algorithms/".$this->algorithm_id)."/ads.csv:/ads.csv" => (object) []]),
        // (new \ArrayObject([storage_path("app/algorithms/".$this->algorithm_id)."/slots.csv:/slots.csv" => (object) []])));

            // $containerConfig->setHostConfig(
            //     (new HostConfig())->setBinds([storage_path("app/algorithms/".$this->algorithm_id)."/ads.csv:/ads.csv",
            //         storage_path("app/algorithms/".$this->algorithm_id)."/slots.csv:/slots.csv"])
            // );
          
               // dd(storage_path("app/algorithms/".$this->algorithm_id)."/ads.csv:/ads.csv");
        try {
            //$containerCreateResult = $containerManager->create($containerConfig);
             $containerCreateResult = $docker->containerCreate($containerConfig);
        } catch(ContainerCreateInternalServerErrorException $err) {
            $this->saveError($test_run, $err);
        }
        $containerConfig->setAttachStdout(true);
        $containerConfig->setAttachStderr(true);
        $attachStream = $docker->containerAttach($containerCreateResult->getId(), [
            'stream' => true, 
            'stdout' => true,
            'stderr' => true
        ]);

         try{
            // $containerManager->start($containerCreateResult->getId());
             $docker->containerStart($containerCreateResult->getId());
            
        } catch(ContainerStartInternalServerErrorException $err) {
            dd($err);
            $this->saveError($test_run, $err);
        }
          

        $attachStream->onStdout(function ($stdout) {
            $this->stream_text = $this->stream_text.$stdout;
        });
        
        $attachStream->onStderr(function ($stderr) {
            $this->stream_text = $this->stream_text.$stderr;
            dd($stderr);
        });
        // $test_run->info = $this->stream_text;
        // $test_run->save();
        $attachStream->wait();
        $test_run->info = $this->stream_text;
        $test_run->save();
        $docker->containerWait($containerCreateResult->getId());
        //Set a reasonable timeout for execution
        $test_run->status = 4;
        $test_run->info = $this->stream_text;
        $test_run->save();
        

        $score = 0;
        $json_ads = $csv_ads->jsonSerialize();
        $json_slots = $csv_slots->jsonSerialize();
        // dd($json_ads);
        $stdout_array = explode("\n", $this->stream_text);
        array_pop($stdout_array);
        // dd($stdout_array[0]);
    

        $result_array = [];
        $already_checked = [];
        $kek = "";
        for($i = 1; $i < sizeof($json_ads); $i++) {
            $result_array[] = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
        }
        // dd($result_array);
        // dd($stdout_array);
        foreach($stdout_array as $spot) {
            if(!array_search($spot, $already_checked, true)) {
                
                $match = explode(",", $spot);
                $slot = $json_slots[$match[1]];
                // dd($slot);
                for ($i = 3; $i < sizeof($slot); $i++) {
                    $result_array[$match[0] - 1][$i - 3] += $slot[$i]; // pārkopē slot trp vērtības uz $result_array
                }
                $already_checked[] = $spot;
            }
        }
        // dd($json_ads);

        for($i = 1; $i < sizeof($json_ads); $i++) {
            for($j = 0; $j < sizeof($result_array[$i - 1]); $j++) {
                if($result_array[$i - 1][$j] < $json_ads[$i][$j + 2]) {
                    $score += $result_array[$i - 1][$j];
                   
                } else {
                    $score += $json_ads[$i][$j + 2];
                }
            }
        }
        $test_run->score = $score;
        $test_run->save();


    }
}

