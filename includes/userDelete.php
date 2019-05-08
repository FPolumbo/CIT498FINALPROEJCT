<?php
    session_start();
    if(!isset($_SESSION['loginID'])){
        die("No user signed in");
    }
    require_once 'dbtools.php';
    if(delete_user()){
        header("Location: ../index.php");
    }
    die("Database error");