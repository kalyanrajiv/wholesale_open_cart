<?php
    include("includes/config.php");
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `customers`";
        $result = mysqli_query($hpcon,$query);//echo'<pre>';print_r($result);die;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $customerId = $row['id'];
                $kioskId = $row['kiosk_id'];
                $agentID = $row['agent_id'];
                $createdBy = $row['created_by'];
                $business = mysqli_real_escape_string($hpcon,$row['business']);
                $fName = mysqli_real_escape_string($hpcon,$row['fname']);
                $lName = mysqli_real_escape_string($hpcon,$row['lname']);
                $vatNum = $row['vat_number'];
                $DOB = $row['date_of_birth'];
                $email = $row['email'];
                $mobile = $row['mobile'];
                $landline = $row['landline'];
                $city = mysqli_real_escape_string($hpcon,$row['city']);
                $state = $row['state'];
                if(!empty($state)){
                    $findStateQry = "SELECT `zone_id` FROM `oc_zone` WHERE `name`='$state'";
                    $results = mysqli_query($opcon,$findStateQry);
                    if (mysqli_num_rows($results) > 0) {
                        while($rows = mysqli_fetch_assoc($results)) {
                            $StateId = $rows['zone_id'];
                        }
                    }else{
                        $StateId = '99';
                    }
                }else{
                    $StateId = '99';
                }
                
                $zip = mysqli_real_escape_string($hpcon,$row['zip']);
                $address1 = mysqli_real_escape_string($hpcon,$row['address_1']);
                $address2 = mysqli_real_escape_string($hpcon,$row['address_2']);
                $sameDelvryAdd = $row['same_delivery_address'];
                $imei = $row['imei'];
                $status = $row['status'];
                $memo = $row['memo'];
                $delCity = $row['del_city'];
                $delState = $row['del_state'];
                if(!empty($delState)){
                    $findStateQry = "SELECT `zone_id` FROM `oc_zone` WHERE `name`='$delState'";
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
                
                $country = $row['country'];
                if($country == 'GB'){
                    $countryID = '222';
                    $custGrpId = '1';
                }else{
                    $countryID = '258';
                    $custGrpId = '2';
                }
                $delZip = $row['del_zip'];
                $delAdd1 = $row['del_address_1'];
                $delAdd2 = $row['del_address_2'];
                $systemUser = $row['system_user'];
                $created = $row['created'];
                $selQry1 = "SELECT * FROM `oc_address` WHERE `customer_id`='$customerId'";
                $count1 = mysqli_num_rows(mysqli_query($opcon,$selQry1));
                if($count1){
                    //customer id already exist in opencart table
                    $updtQry = "UPDATE `oc_address` SET `firstname`='$fName', `lastname`='$lName', `company`='$business', `address_1`='$address1', `address_2`='$address2', `city`='$city', `postcode`='$zip', `country_id`=$countryID, `zone_id`=$StateId, `custom_field`='' WHERE `customer_id`=$customerId";
                    
                    if (mysqli_query($opcon, $updtQry)) {
                        echo "$fName updated successfully.<br/>";
                    } else {
                        echo "Error updating $fName. <br/>";
                    }
                }else{
                    //customer inserted in opencart table
                    $checkAddCount = "SELECT * FROM `oc_address` WHERE `customer_id`=$customerId";
                    $res = mysqli_num_rows(mysqli_query($opcon,$checkAddCount));
                    if($res == 0){
                        $inserQry1 = "INSERT INTO `oc_address`(`customer_id`, `firstname`, `lastname`, `company`, `address_1`, `address_2`, `city`, `postcode`, `country_id`, `zone_id`, `custom_field`) VALUES ('$customerId', '$fName', '$lName', '$business', '$address1', '$address2', '$city', '$zip', $countryID,$StateId, '')";
                        $abc = mysqli_query($opcon, $inserQry1);
                        if ($abc) {
                            $address_id = $opcon->insert_id;
                            echo "$fName inserted successfully. <br/>";
                        } else {
                            //echo $inserQry.'<br/>';
                            echo "error while inserting $fName. <br/>";
                        }
                        
                    }
                }
                $selQry = "SELECT * FROM `oc_customer` WHERE `customer_id`='$customerId' AND `email`='$email'";
                $count = mysqli_num_rows(mysqli_query($opcon,$selQry));
                //$resSelQry = mysqli_query($opcon,$selQry);//echo'<pre>';print_r($resSelQry);die;
                $password = "12345678";
                $encrptPassword = md5($password);
                $customFieldAgentId = (object) array('1'=>$agentID);
                $customFieldAgentId = json_encode($customFieldAgentId);
                if($count){
                    //customer id already exist in opencart table
                    $updtQry = "UPDATE `oc_customer` SET `customer_group_id`=$custGrpId,`store_id`='0', `language_id`='1', `firstname`='$fName', `lastname`='$lName', `email`='$email', `telephone`='$mobile', `fax`='', `password`='$encrptPassword', `salt`='', `cart`='', `wishlist`='', `newsletter`='', `address_id`='', `custom_field`='$customFieldAgentId', `ip`='', `status`='$status', `safe`='0', `token`='', `code`='' WHERE `customer_id`=$customerId";
                    
                    if (mysqli_query($opcon, $updtQry)) {
                        echo "$fName updated successfully.<br/>";
                    } else {
                        echo "Error updating $fName. <br/>";
                    }
                }else{
                    //customer inserted in opencart table
                    $inserQry = "INSERT INTO `oc_customer` (`customer_id`, `customer_group_id`, `store_id` , `language_id`, `firstname`, `lastname`, `email`, `telephone`, `fax`, `password`, `salt`, `cart`, `wishlist`, `newsletter`, `address_id`, `custom_field`, `ip`, `status`, `safe`, `token`, `code`,`date_added`) VALUES ($customerId, $custGrpId, '0' , '1', '$fName', '$lName', '$email', '$mobile', '', '$encrptPassword', '', '', '', '', $address_id, '$customFieldAgentId', '', '$status', '0', '', '', '$created')";
                    if (mysqli_query($opcon, $inserQry)) {
                        echo "$fName inserted successfully . <br/>";
                    } else {
                        echo "error while inserting $fName. <br/>";
                    }
                }
                
                if($sameDelvryAdd == 0){
                    $checkAddressCount = "SELECT * FROM `oc_address` WHERE `customer_id`=$customerId";
                    $resultCount = mysqli_num_rows(mysqli_query($opcon,$checkAddressCount));
                    if($resultCount == 1){
                        $inserQry1 = "INSERT INTO `oc_address`(`customer_id`, `firstname`, `lastname`, `company`, `address_1`, `address_2`, `city`, `postcode`, `country_id`, `zone_id`, `custom_field`) VALUES ('$customerId', '$fName', '$lName', '$business', '$delAdd1', '$delAdd2', '$delCity', '$delZip', $countryID,$delStateId, '')";
                    
                        if (mysqli_query($opcon, $inserQry1)) {
                            echo "$fName inserted successfully. <br/>";
                        } else {
                            echo "error while inserting $fName. <br/>";
                        }
                    }
                }
            }
        } else {
            echo "0 results";
        }
        
    }
    /*customer_group_id(1)
    store_id(0)
    language_id(1)
    fax()
    password()
    salt()
    cart
    wishlist()
    newsletter()
    address_id()
    custom_feild()
    ip()
    safe(0)
    token
    code
    
    country_id(99 India)
    zone_id(1500 Punjab)*/
?>