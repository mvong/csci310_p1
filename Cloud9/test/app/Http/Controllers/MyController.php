<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;


class MyController extends Controller {
  private $verification = '&apikey=f5da5786a4cf41bbdc7f52e5a71c8e0d';

  public function getArtistSuggestions($name){
  	$client = new Client([
  		'base_uri' => 'http://api.musixmatch.com/ws/1.1/',
  		'timeout' => 2.0
  	]);
  	$response = $client->get('artist.search?q_artist=' . $name . '&page_size=3&s_artist_rating=DESC' . $this->verification);

  	$obj = json_decode($response->getBody(), true);
  	$obj = $obj['message']['body']['artist_list'];

    $artistSuggestions = array();
    if (sizeof($obj) > 0) $artistSuggestions[0] = array("artistName" => $obj[0]['artist']['artist_name'], "artistId" => $obj[0]['artist']['artist_id']);
    if (sizeof($obj) > 1) $artistSuggestions[1]= array("artistName" => $obj[1]['artist']['artist_name'], "artistId" => $obj[1]['artist']['artist_id']);
    if (sizeof($obj) > 2) $artistSuggestions[2]= array("artistName" => $obj[2]['artist']['artist_name'], "artistId" => $obj[2]['artist']['artist_id']);

    return view('homepage', ['artistSuggestions' => $artistSuggestions, 'textString' => $name]);
  }

  public function getWordCloudList($artistId){
    $client = new Client([
      'base_uri' => 'http://api.musixmatch.com/ws/1.1/',
      'timeout' => 2.0
    ]);
    $response = $client->get('track.search?f_artist_id=' . $artistId . '&page_size=5&page=1&f_lyrics_language=en&f_has_lyrics=true' . $this->verification);

    $trackList = json_decode($response->getBody(), true);
    $trackList = $trackList['message']['body']['track_list'];
    $artistName = $trackList[0]['track']['artist_name'];

    $allSongLyrics = " ";
    for ($i = 0; $i < count($trackList); $i++){
      $response = $client->get('track.lyrics.get?track_id=' . $trackList[$i]['track']['track_id'] . $this->verification);

      $songLyrics = json_decode($response->getBody(), true);
      $songLyrics = $songLyrics['message']['body']['lyrics']['lyrics_body'];

      //getting rid of common stuff.
      $toReplace = array(".", ")", "(", "\"", "]", "[", "1409614316181", "\n", ',', '******* This Lyrics is NOT for Commercial use *******'); 
      $songLyrics = str_replace($toReplace, " ", $songLyrics);
      $songLyrics = strtolower($songLyrics);

      $commonEnglishWords = array(" a ", " i ", " i'm ", " it's ", " do ", " am ", " the ", " to ", " in ", " at ", " is ", " it ", " was ", " are ", " that ", " of ", " be ", " at ", " or ", " by ", " this ", " and ", " you ", " me ", " some ", " how ", " my ", " on ", " they ", " get ", " we ", " so ", " but "); 
      $songLyrics = str_replace($commonEnglishWords, " ", $songLyrics);

      $allSongLyrics = $allSongLyrics . " " . $songLyrics;
    }

    $wordList = explode(" ", $allSongLyrics);
    $wordList = array_filter($wordList);
    $wordList = array_count_values($wordList);
    arsort($wordList);
    $wordList = array_slice($wordList, 0, 250, true);
/*
    echo '<pre>';
    print_r($wordList);
    echo '</pre>';
*/

    $wordCloudString = "";
    $startingATag = "<a style='color:";
    $fontSizeString = "; font-size:";
    $linkString = "px;' href='http://localhost:8000/api/songlist/";
    $colors = array("red", "blue", "green", "purple", "yellow", "black", "orange", "gray");

    $shuffled_array = array();
    $shuffled_keys = array_keys($wordList);
    shuffle($shuffled_keys);
    foreach ($shuffled_keys as $shuffled_key){
      $shuffled_array[$shuffled_key] = $wordList[$shuffled_key];
    }

    foreach ($shuffled_array as $key => $value){
      $color = $colors[array_rand($colors, 1)];
      //echo $color;
      $fontSize = $value * 6;
      if ($fontSize > 40) $fontSize = 40;
      if ($fontSize < 12) $fontSize = 10;
      $toAdd = $startingATag . $color . $fontSizeString . $fontSize . $linkString . $key . "/" . $artistId . "'> " . $key . " </a>";
      $wordCloudString = $wordCloudString . $toAdd;
    }

    $wordList = json_encode($wordList);

    return view('wordcloud', ['wordCloudString' => $wordCloudString, 'wordList' => $wordList, 'artistName' => $artistName, 'artistId' => $artistId]);
  }


  public function getSongList($word, $artistId){
    $client = new Client([
  		'base_uri' => 'http://api.musixmatch.com/ws/1.1/',
  		'timeout' => 2.0
  	]);
    $response = $client->get('track.search?f_artist_id=' . $artistId . '&page_size=5&page=1&f_lyrics_language=en&f_has_lyrics=true' . $this->verification);

    $trackList = json_decode($response->getBody(), true);
    $trackList = $trackList['message']['body']['track_list'];

    $songList = array();

    for ($i = 0; $i < count($trackList); $i++){
      $response = $client->get('track.lyrics.get?track_id=' . $trackList[$i]['track']['track_id'] . $this->verification);
      $songLyrics= json_decode($response->getBody(), true);
      $songLyrics = $songLyrics['message']['body']['lyrics']['lyrics_body'];

      $toReplace = array(".", ")", "(", "\"", "]", "[", "1409614316181", "\n", ',', '******* This Lyrics is NOT for Commercial use *******'); 
      $songLyrics = str_replace($toReplace, " ", $songLyrics);
      $songLyrics = strtolower($songLyrics);

      $count = substr_count($songLyrics, " " . $word . " ");
      $songList[$trackList[$i]['track']['track_name']] = $count;
    }

    arsort($songList);
    $songList = json_encode($songList);
    $trackList = json_encode($trackList);

    return view('songlist', ['trackList' => $trackList, 'songList' => $songList, 'word' => $word, 'artistId' => $artistId]);
  }

  public function getSongLyrics($songName, $artistId, $word){
    $client = new Client([
  		'base_uri' => 'http://api.musixmatch.com/ws/1.1/',
  		'timeout' => 2.0
  	]);
    $response = $client->get('track.search?f_artist_id=' . $artistId . '&q_track=' . $songName . '&f_has_lyrics=true' . $this->verification);
    $track = json_decode($response->getBody(), true);
    $track = $track['message']['body']['track_list'][0]['track'];
    $trackName = $track['track_name'];
    $artistName = $track['artist_name'];
    
    $response = $client->get('track.lyrics.get?track_id=' . $track['track_id'] . $this->verification);
    $lyrics = json_decode($response->getBody(), true);    
    $lyrics = $lyrics['message']['body']['lyrics']['lyrics_body'];
    
    $lyrics = str_replace("******* This Lyrics is NOT for Commercial use *******", "", $lyrics);
    $lyrics = str_replace("(1409614310238)", "", $lyrics);
    $lyrics = str_replace("\""," ", $lyrics);
    $lyrics = str_replace("\n", " <br> ", $lyrics);

    return view('lyrics', ['lyrics' => $lyrics, 'word' => $word, 'artistId' => $artistId, 'artistName' => $artistName, 'songTitle' => $trackName]);
  }
}
