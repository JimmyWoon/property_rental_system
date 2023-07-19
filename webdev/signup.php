<?php
require 'config/database.php';

$username = $_SESSION['signup-data']['username'] ?? null;
$email = $_SESSION['signup-data']['email'] ?? null;
$phone = $_SESSION['signup-data']['phone'] ?? null;
$createpassword = $_SESSION['signup-data']['createpassword'] ?? null;
$confirmPassword = $_SESSION['signup-data']['confirmpassword'] ?? null;
unset($_SESSION['signup-data']);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signin.css">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <title>Sign Up</title>
</head>

<body>
    <?php include "partials/header.php" ?>

    <section class="container">
        <div class="signupContent">
            <img src="images/bg-login.png" alt="login image" class="signupImg">
            <form action="signup_backend.php" method="POST" enctype="multipart/form-data" class="signupForm">
                <div>
                    <h1 class="loginTitle">
                        <span>Register</span> Account
                    </h1>
                    <p class="loginDescription">
                        Please enter your account details.
                    </p>
                </div>


                <?php if (isset($_SESSION['signup-failed'])) : ?>
                    <div class="error">
                        <p>
                            <?= $_SESSION['signup-failed'];
                            unset($_SESSION['signup-failed']); ?>
                        </p>
                    </div>
                <?php endif ?>

                <div class="signupInputs">
                    <div>
                        <div class="inputContainer">
                            <label for="username" class="loginLabel">Username</label>
                            <input type="text" name="username" placeholder="Enter username" value='<?= $username ?>' class="loginInput">
                        </div>
                        <div class="inputContainer">
                            <label for="email" class="loginLabel">Email</label>
                            <input type="email" name="email" placeholder="Enter email" value='<?= $email ?>' class="loginInput">
                        </div>
                        <div class="inputContainer">
                            <label for="phone" class="loginLabel">Contact Number</label>
                            <input type="tel" name="phone" placeholder="Enter phone number" value='<?= $phone ?>' class="loginInput">
                        </div>
                    </div>
                    <div>
                        <div class="inputContainer">
                            <label for="createpassword" class="loginLabel">Password</label>
                            <input type="password" name="createpassword" placeholder="Password" value='<?= $createpassword ?>' class="loginInput">
                        </div>
                        <div class="inputContainer">
                            <label for="confirmpassword" class="loginLabel">Confirm Password</label>
                            <input type="password" name="confirmpassword" placeholder="Confirm Password" value='<?= $confirmPassword ?>' class="loginInput">
                        </div>

                    </div>
                </div>
                <div class="signupInputs">
                    <div>
                        <label for="role" class="loginLabel">Register As</label>
                        <input type="radio" name="role" value="Tenant">Tenant
                        <input type="radio" name="role" value="Host">Host
                    </div>
                    <div>
                        <label for="avatar" class="loginLabel">User Avatar</label>
                        <input type="file" name="avatar">
                    </div>

                </div>


                <div class="loginButtons">
                    <button class="loginButton" name="submit" type="submit">Sign Up</button>
                </div>
            </form>
        </div>
    </section>

    <?php include 'partials/footer.php' ?>

    <script src="js/nav_bar.js"></script>
</body>

</html>