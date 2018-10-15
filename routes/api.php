<?php

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});


//Route::get('login/facebook', 'AuthController@redirectToProvider');
//Route::get('login/facebook/callback', 'AuthController@handleProviderCallback');


Route::get('users', function () {
    $users = \App\User::all();
    return response(['users' => $users]);
});



Route::apiResource('/genres', 'GenreController');

Route::apiResource('/musics', 'MusicController');
Route::post('/musics/update/{music}', 'MusicController@updatemusic');

 Route::get('/musics/order/last', 'MusicController@last');
Route::get('/musics/order/last/{limit}', 'MusicController@lastLimit');
// Route::get('/musics/order/paginate', 'MusicController@paginate');

Route::get('/musics/last/page', 'MusicController@lastPaginate');

Route::get('/musics/user/{id}', 'MusicController@usermusic');
Route::get('/musics/user/{id}/playlists', 'MusicController@userplaylists');
Route::get('/musics/user/{user_id}/playlists/{playlist_id}', 'MusicController@userplaylistsMusic');



Route::apiResource('/playlists', 'PlaylistController');

Route::apiResource('/mp', 'MusicPlayController');
Route::apiResource('/fav', 'FavoriteController');

Route::post('/search', 'Search@action');




Route::get('dowload/{id}', function (Request $request, $id) {
    $music = \App\Music::find($id);
    $path = $music->music_server_path;

    return response()->download(public_path().$path);
});
































