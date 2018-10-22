<?php
include("includes/config.php");
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `customers` WHERE DATE(`modified`) >= DATE_SUB(CURDATE(), INTERVAL 10 HOUR)";
        $result = mysqli_query($hpcon,$query);//echo'<pre>';print_r($result);die;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $custmerId = $row['id'];
                $same_delivery_address = $row['same_delivery_address'];
                $firstname = $row['fname'];
                $lastname = $row['lname'];
                $business = $row['business'];
                $country = $row['country'];
                if($country == 'GB'){
                    $countryID = '222';
                }else{
                    $countryID = '258';
                }
                $del_city = $row['del_city'];
                $del_state = $row['del_state'];
                $del_zip = $row['del_zip'];
                $del_address_1 = $row['del_address_1'];
                $del_address_2 = $row['del_address_2'];
                if($same_delivery_address == 0){
                    $checkAddressCount = "SELECT * FROM `oc_address` WHERE `customer_id`=$custmerId";
                    $result = mysqli_num_rows(mysqli_query($opcon,$checkAddressCount));
                    if($result == 1){
                        if(!empty($del_state)){
                            $findStateQry = "SELECT `zone_id` FROM `oc_zone` WHERE `name`='$del_state'";
                            $results = mysqli_query($opcon,$findStateQry);
                            if (mysqli_num_rows($results) > 0) {
                                while($rows = mysqli_fetch_assoc($results)) {
                                    $delStateId = $rows['zone_id'];
                                }
                            }else{
                                $delStateId = '99';
                            }
                        }else{
                            $delStateId = '99';
                        }
                        echo $insrtDelAddQry = "INSERT INTO `oc_address` (`customer_id`,`firstname`,`lastname`,`company`,`address_1`,`address_2`,`city`,`postcode`,`country_id`,`zone_id`,`custom_field`)VALUES($custmerId,'$firstname','$lastname','$business','$del_address_1','$del_address_2','$del_city','$del_zip',$countryID,$delStateId,'')";
                        if(mysqli_query($opcon, $insrtDelAddQry)) {
                            echo "inserted successfully. <br/>";
                        }else{
                            echo "Error while inserting. <br/>";
                        }
                    }
                    
                }
            }
        }
    }
?>