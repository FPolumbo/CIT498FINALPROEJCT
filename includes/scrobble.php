<?php
    session_start();
    $session = $_SESSION['loginLastFMToken'];
    $artist = urldecode($_GET['artist']);
    $track = urldecode($_GET['track']);

    if(!$session || !$artist || !$track){
        die('Invalid data!');
    }

    $key = "c74a96bff24031c6f7a8f677de97a8b3";
    $secret = "fa9ebdac58dda209a9d11f4ab0179185";
    $timestamp = time();
    $sig = md5(
        "api_key" . $key .
        "artist" . $artist .
        "methodtrack.scrobble" .
        "sk" . $session .
        "timestamp" . $timestamp .
        "track" . $track . $secret);
    $url = "http://ws.audioscrobbler.com/2.0/";
    $params = "method=track.scrobble" .
        "&api_key=" . $key .
        "&artist=" . $artist .
        "&track=" . $track .
        "&timestamp=" . $timestamp .
        "&sk=" . $session .
        "&api_sig=" . $sig;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 7);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $response = curl_exec($ch);
    curl_close($ch);
