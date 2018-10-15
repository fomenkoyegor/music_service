<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Http\Resources\MusicsResource;
use App\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = auth()->user()->favorites;
        return response(\App\Http\Resources\Favorite::collection($favorites));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $music_id = $request->input('music_id');
        $fav = new Favorite();
        $fav->music_id = $music_id;
        $fav->user_id = $user->id;
        $fav->save();
        $music = Music::find($music_id);
        return response(new MusicsResource($music));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Favorite $favorite
     * @return \Illuminate\Http\Response
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Favorite $favorite
     * @return \Illuminate\Http\Response
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Favorite $favorite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Favorite $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $fav)
    {
        $user = auth()->user();
        $music_id = $fav;
        $favorite = Favorite::where('music_id', $music_id)->where('user_id', $user->id)->first();

        if ($favorite) {
            $favorite->delete();
            $music = Music::find($music_id);
            return response(new MusicsResource($music));
        }

    }
}
