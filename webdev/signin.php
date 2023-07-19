<?php
require 'config/database.php';

$username_email = $_SESSION['signin-data']['username_email'] ?? null;
$password = $_SESSION['signin-data']['password'] ?? null;

unset($_SESSION['user']);
unset($_SESSION['signin-data']);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/signin.css">

    <title>Login</title>
</head>
<body>
    <?php include "partials/header.php" ?>

    <section class="container">
        <div class="loginContent">
            <img src="images/bg-login.png" alt="login image" class="loginImg">

            <form action="signin_backend.php" method="POST" class="loginForm">
                <div>
                    <h1 class="loginTitle">
                        <span>Welcome</span> Back
                    </h1>
                    <p class="loginDescription">
                        Welcome! Please login to continue.
                    </p>
                </div>

                <?php if (isset($_SESSION['signup-success'])) : ?>
                    <div class="success">
                        <p>
                            <?= $_SESSION['signup-success'];
                            unset($_SESSION['signup-success']);
                            ?>
                        </p>
                    </div>
                <?php elseif (isset($_SESSION['signin'])) : ?>
                    <div class="error">
                        <p>
                            <?= $_SESSION['signin'];
                            unset($_SESSION['signin']);
                            ?>
                        </p>
                    </div>
                <?php endif ?>
                <div>
                    <div class="loginInputs">
                        <div>
                            <label for="" class="loginLabel">Email</label>
                            <input type="email" name="username_email" value="<?= $username_email ?>" placeholder="Enter your email address" class="loginInput">
                        </div>

                        <div>
                            <label for="" class="loginLabel">Password</label>
                            <div class="loginBox">
                                <input type="password" name="password" value="<?= $password ?>" placeholder="Enter your password" class="loginInput" id="inputPass">
                                <i class="ri-eye-off-line loginEye" id="inputIcon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="loginButtons">
                        <button type="submit" name="submit" class="loginButton">Log In</button>
                        <a href="signup.php" class="loginButton loginButton-ghost">Sign Up</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <?php include 'partials/footer.php' ?>

    <script src="js/login.js"></script>
    <script src="js/nav_bar.js"></script>
</body>

</html>