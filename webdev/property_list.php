<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="./css/property_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<?php 
        include "property_list_backend.php";
        include "partials/header.php";

        $types = array("Bungalow", "Condominium", "Flat/Apartment", "Penthouse", "Semi-D", "Shop House", "Terrace", "Townhouse");
        $facilities = array("Gym", "Swimming Pool", "Security");

        function getSearchFor(){
            $value = "";
            if(isset($_GET["searchtext"])) {
                $value = str_replace('-', ' ', $_GET["searchtext"]);
            }
            return $value;
        } 
?>

<body>
    <?php
        if(isset($_GET["manage"]) && isset($role) && $role == "Host") {
            echo 
            '<div class="upload-property">
                <form method="POST" action="edit_property.php">
                    <button class="button" type="click"><i class="fa-solid fa-circle-plus"></i><span class="buttonText" title="Upload Property"></span></button>
                </form>
            </div>';
        }
    ?>
    <div id="content" class="mainContainer">
        <div class="search-bar">
            <form method="GET" action="property_list.php">
                <?php 
                    if(isset($_GET["manage"]) && isset($userID) && isset($role) && ($role == "Host" || $role == "Admin")) {
                        echo '<input type="hidden" name="manage" value='.$userID.' />';
                    }
                ?>
                <input type="text" id="searchinput" name="searchtext" placeholder="Search for property..." value="<?php echo getSearchFor(); ?>" >
                <button class="button" type="submit" id="submitbutton" disabled><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="list-container">
            
            <div class="left-col">
                <div class="sidebar">

                    <h2>Select Filters</h2>

                    <form method="GET" action="property_list.php">

                    <?php 
                        if(isset($saveSearchText)) {
                            echo '<input type="hidden" name="searchtext" value='.$saveSearchText.' />';
                        }
                        if(isset($_GET["manage"]) && isset($userID) && isset($role) && ($role == "Host" || $role == "Admin")) {
                            echo '<input type="hidden" name="manage" value='.$userID.' />';
                        }
                    ?>

                        <h3>Sort Property By</h3>

                        <div class="filter">
                            <select name="sortProperty">
                                <option value="default" <?php if(isset($_GET['sortProperty']) && $_GET['sortProperty']=="default") echo "selected"?>>Default</option>
                                <option value="price" <?php if(isset($_GET['sortProperty']) && $_GET['sortProperty']=="price") echo "selected"?>>Price</option>
                                <option value="rating" <?php if(isset($_GET['sortProperty']) && $_GET['sortProperty']=="rating") echo "selected"?>>Rating</option>
                            </select>
                        </div>

                        <h3>Property Type</h3>

                        <?php 
                            foreach($types as $type) {
                                if(isset($_GET['types']) && in_array($type, $_GET['types'])) {
                                    echo '
                                    <div class="filter">
                                        <input type="checkbox" name="types[]" value="'.$type.'" checked> <p>'.$type.'</p> 
                                    </div>';
                                } else {
                                    echo '
                                    <div class="filter">
                                        <input type="checkbox" name="types[]" value="'.$type.'"> <p>'.$type.'</p> 
                                    </div>';
                                }
                            }
                        ?>

                        <h3>Facilities</h3>

                        <?php 
                            foreach($facilities as $facility) {
                                if(isset($_GET['facilities']) && in_array($facility, $_GET['facilities'])) {
                                    echo '
                                    <div class="filter">
                                        <input type="checkbox" name="facilities[]" value="'.$facility.'" checked> <p>'.$facility.'</p> 
                                    </div>';
                                } else {
                                    echo '
                                    <div class="filter">
                                        <input type="checkbox" name="facilities[]" value="'.$facility.'"> <p>'.$facility.'</p> 
                                    </div>';
                                }
                            }
                        ?>

                        <div class="sidebar-link">
                            <button class="button" type="submit" class="register-btn">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="right-col">
                <p><span><?php 
                    if($resultCheck != 0) {
                        echo $resultCheck;
                    } else
                        echo "No"; ?></span> Properties Found</p>
                <?php 
                    $location = getSearchFor();
                    if($location == "") {
                        $location = "All Location";
                    }

                    if(isset($_GET["manage"]) && isset($role) && ($role == "Host" || $role == "Admin")) {
                        echo '<h1>Manage Properties In <span>'.$location.'</span></h1>';
                    } else {
                        echo '<h1>Properties In <span>'.$location.'</span></h1>';
                    }
                ?>

                <?php 
                    foreach ($properties as $property) {
                ?>
                        <div class="house">
                            <div class="house-img">
                                <?php 
                                    if(isset($property["imageFile"])){
                                        echo "<a href='property_details.php?id=".$property["id"]."'><figure class='figureUploaded'>
                                                <img class='imgUploaded' src= data:{$property["imageType"]};charset=utf8;base64," .base64_encode($property["imageFile"]) .">
                                            </figure></a>";
                                    }
                                ?>
                                
                            </div>
                            <div class="house-info">
                                <h3 id="house-title"><a href=<?="property_details.php?id=".$property["id"]?>>
                                <?php 
                                    if(isset($property["title"])) { 
                                        $property["title"] = $property["title"];
                                        if(strlen($property["title"]) > 45) {
                                            echo substr($property["title"], 0, 45)."...";
                                        } else {
                                            echo $property["title"];
                                        }
                                    }
                                ?></a></h3>  
                                <p id="desc">
                                    <?php 
                                        if(isset($property["description"])) {
                                            $property["description"] = $property["description"];
                                            if(strlen($property["description"]) > 65) {
                                                echo substr($property["description"], 0, 65)."...";
                                            } else {
                                                echo $property["description"];
                                            }
                                        }
                                    ?>
                                </p>
                                <div class="rate">
                                    <p><?php if(isset($property["rating"])) {echo number_format($property["rating"], 1);} ?>&nbsp;&nbsp;</p>
                                    <?php 
                                        if(isset($property["rating"])) {
                                            $fullStarNum = floor($property["rating"]);
                                            $emptyStarNum = 5 - ceil($property["rating"]);
                                            for($i = 0; $i < $fullStarNum; $i++) {
                                                echo '<i class="fas fa-star"></i>';
                                            }
                                            if($fullStarNum - $property["rating"] != 0) {
                                                echo '<i class="fas fa-star-half-alt"></i>';
                                            }
                                            for($i = 0; $i < $emptyStarNum; $i++) {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="house-price">
                                    <?php 
                                        if(isset($property["status"])) {
                                            if($property["status"] == "Vacant") {
                                                echo '<p id="prop-stat-green">'.$property["status"].'</p>';
                                            } else if($property["status"] == "Pending") {
                                                echo '<p id="prop-stat-orange">'.$property["status"].'</p>';
                                            } else {
                                                echo '<p id="prop-stat-red">'.$property["status"].'</p>';
                                            }
                                        }
                                    ?>
                                    <h4>RM <?php if(isset($property["price"])) {echo $property["price"];} ?> <span>/ day</span> </h4>
                                </div>
                                <?php 
                                    if(isset($_GET["manage"]) && isset($role) && ($role == "Host" || $role == "Admin")) {
                                        echo '
                                        <div class="tools">
                                            <form method="POST" action="edit_property.php?PropertyID='.$property["id"].'">
                                                <button type="submit" title="Edit Property"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </form>
                                            <form method="POST" action="property_list.php" onsubmit="return confirm(\'Are you sure you want to delete this property?\');">
                                                <input type="hidden" name="manage" value='.$userID.' />
                                                <input type="hidden" name="delete_property" value='.$property["id"].' />
                                                <button type="submit" title="Delete Property"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>';
                                    }
                                ?>
                            </div>
                        </div>
                <?php  
                    }
                ?>
            </div>
            

        </div> 
    </div>

    <div class="pagination">
        <form method="GET" action="property_list.php">
            <?php 
                if(isset($saveSearchText)) {
                    echo '<input type="hidden" name="searchtext" value='.$saveSearchText.' />';
                }
                if(isset($_GET["manage"]) && isset($role) && ($role == "Host" || $role == "Admin")) {
                    echo '<input type="hidden" name="manage" value='.$userID.' />';
                }
                if(isset($_GET["types"])) {
                    foreach($_GET["types"] as $type) {
                        echo '<input type="hidden" name="types[]" value='.$type.' />';
                    }
                }
                if(isset($_GET["facilities"])) {
                    foreach($_GET["facilities"] as $facility) {
                        echo '<input type="hidden" name="facilities[]" value='.$facility.' />';
                    }
                }
                echo '<input type="hidden" name="page" value='.($currentPage-1).' />';
            ?>
            <button type="submit" title="Previous Page" <?= $currentPage == 1 ? "disabled" : ""?>><i class="fa-sharp fa-solid fa-angle-left"></i></button>
        </form>
        <p class="pageNum"><?= $currentPage?>&nbsp;/&nbsp;<?= $totalPage?></p>
        <form method="GET" action="property_list.php">
            <?php 
                if(isset($saveSearchText)) {
                    echo '<input type="hidden" name="searchtext" value='.$saveSearchText.' />';
                }
                if(isset($_GET["manage"]) && isset($role) && ($role == "Host" || $role == "Admin")) {
                    echo '<input type="hidden" name="manage" value='.$userID.' />';
                }
                if(isset($_GET["types"])) {
                    foreach($_GET["types"] as $type) {
                        echo '<input type="hidden" name="types[]" value='.$type.' />';
                    }
                }
                if(isset($_GET["facilities"])) {
                    foreach($_GET["facilities"] as $facility) {
                        echo '<input type="hidden" name="facilities[]" value='.$facility.' />';
                    }
                }
                echo '<input type="hidden" name="page" value='.($currentPage+1).' />';
            ?>
            <button type="submit" title="Next Page" <?= $currentPage == $totalPage ? "disabled" : ""?>><i class="fa-sharp fa-solid fa-angle-right"></i></button>
        </form>
    </div>

    <footer>
        <?php
            include 'partials/footer.php';
        ?>
    </footer>

    <script src="js/nav_bar.js"></script>

    <script>
        let searchInput = document.getElementById('searchinput');
        let submitButton = document.getElementById('submitbutton');

        searchInput.addEventListener("input", function(){
            submitButton.disabled = (this.value.trim() === '');
        })
    </script>
</body>
</html>