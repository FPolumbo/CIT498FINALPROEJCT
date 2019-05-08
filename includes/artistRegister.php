<?php
    require_once 'dbtools.php';
    require_once 'debug.php';

    $registerUsername = $registerPassword = $registerEmail = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $artistName = test_input($_POST['registerArtistName']);
        $artistGenre = test_input($_POST['registerArtistGenre']);
        $artistLocation = test_input($_POST['registerArtistLocation']);
        $artistDescription = test_input($_POST['registerArtistBio']);
    }

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // validate
    if($artistName == '' || $artistGenre == '' || $artistLocation == '' ||
        $artistDescription == ''){
        die('Invalid form submission');
    }

    if(register_artist($artistName, $artistGenre, $artistLocation, $artistDescription)){
        // artist registered: redirect
        header('Location: ../profile.php');
    }else{
        // fail condition - account exists w/ same email/name
        die('Artist exists!');
    }