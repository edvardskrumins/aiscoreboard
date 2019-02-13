<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestRun extends Model
{
    protected $table = "test_run";

    public function algorithm(){
        $this->belongsTo("App\Algorithm");
    }

    public function data(){
        $this->belongsTo("App\TestData", "test_data_id");
    }
}
