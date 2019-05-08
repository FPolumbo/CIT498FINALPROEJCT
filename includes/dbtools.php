<?php
    function get_db_connection(){
        $host = "localhost";
        $db_username = "nmgpkihx_FYFTopD";
        $db_password = "Madball3!";
        $database = "nmgpkihx_FindYourFrequency";

        $conn = mysqli_connect($host, $db_username, $db_password, $database);

        if(!$conn){
            die("Error: You are not connected to the database!");
        }

        return $conn;
    }

    function get_tracks($artistID){
        $conn = get_db_connection();

        $stmt = $conn->prepare("SELECT * FROM tracks WHERE userID = ?");
        $stmt->bind_param("i", $artistID);
        $stmt->execute();

        $result = $stmt->get_result();
        $tracks = array();
        while($row = $result->fetch_assoc()){
            $trackData = array();
            $trackData['name'] = $row['songName'];
            $trackData['url'] = $row['songURL'];
            array_push($tracks, $trackData);
        }
        $stmt->close();
        $conn->close();
        return $tracks;
    }

    function get_artists(){
        $conn = get_db_connection();

        $stmt = $conn->prepare("SELECT * FROM artists");
        $stmt->execute();

        $result = $stmt->get_result();
        $artists = array();
        while($row = $result->fetch_assoc()){
            $artists[] = $row;
        }
        $stmt->close();
        $conn->close();

        return $artists;
    }

    function get_artist_info($artistID){
        $conn = get_db_connection();

        $stmt = $conn->prepare("SELECT * FROM artists WHERE userID = ?");
        $stmt->bind_param("i", $artistID);
        $stmt->execute();

        $result = $stmt->get_result();
        $artistData = array();
        while($row = $result->fetch_assoc()){
            $artistData = $row;
        }

        $stmt->close();

        $stmt = $conn->prepare("SELECT userEmail FROM users WHERE userID = ?");
        $stmt->bind_param("i", $artistID);
        $stmt->execute();

        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $artistData['email'] = $row['userEmail'];
        }

        $stmt->close();
        $conn->close();
        return $artistData;
    }

    function login_user($username, $password){
        // hash and salt the password
        $password = hash_and_salt($password);

        $conn = get_db_connection();

        // try to get user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE userName = ? AND userPassword = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows == 0){
            return false;   // user does not exist - can't login
        }

        // load data into session
        $sessionLoaded = false;
        session_start();
        while($row = $result->fetch_assoc()){
            // set session data
            $_SESSION['loginID'] = $row['userID'];
            $_SESSION['loginUsername'] = $row['userName'];
            $_SESSION['loginEmail'] = $row['userEmail'];
            $_SESSION['loginIsArtist'] = $row['isArtist'];
            $_SESSION['loginLastFMToken'] = $row['lastfm'];
            $sessionLoaded = true;
        }

        $stmt->close();
        $conn->close();

        if(!$sessionLoaded){
            die('Critical database error: inconsistent result count');
        }
        return true;
    }

    function register_track($songName, $songURL){
        session_start();

        // make sure user is an artist
        if(!$_SESSION['loginIsArtist']){
            return false;
        }

        $conn = get_db_connection();

        $stmt = $conn->prepare("INSERT INTO tracks (userID, songName, songURL) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_SESSION['loginID'], $songName, $songURL);
        $stmt->execute();

        $stmt->close();
        $conn->close();
        return true;
    }

    function register_lastfm($token){
        session_start();

        if(!$_SESSION['loginID']){
            return false;
        }

        $conn = get_db_connection();
        $stmt = $conn->prepare("UPDATE users SET lastfm = ? WHERE userID = ?");
        $stmt->bind_param("si", $token, $_SESSION['loginID']);
        $stmt->execute();

        $_SESSION['loginLastFMToken'] = $token;

        return true;
    }

    function unlink_lastfm(){
        session_start();

        if(!$_SESSION['loginID']){
            return false;
        }

        $conn = get_db_connection();
        $stmt = $conn->prepare("UPDATE users SET lastfm = NULL WHERE userID = ?");
        $stmt->bind_param("i", $_SESSION['loginID']);
        $stmt->execute();

        $_SESSION['loginLastFMToken'] = null;
        return true;
    }

    function register_profile_pic($filepath){
        session_start();

        // make sure user is an artist
        if(!$_SESSION['loginIsArtist']){
            return false;
        }

        $conn = get_db_connection();

        // delete old profile pic
        $stmt = $conn->prepare("SELECT * FROM artists WHERE userID = ?");
        $stmt->bind_param("i", $_SESSION['loginID']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0){
            return false;   // user does not exist?
        }

        while($row = $result->fetch_assoc()){
            if(isset($row['artistPic']) && $row['artistPic'] !== null){
                unlink($row['artistPic']);
            }
        }
        $stmt->close();

        $stmt = $conn->prepare("UPDATE artists SET artistPic = ? WHERE userID = ?");
        $stmt->bind_param("si", $filepath, $_SESSION['loginID']);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        return true;
    }

    function update_artist($name, $genre, $location, $bio){
        session_start();

        if(!isset($_SESSION['loginID'])){
            return false;   // not signed in
        }

        $conn = get_db_connection();
        $stmt = $conn->prepare("UPDATE artists SET artistName = ?, artistGenre = ?, artistLocation = ?, artistBio = ? WHERE userID = ?");
        $stmt->bind_param("ssssi", $name, $genre, $location, $bio, $_SESSION['loginID']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        return true;
    }

    function register_artist($artistName, $genre, $location, $description){
        session_start();

        // check if user is not logged in or already an artist
        if(!isset($_SESSION['loginID']) || $_SESSION['loginIsArtist']){
            return false;
        }

        $conn = get_db_connection();

        // set user is artist
        $stmt = $conn->prepare("UPDATE users SET isArtist = 1 WHERE userID = ?");
        $stmt->bind_param("s", $_SESSION['loginID']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['loginIsArtist'] = 1;

        // submit data
        $stmt = $conn->prepare("INSERT INTO artists (userID, artistName, artistGenre, artistLocation, artistBio) values (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $_SESSION['loginID'], $artistName, $genre, $location, $description);
        $stmt->execute();
        $stmt->close();

        $conn->close();
        return true;
    }

    function delete_user(){
        session_start();
        if(!isset($_SESSION['loginID'])){
            return false;
        }

        $conn = get_db_connection();
        $stmt = $conn->prepare("DELETE FROM users WHERE userID = ?");
        $stmt->bind_param("i", $_SESSION['loginID']);
        $stmt->execute();

        return true;
    }

    function update_user($email, $username, $old_password, $new_password){
        session_start();
        if(!isset($_SESSION['loginID'])){
            return false;   // not logged in
        }

        $conn = get_db_connection();

        $stmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
        $stmt->bind_param("i", $_SESSION['loginID']);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows == 0){
            return false;   // bad session info
        }

        while($row = $result->fetch_assoc()){
            $stored_password = $row['userPassword'];
        }
        $stmt->close();

        if(!isset($stored_password) || hash_and_salt($old_password) !== $stored_password){
            return false;   // bad password
        }
        // password good - update DB
        $stmt = $conn->prepare("UPDATE users SET userName = ?, userEmail = ?, userPassword = ? WHERE userID = ?");
        $stmt->bind_param("sssi", $username, $email, hash_and_salt($new_password), $_SESSION['loginID']);
        $stmt->execute();

        $stmt->close();
        $conn->close();

        $_SESSION['loginUsername'] = $username;
        $_SESSION['loginEmail'] = $email;

        return true;
    }

    function register_user($email, $username, $password){
        $conn = get_db_connection();

        // check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE userName = ? OR userEmail = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows != 0){
            $stmt->close();
            $conn->close();
            return false;   // user exists - can't register
        }

        // hash and salt the password
        $password = hash_and_salt($password);

        $stmt = $conn->prepare("INSERT INTO users (userName, userPassword, userEmail) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();

        $stmt->close();
        $conn->close();
        return true;
    }

    function hash_and_salt($password){
        return hash('sha512', $password . 'frank');
    }