<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function music_plays()
    {
        return $this->belongsTo(MusicPlay::class);
    }


}
