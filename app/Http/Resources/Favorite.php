<?php

namespace App\Http\Resources;

use App\Music;
use Illuminate\Http\Resources\Json\JsonResource;

class Favorite extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return new  MusicsResource(Music::find($this->music_id));
    }
}
