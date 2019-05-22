<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'body' , 'user_id'
    ];

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
