<?php
require 'config/database.php';

function active($currect_page){
    $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
    $url = end($url_array);  
    if($currect_page == $url){
        echo 'active-link';
    } 
}

$imgFile = $_SESSION['user']['imgFile'] ?? null;
$imgType = $_SESSION['user']['imgType'] ?? null;

?>

<!--=============== REMIXICONS ===============-->
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

<!--=============== CSS ===============-->
<link rel="stylesheet" href="css/header.css">
        

<header class="header" id="header">
    <nav class="nav mainContainer">
        <a href="home.php"><img src = "images/logo.png" class="navLogo"></a>

        <div class="navMenu" id="nav-menu">
            <ul class="navList">
                <li class="navItem"><a href="home.php" class="navLink <?php active('home.php');?>">Home</a></li>
                <li class="navItem"><a href="property_list.php" class="navLink <?php active('property_list.php');?>">Properties</a></li>
                <?php if (isset($_SESSION['user'])) : 
                    if ($_SESSION['user']['Role'] == "Host" || $_SESSION['user']['Role'] == "Admin") : ?>
                        <li class="navItem"><a href="property_list.php?manage=1" class="navLink <?php active('property_list.php?manage=1');?>">Manage Properties</a></li>
                    <?php endif ?>

                    <li class="navItem inviItem"><a href="user_profile.php" class="navLink <?php active('user_profile.php');?>">Edit Profile</a></li>
                    <?php if ($_SESSION['user']['Role'] == "Admin") : ?>
                        <li class="navItem inviItem"><a href="create_user.php" class="navLink <?php active('create_user.php');?>">Create User</a></li>
                    <?php endif ?>
                    <li class="navItem inviItem"><a href="signin.php" class="navLink <?php active('signin.php');?>"><span class="logout">Log Out</span></a></li>

                    <li class="navItem userMenu">
                        <?php 
                            if(isset($imgFile) & !empty($imgFile)){
                                echo "<img class='navLink userPic' id='userMenu' src= data:{$imgType};charset=utf8;base64," .base64_encode($imgFile) .">";
                            }else{
                                echo "<img class='navLink userPic' id='userMenu' src='images/user.png'>";
                            }
                        ?>
                    </li>
                    
                    <div class="subMenuWrap" id="subMenu">
                        <div class="subMenu">
                            <div class="userInfo">
                                <?php 
                                    if(isset($imgFile) & !empty($imgFile)){
                                        echo "<img src= data:{$imgType};charset=utf8;base64," .base64_encode($imgFile) .">";
                                    }else{
                                        echo "<img src='images/user.png'>";
                                    }
                                ?>
                                <h2><?= $_SESSION['user']['Name']?></h2>
                            </div>
                            <hr>
                            <a href="user_profile.php" class="subMenuLink <?php active('user_profile.php');?>">
                                <i class="ri-profile-line"></i>
                                <p>View Profile</p>
                            </a>
                            <?php if ($_SESSION['user']['Role'] == "Admin") : ?>
                                <a href="create_user.php" class="subMenuLink <?php active('create_user.php');?>">
                                <i class="ri-user-add-line"></i>
                                <p>Create User</p>
                                </a>
                            <?php endif ?>
                            <a href="signin.php" class="subMenuLink logout">
                            <i class="ri-logout-box-line"></i>
                            <p>Log Out</p>
                            </a>

                        </div>
                    </div>
                    <!-- <li class="navItem"><a href="signin.php" class="navLink" id="nav-logout">Log Out</a></li> -->
                <?php else : ?>
                    <li class="navItem"><a href="signin.php" class="navLink <?php active('signin.php');?>">Log In</a></li>
                    <li class="navItem"><a href="signup.php" class="navLink <?php active('signup.php');?>">Sign Up</a></li>
                <?php endif ?>
            </ul>

            <div class="navClose" id="nav-close">
                <i class="ri-close-line"></i>
            </div>
        </div>

        <div class="navToggle" id="nav-toggle">
            <i class="ri-menu-line"></i>
        </div>
    </nav>
</header>