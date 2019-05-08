<?php
    require_once 'dbtools.php';
    require_once 'debug.php';

    $registerUsername = $registerPassword = $registerEmail = "";
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $registerUsername = test_input($_POST['registerUserName']);
        $registerPassword = test_input($_POST['registerUserPassword']);
        $registerConfirm  = test_input($_POST['registerUserConfirmPassword']);
        $registerEmail    = test_input($_POST['registerUserEmail']);
	}

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // validate
    if($registerUsername == '' || $registerPassword == '' || $registerEmail == '' ||
        $registerConfirm !== $registerPassword){
        die('Invalid form submission');
    }

	if(register_user($registerEmail, $registerUsername, $registerPassword)){
	    // user registered: login and redirect
        login_user($registerUsername, $registerPassword);
        header('Location: ../profile.php');
    }else{
	    // fail condition - account exists w/ same email/name
        die('User exists!');
    }