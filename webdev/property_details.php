<?php
    require 'config/database.php';

    // Get property details
    if (!isset($_GET['id'])) {
        header('location: property_list.php');
        die();
    }
    $propertyID = $_GET['id'];
    $fetch_details_query = "SELECT * FROM property_tb WHERE id = $propertyID";
    $fetch_details_result = mysqli_query($conn, $fetch_details_query);
    $result;
    if (mysqli_num_rows($fetch_details_result) == 1) {
        $result = mysqli_fetch_assoc($fetch_details_result);
    }

    // Get property images
    $fetch_images_query = "SELECT * FROM propertyimg_tb WHERE propertyID = $propertyID";
    $fetch_images_result = mysqli_query($conn, $fetch_images_query);
    $images_array = array();
    if (mysqli_num_rows($fetch_images_result) > 0){
        while ($row = mysqli_fetch_assoc($fetch_images_result)){
            $images_array[] = array($row['imageName'],$row['imageType'],$row['imageFile']);
        }
    }

    // Get property host details
    $fetch_host_query = "SELECT Name, Phone FROM user_tb WHERE id = {$result['AgentID']}";
    $fetch_host_result = mysqli_query($conn, $fetch_host_query);
    $host_result;
    if (mysqli_num_rows($fetch_host_result) == 1) {
        $host_result = mysqli_fetch_assoc($fetch_host_result);
    }

    // Get property reviews
    $fetch_reviews_query = "SELECT * FROM review_tb WHERE propertyID = $propertyID ORDER BY time DESC";
    $fetch_reviews_result = mysqli_query($conn, $fetch_reviews_query);
    $reviews_array = array();
    if (mysqli_num_rows($fetch_reviews_result) > 0){
        while ($row = mysqli_fetch_assoc($fetch_reviews_result)){
            $reviews_array[] = array($row['username'],$row['userComment'],$row['userRating'],$row['time']);
        }
    }

    mysqli_free_result($fetch_details_result);
    mysqli_free_result($fetch_host_result);
    mysqli_free_result($fetch_images_result);
    mysqli_free_result($fetch_reviews_result);
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/property_details.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- swiper js cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <title>Property Details</title>
</head>

