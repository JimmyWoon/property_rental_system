<?php
    require 'config/database.php';

    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $password = mysqli_real_escape_string($conn, $_POST['createpassword']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
        $avatar = $_FILES['avatar'];

        // ensure fields are filled
        if (!$username) {
            $_SESSION['create-failed'] = 'Please enter an username.';
        } elseif (!$email) {
            $_SESSION['create-failed'] = 'Please enter an email.';
        } elseif (!$phone) {
            $_SESSION['create-failed'] = 'Please enter a phone number.';
        } elseif (!$role) {
            $_SESSION['create-failed'] = 'Please select a role.';
        } elseif (!$password) {
            $_SESSION['create-failed'] = 'Please enter a password.';
        } elseif (!$confirmPassword) {
            $_SESSION['create-failed'] = 'Please enter the same password.';
        } elseif (!$avatar['name']) {
            $_SESSION['create-failed'] = 'Please select an user profile picture.';
        } else {
            // check if password match 
            if ($password !== $confirmPassword) {
                $_SESSION['create-failed'] = 'Password does not match.';
            } else {
                // hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
                // check if username or email exists
                $find_existing_query = "SELECT * FROM user_tb WHERE Name='$username' OR Email='$email'";
                $find_existing_result = mysqli_query($conn, $find_existing_query);
                
                if (mysqli_num_rows($find_existing_result) > 0) {
                    $_SESSION['create-failed'] = "Username or email is used";
                    mysqli_free_result($find_existing_result);
                } else {
                    // validate user profile picture uploaded
                    $img_name = $_FILES['avatar']['name'];
                    $img_size = $_FILES['avatar']['size'];
                    $tmp_name = $_FILES['avatar']['tmp_name'];
                    $error = $_FILES['avatar']['error'];
                    $imgProp = getimagesize($tmp_name);

                    if ($error === 0) {
                        // make sure file is in acceptable image format and size is below 1MB
                        $allowed_files = ['png', 'jpeg', 'jpg'];
                        $extension = explode('.', $img_name);
                        $extension = end($extension);

                        if (!in_array($extension, $allowed_files)) {
                            $_SESSION['create-failed'] = 'User profile picture should only be in png, jpeg, or jpg';
                        } elseif ($avatar['size'] > 500000) {
                            $_SESSION['create-failed'] = 'Image size is too large.';
                        }
                    }
                }
            }
        }

        // if encounter any problem
        if (isset($_SESSION['create-failed'])) {
            // pass back filled data
            $_SESSION['create-data'] = $_POST;
            header('location: create_user.php');
            die();
        } else {
            // insert user to database 
            $imgData = addslashes(file_get_contents($tmp_name));
            $create_user_query = "INSERT INTO user_tb SET Name='$username', Email='$email', Phone='$phone', Role='$role', Password='$hashed_password', imgName='$img_name', imgType='{$imgProp['mime']}', imgFile='$imgData'";
            $create_user_result = mysqli_query($conn, $create_user_query);

            if (!mysqli_errno($conn)) {
                // redirect to back to create user page
                $_SESSION['create-success'] = 'User created successfully.';
                header('location: create_user.php');
                mysqli_close($conn);
                die();
            }
        }

    } else {
        header('location: create_user.php');
        mysqli_close($conn);
        die();
    }
?>