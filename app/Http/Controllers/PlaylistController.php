<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaylistIndex;
use App\Playlist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = (auth()->user())->id;
        $user = User::find($user_id);

        $playlist = $user->playlists;

        return response([
            'data' => PlaylistIndex::collection($playlist),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user_id = (auth()->user())->id;
        $user_name = (auth()->user())->name;
        $prefix = random_int(111,999);
        $request->validate([
            'name'=>'required|min:2|max:12'
        ]);
        $name = $request->input('name');
        $playlist = new Playlist();
        $playlist->name=$name;
        $playlist->prefix=$prefix;
        $playlist->user_id=$user_id;
        $playlist->save();

        return response([
            'playlist' => new  PlaylistIndex($playlist),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function show(Playlist $playlist)
    {

        $musicplays = $playlist->musicplays;
        $user_id = (auth()->user())->id;
        $user_name = (auth()->user())->name;
        return response([
            'data' =>\App\Http\Resources\Playlist::collection($musicplays),
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Playlist $playlist)
    {
        $name = $request->input('name');
        $playlist->name = $name;
            $playlist->save();

            return response([
                'playlist' => $playlist
            ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Playlist $playlist)
    {
        $user_id = (auth()->user())->id;
        $music_user_id = $playlist->user_id;
        if ($user_id !== $music_user_id) {
            return response([
                'data' => 'not found',
                'status' => 'fail',
            ], 203);
        }else{
            $playlist->delete();
            $mp = DB::table('music_plays')->where('playlist_id', '=', $playlist->id)->delete();
            return response([
                'status' => 'ok',
                'message' => 'playlist was deleted',
            ], 201);
        }

    }
}
