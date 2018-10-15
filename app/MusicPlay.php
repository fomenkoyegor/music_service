<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MusicPlay extends Model
{
    public function musics()
    {
        return $this->hasMany(Music::class);
    }
}
