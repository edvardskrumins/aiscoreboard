<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    const UPDATED_AT = null;
    protected $table = "test_data";

    public function testRuns() {
        return $this->hasMany("App\TestRun");
    }
}
