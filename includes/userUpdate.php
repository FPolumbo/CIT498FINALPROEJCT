<?php
    session_start();
    if(!isset($_SESSION['loginID'])){
        die("No user logged it!");
    }

    $email = test_input($_POST['editUserEmail']);
    $name = test_input($_POST['editUserName']);
    $oldPass = test_input($_POST['editOldUserPassword']);
    $newPass = test_input($_POST['editUserPassword']);
    $newPassConfirm = test_input($_POST['editUserConfirmPassword']);

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if($email == null || $name == null || $oldPass == null || $newPass == null || $newPass !== $newPassConfirm){
        die("Invalid form data");
    }

    require_once 'dbtools.php';
    if(update_user($email, $name, $oldPass, $newPass)){
        // update done
        header("Location: ../profile.php");
    }

    die("DB error: check password");