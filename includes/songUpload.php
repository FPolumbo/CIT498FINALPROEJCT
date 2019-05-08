<?php
    require_once 'filetools.php';
    require_once 'debug.php';

    session_start();
    if(!$_SESSION['loginID']){
        die('Must be signed in to upload!');
    }

    $target_dir = "../uploads/songs/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Allow certain file formats
    if ($file_type != "mp3" && $file_type != "ogg" && $file_type != "wav") {
        die("Sorry, only MP3, OGG, & WAV files are allowed.");
    }

    if(!isset($_POST['songName'])){
        die('Song must have a name!');
    }

    if(upload_file($target_file)){
        // upload successful - update DB and redirect
        require_once 'dbtools.php';
        if(!register_track($_POST['songName'], $target_file)){
            die("Database error");
        }
        header("Location: ../profile.php");
    }
    die("Upload failed: too large or already exists.");