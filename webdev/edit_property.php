<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/edit_property.css">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <title>Edit Property</title>
</head>
<body>
    <?php include"partials/header.php"?>
    <?php 
        if (isset($_SESSION['user']['id'])){

            if(isset($_GET['PropertyID'])){
                include "config/database.php";
                $propertyID = mysqli_real_escape_string($conn,$_GET['PropertyID']);

                $propertyData = "SELECT * from property_tb where id = '{$propertyID}';";
                $result_property_data = mysqli_query($conn,$propertyData);
                $resultCheck = mysqli_num_rows($result_property_data);

                if($resultCheck > 0){
                    //have data of the particular ID 
                    while ($row = mysqli_fetch_assoc($result_property_data)){
                        $name = (is_null($row['PropertyName']) ? NULL : $row['PropertyName']);

                        $address = (is_null($row['Address']) ? NULL : $row['Address']);

                        $status = (is_null($row['Status']) ? NULL : $row['Status']);

                        $type = (is_null($row['Type']) ? NULL : $row['Type']);
                        $price = (is_null($row['Price']) ? NULL : $row['Price']);
                        $square = (is_null($row['SquareFeet']) ? NULL : $row['SquareFeet']);
                        $room = (is_null($row['Room']) ? NULL : $row['Room']);
                        $bathroom = (is_null($row['Bathroom']) ? NULL : $row['Bathroom']);

                        $gym = (is_null($row['Gym']) ? NULL : $row['Gym']);
                        $swim = (is_null($row['Swim']) ? NULL : $row['Swim']);
                        $security = (is_null($row['Security']) ? NULL : $row['Security']);

                        $description = (is_null($row['Description']) || empty($row['Description']) ? NULL : $row['Description']);
                        // $agentName = (is_null($row['AgentName']) ? NULL : $row['AgentName']);
                        // $agentPhone = (is_null($row['AgentPhone']) ? NULL : $row['AgentPhone']);  
                    }
                    mysqli_free_result($result_property_data);

                    $propertyImage = "SELECT * from propertyimg_tb where propertyID = {$propertyID};";
                    $ImageObtained = mysqli_query($conn,$propertyImage);
                    $ImageresultCheck = mysqli_num_rows($ImageObtained);
                    if ($ImageresultCheck > 0){

                        $imgArry = array();
                        while ($row = mysqli_fetch_assoc($ImageObtained)){
                            $imgArry[] = array($row['imageName'],$row['imageType'],$row['imageFile']);
                        }

                        mysqli_free_result($ImageObtained);
                    }            
                }else{
                // no data found for the particular ID 
                $_SESSION['modify-property-failed'] = "Property not found";

                }
                mysqli_close($conn);

            }else{  
            }
        }else{
            header("Location: ./signin.php");       
        }
    ?>
    <div class="container">
        <div class="MainPanel">
            <div class="left"></div>
            <div class="center">
                <div class="top">
                    <input id="saveBtn" class="submit" name="save" form="form" type="submit" value="Save">
                    <?php echo '<button onclick="window.location.href=\'property_list.php?manage='.$_SESSION['user']['id'].'\';" class="cancel">Cancel</button>'; ?>
                </div>
                <?php if(isset($_SESSION['modify-property-failed'])){?>
                    <div class="error">
                        <p>
                            <?php if (isset($_SESSION['modify-property-failed'])): ?>
                                <?= $_SESSION['modify-property-failed']; unset($_SESSION['modify-property-failed']);?>
                            <?php endif?>
                        </p>
                    </div>
                <?php }?>
                <form id="form" action="<?= (isset($propertyID)) ? "edit_property_backend.php?PropertyID={$propertyID}" : 'edit_property_backend.php';?>" name="formUpload" method="POST" enctype="multipart/form-data">
                    <div class="TextPanel">
                        <div class="card">
                            <h2 class="textBoxH2">Name</h2>
                            <label class="input">
                                <input required id="nameInput" name="nameInput" class="input__field" type="text" placeholder=" " value="<?php if (isset($name)) echo $name ; ?>"/>
                                <span class="input__label">Enter name of property</span>
                            </label>
                        </div>
                    </div>
                    <div class="TextPanel">
                        <div class="card">
                            <h2 class="textBoxH2">Address</h2>
                            <label class="input">
                                <input required id="addressInput" name="addressInput" class="input__field" type="text" placeholder=" " value="<?php if (isset($address)) echo $address ; ?>" />
                                <span class="input__label">Enter address of property</span>
                            </label>
                        </div>
                    </div>
                    <div class="TextPanel">
                        <div class="card">
                            <h2 class="textBoxH2">Status</h2>
                                <select required name="statusInput" id="statusInput" class="form-control input__field">
                                    <option value="" value="">Choose...</option>
                                    <option value="Pending" <?php 
                                        if(isset($status)){
                                            if($status == 'Pending'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?> >Pending</option>
                                    <option value="Rent" <?php 
                                        if(isset($status)){
                                            if($status == 'Rent'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?> >Rent</option>
                                    <option value="Vacant" <?php 
                                        if(isset($status)){
                                            if($status == 'Vacant'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?> >Vacant</option>
                                </select>
                        </div>
                    </div>
                    <div class="TextPanel">
                        <div class="card">
                            <h2 class="textBoxH2">Property Detail</h2>
                            <select required id="typeInput" name="typeInput" class="form-control input__field gap">
                                <option value="">Choose...</option>
                                <option value="Bungalow" <?php 
                                        if(isset($type)){
                                            if($type == 'Bungalow'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?> >Bungalow</option>
                                <option value="Condominium" <?php 
                                        if(isset($type)){
                                            if($type == 'Condominium'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Condominium</option>  
                                <option value="Flat/Apartment" <?php 
                                        if(isset($type)){
                                            if($type == 'Flat/Apartment'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Flat/Apartment</option>
                                <option value="Penthouse" <?php 
                                        if(isset($type)){
                                            if($type == 'Penthouse'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Penthouse</option>
                                <option value="Semi-D" <?php 
                                        if(isset($type)){
                                            if($type == 'Semi-D'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Semi-D</option>
                                <option value="Shop House" <?php 
                                        if(isset($type)){
                                            if($type == 'Shop House'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Shop House</option>
                                <option value="Terrace" <?php 
                                        if(isset($type)){
                                            if($type == 'Terrace'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Terrace</option>
                                <option value="Townhouse" <?php 
                                        if(isset($type)){
                                            if($type == 'Townhouse'){
                                                echo 'selected="selected"';
                                            }
                                        } 
                                    ?>>Townhouse</option>
                            </select>
                            <label class="input">
                                <input required id="priceInput" name="priceInput" class="input__field gap" type="number" min="0" step="0.1" placeholder=" " value="<?php if (isset($price)) echo $price ; ?>"/>
                                <span class="input__label">Price(RM) <strong style="color:red;">per Day</strong></span>
                            </label>
                            <label class="input">
                                <input required id="squareFeetInput" name="squareFeetInput" class="input__field gap" type="text" placeholder=" " value="<?php if (isset($square)) echo $square ; ?>"/>
                                <span class="input__label">Square Feet</span>
                            </label>
                            <label class="input">
                                <input required id="roomInput" name="roomInput" class="input__field gap" type="number" min="0" placeholder=" " value="<?php if (isset($room)) echo $room ; ?>"/>
                                <span class="input__label">Amount of room</span>
                            </label>
                            <label class="input">
                                <input required id="bathroomInput" name="bathroomInput" class="input__field gap" type="number" min="0" placeholder=" " value="<?php if (isset($bathroom)) echo $bathroom ; ?>"/>
                                <span class="input__label">Amount of bathroom</span>
                            </label>
                        </div>
                    </div>

                    <div class="TextPanel">
                        <div class="card">
                            <h2 class="textBoxH2">Facility</h2>
                            <div class="gap">
                                <input class="form-check-input " type="checkbox" value="gym" id="gymInput" name='gymInput' <?php 
                                        if(isset($gym)){
                                            if($gym == 1){
                                                echo 'checked="checked"';
                                            }
                                        } 
                                    ?>>
                                <label class="form-check-label" for="gymInput">
                                    Gymnasium room
                                </label>
                            </div>
                            <div class="gap">
                                <input class="form-check-input " type="checkbox" value="swim" id="swimInput" name='swimInput' <?php 
                                        if(isset($swim)){
                                            if($swim == 1){
                                                echo 'checked="checked"';
                                            }
                                        } 
                                    ?>>
                                <label class="form-check-label" for="swimInput">
                                    Swimming pool
                                </label>
                            </div>
                            <div class="gap">
                                <input class="form-check-input " type="checkbox" value="security" id="securityInput" name='securityInput' <?php 
                                        if(isset($security)){
                                            if($security == 1){
                                                echo 'checked="checked"';
                                            }
                                        } 
                                    ?>>
                                <label class="form-check-label" for="securityInput">
                                    24 hours security
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="ImagePanel">
                        <div id="images">
                            <?php 
                                if(isset($imgArry)){
                                    // has image stored in database
                                    foreach($imgArry as $img){
                                        echo "<figure class='figureUploaded'>
                                                <img class='imgUploaded' src= data:{$img[1]};charset=utf8;base64," .base64_encode($img[2]) .">
                                                <figcaption class='figcaptionUploaded'>{$img[0]}</figcaption>
                                            </figure>";
                                    }
                                }
                            ?>
                        </div>    
                        <input type="file" id="file-input" name="property_image[]" accept="image/png, image/jpeg" multiple="multiple" class="file-input" >
                        <label id="choosePht" for="file-input">
                            <i class="fas fa-cloud-upload-alt"></i> &nbsp; Choose A Photo
                        </label>
                        <P>
                            <INPUT type="checkbox" id="deleteImage" name="deleteImage" hidden  />

                            <label id="choosePht" for="deleteImage">
                                <i class="fa-solid fa-trash"></i>  &nbsp; Delete Image
                            </label>
                        </P>
                    </div>
                    <div class="TextPanel">
                        <div class="card">
                            <h2 class="textBoxH2">Description</h2>
                            <label class="input">
                                <input id="descriptionInput" name="descriptionInput" class="input__field" type="text" placeholder=" " value="<?php if (isset($description)) echo $description ; ?>" />
                                <span class="input__label">Enter description of property</span>
                            </label>
                        </div>
                    </div>
                </form>
            
            </div>
        </div>
        <div class="right"></div>
        <?php
            include 'partials/footer.php';
        ?>
    </div>

    <script src="./js/edit_property.js"></script>
    <script src="./js/nav_bar.js"></script>

</body>
</html>

