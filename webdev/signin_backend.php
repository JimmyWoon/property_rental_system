<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    // get form data
    $username_email = (isset($_POST['username_email']) ? mysqli_real_escape_string($conn,$_POST['username_email']) : 'NULL');
    $password = (isset($_POST['password']) ? mysqli_real_escape_string($conn,$_POST['password']) : 'NULL');


    if (!$username_email) {
        $_SESSION['signin'] = "Username or Email required";
    } else if (!$password) {
        $_SESSION['signin'] = "Password required";
    } else {
        // fetch user from database
        $fetch_user_query = "SELECT * FROM user_tb WHERE name='$username_email' OR email='$username_email'";
        $fetch_user_result = mysqli_query($conn, $fetch_user_query);

        if (mysqli_num_rows($fetch_user_result) == 1) {
            // convert the record into assoc array
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['Password'];

            if (password_verify($password, $db_password)) {
                // set session for access control
                $_SESSION['user'] = $user_record;
                // log user in
                header('location: property_list.php');
            } else {
                $_SESSION['signin'] = "Incorrect password";
            }
        } else {
            $_SESSION['signin'] = "User not found";
        }
    }

    // if any problem, redirect back to signin page with login data
    if(isset($_SESSION['signin'])) {
        $_SESSION['signin-data'] = $_POST;
        header("location: signin.php");
        die();
    }
} else {
    header('location: signin.php');
    die();
}