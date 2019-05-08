<?php
    session_start();
    if(!isset($_SESSION['loginLastFMToken'])){
        die('Invalid state!');
    }

    require_once 'dbtools.php';

    if(!unlink_lastfm()){
        die("DB error!");
    }

    header("Location: ../profile.php");