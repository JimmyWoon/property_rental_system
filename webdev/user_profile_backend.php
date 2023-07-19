<?php
    if (isset($_POST['save'])){
        include "config/database.php";
        $name = (isset($_POST['name']) ? mysqli_real_escape_string($conn,$_POST['name']) : 'NULL');
        $phone = (isset($_POST['phone']) ? mysqli_real_escape_string($conn,$_POST['phone']) : 'NULL');
        $mail = (isset($_POST['mail']) ? mysqli_real_escape_string($conn,$_POST['mail']) : 'NULL');

        // fetch user from database
        $find_existing_query = "SELECT * FROM user_tb WHERE Name='{$name}' OR Email='{$mail}';";
        $find_existing_result = mysqli_query($conn, $find_existing_query);

        if (mysqli_num_rows($find_existing_result) > 1 ) {
            // greater than 1 because in database already got 1 record which is the particular user with the same name or email
            $_SESSION['modify-failed'] = "Username or email is used";
            mysqli_free_result($find_existing_result);            
            header('location: user_profile.php');
            die();
        }else{
            if (isset($_SESSION['user']['id'])){
                $userID_from_session = $_SESSION['user']['id'];
                // convert the record into assoc array
                // $user_record = mysqli_fetch_assoc($find_existing_result);
                // $db_password = $user_record['Password'];

                $select_password = "SELECT Password from user_tb where id = '{$userID_from_session}'";
                $select_password_result = mysqli_query($conn, $select_password);
                if (mysqli_num_rows($select_password_result) > 0 ) {
                    while ($row = mysqli_fetch_assoc($select_password_result)){
                        $db_password=$row['Password'];
                    }
                    mysqli_free_result($select_password_result);  
                }

                if (isset($_POST['edit_password_btn'])){
                    $old_password = (isset($_POST['oldpassword']) ? mysqli_real_escape_string($conn,$_POST['oldpassword']) : 'NULL');
                    $new_password = (isset($_POST['newpassword']) ? mysqli_real_escape_string($conn,$_POST['newpassword']) : 'NULL');
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    if (password_verify($old_password, $db_password)){
                        //correct old password entered
                        if (is_uploaded_file($_FILES['image']['tmp_name'])){ 
                            $img_name = $_FILES['image']['name'];
                            $img_size = $_FILES['image']['size'];
                            $tmp_name = $_FILES['image']['tmp_name'];
                            $error = $_FILES['image']['error'];

                            if ($error === 0){

                                $img_ex = pathinfo($img_name,PATHINFO_EXTENSION);
                                $img_ex_lc = strtolower($img_ex);

                                $allowed_exs = array("jpg","jpeg","png");
                                if (in_array($img_ex_lc,$allowed_exs)){

                                    //Insert into database
                                    $imgData = addslashes(file_get_contents($tmp_name));
                                    $imgProp = getimagesize($tmp_name);

                                    if(isset($_GET['userID'])){
                                        //verify the ID
                                        $userID = mysqli_real_escape_string($conn,$_GET['userID']);

                                        $select_user = "SELECT * from user_tb where id = '{$userID}' LIMIT 1;";
                                        $result_select_user = mysqli_query($conn,$select_user);
                                        $resultCheck = mysqli_num_rows($result_select_user);

                                        if($resultCheck > 0){
                                            mysqli_free_result($result_select_user);

                                            $sql3 = "Update user_tb SET Name='{$name}',Phone='{$phone}',Email='{$mail}',Password='{$hashed_password}',imgName='{$img_name}',imgType='{$imgProp['mime']}',imgFile='{$imgData}' WHERE id = '{$userID}';";
                                        }else{
                                            //default set the customer to Tenant
                                            $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password,imgName,imgType,imgFile) value ('{$name}','{$phone}','{$mail}','Tenant','{$hashed_password}','{$img_name}','{$imgProp['mime']}','{$imgData}');";
                                        }
                                    }else{
                                        //default set the customer to Tenant
                                        $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password,imgName,imgType,imgFile) value ('{$name}','{$phone}','{$mail}','Tenant','{$hashed_password}','{$img_name}','{$imgProp['mime']}','{$imgData}');";
                                    }
                                    try{
                                        $result = mysqli_query($conn, $sql3);
                                        // $_SESSION['user']['Name'] = $name;
                                        // $_SESSION['user']['Phone'] = $phone;
                                        // $_SESSION['user']['Email'] = $mail;
                                        // $_SESSION['user']['Password'] = $hashed_password;
                                        // $_SESSION['user']['imgFile'] = $imgData;
                                        // $_SESSION['user']['imgType'] = $imgProp['mime'];
                                        $fetch_user_query = "SELECT * FROM user_tb WHERE id='$userID'";
                                        $fetch_user_result = mysqli_query($conn, $fetch_user_query);
                                        if (mysqli_num_rows($fetch_user_result) == 1) {
                                            // convert the record into assoc array
                                            $user_record = mysqli_fetch_assoc($fetch_user_result);
                                            $_SESSION['user'] = $user_record;
                                        }
                                    }catch(Exception $e){
                                        $_SESSION['modify-failed'] = "Image file too big.";
                                        header("Location: ./user_profile.php");      
                                    }

                                }else{
                                    $_SESSION['modify-failed'] = "You can't upload files of this type.";
                                    header("location: user_profile.php");
                                }
                            }else{
                                // $_SESSION['modify-failed'] = "error occured! Please contact techinical staff.";
                                // header("location: user_profile.php");
                            }
                            
                        }else{
                            // no image uploaded
                            if(isset($_GET['userID'])){
                                //verify the ID
                                $userID = mysqli_real_escape_string($conn,$_GET['userID']);

                                $select_user = "SELECT * from user_tb where id = '{$_GET['userID']}' LIMIT 1;";
                                $result_select_user = mysqli_query($conn,$select_user);
                                $resultCheck = mysqli_num_rows($result_select_user);

                                if($resultCheck > 0){
                                    mysqli_free_result($result_select_user);

                                    $sql3 = "Update user_tb SET Name='{$name}',Phone='{$phone}',Email='{$mail}',Password='{$hashed_password}' WHERE id = '{$userID}';";
                                }else{
                                    //default set the customer to Tenant
                                    $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password) value ('{$name}','{$phone}','{$mail}','Tenant','{$hashed_password}');";
                                }
                            }else{
                                //default set the customer to Tenant
                                $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password) value ('{$name}','{$phone}','{$mail}','Tenant','{$hashed_password}');";
                            }
                            $result = mysqli_query($conn, $sql3);
                            // $_SESSION['user']['Name'] = $name;
                            // $_SESSION['user']['Phone'] = $phone;
                            // $_SESSION['user']['Email'] = $mail;
                            // $_SESSION['user']['Password'] = $hashed_password;
                            $fetch_user_query = "SELECT * FROM user_tb WHERE id='$userID'";
                            $fetch_user_result = mysqli_query($conn, $fetch_user_query);
                            if (mysqli_num_rows($fetch_user_result) == 1) {
                                // convert the record into assoc array
                                $user_record = mysqli_fetch_assoc($fetch_user_result);
                                $_SESSION['user'] = $user_record;
                            }

                        }
                    }else{
                        //wrong old password
                        $_SESSION['modify-failed'] = 'Incorrect password';
                        header("location: user_profile.php");
                    }
                }else{
                    if (is_uploaded_file($_FILES['image']['tmp_name'])){ 
                        $img_name = $_FILES['image']['name'];
                        $img_size = $_FILES['image']['size'];
                        $tmp_name = $_FILES['image']['tmp_name'];
                        $error = $_FILES['image']['error'];

                        if ($error === 0){

                            $img_ex = pathinfo($img_name,PATHINFO_EXTENSION);
                            $img_ex_lc = strtolower($img_ex);

                            $allowed_exs = array("jpg","jpeg","png");
                            if (in_array($img_ex_lc,$allowed_exs)){

                                //Insert into database
                                $imgData = addslashes(file_get_contents($tmp_name));
                                $imgProp = getimagesize($tmp_name);

                                if(isset($_GET['userID'])){
                                    //verify the ID
                                    $userID = mysqli_real_escape_string($conn,$_GET['userID']);

                                    $select_user = "SELECT * from user_tb where id = '{$userID}' LIMIT 1;";
                                    $result_select_user = mysqli_query($conn,$select_user);
                                    $resultCheck = mysqli_num_rows($result_select_user);

                                    if($resultCheck > 0){
                                        mysqli_free_result($result_select_user);

                                        $sql3 = "Update user_tb SET Name='{$name}',Phone='{$phone}',Email='{$mail}',imgName='{$img_name}',imgType='{$imgProp['mime']}',imgFile='{$imgData}' WHERE id = '{$userID}';";
                                    }else{
                                        //default set the customer to Tenant
                                        $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password,imgName,imgType,imgFile) value ('{$name}','{$phone}','{$mail}','Tenant','NULL','{$img_name}','{$imgProp['mime']}','{$imgData}');";
                                    }
                                }else{
                                    //default set the customer to Tenant
                                    $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password,imgName,imgType,imgFile) value ('{$name}','{$phone}','{$mail}','Tenant','NULL','{$img_name}','{$imgProp['mime']}','{$imgData}');";
                                }
                                try{
                                    $result = mysqli_query($conn, $sql3);
                                    $fetch_user_query = "SELECT * FROM user_tb WHERE id='$userID'";
                                    $fetch_user_result = mysqli_query($conn, $fetch_user_query);
                                    if (mysqli_num_rows($fetch_user_result) == 1) {
                                        // convert the record into assoc array
                                        $user_record = mysqli_fetch_assoc($fetch_user_result);
                                        $_SESSION['user'] = $user_record;
                                    }
                                    // $_SESSION['user']['Name'] = $name;
                                    // $_SESSION['user']['Phone'] = $phone;
                                    // $_SESSION['user']['Email'] = $mail;
                                    // $_SESSION['user']['imgFile'] = $imgData;
                                    // $_SESSION['user']['imgType'] = $imgProp['mime'];

                                }catch(Exception $e){
                                    $_SESSION['modify-failed'] = "Image file too big.";
                                    header("Location: ./user_profile.php");      
                                }

                            }else{
                                $_SESSION['modify-failed'] = "You can't upload files of this type.";
                                header("location: user_profile.php");
                            }
                        }else{
                            // $_SESSION['modify-failed'] = "error occured! Please contact techinical staff.";
                            // header("location: user_profile.php");
                        }
                        
                    }else{
                        // no image uploaded
                        if(isset($_GET['userID'])){
                            //verify the ID
                            $userID = mysqli_real_escape_string($conn,$_GET['userID']);

                            $select_user = "SELECT * from user_tb where id = '{$_GET['userID']}' LIMIT 1;";
                            $result_select_user = mysqli_query($conn,$select_user);
                            $resultCheck = mysqli_num_rows($result_select_user);

                            if($resultCheck > 0){
                                mysqli_free_result($result_select_user);

                                $sql3 = "Update user_tb SET Name='{$name}',Phone='{$phone}',Email='{$mail}' WHERE id = '{$userID}';";
                            }else{
                                //default set the customer to Tenant
                                $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password) value ('{$name}','{$phone}','{$mail}','Tenant','NULL');";
                            }
                        }else{
                            //default set the customer to Tenant
                            $sql3 = "INSERT INTO user_tb (Name,Phone,Email,Role,Password) value ('{$name}','{$phone}','{$mail}','Tenant','NULL');";
                        }
                        $result = mysqli_query($conn, $sql3);
                        $fetch_user_query = "SELECT * FROM user_tb WHERE id='$userID'";
                        $fetch_user_result = mysqli_query($conn, $fetch_user_query);
                        if (mysqli_num_rows($fetch_user_result) == 1) {
                            // convert the record into assoc array
                            $user_record = mysqli_fetch_assoc($fetch_user_result);
                            $_SESSION['user'] = $user_record;
                        }
                        // $_SESSION['user']['Name'] = $name;
                        // $_SESSION['user']['Phone'] = $phone;
                        // $_SESSION['user']['Email'] = $mail;
                    }
                }
            }        
        }
        
        mysqli_close($conn);   
        header("Location: ./user_profile.php");      
    }
?>