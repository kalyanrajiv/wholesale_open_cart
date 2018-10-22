<?php
    include("includes/config.php");
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `brands`";
        $result = mysqli_query($hpcon,$query);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $brandId = $row['id'];
                $brandname = $row['brand'];
                $company = $row['company'];
                $created = $row['created'];
                $modified = $row['modified'];
                if (!mysqli_connect_errno()){
                    $selQry = "SELECT * FROM `oc_manufacturer` WHERE `manufacturer_id`='$brandId'";
                    $count = mysqli_num_rows(mysqli_query($opcon,$selQry));
                    
                    //$resSelQry = mysqli_query($opcon,$selQry);//echo'<pre>';print_r($resSelQry);die;
                    if($count){
                        //brand id already exist in opencart table
                        $updtQry = "UPDATE `oc_manufacturer` SET `name`='$brandname',`image`='',`sort_order`='0' WHERE `manufacturer_id`=$brandId";
                        if (mysqli_query($opcon, $updtQry)) {
                            echo "$brandname updated successfully.<br/>";
                        } else {
                            echo "Error updating $brandname. <br/>";
                        }
                    }else{
                        //brand inserted in opencart table
                        $inserQry = "INSERT INTO `oc_manufacturer` (`manufacturer_id`, `name`, `image` , `sort_order`) VALUES ('$brandId', '$brandname', '' , '0')";
                        if (mysqli_query($opcon, $inserQry)) {
                            echo "$brandname inserted successfully. <br/>";
                        } else {
                            echo "error while inserting $brandname. <br/>";
                        }
                    }
                    $selQry1 = "SELECT * FROM `oc_manufacturer_to_store` WHERE `manufacturer_id`='$brandId'";
                    $count1 = mysqli_num_rows(mysqli_query($opcon,$selQry1));
                    
                    if($count1){
                        //brand id already exist in opencart table
                        $updtQry = "UPDATE `oc_manufacturer_to_store` SET `store_id`='0' WHERE `manufacturer_id`=$brandId";
                        if (mysqli_query($opcon, $updtQry)) {
                            echo "$brandname updated successfully.<br/>";
                        } else {
                            echo "Error updating $brandname. <br/>";
                        }
                    }else{
                        //brand inserted in opencart table
                        $inserQry = "INSERT INTO `oc_manufacturer_to_store` (`manufacturer_id`, `store_id`) VALUES ('$brandId', '0')";
                        if (mysqli_query($opcon, $inserQry)) {
                            echo "$brandname inserted successfully. <br/>";
                        } else {
                            echo "error while inserting $brandname. <br/>";
                        }
                    }
                }
            }
        } else {
            echo "0 results";
        }
        
    }else{
        echo"Error : Connection failed";die;
    }
?>