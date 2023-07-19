<?php
    require 'config/database.php';

    $viewpropertyquery = "SELECT * from property_tb order by Rating desc LIMIT 10";

    $result = mysqli_query($conn, $viewpropertyquery);
    if($result != null) {
        $resultCheck = mysqli_num_rows($result);
    }

    if($resultCheck > 0){

        while ($row = mysqli_fetch_assoc($result)){
            $property = array();

            $property["id"] = $row['id'];
            $property["title"] = (is_null($row['PropertyName']) ? NULL : $row['PropertyName']);
            $property["description"] = (is_null($row['Description']) || empty($row['Description']) ? NULL : $row['Description']);
            $property["status"] = (is_null($row['Status']) ? NULL : $row['Status']);
            $property["price"] = (is_null($row['Price']) ? NULL : $row['Price']);
            $property["rating"] = (is_null($row['Rating']) ? NULL : $row['Rating']);

            $propertyImage = "SELECT * from propertyimg_tb where propertyID = {$row['id']};";
            $ImageObtained = mysqli_query($conn,$propertyImage);
            $ImageresultCheck = mysqli_num_rows($ImageObtained);
            if ($ImageresultCheck > 0){
                while ($row = mysqli_fetch_assoc($ImageObtained)){
                    $property["imageName"] = $row['imageName'];
                    $property["imageType"] = $row['imageType'];
                    $property["imageFile"] = $row['imageFile'];
                    break;
                }
                mysqli_free_result($ImageObtained);
            }

            $properties[$property["id"]] = $property;

        }
        mysqli_free_result($result);
    }

