<?php
    // require 'config/constants.php';
    $sname = "localhost";
    $uname = 'root';
    $password = '';

    $db_name = 'webdev_db';

    $conn = mysqli_connect($sname,$uname,$password,$db_name);

    if (!$conn){
        echo "Connection failed";
        exit();
    }

    if(!isset($_SESSION)){
        session_start();
    }
?>