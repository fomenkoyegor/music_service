<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistIndex extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::find($this->user_id);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_name'=>$user->name,
            'front_name' => $user->name . '_' . $this->name . '_' . $this->prefix,
            'user_id' => $this->user_id,
            'link' => route('playlists.show', $this->id)
        ];
    }
}
