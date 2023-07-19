<?php
    require 'config/database.php';
    if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
        header('location: property_list.php');
    }
    $username = $_SESSION['create-data']['username'] ?? null;
    $email = $_SESSION['create-data']['email'] ?? null;
    $phone = $_SESSION['create-data']['phone'] ?? null;
    $createpassword = $_SESSION['create-data']['createpassword'] ?? null;
    $confirmPassword = $_SESSION['create-data']['confirmpassword'] ?? null;
    unset($_SESSION['create-data']);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/create_user.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <title>Create User</title></head>
    <body>
    <?php include "partials/header.php"?>

    <section class="mainContainer">
        <div class="form-container">
            <?php if (isset($_SESSION['create-failed']) || isset($_SESSION['create-success'])): ?>
                <div class="<?php if(isset($_SESSION['create-failed'])) echo' error'; if(isset($_SESSION['create-success'])) echo' success'?>">
                    <p>
                        <?php if (isset($_SESSION['create-failed'])): ?>
                            <?= $_SESSION['create-failed']; unset($_SESSION['create-failed']);?>
                        <?php elseif (isset($_SESSION['create-success'])): ?>
                            <?= $_SESSION['create-success']; unset($_SESSION['create-success']);?>
                        <?php endif?>
                    </p>
                </div>
            <?php endif?>
            <div>
                <h2><span>Create  Us</span>er Account</h2>
                <form action="create_user_backend.php" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="username">Username</label>
                        <input type="text" name="username" placeholder="Enter username" value='<?= $username?>'>
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Enter email" value='<?= $email?>'>
                    </div>
                    <div>
                        <label for="phone">Contact Number</label>
                        <input type="tel" name="phone" placeholder="Enter phone number" value='<?= $phone?>'>
                    </div>
                    <div>
                        <label for="role">Register As</label>
                        <input type="radio" name="role" value="Tenant">Tenant
                        <input type="radio" name="role" value="Host">Host
                        <input type="radio" name="role" value="Admin">Admin
                    </div>
                    <div id="create-password">
                        <label for="createpassword">Password</label>
                        <input id='createpassword' type="password" name="createpassword" placeholder="Password" value='<?= $createpassword?>'>
                        <i onclick="toggleVisibility('create-password i', 'createpassword')" class="fa-regular fa-eye-slash"></i>
                    </div>
                    <div id="confirm-password">
                        <label for="confirmpassword">Confirm Password</label>
                        <input id="confirmpassword" type="password" name="confirmpassword" placeholder="Confirm Password" value='<?= $confirmPassword?>'>
                        <i onclick="toggleVisibility('confirm-password i', 'confirmpassword')" class="fa-regular fa-eye-slash"></i>
                    </div>
                    <div>
                        <label for="avatar">User Avatar</label>
                        <input type="file" name="avatar">
                    </div>
                    <button class="btn" name="submit" type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'partials/footer.php'?>

    <script src="js/nav_bar.js"></script>
    <script>
        function toggleVisibility(iconId, inputId) {
            const icon = document.querySelector(`#${iconId}`);
            const toToggle = document.getElementById(inputId);
            icon.className === 'fa-regular fa-eye-slash' ? icon.className = 'fa-regular fa-eye': icon.className = 'fa-regular fa-eye-slash';
            toToggle.type === 'password' ? toToggle.type = 'text': toToggle.type = 'password';
        }
    </script>

</body>
</html>