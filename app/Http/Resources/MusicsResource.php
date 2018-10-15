<?php

namespace App\Http\Resources;

use App\Genre;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isFav = false;

        if (auth()->user()) {
            $fav = \App\Favorite::where('music_id', $this->id)->first();
            if($fav && $fav->user_id===auth()->user()->id){
                $isFav=true;
            }

        }

        $genre = Genre::find($this->genre_id);
        $user = User::find($this->user_id);
        return [
            'id'=>$this->id,
            'name'=>$this->title.' - '.$this->artist,
            'title'=>$this->title,
            'artist'=>$this->artist,
            'album'=>$this->album,
            'genre'=>$genre->name,
            'genre_id'=>$genre->id,
            'year'=>$this->year,
            'durration'=>$this->durration,
            'user'=>$user->name,
            'user_id'=>$user->id,
            'audio_url'=>$this->music_server_path,
            'cover_url'=>\url('/').$this->cover,
            'music_server_path'=>\url('/').$this->path,
            'fav'=>$isFav

        ];
    }
}
