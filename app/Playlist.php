<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function musicplays()
    {
        return $this->hasMany(MusicPlay::class);
    }
}
