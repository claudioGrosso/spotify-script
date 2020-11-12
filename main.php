<?php

require_once __DIR__ . '/vendor/autoload.php';

$api = new \Audeio\Spotify\API();
$api->setAccessToken(
    'BQAhs2YQq_-E0MmAJzFguEHHvhpiX-dbbcT6u2pRngGfZhUSkt543NcKqF21eNMYCkjTB5h_vWc8s4a1P5ynINcqhhO68ztjXk5BeaE29JWhD2BAgHSOIiBhUPaBzYlzqjwjlw6O9Nj9ySEO9qUTG7f3dSpXf9BXOhWFVleAmA2b'
);

fetchUserPlaylist($api);

function fetchUserPlaylist($api)
{
    $user = $api->getCurrentUser();

    $userId = $user->getId();

    $limit = 20;

    $offset = 0;

    $playlistListGroup = [];

    for ($i = 0; $i < 5; $i++){
        echo $offset.PHP_EOL;
        $playlistList = $api->getUserPlaylists($userId, $limit, $offset)->getItems();
        array_push($playlistListGroup, $playlistList);
        $offset += $limit;
    }

    foreach ($playlistListGroup as $playlist){
        echo $playlist.PHP_EOL; 
    }
}

function filterPlaylist($playlist)
{
    $tracks = $playlist->getTracks();

    $items = $tracks->getItems();

    $result = [];

    foreach($items as $item){
        $track = $item->getTrack();
        $name = $track->getName();
        $album = $track->getAlbum()->getName();
        $artist = $track->getArtists()->first()->getName();
        $trackDTO = compact('artist','album','name');
        array_push($result,$trackDTO);
    }

    file_put_contents('./foo.json', json_encode($result,JSON_PRETTY_PRINT,512));
}