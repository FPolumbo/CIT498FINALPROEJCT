<?php
    require_once 'debug.php';

    function upload_file($target_file){
        // Check if file already exists
        if (file_exists($target_file)) {
            return false;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            return false;
        }

        return move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    }