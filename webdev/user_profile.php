<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/user_profile.css">
    <link rel="stylesheet" href="css/global.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous">
    <title>User Profile</title>

</head>
<body>
    <?php include"partials/header.php"?>
    <?php
        include "config/database.php";
        if (isset($_SESSION['user']['id'])){
            $userID = $_SESSION['user']['id'];

            $userData = "SELECT * from user_tb where id = '{$userID}';";
            $result_user_data = mysqli_query($conn,$userData);
            $resultCheck = mysqli_num_rows($result_user_data);

            if($resultCheck > 0){
                //have data of the particular ID 

                while ($row = mysqli_fetch_assoc($result_user_data)){
                    $name = (is_null($row['Name']) ? NULL : $row['Name']);
                    $phone = (is_null($row['Phone']) ? NULL : $row['Phone']);
                    $email = (is_null($row['Email']) ? NULL : $row['Email']);
                    $role = (is_null($row['Role']) ? NULL : $row['Role']);
                    $password = (is_null($row['Password']) ? NULL : $row['Password']);
                    $imgName = (is_null($row['imgName']) ? NULL : $row['imgName']);
                    $imgType = (is_null($row['imgType']) ? NULL : $row['imgType']);
                    $imgFile = (is_null($row['imgFile']) ? NULL : $row['imgFile']);
                }
                mysqli_free_result($result_user_data);

            }else{
                //that ID not exist in the database
                $_SESSION['modify-failed'] = "User not found";
            }
        }
        else{
            header("Location: ./signin.php");       
        }
        mysqli_close($conn);
    ?>

    <div class="container">
        <div class="MainPanel">
            <div class="left"></div>
            <div class="center">
                <div class="top">
                    <input id="editBtn" class="submit" name="edit"  type="button" value="Edit">
                    <input id="saveBtn" class="submit" name="save" form="userProfileform" type="submit" value="Save">
                    <button id="cancelBtn" onclick="history.back()" class="cancel">Cancel</button>
                </div>
                <?php if(isset($_SESSION['modify-failed'])){?>
                    <div class="error">
                        <p>
                            <?php if (isset($_SESSION['modify-failed'])): ?>
                                <?= $_SESSION['modify-failed']; unset($_SESSION['modify-failed']);?>
                            <?php endif?>
                        </p>
                    </div>
                <?php }?>
                <div class="userInformationContainer">
                    <div class="profile-card">
                        <div class="image-container" id='imgContainer'>
                            <?php 
                                if(isset($imgFile) & !empty($imgFile)){
                                    // has image stored in database
                                    echo "<img class='userImage' id='userImage' src= data:{$imgType};charset=utf8;base64," .base64_encode($imgFile) .">";
                                }else{
                                    echo "<img class='userImage' id='userImage' src='images/user.png'>";
                                }
                            ?>
                        </div>
                        <input type="file" form="userProfileform" id="imageInput" name="image" accept="image/png, image/jpeg" class="file-input" >

                        <label id="choosePht" for="imageInput" style="display: none;">
                            <i class="fas fa-cloud-upload-alt"></i> &nbsp; Choose A Photo
                        </label>
                    </div>
                    <form id="userProfileform" name="userProfileform" class="user_form" method="POST" action="<?= (isset($userID)) ? "user_profile_backend.php?userID={$userID}" : 'user_profile_backend.php';?>" enctype="multipart/form-data">
                        <div class="information">
                            <i class="fa-solid fa-user"></i>
                            <label>Role </label>
                            <input type="text" style="font-weight:bold;" required class="textB" disabled name="role" id="role" value="<?php if (isset($role)) echo $role ; ?>">
                        </div> 
                        <div class="information">
                            <i class="fa fa-address-card" aria-hidden="true"></i>
                            <label>Name </label>
                            <input type="text" required class="textB" disabled name="name" id="name" value="<?php if (isset($name)) echo $name ; ?>">
                        </div>
                        <div class="information">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <label>Phone Number </label>
                            <input type="text" required class="textB" disabled name="phone" id="phone" value="<?php if (isset($phone)) echo $phone ; ?>">
                        </div>
    
                        <div class="information">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <label>E-mail </label>
                            <input type="text" required class="textB" disabled name="mail" id="mail" value="<?php if (isset($email)) echo $email ; ?>">
                        </div>  

                        <input type="checkbox" id="edit_password_btn" name="edit_password_btn" class="file-input" >
                        <label id="edit_password" for="edit_password_btn" style="display: none;">
                            <i class="fa-solid fa-lock"></i> &nbsp; Edit password
                        </label>    

                        <div id="old_password_div" class="information hidden">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <label>Old Passowrd </label>
                            <input type="text" required class="textB" disabled name="oldpassword" id="oldpassword" value="">
                        </div>
                        <div id="new_password_div" class="information hidden">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <label>New Passowrd </label>
                            <input type="text" required class="textB" disabled name="newpassword" id="newpassword" value="">
                        </div>
                    </form>
                </div>
            </div>
            <div class="right"></div>
            
        </div>
        <?php
            include 'partials/footer.php';
        ?>
    </div>
</body>
<script src="./js/user_profile.js"></script>
<script src="./js/nav_bar.js"></script>

</html>