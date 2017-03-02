<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('homepage', ['textString' => "", 'artistSuggestions' => []]);
});

Route::get('/api/artist/{name}', 'MyController@getArtistSuggestions')->name('suggestions.get');

Route::get('/api/wordcloud/{artistId}', 'MyController@getWordCloudList')->name('wordcloud.get');

Route::get('/api/songlist/{word}/{artistId}', 'MyController@getSongList')->name('songlist.get');

Route::get('/api/lyrics/{songName}/{artistId}/{word}', 'MyController@getSongLyrics')->name('lyrics.get');

Route::resource('my','MyController');

