<?php

namespace App\Http\Resources;

use App\Genre;
use App\Music;
use App\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Playlist extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $music = Music::find($this->music_id);
        $genre = Genre::find($music->genre_id);
        $user = User::find($music->user_id);

        return [
            'music_playlist_id' => $this->id,
            'id' => $music->id,
            'name' => $music->title . ' - ' . $music->artist,
            'title' => $music->title,
            'artist' => $music->artist,
            'album' => $music->album,
            'genre' => $genre->name,
            'year' => $music->year,
            'durration' => $music->durration,
            'user' => $user->name,
            'user_id' => $user->id,
            'audio_url' => $music->music_server_path,
            'cover_url' => \url('/') . $music->cover,
            'music_server_path'=>\url('/').$music->path
        ];
        
       
    }
}
