<?php
    include("includes/config.php");
    if (!mysqli_connect_errno()){
        $orderQuery = "DELETE FROM `invoice_orders` WHERE `created` < DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();";
        $orderResult = mysqli_query($hpcon,$orderQuery);
        
        $orderDetailQuery = "DELETE FROM `invoice_order_details` WHERE `created` < DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();";
        $orderDetailsResult = mysqli_query($hpcon,$orderDetailQuery);
        
        $ocOrderQry = "SELECT * FROM `oc_order` WHERE `date_added` < DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();";
        $ocOrderRslt = mysqli_query($opcon,$ocOrderQry);
        if (mysqli_num_rows($ocOrderRslt) > 0) {
            while($row = mysqli_fetch_assoc($ocOrderRslt)) {
                $orderId = $row['order_id'];
                $ocOrderProductQuery = "DELETE FROM `oc_order_product` WHERE `order_id` = '$orderId'";
                $ocOrderProductResult = mysqli_query($opcon,$ocOrderHistoryQuery);
            }
            $ocOrderQuery = "DELETE FROM `oc_order` WHERE `date_added` < DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();";
            $ocOrderResult = mysqli_query($opcon,$ocOrderQuery);
        }
        
        $ocOrderHistoryQuery = "DELETE FROM `oc_order_history` WHERE `date_added` < DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();";
        $ocOrderHistoryResult = mysqli_query($opcon,$ocOrderHistoryQuery);
        echo "Script Run Successfully";
    }
?>

