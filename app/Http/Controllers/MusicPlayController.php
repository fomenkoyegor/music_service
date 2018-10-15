<?php

namespace App\Http\Controllers;

use App\MusicPlay;
use App\Playlist;
use App\User;
use Illuminate\Http\Request;

class MusicPlayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mp = MusicPlay::all();
        return response(['data' => $mp]);
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
        $request->validate([
            'music_id'=>'required',
            'playlist_id'=>'required',
        ]);
        $music_id = $request->input('music_id');
        $playlist_id = $request->input('playlist_id');

        $mp = new MusicPlay();
        $mp->music_id=$music_id;
        $mp->playlist_id=$playlist_id;

        $mp->save();

        return response([
            'status' => 'ok',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MusicPlay  $musicPlay
     * @return MusicPlay
     */
    public function show(MusicPlay $musicPlay, $id)
    {
        $music_in_playlist = MusicPlay::find($id);
        return $music_in_playlist;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MusicPlay  $musicPlay
     * @return \Illuminate\Http\Response
     */
    public function edit(MusicPlay $musicPlay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MusicPlay  $musicPlay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MusicPlay $musicPlay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MusicPlay  $musicPlay
     * @return \Illuminate\Http\Response
     */
    public function destroy(MusicPlay $musicPlay, $id)
    {
        $user_id = (auth()->user())->id;
        $user = User::find($user_id);
        $music_in_playlist = MusicPlay::find($id);
        $playlist_id = $music_in_playlist->playlist_id;
        $playlist = Playlist::find($playlist_id);
        if ($playlist->user_id === $user_id){
            $music_in_playlist->delete();
            return response([
                'status'=>'ok',
                'msg'=>'delete succes'
            ]);
        }else{
            return response([
                'status'=>'fail',
                'msg'=>'delete only your playlist'
            ]);
        }

    }
}
