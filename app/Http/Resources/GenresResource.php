<?php

namespace App\Http\Resources;

use App\Genre;
use Illuminate\Http\Resources\Json\JsonResource;

class GenresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $genres = Genre::find($this->id);
        $musics = $genres->musics;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count'=>count($musics),
            'link' => route('genres.show', $this->id),
            'cover_url'=>\url('/').'/'.$this->cover
        ];
    }
}
