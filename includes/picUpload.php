<?php
    require_once 'filetools.php';
    require_once 'debug.php';

    session_start();
    if(!$_SESSION['loginIsArtist']){
        die('Must be signed in as artist to upload!');
    }

    $target_dir = "../uploads/pics/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Paranoia: check if PHP thinks it's an image
    if(getimagesize($_FILES["fileToUpload"]["tmp_name"]) === false){
        die("File must be an image!");
    }

    // Allow certain file formats
    if ($file_type != "jpg" && $file_type != "jpeg" && $file_type != "png" && $file_type != "gif") {
        die("Sorry, only JPG, PNG, & GIF files are allowed.");
    }

    if(upload_file($target_file)){
        // upload successful - update DB and redirect
        require_once 'dbtools.php';
        if(!register_profile_pic($target_file)){
            die("Database error");
        }

        header("Location: ../profile.php");
    }
    die("Upload failed: too large or already exists.");
