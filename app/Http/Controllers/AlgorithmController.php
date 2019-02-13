<?php

namespace App\Http\Controllers;

use App\TestRun;
use Illuminate\Http\Request;
use App\Algorithm;
use App\TestData;
use Illuminate\Support\Facades\Storage;
use App\Jobs\BuildContainer;

class AlgorithmController extends Controller
{
    function list() {
        $algorithms = Algorithm::all();
        return view("algorithms", ["algorithms" => $algorithms]);
    }

    function upload(Request $request) {
        $algorithm = new Algorithm();
        $algorithm->name = $request->input("algorithmName");
        $algorithm->save();
        Storage::putFileAs("algorithms", $request->file("algoFile"), $algorithm->id.".zip");
        return redirect("/algorithm/");
    }

    function delete($id) {
        $algorithm = Algorithm::find($id);
        $algorithm->delete();
        Storage::delete("algorithms/".$id.".zip");
        return redirect("/algorithm/");
    }

    function testList($id) {
        $data_entries = TestData::all();
        $run_statuses = [];
        foreach($data_entries as $data_entry) {
            $test_run = TestRun::where("test_data_id", "=", $data_entry->id, "and")->where("algorithm_id", "=", $tesst_run->id)->get();
            if($test_run->isEmpty()) {
                $run_statuses[$data_entry->id] = ["status" => "not run", "id" => 0];
            } else {
                $test_run = $test_run->first();
                $run_statuses[$data_entry->id] = ["id" => $test_run->id, "status" => $test_run->status, "score" => $test_run->score];
            }

        }
        return view("tests", ["data_entries" => $data_entries, "algorithm_id" => $id, "run_stats" => $run_statuses]);
    }

    function runTest($algorithm_id, $test_data_id) {
        BuildContainer::dispatch($algorithm_id, $test_data_id);
        return redirect("/algorithm/".$algorithm_id."/");
    }

    function downloadOutput($id) {
        return response()->streamDownload(function () use($id) {
            $test_run = TestRun::find($id);
            echo $test_run->info;
        }, $id."txt");
    }
}
