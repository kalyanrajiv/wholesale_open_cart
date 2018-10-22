<?php
include("includes/config.php");
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `customers` WHERE DATE(`modified`) >= DATE_SUB(CURDATE(), INTERVAL 10 HOUR)";
        $result = mysqli_query($hpcon,$query);//echo'<pre>';print_r($result);die;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $custId = $row['id'];
                $same_delivery_address = $row['same_delivery_address'];
                $del_city = $row['del_city'];
                $del_state = $row['del_state'];
                $del_zip = $row['del_zip'];
                $del_address_1 = $row['del_address_1'];
                $del_address_2 = $row['del_address_2'];
                $addressTableCountQry = "SELECT * FROM `oc_address` WHERE `customer_id`=$custId";
                $addressTableCountRslt = mysqli_num_rows(mysqli_query($opcon,$addressTableCountQry));
                if($addressTableCountRslt == 2){
                    $addressTableQry = "SELECT * FROM `oc_address` WHERE `customer_id`=$custId ORDER BY `address_id` ASC LIMIT 1,1";
                    $addressTableRslt = mysqli_query($opcon,$addressTableQry);
                    if(mysqli_num_rows($addressTableRslt)>0){
                        while($rows = mysqli_fetch_assoc($addressTableRslt)){
                            $address_1 = $rows['address_1'];
                            $address_2 = $rows['address_2'];
                            $city = $rows['city'];
                            $postcode = $rows['postcode'];
                            $country_id = $rows['country_id'];
                            if($country_id == '222'){
                                $country = "GB";
                            }else{
                                $country = "OTH";
                            }
                            $zone_id = $rows['zone_id'];
                            $zoneNameQry = "SELECT `name` FROM `oc_zone` WHERE `zone_id`=$zone_id";
                            $zoneNameRes = mysqli_query($opcon,$zoneNameQry);
                            if(mysqli_num_rows($zoneNameRes)>0){
                                while($roww = mysqli_fetch_assoc($zoneNameRes)){
                                    $state = $roww['name'];
                                }
                            }else{
                                $state = '';
                            }
                            if($same_delivery_address == 1 && $del_city == '' && $del_state == '' && $del_zip == '' && $del_address_1 == '' && $del_address_2 == ''){
                                $updtDelAddQry = "UPDATE `customers` SET `del_city`='$city',`del_state`='$state',`del_zip`='$postcode',`del_address_1`='$address_1',`del_address_2`='$address_2' WHERE `id`=$custId";
                                if(mysqli_query($hpcon, $updtDelAddQry)) {
                                    echo "Updated successfully. <br/>";
                                }else{
                                    echo "Error while updating. <br/>";
                                }    
                            }
                            
                        }
                    }
                }
            }
        }
    }
?>