<?php 

    include "config/database.php";

    if(!isset($_SESSION)){
        session_start();
    }

    $userID = $_SESSION["user"]["id"] ?? null;
    $role = $_SESSION["user"]["Role"] ?? null;

    $currentPage = isset($_GET["page"]) ? $_GET["page"] : 1;

    $totalPage = 1;
    $resultCheck = 0;
    $offset = 0;

    $properties = array();

    $viewpropertyquery = "SELECT * from property_tb";

    if(isset($_GET["searchtext"]) || isset($_GET["types"]) || isset($_GET["facilities"])) {
        $viewpropertyquery = $viewpropertyquery." WHERE (";
    }

    if(isset($_GET["manage"]) && $role == "Host") {
        if(!(strpos($viewpropertyquery, "WHERE") !== false)) {
            $viewpropertyquery = $viewpropertyquery." WHERE (";
        }
        $viewpropertyquery = $viewpropertyquery." AgentID = ".$userID." ";
        if(isset($_GET["searchtext"]) || isset($_GET["types"]) || isset($_GET["facilities"])) {
            $viewpropertyquery = $viewpropertyquery.") AND (";
        } else {
            $viewpropertyquery = $viewpropertyquery.")";
        }
    }

    if(isset($_GET["searchtext"])) {

        $searchQueryArray = array();

        $saveSearchText = mysqli_real_escape_string($conn, $_GET["searchtext"]);

        if(strpos($viewpropertyquery, " ") !== false) {
            $saveSearchText = preg_replace('/\s+/', '-', $_GET["searchtext"]);
        }

        $searchArr = explode("-", $saveSearchText);

        $searchArr = array_values(array_filter($searchArr, 'strlen'));

        for($i = 0; $i < count($searchArr); $i++) {
            if($i != 0) {
                $viewpropertyquery = $viewpropertyquery." OR";
            }
            $viewpropertyquery = $viewpropertyquery." PropertyName LIKE '%".$searchArr[$i]."%'";
            $partialquery = "(PropertyName LIKE '%".$searchArr[$i]."%')";
            array_push($searchQueryArray, $partialquery);
        }

        if(isset($_GET["types"]) || isset($_GET["facilities"])) {
            $viewpropertyquery = $viewpropertyquery.") AND (";
        } else {
            $viewpropertyquery = $viewpropertyquery.")";
        }

    }

    if(isset($_GET["types"])) {

        for($i = 0; $i < count($_GET["types"]); $i++) {
            if($i != 0) {
                $viewpropertyquery = $viewpropertyquery." OR";
            }
            $viewpropertyquery = $viewpropertyquery." Type = '".$_GET["types"][$i]."'";
        }

        if(isset($_GET["facilities"])) {
            $viewpropertyquery = $viewpropertyquery." OR";
        }

    }

    if(isset($_GET["facilities"])) {

        for($i = 0; $i < count($_GET["facilities"]); $i++) {
            if($i != 0) {
                $viewpropertyquery = $viewpropertyquery." OR";
            }
            $viewpropertyquery = $viewpropertyquery." ".$_GET["facilities"][$i]." > 0";
        }

    }
        
    if(isset($_GET["types"]) || isset($_GET["facilities"])) {
        $viewpropertyquery = $viewpropertyquery." )";
    }

    if(isset($searchQueryArray) && count($searchQueryArray) > 1) {
        $orderQuery = " ORDER BY (".$searchQueryArray[0];
        for($i = 1; $i < count($searchQueryArray); $i++) {
            $orderQuery = $orderQuery." + ".$searchQueryArray[$i];
        }
        $orderQuery = $orderQuery.") desc";
        if(isset($_GET['sortProperty']) && $_GET['sortProperty'] != "default") {
            if($_GET['sortProperty'] == "price") {
                $orderQuery = $orderQuery.", ".$_GET['sortProperty']." asc";
            } else if($_GET['sortProperty'] == "rating") {
                $orderQuery = $orderQuery.", ".$_GET['sortProperty']." desc";
            }
        }
        $viewpropertyquery = $viewpropertyquery.$orderQuery;
    } else if(isset($_GET['sortProperty']) && $_GET['sortProperty'] != "default") {
        if($_GET['sortProperty'] == "price") {
            $viewpropertyquery = $viewpropertyquery." ORDER BY ".$_GET['sortProperty']." asc";
        } else if($_GET['sortProperty'] == "rating") {
            $viewpropertyquery = $viewpropertyquery." ORDER BY ".$_GET['sortProperty']." desc";
        }
    }

    $query_result = mysqli_query($conn, $viewpropertyquery);
    if($query_result) {
        $resultCheck = mysqli_num_rows($query_result);
        mysqli_free_result($query_result);
    }
    $totalPage = ceil($resultCheck / 5) == 0 ? 1 : ceil($resultCheck / 5);

    if(isset($_GET["page"])) {
        $offset = ($currentPage - 1) * 5;
    } 

    $finalquery = $viewpropertyquery." LIMIT 5 OFFSET ".$offset;

    $result = mysqli_query($conn, $finalquery);

    if($result){

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

    if(isset($_POST['delete_property']) && ($role == "Admin")) {
        $property_id = mysqli_real_escape_string($conn, $_POST['delete_property']);

        $query1 = "DELETE FROM property_tb WHERE id=".$property_id.";";
        $query_run1 = mysqli_query($conn, $query1);

        $query2 = "DELETE FROM propertyimg_tb WHERE propertyID=".$property_id.";";
        $query_run2 = mysqli_query($conn, $query2);
    
        if($query_run1 && $query_run2)
        {
            echo '<script>alert("Property remove successfully")</script>';
            header('Location: '.$_SERVER['REQUEST_URI'].'?manage='.$userID);
            exit(0);
        }
        else
        {
            echo '<script>alert("Property remove failed")</script>';
            header('Location: '.$_SERVER['REQUEST_URI'].'?manage='.$userID);
            exit(0);
        }
    } else if(isset($_POST['delete_property']) && ($role == "Host")) {
        $property_id = mysqli_real_escape_string($conn, $_POST['delete_property']);
    
        $query1 = "DELETE FROM property_tb WHERE id=".$property_id." AND AgentID=".$userID.";";
        $query_run1 = mysqli_query($conn, $query1);

        if($query_run1) {
            $query2 = "DELETE FROM propertyimg_tb WHERE propertyID=".$property_id.";";
            $query_run2 = mysqli_query($conn, $query2);
        }
    
        if($query_run1 && $query_run2)
        {
            echo '<script>alert("Property remove successfully")</script>';
            header('Location: '.$_SERVER['REQUEST_URI'].'?manage='.$userID);
            exit(0);
        }
        else
        {
            echo '<script>alert("Property remove failed")</script>';
            header('Location: '.$_SERVER['REQUEST_URI'].'?manage='.$userID);
            exit(0);
        }
    }

    mysqli_close($conn);

?>