<body>
    <?php include "partials/header.php"?>

    <!-- property main details -->
    <section class="main-details">
        <div class=" mainContainer">
            <div class="flex-display">
                <h2><?= $result['PropertyName']?></h2>
                <div class="flex-display main-review-stars">
                <div>
                    <?php
                        $stars = 0;
                        $half_star = false;
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < floor($result['Rating'])) {
                                echo "<i class='fa-solid fa-star'></i>";
                                $stars++;
                            } elseif (is_numeric( $result['Rating'] ) && floor( $result['Rating'] ) != $result['Rating'] && !$half_star) {
                                echo "<i class='fa-solid fa-star-half-stroke'></i>";
                                $stars++;
                                $half_star = true;
                            } else {
                                echo "<i class='fa-regular fa-star'></i>";
                            }
                        }
                    ?> 
                </div>
                <p><?= '( '.number_format($result['Rating'], 2).' from '.'<span>'.count($reviews_array)?> review(s)</span> )</p>
            </div>
            </div>
            <div class="flex-display main-details-small">
                <p><b>Status: </b> <?= $result['Status']?></p>                    
                <p><b>Address:</b> <?= $result['Address']?></p>
            </div>
        </div>
    </section>

    <!-- property images slider -->
    <section class=" swiper mySwiper mainContainer">
        <div class="swiper-wrapper">
            <?php
                foreach($images_array as $image) {
                    echo "            
                    <article class='swiper-slide'>
                        <img src=data:{$image[1]};charset=utf8;base64," .base64_encode($image[2]) .">
                    </article>";
                }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </section>

    <section class="fullscreen">
        <span>&times;</span>
        <img src="">
    </section>

    <!-- property description -->
    <section class="details">
        <div class="mainContainer sub-container">
            <div class="general-details">
                <div class="agent">
                    <h3>HOST</h3>
                    <div class="agent-details flex-display center">
                        <div class="agent-name flex-display small-gap">
                            <i class="fa-solid fa-user-tie"></i>
                            <p><b>Name: </b><?= $host_result['Name']?></p>
                        </div>
                        <div class="agent-contact flex-display small-gap">
                            <i class="fa-solid fa-phone"></i>
                            <p><b>Contact: </b><?= $host_result['Phone']?></p>
                        </div>
                    </div>
                </div>
                <h3>OVERVIEW</h3>
                <div class="flex-display">
                    <div class="price">
                        <p>RM<?= $result['Price']?> / <span>day</span></p>
                    </div>
                    <div class="grid-display">
                        <div class="type flex-display small-gap">
                            <i class="fa-sharp fa-solid fa-house"></i>
                            <p><?= $result['Type']?></p>
                        </div>
                        <div class="flex-display small-gap">
                            <i class="fa-solid fa-shoe-prints"></i>
                            <p><?= $result['SquareFeet']?> sqft</p>
                        </div>
                        <div class="flex-display small-gap">
                        <i class="fa-solid fa-bed"></i>
                            <p><?= $result['Room']?> Bed Rooms</p>
                        </div>
                        <div class="flex-display small-gap">
                            <i class="fa-sharp fa-solid fa-bath"></i>
                            <p><?= $result['Bathroom']?> Bath Rooms</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="facilities">
                <h3>FACILITIES & AMENITIES</h3>
                <div class="flex-display">
                    <?php
                        if ($result['Gym'] == 1) {
                            echo "
                                <article class='flex-display small-gap'>
                                    <i class='fa-solid fa-dumbbell'></i>
                                    <p>Gym</p>
                                </article>
                            ";
                        }
                        if ($result['Swim'] == 1) {
                            echo "
                                <article class='flex-display small-gap'>
                                    <i class='fa-solid fa-water-ladder'></i>
                                    <p>Pool</p>
                                </article>
                            ";
                        }
                        if ($result['Security'] == 1) {
                            echo "
                                <article class='flex-display small-gap'>
                                <i class='fa-solid fa-person-military-pointing'></i>
                                <p>Security</p>
                                </article>
                            ";
                        }
                        if ($result['Gym'] == 0 && $result['Swim'] == 0 && $result['Security'] == 0) {
                            echo "<i>n/a</i>";
                        }
                    ?>
                </div>
            </div>
            <div class="description">
                <h3>DESCRIPTION</h3>
                <p><?= strlen($result['Description']) > 0 ? $result['Description']: '-'?></p>
            </div>
        </div>
    </section>

    <section class="reviews">
        <div class="mainContainer review__container">
            <div class="review-add flex-display justify">
                <h2><span>Community</span> Review(s)</h2>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['Role'] === 'Tenant')
                    echo "<button id='add-review'><i class='fa-solid fa-plus'></i>Add Review</button>"
                ?>
            </div>
            <div class="user-reviews">
                <?php if (count($reviews_array) > 0): ?>
                    <?php
                        foreach ($reviews_array as $review) {
                            echo "
                            <article class='review'>
                                <h4>{$review[0]}</h4>
                                <small>{$review[3]}</small>
                                <div class='review-stars'>";
                            
                            $rating = '';
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $review[2]) {
                                    $rating .= "<i class='fa-solid fa-star'></i>";
                                } else {
                                    $rating .= "<i class='fa-regular fa-star'></i>";
                                }
                            }

                            echo "
                                    $rating
                                </div>
                                <p>{$review[1]}</p>
                            </article>
                            ";
                        }
                    ?>
                <?php else: ?>
                    <h4 class='no-comment'>No review yet.</h4>
                <?php endif ?>
                <?php
                    if (count($reviews_array) > 2) {
                        echo "<h5>Load more</h5>";
                    }
                ?>
            </div>

            <div class="review-form">
                <form action="property_details_backend.php<?= '?id='.$propertyID.'&total='.count($reviews_array) ?>" method="POST">
                    <div class="actual-review">
                        <div class="actual-review-stars">
                            <label for="rating">Rating: </label>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <h5>Please provide your rating.</h5>
                        </div>
                        <input type="number" name="rating" id="rating" required>
                        <textarea name="review" id="review" rows="2" placeholder="Write your review..." required></textarea>
                    </div>
                    <button id="submit" name="submit" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>

    <?php
    include 'partials/footer.php';
    ?>

    <script src="js/property_details.js"></script>
    <script src="js/nav_bar.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 10,
            grabCursor: 'true',
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            // media query when window width >= 1200px
            breakpoints: {
                1200: {
                    slidesPerView: 3,
                },
                500: {
                    slidesPerView: 2,
                }
            },
        });
    </script>

</body>
</html>