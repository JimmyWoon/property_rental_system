<?php
    require 'config/database.php';

    if (isset($_POST['submit'])) {
        $username = $_SESSION['user']['Name'];
        $propertyID = $_GET['id'];
        $totalReview = $_GET['total'];
        $rating = $_POST['rating'];
        $review = mysqli_real_escape_string($conn, $_POST['review']);
        
        // insert review to database
        $insert_review_query = "INSERT INTO review_tb SET username='$username', propertyID=$propertyID, userComment='$review', userRating=$rating, time=now()";
        $insert_review_result = mysqli_query($conn, $insert_review_query);

        // // get inital house rating
        $fetch_rating_query = "SELECT Rating FROM property_tb WHERE id=$propertyID";
        $fetch_rating_result = mysqli_query($conn, $fetch_rating_query);
        $inital_rating = mysqli_fetch_assoc($fetch_rating_result);
        mysqli_free_result($fetch_rating_result);

        // // calculate new rating
        $new_rating = (($inital_rating['Rating']*$totalReview)+$rating) / ($totalReview+1);
        echo "{$inital_rating['Rating']} <br> $totalReview <br> $rating";
        // // update house rating
        $update_rating_query = "UPDATE property_tb SET Rating = $new_rating WHERE id = $propertyID";
        $update_rating_result = mysqli_query($conn, $update_rating_query);

        if (!mysqli_errno($conn)) {
            // redirect back to property details page
            header('location: property_details.php?id='.$_GET['id']);
            mysqli_close($conn);
        }
    } else {
        header('location: property_list.php');
        mysqli_close($conn);
        die();
    }
?>