<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Http\Requests\MusicStore;
use App\Http\Resources\MusicsResource;
use App\Music;
use App\MusicPlay;
use App\User;
use App\Playlist;
use Illuminate\Http\Request;
use App\Http\Resources\PlaylistIndex;

use getID3;
use getid3_lib;
use getid3_writetags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Console\Helper\Helper;

class MusicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',
            ['except' => [
                'index',
                'show',
                'last',
                'paginate',
                'lastLimit',
                'usermusic',
                'lastPaginate',
                'dowload',
                'userplaylists',
                'userplaylistsMusic'
            ]]
        );
    }

    public function dowload(Request $request)
    {
        $url = $request->input('url');
        return response()->download($url,"1.mp3");
    }

    public function lastPaginate()
    {
        $musics = Music::latest()->paginate(10);
        return  MusicsResource::collection($musics);

    }

    public function usermusic(Request $request, $id)
    {
        $user = User::find($id);
        if($user){
            $music = $user->musics;
            return MusicsResource::collection($music);
        }else{
            return response([
                'data'=>[],
                'msg'=>'not found user'
            ]);
        }
        
    
    }

    public function userplaylists(Request $request, $id)
    {
        $user = User::find($id);
        $playlists = DB::table('playlists')->where('user_id', '=', $id)->get();
        if($user){
            return response([
                'data' => PlaylistIndex::collection($playlists)
            ], 200);
        }else{
            return response([
                'data'=>[],
                'msg'=>'not found user'
            ]);
        }
        
    }


    public function userplaylistsMusic(Request $request, $user_id, $playlist_id)
    {
        $user = User::find($user_id);
        $playlist = Playlist::find($playlist_id);

        if(!$playlist){
            return response([
                'data'=>[],
                'msg'=>'not found user or playlist'
            ]);
        }
        
        if($playlist->user_id!==$user->id){
            return response([
                'data'=>[],
                'msg'=>'not found user or playlist'
            ]);
        }

        

        if($user && $playlist ){
            return response([
                'data' =>\App\Http\Resources\Playlist::collection($playlist->musicplays),
                'playlist'=>[
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'user_name'=>$user->name,
                    'front_name' => $user->name . '_' . $playlist->name . '_' . $playlist->prefix,
                    'user_id' => $user->user_id,
                ]
            ], 200);
        }else{
            return response([
                'data'=>[],
                'msg'=>'not found user or playlist'
            ]);
        }

        
        
    }

    public function paginate()
    {
        $musics = Music::paginate(10);

        return MusicsResource::collection($musics);

    }

    public function last()
    {
//        $musics = Music::orderBy('updated_at', 'id')->get();
        $musics = DB::table('musics')->orderBy('updated_at', 'id')->get();
        return response([
            'data' => MusicsResource::collection($musics)
        ], 200);
    }

    public function lastLimit(Request $request, $limit)
    {
        $musics = DB::table('musics')->orderBy('updated_at', 'id')->limit($limit)->get();
        return response([
            'data' => MusicsResource::collection($musics)
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $musics = [];
        if ($request->header('Authorization')) {
            
            if(!auth()->user())  return response()->json(['error' => 'Unauthorized'], 401);

            $user_id = (auth()->user())->id;
            $user = User::find($user_id);
            $musics = DB::table('musics')
                ->where('user_id', '=', $user_id)
                ->latest()
                ->get();
            if (count($musics) > 0) {
                return response([
                    'data' => MusicsResource::collection($musics)
                ], 200);
            } else {
                return response([
                    'data' => $musics
                ], 200);
            }

        } else {
//            $musics = Music::orderBy('updated_at','id')->get();
            $musics = Music::all();
            return response([
                'data' => MusicsResource::collection($musics)
            ], 200);
        }

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
     * @throws \Exception
     */
    public function store(MusicStore $request)
    {
    
        $getID3 = new getID3;

        $audio = $request->file('audio');
        $img = $request->file('cover');

        $title = $request->input('title');
        $artist = $request->input('artist');
        $album = $request->input('album');
        $year = $request->input('year');
        $genre = $request->input('genre');


        $ThisFileInfo = $getID3->analyze($audio);
        if (isset($ThisFileInfo['playtime_string'])) {
            $len = $ThisFileInfo['playtime_string'];
            $dur = $ThisFileInfo['playtime_string'];
        } else {
            $dur = '--:--';
        }

        $name = random_int(11111, 4587846);
        $type = $audio->getClientOriginalExtension();


        $username = (auth()->user())->email;
        $user_id = (auth()->user())->id;

        $path = '/audio/' . $username . '/' . $name . '/' . $name . '.' . $type;
//        $path = \url('/audio') . '/' . $username . '/' . $name . '/' . $name . '.' . $type;

        $audio->move(public_path() . '/audio/' . $username . '/' . $name, $name . '.' . $type);

        $music_server_path = '/audio/' . $username . '/' . $name . '/' . $name . '.' . $type;
//        $music_server_path = public_path() . '/audio/' . $username . '/' . $name . '/' . $name . '.' . $type;


        if ($img) {
            $ImgType = $img->getClientOriginalExtension();
            $img->move(public_path() . '/audio/' . $username . '/' . $name, $name . '.' . $ImgType);

            $imgPath =  '/audio/' . $username . '/' . $name . '/' . $name . '.' . $ImgType;
//            $imgPath = \url('/audio') . '/' . $username . '/' . $name . '/' . $name . '.' . $ImgType;
            $cover =  '/audio/' . $username . '/' . $name . '/' . $name . '.' . $ImgType;
//            $cover = public_path() . '/audio/' . $username . '/' . $name . '/' . $name . '.' . $ImgType;

        } else {
//            $cover = public_path() . '/audio/ico/music.jpg';
            $cover = '/audio/ico/music.jpg';
            $imgPath = '/audio/ico/music.jpg';
//            $imgPath = \url('/audio/ico/music.jpg');
        }


        $music = new Music();
        $music->name = $name;

        $music->cover_server_path = $cover;

        $music->music_server_path = $music_server_path;

        $music->path = $path;

        $music->cover = $imgPath;


        $music->title = $title;
        $music->album = $album;
        $music->artist = $artist;
        $music->genre_id = $genre;
        $music->year = $year;
        $music->durration = $dur;
        $music->user_id = $user_id;
        $music->save();

        $user = User::find($user_id);
        $musics = $user->musics;
        $count = $musics->count();


        $TaggingFormat = 'UTF-8';
        $getID3->setOption(array('encoding' => $TaggingFormat));

        $TextEncoding = 'UTF-8';
        $tagwriter = new getid3_writetags;


        $tagwriter->filename = public_path() . '/audio/' . $username . '/' . $name . '/' . $name . '.' . $type;

        $tagwriter->tagformats = array('id3v2.3');

        $tagwriter->overwrite_tags = true;
        $tagwriter->remove_other_tags = false;
        $tagwriter->tag_encoding = $TextEncoding;
        $tagwriter->remove_other_tags = true;

        $TagData = [
            'title' => [$title],
            'artist' => [$artist],
            'album' => [$album],
            'year' => [$year],
            'genre' => [$genre],
            'track' => [$count],


        ];


        $fd = @fopen(public_path() .$cover, 'rb');
        $APICdata = fread($fd, filesize(public_path() .$cover));
        fclose($fd);
        list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize(public_path() .$cover);
        $TagData['attached_picture'][0]['data'] = $APICdata;
        $TagData['attached_picture'][0]['picturetypeid'] = 0x03;
        $TagData['attached_picture'][0]['description'] = 'cover';
        $TagData['attached_picture'][0]['mime'] = 'image/png';


        $tagwriter->tag_data = $TagData;

        $tagwriter->WriteTags();


        // return response([
        //     'music' => $music,


        // ], 201);
        $genreMusicResponse = Genre::find($genre);
        return response([
                'id'=>$music->id,
                'name'=>$music->title.' - '.$music->artist,
                'title'=>$music->title,
                'artist'=>$music->artist,
                'album'=>$music->album,
                'genre'=>$genreMusicResponse->name,
                'genre_id'=>$genreMusicResponse->id,
                'year'=>$music->year,
                'durration'=>$music->durration,
                'user'=>$user->name,
                'user_id'=>$user->id,
                'audio_url'=>$music->music_server_path,
                'cover_url'=>\url('/').$music->cover,
                'music_server_path'=>\url('/').$music->path
        ]);

        


    }

    // -------------------------------------------------------------------------------------------------------------------

    public function show(Music $music)
    {

        $genre = Genre::find($music->genre_id);
        $user = User::find($music->user_id);
        return response([
            
                'id'=>$music->id,
                'name'=>$music->title.' - '.$music->artist,
                'title'=>$music->title,
                'artist'=>$music->artist,
                'album'=>$music->album,
                'genre'=>$genre->name,
                'genre_id'=>$genre->id,
                'year'=>$music->year,
                'durration'=>$music->durration,
                'user'=>$user->name,
                'user_id'=>$user->id,
                'audio_url'=>$music->music_server_path,
                'cover_url'=>\url('/').$music->cover,
                'music_server_path'=>\url('/').$music->path
            
        ]);
    }

    // ----------------------------------------------------------------------------------------------------------------------
    public function edit(Music $music)
    {
        //
    }

    // ------------------------------------------------------------------------------------------------------------------
    public function updatemusic(Request $request, Music $music)
    {

        $user = auth()->user();
        $user_id = (auth()->user())->id;
        $music_user_id = $music->user_id;
        if ($user_id !== $music_user_id) {
            return response([
                'data' => 'not found',
                'status' => 'fail',
            ], 404);
        } else {
            $cover = $request->file('cover');
            if ($cover) {
                $cover_type = $cover->getClientOriginalExtension();
                $name = $music->name;
                $username = (auth()->user())->email;

                $old_path_cover = $music->cover;
                $standart_path_cover = '/audio/ico/music.jpg';

                if ($old_path_cover === $standart_path_cover) {
                    $cover->move(public_path() . '/audio/' . $username . '/' . $name, $name . '.' . $cover_type);
                    $cover_path = '/audio' . '/' . $username . '/' . $name . '/' . $name . '.' . $cover_type;
                    $music->cover = $cover_path;
                    $music->cover_server_path = '/audio/' . $username . '/' . $name . '/' . $name . '.' . $cover_type;
                } else {
                    unlink(public_path().$music->cover_server_path);
                    $new_name = random_int(11111, 4587846);
                    $cover->move(public_path() . '/audio/' . $username . '/' . $name, $new_name . '.' . $cover_type);
                    $cover_path = '/audio' . '/' . $username . '/' . $name . '/' . $new_name . '.' . $cover_type;
                    $music->cover = $cover_path;
                    $music->cover_server_path = '/audio/' . $username . '/' . $name . '/' . $new_name . '.' . $cover_type;
                }


            }

            $music->title = $request->input('title');
            $music->artist = $request->input('artist');
            $music->year = $request->input('year');
            $music->album = $request->input('album');
            $music->genre_id = $request->input('genre_id');
            $music->save();

            $music_server_path =  $music->music_server_path;
            $cover = $music->cover_server_path;
            try {
                $getID3 = new getID3;
            } catch (\getid3_exception $e) {
            }
            $TaggingFormat = 'UTF-8';
            $getID3->setOption(array('encoding' => $TaggingFormat));
            $TextEncoding = 'UTF-8';
            $tagwriter = new getid3_writetags;
            $tagwriter->filename = public_path().$music_server_path;
            $tagwriter->tagformats = array('id3v2.3');
            $tagwriter->overwrite_tags = true;
            $tagwriter->remove_other_tags = false;
            $tagwriter->tag_encoding = $TextEncoding;
            $tagwriter->remove_other_tags = true;
            $genre = Genre::find($request->input('genre_id'));
            $TagData = [
                'title' => [$request->input('title')],
                'artist' => [$request->input('artist')],
                'album' => [$request->input('album')],
                'year' => [$request->input('year')],
                'genre' => [$genre->name]
            ];
            $fd = @fopen(public_path().$cover, 'rb');
            $APICdata = fread($fd, filesize(public_path().$cover));
            fclose($fd);
            list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize(public_path().$cover);
            $TagData['attached_picture'][0]['data'] = $APICdata;
            $TagData['attached_picture'][0]['picturetypeid'] = 0x03;
            $TagData['attached_picture'][0]['description'] = 'cover';
            $TagData['attached_picture'][0]['mime'] = 'image/png';
            $tagwriter->tag_data = $TagData;
            $tagwriter->WriteTags();


            return response([
            
                'id'=>$music->id,
                'name'=>$music->title.' - '.$music->artist,
                'title'=>$music->title,
                'artist'=>$music->artist,
                'album'=>$music->album,
                'genre'=>$genre->name,
                'genre_id'=>$genre->id,
                'year'=>$music->year,
                'durration'=>$music->durration,
                'user'=>$user->name,
                'user_id'=>$user->id,
                'audio_url'=>$music->music_server_path,
                'cover_url'=>\url('/').$music->cover,
                'music_server_path'=>\url('/').$music->path
            
        ]);
        }

    }


    public function update(Request $request, Music $music)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Music $music
     * @return \Illuminate\Http\Response
     */
    public function destroy(Music $music)
    {
        $user_id = (auth()->user())->id;
        $music_user_id = $music->user_id;
        if ($user_id !== $music_user_id) {
            return response([
                'data' => 'not found',
                'status' => 'fail',
            ], 404);
        } else {
            $standart_path_cover = '/audio/ico/music.jpg';
            if ($music->cover_server_path === $standart_path_cover) {
                unlink(public_path() . $music->music_server_path);
            } else {
                unlink(public_path() . $music->cover_server_path);
                unlink(public_path() . $music->music_server_path);
            }


            $music->delete();

            $mp = DB::table('music_plays')->where('music_id', '=', $music->id)->delete();

            return response([
                'status' => 'ok',
                'message' => 'music was deleted',

            ]);
        }
    }
}
