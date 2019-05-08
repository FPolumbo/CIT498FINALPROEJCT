<?php
    session_start();
    if(!isset($_SESSION['loginID'])){
        die("No user logged it!");
    }

    $name = test_input($_POST['editArtistName']);
    $genre = test_input($_POST['editArtistGenre']);
    $location = test_input($_POST['editArtistLocation']);
    $bio = test_input($_POST['editArtistBio']);

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if($name == null || $genre == null || $location == null || $bio == null){
        die("Invalid form data");
    }

    require_once 'dbtools.php';
    if(update_artist($name, $genre, $location, $bio)){
        // update done
        header("Location: ../profile.php");
    }

    die("DB error: check password");