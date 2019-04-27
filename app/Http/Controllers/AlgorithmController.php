<?php

namespace App\Http\Controllers;

use DB;
use App\TestRun;
use Illuminate\Http\Request;
use App\Algorithm;
use App\TestData;
use Illuminate\Support\Facades\Storage;
use App\Jobs\BuildContainer;
use Auth;
use App\User;

class AlgorithmController extends Controller
{
    function list() {
        $algorithms = Algorithm::all();
        return view("submissions", ["algorithms" => $algorithms]);
    }



    function upload(Request $request) {
        $algorithm = new Algorithm();
        $algorithm->name = $request->input("algorithmName");
        $id = Auth::user()->id;
        $user = User::find($id);
        $algorithm->save();
        $algorithm->user()->associate($user)->save();
        Storage::putFileAs("algorithms", $request->file("algoFile"), $algorithm->id.".zip");
        return redirect("/submissions/");
    }

    function delete($id) {
        $algorithm = Algorithm::find($id);
        $algorithm->delete();
        Storage::delete("algorithms/".$id.".zip");
        return redirect("/myalgorithms");
    }

    function testList($id) 
    {
        $data_entries = TestData::all();
        $run_statuses = [];
        foreach($data_entries as $data_entry) {
            // TEST_RUN table atrod, kur test_data_id ir vienāds ar test_data id un kur algorithm_id ir vienads ar izveleta algoritma id
            $test_run = TestRun::where("test_data_id", "=", $data_entry->id, "and")->where("algorithm_id", "=", $id)->get();
            if($test_run->isEmpty()) {
                $run_statuses[$data_entry->id] = ["status" => "not run", "id" => 0];
            } else {
                $test_run = $test_run->first();
                $run_statuses[$data_entry->id] = ["id" => $test_run->id, "status" => $test_run->status, "score" => $test_run->score];
            }

        }
        $algorithms = Algorithm::whereNotIn('id',[$id])->get();
        $chosen_algorithm = Algorithm::find($id);

        return view("tests", ["data_entries" => $data_entries, "algorithm_id" => $id, "run_stats" => $run_statuses,
         "algorithms" => $algorithms, 'chosen_algorithm' => $chosen_algorithm]);
    }
    function showBest()
    {
        $algorithm_id = TestRun::all();
        $last_algorithm_id = Algorithm::orderBy('id', 'desc')->first();
        $total_algorithm_count = $last_algorithm_id->id;
        $score = 0;
        $score_table = array();
        $algorithm_names_by_id = array();
        $score_table_tested_only = array();
        
        for($i=1; $i<=$total_algorithm_count; $i++)
        {
            $score_table[$i] = array(0,0);
        }

        foreach($algorithm_id as $test_case)
        {
            $score_table[$test_case->algorithm_id][0] = $test_case->algorithm_id; 
            $score_table[$test_case->algorithm_id][1] += $test_case->score;
        }

        array_multisort( array_column($score_table, 1), SORT_DESC, $score_table );
       
        $i=0;
        while($score_table[$i][0] != 0)
        {
            $score_table_tested_only[$i][0] = $score_table[$i][0];
            $score_table_tested_only[$i][1] = $score_table[$i][1];
            $i++;
        }
        for($i = 0; $i < sizeof($score_table_tested_only); $i++)
        {
            $algorithm_row = Algorithm::where("id", "=", $score_table_tested_only[$i][0])->first();
            $algorithm_names_by_id[$i][0] = $algorithm_row->name;
        }
      

        return view('dashboard', ['total_algorithm_count' => $total_algorithm_count,'score_table_tested_only' => $score_table_tested_only, 'algorithm_names_by_id' => $algorithm_names_by_id]);


    }

 
    function runTest($algorithm_id, $test_data_id)
     {

        BuildContainer::dispatch($algorithm_id, $test_data_id);
        return redirect("/algorithm/".$algorithm_id."/");
    }

    function testTheUntested($test_data_id, $algorithm_id)
    {
        BuildContainer::dispatch($algorithm_id, $test_data_id);
        return redirect("/data/".$test_data_id."/showalgorithms");
    }

    function testAll($algorithm_id)
    {
        $test_data = TestData::all();
        foreach($test_data as $test)
        {
            BuildContainer::dispatch($algorithm_id, $test->id);
        }
        return redirect("/algorithm/".$algorithm_id."/");
    }


    function downloadOutput($id) {
        return response()->streamDownload(function () use($id) {
            $test_run = TestRun::find($id);
            echo $test_run->info;
        }, $id."txt");
    }

    function showAlgorithms($id) { 
        $data_entries = TestData::all();
        $test_results_object = TestRun::where("test_data_id", "=", $id, "and")->where("score", ">", "0")->get();
        $not_tested = DB::select( DB::raw('
            SELECT id
            FROM algorithm
            WHERE id NOT IN (SELECT algorithm_id FROM test_run WHERE test_data_id = :id)'),
        array('id' => $id,
        ));
        $algorithm_names_by_id = array();
        $not_run_algorithm_names_by_id = array();
        
        $test_results_array = array();
        for($i = 0; $i < sizeof($test_results_object); $i++)
        {
            // dd($test_results_object[$i]->id);
            $test_results_array[$i][0] = $test_results_object[$i]->algorithm_id;
            $test_results_array[$i][1] = $test_results_object[$i]->score;
            $test_results_array[$i][2] = $test_results_object[$i]->created_at;
            $test_results_array[$i][3] = $test_results_object[$i]->updated_at;
        }
        array_multisort( array_column($test_results_array, 1), SORT_DESC, $test_results_array);

            for($i=0;$i<sizeof($test_results_array);$i++)
            {
                $algorithm_row = Algorithm::where("id", "=", $test_results_array[$i][0])->first();

                $algorithm_names_by_id[$i][0] = $algorithm_row->name;
            }

        foreach($not_tested as $no_test)
            {
                $algorithm_row = Algorithm::where("id", "=", $no_test->id)->first();

                $not_run_algorithm_names_by_id[$no_test->id] = $algorithm_row->name;
            }
        return view('testedalgorithms', ["tests" => $test_results_array, "data_entries" => $data_entries, "algorithm_names_by_id" => $algorithm_names_by_id, "test_data_id" => $id,
        "not_run_algorithm_names_by_id" => $not_run_algorithm_names_by_id, "not_tested" => $not_tested]);
    }

    function mysubmissions()
    {
        $id = Auth::user()->id;
        $algorithms = Algorithm::where('user_id', '=', $id)->get();
        return view('mysubmissions', ['algorithms' => $algorithms]);
    }
}
