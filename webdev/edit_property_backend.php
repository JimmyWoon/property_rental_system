<?php
    include "config/database.php";
    $find_latest_propertyID = "SELECT MAX(id) FROM property_tb;";
    $find_latest_propertyID_result = mysqli_query($conn, $find_latest_propertyID);
    if (mysqli_num_rows($find_latest_propertyID_result) > 0 ) {
        while ($row = mysqli_fetch_assoc($find_latest_propertyID_result)){
            $propertyID=$row['id']+1;
        }
        mysqli_free_result($find_latest_propertyID_result);  

    }else{
        $propertyID=1;
    }



    if (isset($_POST['save'])){
        $name = (isset($_POST['nameInput']) ? mysqli_real_escape_string($conn,$_POST['nameInput']) : 'NULL');

        $address = (isset($_POST['addressInput']) ? mysqli_real_escape_string($conn,$_POST['addressInput']) : 'NULL');

        $status = (isset($_POST['statusInput']) ? mysqli_real_escape_string($conn,$_POST['statusInput']) : 'NULL');

        $type = (isset($_POST['typeInput']) ? mysqli_real_escape_string($conn,$_POST['typeInput']) : 'NULL');
        $price = (isset($_POST['priceInput']) ? mysqli_real_escape_string($conn,$_POST['priceInput']) : 'NULL');
        $square = (isset($_POST['squareFeetInput']) ? mysqli_real_escape_string($conn,$_POST['squareFeetInput']) : 'NULL');
        $room = (isset($_POST['roomInput']) ? mysqli_real_escape_string($conn,$_POST['roomInput']) : 'NULL');
        $bathroom = (isset($_POST['bathroomInput']) ? mysqli_real_escape_string($conn,$_POST['bathroomInput']) : 'NULL');

        $gym = (isset($_POST['gymInput']) ? 1 : 0);
        $swim = (isset($_POST['swimInput']) ? 1 : 0);
        $security = (isset($_POST['securityInput']) ? 1 : 0);
        $description = (isset($_POST['descriptionInput']) ? mysqli_real_escape_string($conn,$_POST['descriptionInput']) : 'NULL');
       
        $user_id = "";

        
        if(isset($_SESSION['user']['id'])){
            $user_id = $_SESSION['user']['id'];
        }     


        // store data of property
        if (isset($_GET['PropertyID'])){
            $propertyID = mysqli_real_escape_string($conn,$_GET['PropertyID']);

            $find_existing_query = "SELECT * FROM property_tb WHERE Address ='{$address}';";
            $find_existing_result = mysqli_query($conn, $find_existing_query);
            if (mysqli_num_rows($find_existing_result) > 0 ) {
                while ($row = mysqli_fetch_assoc($find_existing_result)){
                    if($propertyID != $row['id']){
                        // alert message will pop up only when the id of the modify property is not similar with the property with the particular address
                        //check whether the address of _GET ID is similar with the user typed in
                        $_SESSION['modify-property-failed'] = "This property address already exists.";
                    }
                }                
                mysqli_free_result($find_existing_result);  
            }
            if (isset($_SESSION['modify-property-failed'])){
                header("location: edit_property.php?PropertyID=$propertyID");
            }else{
                $validatePropertyID = "SELECT * from property_tb where id = '{$propertyID}' LIMIT 1;";
                $resultOfValidation = mysqli_query($conn,$validatePropertyID);

                $resultCheck = mysqli_num_rows($resultOfValidation);
                if($resultCheck > 0){
                    mysqli_free_result($resultOfValidation);
                    //has this id
                    //update the data instead of insert 
                    $insert_user = "UPDATE property_tb set PropertyName='{$name}',Address='{$address}',Status='{$status}',Type='{$type}',Price='{$price}',SquareFeet='{$square}',Room='{$room}',Bathroom='{$bathroom}',Gym='{$gym}',Swim='{$swim}',Security='{$security}',Description='{$description}',AgentID='{$user_id}' where id = {$propertyID};";
                }else{
                    //id not exist
                    // insert new data
                    $insert_user = "INSERT INTO property_tb (PropertyName,Address,Status,Type,Price,SquareFeet,Room,Bathroom,Gym,Swim,Security,Description,AgentID) 
                            value ('{$name}','{$address}','{$status}','{$type}','{$price}','{$square}','{$room}','{$bathroom}','{$gym}','{$swim}','{$security}','{$description}','{$user_id}');";
                }
            }           
        }else{
            
            $find_existing_query = "SELECT * FROM property_tb WHERE Address ='{$address}';";
            $find_existing_result = mysqli_query($conn, $find_existing_query);
            if (mysqli_num_rows($find_existing_result) > 0 ) {
                
                //no need while to check whether the address of _GET ID is similar with the user typed in
                $_SESSION['modify-property-failed'] = "This property address already exist.";
                mysqli_free_result($find_existing_result);            
                header('location: edit_property.php');
                die();
            }else{
                $insert_user = "INSERT INTO property_tb (PropertyName,Address,Status,Type,Price,SquareFeet,Room,Bathroom,Gym,Swim,Security,Description,AgentID) 
                            value ('{$name}','{$address}','{$status}','{$type}','{$price}','{$square}','{$room}','{$bathroom}','{$gym}','{$swim}','{$security}','{$description}','{$user_id}');";
            }
        }

        $result_insert_user = mysqli_query($conn, $insert_user);

        //get the ID of the property
        $select_property = "SELECT id from property_tb where PropertyName = '{$name}' AND Address = '{$address}' LIMIT 1;";
        $result = mysqli_query($conn,$select_property);

        $resultCheck = mysqli_num_rows($result);

        if($resultCheck > 0){

            while ($row = mysqli_fetch_assoc($result)){
                $propertyID = $row['id'];
            }
            mysqli_free_result($result);

        }

        if(isset($_POST['deleteImage'])){
            //want to delete image 
            $delete = "DELETE From propertyimg_tb where propertyID = {$propertyID};";
            $result_delete = mysqli_query($conn,$delete);
        }

        if (isset($_FILES['property_image'])){ 

            if (!isset($_FILES['property_image']['error'])){
                //remove previous database de image if found because user has uploaded new image
                $delete = "DELETE From propertyimg_tb where propertyID = {$propertyID};";
                $result_delete_record = mysqli_query($conn,$delete);
            }


            foreach($_FILES['property_image']['name'] as $key=>$value){
                $img_name = $value;
                $img_size = $_FILES['property_image']['size'][$key];
                $tmp_name = $_FILES['property_image']['tmp_name'][$key];
                $error = $_FILES['property_image']['error'][$key];

                if ($error === 0){

                    $img_ex = pathinfo($img_name,PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg","jpeg","png");
                    if (in_array($img_ex_lc,$allowed_exs)){
                        //Insert into database
                        $imgData = addslashes(file_get_contents($tmp_name));
                        $imgProp = getimagesize($tmp_name);
                        try{
                            $insert_image = "INSERT INTO propertyimg_tb (propertyID,imageName,imageType,imageFile) value ('{$propertyID}','{$img_name}','{$imgProp['mime']}','{$imgData}');";
                            $result_insert_image = mysqli_query($conn, $insert_image);
                        }catch(Exception $e){
                            $_SESSION['modify-property-failed'] = "Image file too big.";
                            header("location: edit_property.php?PropertyID=$propertyID");
                        }
                    }else{
                        $_SESSION['modify-property-failed'] = "You can't upload files of this type.";
                        header("location: edit_property.php?PropertyID=$propertyID");
                    }
                }else{
                    // $_SESSION['modify-property-failed'] = "error occured! Please contact techinical staff.";
                    // header("location: edit_property.php?PropertyID=$propertyID");
                }
            }
        }else{
            // no image uploaded
        }
        // get all value thn oni sekali insert into database
    }else{
        //error 
        header("Location: ./property_list.php");
        exit();
    }
    mysqli_close($conn);
    header("Location: ./property_list.php?manage=$user_id");   
?>