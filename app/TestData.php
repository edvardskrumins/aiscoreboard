<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    protected $table = "test_data";

    public function testRuns() {
        return $this->hasMany("App\TestRun");
    }
}
