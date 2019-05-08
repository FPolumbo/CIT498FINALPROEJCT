<?php
    require_once 'dbtools.php';
    require_once 'debug.php';

    $loginUsername = $loginPassword = "";

    if($_SERVER['REQUEST_METHOD'] == "POST")
	{
        $loginUsername = test_input($_POST['loginUsername']);
        $loginPassword = test_input($_POST['loginPassword']);
    }

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // validate
    if($loginUsername == '' || $loginPassword == ''){
        die('Invalid form submission');
    }

    if(login_user($loginUsername, $loginPassword)){
        // logged in - redirect
        header('Location: ../profile.php');
    }else{
        // user doesn't exist
        die("User doesn't exist");
    }