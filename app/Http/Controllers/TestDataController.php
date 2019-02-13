<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestData;
use League\Csv\Writer;
use League\Csv\Reader;

class TestDataController extends Controller
{
    const STARTING_TIME = 4;
    const ENDING_TIME = 24;
    const AUDIENCES = ["trp0", "trp1", "trp2", "trp3", "trp4", "trp5", "trp6", "trp7", "grp"];
    const AUDIENCE_COEFICIENT = [6, 4, 3, 2, 10, 12, 5, 1, 3];
    const AD_MIN_LENGTH = 10;
    const AD_MAX_LENGTH = 30;
    const CAMPAIGNS = 300;
    const PROBABILITY_OF_INTEREST = 0.5;

    function list() {
        $data_entries = TestData::all();
        return view("data", ["data_entries" => $data_entries]);
    }

    function generate(Request $request) {
        $csv_slots = Writer::createFromString("");
        $csv_ads = Writer::createFromString("");
        $csv_slots->insertOne(["id", "ts", "duration", "trp0", "trp1", "trp2", "trp3", "trp4", "trp5", "trp6", "trp7", "grp"]);
        $csv_ads->insertOne(["id", "duration", "trp0", "trp1", "trp2", "trp3", "trp4", "trp5", "trp6", "trp7", "grp"]);

        $hours_per_day = abs(self::ENDING_TIME - self::STARTING_TIME);
        $daily_slot_length = $request->input("daySlotLen");
        $slots_per_day = $hours_per_day * 2;
        $ads_start_date = $request->input("dayFrom");
        $ads_end_date = $request->input("dayTill");
        $ad_slot_ratio = $request->input("adsSlotsRatio");
        $days = abs(strtotime($ads_start_date) - strtotime($ads_end_date)) / 86400;
        $slot_length = $daily_slot_length / $slots_per_day;
        $slot = [];
        $id = 0;

        for($i = 0; $i < $days; $i++) {
            $current_time = self::STARTING_TIME;
            for ($j = 0; $j < $slots_per_day; $j++) {
                $slot["id"] = $id + $j + 1;
                $slot["ts"] = strtotime($ads_start_date) + ($current_time * 3600) + ($i * 86400);
                $slot["duration"] = $slot_length;
                for($k = 0; $k < sizeof(self::AUDIENCES); $k++) {
                    $ratio = $current_time / self::AUDIENCE_COEFICIENT[$k];
                    $slot[self::AUDIENCES[$k]] = (sin($ratio * pi()) >= 0.01) ? number_format(sin($ratio * pi()) * rand(1, 200), 2) : 0.00;
                }
                $current_time += 0.5;
                $csv_slots->insertOne($slot);
            }
            $id += $slots_per_day;
        }

        $total_duration = 0;
        $ads = [];
        for($i = 0; $i < self::CAMPAIGNS; $i++) {
            $ad = [];
            $ad["id"] = $i + 1;
            $ad["duration"] = rand(self::AD_MIN_LENGTH, self::AD_MAX_LENGTH);
            $total_duration += $ad["duration"];
            $ads[] = $ad;
        }

        $csv_json = $csv_slots->jsonSerialize();
        $total_trps = [];
        for($i = 0; $i < sizeof(self::AUDIENCES); $i++) {
            $total_trps[self::AUDIENCES[$i]] = 0;
            for($j = 1; $j < sizeof($csv_json); $j++) {
                $total_trps[self::AUDIENCES[$i]] += $csv_json[$j][$i + 3];
            }
            $total_trps[self::AUDIENCES[$i]] *= $ad_slot_ratio * ($daily_slot_length / $total_duration);
        }

        $campaign_targets = [];

        for($i = 0; $i < self::CAMPAIGNS; $i++) {
            $targets = [];
            for($j = 0; $j < sizeof(self::AUDIENCES); $j++) {
                $probability = 100 * (1.0 - self::PROBABILITY_OF_INTEREST);
                if(rand(0, 100) > $probability) {
                    $targets[] = rand(1, 100);
                } else {
                    $targets[] = 0;
                }
            }
            $campaign_targets[] = $targets;
        }

        for($i = 0; $i < sizeof(self::AUDIENCES); $i++) {
            $interest = 0;
            for($j = 0; $j < self::CAMPAIGNS; $j++) {
                $interest += $campaign_targets[$j][$i];
            }
            for($j = 0; $j < self::CAMPAIGNS; $j++) {
                $ads[$j][self::AUDIENCES[$i]] = number_format(($campaign_targets[$j][$i] / $interest) * $total_trps[self::AUDIENCES[$i]], 2);
            }
        }

        $csv_ads->insertAll($ads);
        $test_data = new TestData;
        $test_data->name = $request->input("testName");
        $test_data->spots = $csv_slots->__toString();
        $test_data->campaigns = $csv_ads->__toString();
        $test_data->save();
        return redirect("/data/");
    }

    function download($id, $column) {
        $data_entry = TestData::find($id);
        if($column == "slots") {
            $csv = Reader::createFromString($data_entry->spots);
            $csv->output("slots.csv");
        } elseif ($column == "ads") {
            $csv = Reader::createFromString($data_entry->campaigns);
            $csv->output("ads.csv");
        }
    }

    function delete($id)  {
        $data_entry = TestData::find($id);
        $data_entry->delete();
        return redirect("/data/");
    }
}
