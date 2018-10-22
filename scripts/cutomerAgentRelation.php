<?php
    include("includes/config.php");
    
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `customers` WHERE DATE(`modified`) >= DATE_SUB(CURDATE(), INTERVAL 1 HOUR)";
        $result = mysqli_query($hpcon,$query);//echo'<pre>';print_r($result);die;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $customerId = $row['id'];
                $agentID = $row['agent_id'];
                if(!empty($agentID) && $agentID != 0){
                    $password = "12345678";
                    $encrptPassword = md5($password);
                    $customFieldAgentId = (object) array('1'=>$agentID);
                    $customFieldAgentId = json_encode($customFieldAgentId);
                    $selQry = "SELECT * FROM `oc_customer` WHERE `customer_id`='$customerId'";
                    $count = mysqli_num_rows(mysqli_query($opcon,$selQry));
                    if($count){
                        $updtQry = "UPDATE `oc_customer` SET `custom_field`='$customFieldAgentId', `password`='$encrptPassword' WHERE `customer_id`=$customerId";
                        
                        if(mysqli_query($opcon, $updtQry)) {
                            echo "$customerId updated successfully.<br/>";
                        }else{
                            echo $updtQry.'<br/>';
                            echo "Error updating $customerId. <br/>";
                        }
                    }    
                }else{
                    echo'Agent is not selected.<br/>';
                }
            }
        }else{
            echo"0 results";
        }
    }
    
?>