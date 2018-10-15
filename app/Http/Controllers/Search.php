<?php

namespace App\Http\Controllers;

use App\Http\Resources\MusicsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Search extends Controller
{
    public function action(Request $request)
    {
       
        $query = $request->input('query');
        if(!trim($query)){
            return response([
                'data' => []
            ]);
        }
        $data = DB::table('musics')
                ->where('title','like','%'.$query.'%')
                ->orWhere('artist','like','%'.$query.'%')
                ->get();
        return response([
            'data' => MusicsResource::collection($data)
        ]);
    }
}

