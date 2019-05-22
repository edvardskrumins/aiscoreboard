<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Algorithm extends Model
{
    const UPDATED_AT = null;
    protected $table = "algorithm";

    public function testRuns() {
        return $this->hasMany("App\TestRun");
    }
    public function user() 
    {
        return $this->belongsTo("App\User");
    }
}
