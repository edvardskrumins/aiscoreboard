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
     
        //$containerManager = $docker->getContainerManager();

        // PĀRLIKU 

        $test_data = TestData::find($this->test_id);
        $csv_ads = Reader::createFromString($test_data->campaigns);
        $csv_slots = Reader::createFromString($test_data->spots);
   
        Storage::disk('local')->put("/algorithms/".$this->algorithm_id."/ads.csv", $csv_ads->__toString());
        Storage::disk('local')->put("/algorithms/".$this->algorithm_id."/slots.csv", $csv_slots->__toString());
   
        $context = new Context(storage_path("app/algorithms/".$this->algorithm_id));
        $inputStream = $context->toStream();
        $docker = Docker::create();
        try {
            $buildStream = $docker->imageBuild($inputStream, ["t" => $this->test_id."algo-".$this->algorithm_id]);            
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
        $containerConfig->setImage($this->test_id.'algo-'.$this->algorithm_id); 
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
        
    //   Tālāk seko algoritms, kas apmierina šādus nosacījumus:
   // 1.Reklāmas pauzes garuma precizitāte - par katru 1 sek penalty 10% no reklāmas pauzes score.
  //   Tatad, ja reklamas pauzes garums tiek pārsniegts par 10 sek vai vairāk, reklāmas pauzes score bus 0
 //   2.Reklāmas pauzes reklāmu sumāram TRP jātuvojas pie reklāmas pauzes TRP, vai būt lielākam. 


        $score = 0;
        $json_ads = $csv_ads->jsonSerialize();
        $json_slots = $csv_slots->jsonSerialize();
        $result_array = [];
        $already_checked = [];
        $slot_total = array(); 
        $penalty = 0;
        $seconds_over_limit;
        $slot_score = 0;

        $stdout_array = explode("\n", $this->stream_text);
        array_pop($stdout_array);
        // dd($stdout_array);
        for($i = 1; $i < sizeof($json_slots); $i++) 
        {
            $slot_total[$i] = array(0,0,0,0,0,0,0,0,0,0);
        }
        foreach($stdout_array as $spot)
         {
            if(!array_search($spot, $already_checked, true)) 
            {
                
                $match = explode(",", $spot);
                $ad = $json_ads[$match[0]];
                // dd($stdout_array);
                
                for($i = 1; $i < sizeof($ad); ++$i)
                {
                    // if($match[1] == 127)
                    // {
                    //     dd($stdout_array);
                    // }
                    $slot_total[$match[1]][$i-1] += $ad[$i];
                }
                // tagad $slot_total satur katrai pauzei savāktās trp vērtības
                $already_checked[] = $spot;
            }
        }

        for($i = 1; $i <= sizeof($slot_total); ++$i) 
        {
            $seconds_over_limit = $slot_total[$i][0] - $json_slots[$i][2];
            if($seconds_over_limit >= 10)
             {
                continue;
             } 
            else
             {
                for($j = 1; $j < sizeof($slot_total[$i]); ++$j)
                {
                    if($slot_total[$i][$j] < $json_slots[$i][$j+2]) 
                    {
                        $slot_score += $slot_total[$i][$j]; // ja savakts vairak nekā bija nepieciešams, tad summē savākto rezultātu
                    }
                    else
                    {
                        $slot_score += $json_slots[$i][$j+2]; // citādāk summē to, cik bija nepieciešams.
                    }
                }
            if($seconds_over_limit > 0)
            {
                $penalty = 1 - ($seconds_over_limit / 10);
                $slot_score *= $penalty; 
            }
            $score += $slot_score;
            }
        $slot_score = 0;
        $penalty = 0;
        }
        $score = round($score, 2);
        $test_run->score = $score;
        $test_run->save();


    }
}