?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--=============== REMIXICONS ===============-->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

        <!--=============== CSS ===============-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/home.css">

        <title>Home</title>
    </head>
    
    <body>
        <?php include "partials/header.php"?>

        <!--==================== MAIN ====================-->
        <main class="main">
            <!--==================== HOME ====================-->
            <section class="home section" id="home">
                <div class="homeContainer mainContainer grid">
                    <div class="homeData">
                        <h1 class="homeTitle">
                            Discover <br> Most Suitable <br> Property
                        </h1>
                        <p class="homeDescription">
                            Find a variety of properties that suit you very easily, forget all difficulties in renting a residence for you
                        </p>

                        <form action="property_list.php" method="GET" class="homeSearch">
                            <i class="ri-map-pin-2-fill"></i>
                            <input name="searchtext" type="search" placeholder="Search by keyword..." class="homeSearch-input">
                            <button class="button">Search</button>
                        </form>

                        <div class="homeValue">
                            <div>
                                <h1 class="homeValue-number">
                                    9K <span>+</span>
                                </h1>
                                <span class="homeValue-description">
                                    Premium <br> Product
                                </span>
                            </div>
                            <div>
                                <h1 class="homeValue-number">
                                    2K <span>+</span>
                                </h1>
                                <span class="homeValue-description">
                                    Happy <br> Customer
                                </span>
                            </div>
                            <div>
                                <h1 class="homeValue-number">
                                    28K <span>+</span>
                                </h1>
                                <span class="homeValue-description">
                                    Awards <br> Winning
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="homeImages">
                        <div class="homeOrbe"></div>

                        <div class="homeImg">
                            <img src="images/property4.jpg" alt="">
                        </div>
                    </div>
                </div>
            </section>

            <!--==================== POPULAR ====================-->
            <section class="popular section" id="popular">
                <div class="mainContainer">
                    <span class="sectionSubtitle">Best Choice</span>
                    <h2 class="sectionTitle">
                        Popular Residences <span>.</span>
                    </h2>

                    <div class="popularContainer swiper">
                        <div class="swiper-wrapper">
                            <?php 
                                foreach ($properties as $property) {
                            ?>
                                <a href='property_details.php?id=".$property["id"]."'>
                                    <article class="popularCard swiper-slide">
                                        <?php 
                                            if(isset($property["imageFile"])){
                                                echo "<a href='property_details.php?id=".$property["id"]."'>
                                                        <img class='popularImg' src= data:{$property["imageType"]};charset=utf8;base64," .base64_encode($property["imageFile"]) .">
                                                    </a>";
                                            }
                                        ?>

                                        <div class="popularData">
                                            <h2 class="popularPrice">
                                                <span>RM</span> <?php if(isset($property["price"])) {echo $property["price"];} ?> <span>/ day</span>
                                            </h2>
                                            <h3 class="popularTitle"><?php if(isset($property["title"])) { echo $property["title"];} ?></h3>
                                            <p class="popularDescription">                                    
                                                <?php 
                                                if(isset($property["description"])) {
                                                    if(strlen($property["description"]) > 65) {
                                                        echo substr($property["description"], 0, 65)."...";
                                                    } else {
                                                        echo $property["description"];
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </article>
                                </a>
                            <?php  
                                }
                            ?>
                        </div>

                        <div class="swiper-button-next">
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                        <div class="swiper-button-prev">
                            <i class="ri-arrow-left-s-line"></i>
                        </div>
                    </div>
                </div>
            </section>

            <!--==================== VALUE ====================-->
            <section class="value section" id="value">
                <div class="valueContainer mainContainer grid">
                    <div class="valueImages">
                        <div class="valueOrbe"></div>
                        <div class="valueImg">
                            <img src="images/property3.jpg" alt="">
                        </div>
                    </div>

                    <div class="valueContent">
                        <div class="valueData">
                            <span class="sectionSubtitle">Our Features</span>
                            <h2 class="sectionTitle">
                                Features We Give To You <span>.</span>
                            </h2>
                            <p class="valueDescription">
                                We always ready to help by providing the best service for you. We believe that our websites are prepared with features that could help u to rent or list a property easily.
                            </p>
                        </div>

                        <div class="valueAccordion">
                            <div class="valueAccordion-item">
                                <header class="valueAccordion-header">
                                    <i class="ri-markdown-line  valueAccordion-icon"></i>
                                    <h3 class="valueAccordionTitle">
                                        Search properties easily
                                    </h3>
                                    <div class="valueAccordion-arrow">
                                        <i class="ri-arrow-drop-down-line"></i>
                                    </div>
                                </header>

                                <div class="valueAccordion-content">
                                    <p class="valueAccordion-description">
                                        Easily search for the desired properties through the search or filter functions to find your dream property or compare the latest prices on the market.
                                    </p>
                                </div>
                            </div>
                            <div class="valueAccordion-item">
                                <header class="valueAccordion-header">
                                    <i class="ri-markdown-line  valueAccordion-icon"></i>
                                    <h3 class="valueAccordionTitle">
                                        List your property publicly
                                    </h3>
                                    <div class="valueAccordion-arrow">
                                        <i class="ri-arrow-drop-down-line"></i>
                                    </div>
                                </header>

                                <div class="valueAccordion-content">
                                    <p class="valueAccordion-description">
                                        Agents could upload their property details on the 'Manage Properties' page and make their properties visible for everyone to see.
                                    </p>
                                </div>
                            </div>
                            <div class="valueAccordion-item">
                                <header class="valueAccordion-header">
                                    <i class="ri-markdown-line  valueAccordion-icon"></i>
                                    <h3 class="valueAccordionTitle">
                                        No third party fee charged
                                    </h3>
                                    <div class="valueAccordion-arrow">
                                        <i class="ri-arrow-drop-down-line"></i>
                                    </div>
                                </header>

                                <div class="valueAccordion-content">
                                    <p class="valueAccordion-description">
                                        Prices provided is the best for you. We did not charge any fee from both tenant and agent. All the prices published are set by the agents themselves.
                                    </p>
                                </div>
                            </div>
                            <div class="valueAccordion-item">
                                <header class="valueAccordion-header">
                                    <i class="ri-markdown-line  valueAccordion-icon"></i>
                                    <h3 class="valueAccordionTitle">
                                        Easy to use user interface
                                    </h3>
                                    <div class="valueAccordion-arrow">
                                        <i class="ri-arrow-drop-down-line"></i>
                                    </div>
                                </header>

                                <div class="valueAccordion-content">
                                    <p class="valueAccordion-description">
                                        We provide the best user experience to our users. The user interface is designed to make sure every age group is able to see and use the website easily.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!--==================== CONTACT ====================-->
            <section class="contact section" id="contact">
                <div class="contactContainer mainContainer grid">
                    <div class="contactContent">
                        <div class="contactData">
                            <span class="sectionSubtitle">Contact Us</span>
                            <h2 class="sectionTitle">
                                Easy to Contact Us <span>.</span>
                            </h2>
                            <p class="contactDescription">
                                Is there a problem posting or finding your property? Need a consulation or found issues while browsing the website? Just contact us.
                            </p>
                        </div>

                        <div class="contactCard">
                            <div class="contactCard-box">
                                <div class="contactCard-info">
                                    <i class="ri-phone-fill"></i>
                                    <div>
                                        <h3 class="contactCard-title">
                                            Call
                                        </h3>
                                        <p class="contactCard-description">
                                            123-456789
                                        </p>
                                    </div>
                                </div>

                                <button class="button contactCard-button">
                                    Call Now
                                </button>
                            </div>
                            <div class="contactCard-box">
                                <div class="contactCard-info">
                                    <i class="ri-chat-1-fill"></i>                                    <div>
                                        <h3 class="contactCard-title">
                                            Message
                                        </h3>
                                        <p class="contactCard-description">
                                            123-456789
                                        </p>
                                    </div>
                                </div>

                                <button class="button contactCard-button">
                                    Message Now
                                </button>
                            </div>
                            <div class="contactCard-box">
                                <div class="contactCard-info">
                                   <i class="ri-video-chat-fill"></i>                                    <div>
                                        <h3 class="contactCard-title">
                                            Video Call
                                        </h3>
                                        <p class="contactCard-description">
                                            123-456789
                                        </p>
                                    </div>
                                </div>

                                <button class="button contactCard-button">
                                    Video Call Now
                                </button>
                            </div>
                            <div class="contactCard-box">
                                <div class="contactCard-info">
                                    <i class="ri-mail-fill"></i>                                    <div>
                                        <h3 class="contactCard-title">
                                            Email
                                        </h3>
                                        <p class="contactCard-description">
                                            staybnb@gmail.com
                                        </p>
                                    </div>
                                </div>

                                <button class="button contactCard-button">
                                    Email Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!--==================== SUBSCRIBE ====================-->
            <section class="start section">
                <div class="startContainer mainContainer">
                    <h1 class="startTitle">
                        Get Started with Staybnb
                    </h1>
                    <p class="startDescription">
                        Login to find super attractive properties from us or post your property on sale to everyone.
                    </p>
                    <div class="startButtons">
                        <a href="signin.php" class="button loginButton">Log In</a>
                        <a href="signup.php" class="button signupButton">Sign Up</a>
                    </div>
                </div>
            </section>
        </main>
        <?php include 'partials/footer.php'?>

        <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>        
        <script src="js/home.js"></script>
        <script src="js/nav_bar.js"></script>
    </body>
</html>