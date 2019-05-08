<?php
    session_start();
    if(!isset($_SESSION['loginID']) || !isset($_GET['token'])){
        // someone's not using this correctly
        die('Invalid data!');
    }

    $token = urlencode($_GET['token']);

    if(strlen($token) != 32){
        die('Invalid token!');
    }

    $key = "c74a96bff24031c6f7a8f677de97a8b3";
    $secret = "fa9ebdac58dda209a9d11f4ab0179185";
    $sig = md5("api_key".$key."methodauth.getSessiontoken".$token.$secret);
    $url = "http://ws.audioscrobbler.com/2.0/?method=auth.getSession&api_key=".$key."&token=".$token."&api_sig=".$sig;

    // get the actual token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $xml = simplexml_load_string($response);

    if($xml == false || !$xml->session->key){
        die('Invalid server response!');
    }

    $token = (string)$xml->session->key;

    require_once 'dbtools.php';
    if(register_lastfm($token)){
        header('Location: ../profile.php');
    }

    die('Database error!');
