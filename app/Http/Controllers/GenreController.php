<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Http\Resources\GenresResource;
use App\Http\Resources\MusicsResource;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genre = Genre::all();
        return response([
            'data' => GenresResource::collection($genre)
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Genre $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre)
    {
        // $musics = $genre->musics;
        // return response(['data' =>MusicsResource::collection($musics)]);
        $musics = $genre->musics()->latest()->get();
        return response([
            'data' =>MusicsResource::collection($musics),
            'genre'=>[
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'count'=>count($genre->musics),
                    'link' => route('genres.show', $genre->id),
                    'cover_url'=>\url('/').'/'.$genre->cover
                 ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Genre $genre
     * @return \Illuminate\Http\Response
     */
    public function edit(Genre $genre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Genre $genre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Genre $genre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Genre $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre)
    {
        //
    }
}
