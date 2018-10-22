<?php
    include("includes/config.php");
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `categories`";
        $result = mysqli_query($hpcon,$query);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $categoryId = $row['id'];
                $categoryname = $row['category'];
                $idNamePath = $row['id_name_path'];
                $categoryDes = $row['description'];
                $image = $row['image'];
                $top = $row['top'];
                $parent_id = $row['parent_id'];
                if(empty($parent_id) || $parent_id == null){
                    $parent_id = 0;
                }
                $parentChildArr = array();
                if($parent_id == 0){
                    $parentChildArr = array($categoryId);
                }else{
                    $parents = getParent($categoryId,$hpcon,$data = array());
                    if(is_array($parents) && !empty($parents)){
                        $Parents = array_reverse($parents);
                    }
                    $currentLevel = array($categoryId);
                    $parentChildArr = array_merge($Parents,$currentLevel);
                }
                $column = $row['column'];
                $sortOrder = $row['sort_order'];
                $status = $row['status'];
                $created = $row['created'];
                $modified = $row['modified'];
                if (!mysqli_connect_errno()){
                    $selQry = "SELECT * FROM `oc_category` WHERE `category_id`='$categoryId'";
                    $count = mysqli_num_rows(mysqli_query($opcon,$selQry));
                    //$resSelQry = mysqli_query($opcon,$selQry);//echo'<pre>';print_r($resSelQry);die;
                    if($count){
                        //category id already exist in opencart table
                        $updtQry = "UPDATE `oc_category` SET `parent_id`='$parent_id',`image`='$image',`sort_order`='0',`top`='0', `column`='0', `status`='1' WHERE `category_id`=$categoryId";
                        
                        if(mysqli_query($opcon, $updtQry)) {
                            echo "$categoryname updated successfully.<br/>";
                        }else{
                            echo "Error updating $categoryname. <br/>";
                        }
                    }else{
                        //category inserted in opencart table
                        $inserQry = "INSERT INTO `oc_category` (`category_id`, `image`, `parent_id` , `top` , `column` , `sort_order` , `status`, `date_added`) VALUES ('$categoryId','$image','$parent_id','0','0','0','$status','$created');";
                        
                        if(mysqli_query($opcon, $inserQry)) {
                            echo "$categoryname inserted successfully. <br/>";
                        }else{
                            echo "Error while inserting $categoryname. <br/>";
                        }
                    }
                    
                    $selQry1 = "SELECT * FROM `oc_category_description` WHERE `category_id`='$categoryId'";
                    $count1 = mysqli_num_rows(mysqli_query($opcon,$selQry1));
                    if($count1){
                        //category id already exist in opencart table
                        $updtQry = "UPDATE `oc_category_description` SET `language_id`='1',`name`='$categoryname',`description`='$categoryDes',`meta_title`='$categoryname', `meta_description`='', `meta_keyword`='' WHERE `category_id`=$categoryId";
                        
                        if(mysqli_query($opcon, $updtQry)) {
                            echo "$categoryname updated successfully.<br/>";
                        }else{
                            echo "Error updating $categoryname. <br/>";
                        }
                    }else{
                        //category inserted in opencart table
                        $inserQry = "INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ('$categoryId', '1', '$categoryname', '$categoryDes', '$categoryname', '', '')";
                        
                        if(mysqli_query($opcon, $inserQry)) {
                            echo "$categoryname inserted successfully. <br/>";
                        }else{
                            echo "Error while inserting $categoryname. <br/>";
                        }
                    }
                    $selQry2 = "SELECT * FROM `oc_category_to_store` WHERE `category_id`='$categoryId'";
                    $count2 = mysqli_num_rows(mysqli_query($opcon,$selQry2));
                    if($count2){
                        //category id already exist in opencart table
                        $updtQry = "UPDATE `oc_category_to_store` SET `store_id`='0' WHERE `category_id`=$categoryId";
                        
                        if(mysqli_query($opcon, $updtQry)) {
                            echo "$categoryname updated successfully.<br/>";
                        }else{
                            echo "Error updating $categoryname. <br/>";
                        }
                    }else{
                        //category inserted in opencart table
                        $inserQry = "INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES ('$categoryId','0');";
                        
                        if(mysqli_query($opcon, $inserQry)) {
                            echo "$categoryname inserted successfully. <br/>";
                        }else{
                            echo "Error while inserting $categoryname. <br/>";
                        }
                    }
                    $selQry3 = "SELECT * FROM `oc_category_path` WHERE `category_id`='$categoryId'";
                    $count3 = mysqli_num_rows(mysqli_query($opcon,$selQry3));
                    if($count3){
                        //category id already exist in opencart table
                        //foreach($parentChildArr as $level => $child_path_id){
                        //    $updtQry = "UPDATE `oc_category_path` SET `path_id`='$child_path_id', `level`='$level' WHERE `category_id`=$categoryId";
                        //    //echo $updtQry;
                        //    if(mysqli_query($opcon, $updtQry)) {
                        //        echo "$categoryname updated successfully.<br/>";
                        //    }else{
                        //        //echo $updtQry.'<br/>';
                        //        echo "Error updating $categoryname. <br/>";
                        //    }
                        //}
                        
                    }else{
                        //category inserted in opencart table
                        foreach($parentChildArr as $level => $child_path_id){
                            $inserQry = "INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES ('$categoryId','$child_path_id','$level');";
                            if(mysqli_query($opcon, $inserQry)) {
                                echo "$categoryname inserted successfully. <br/>";
                            }else{
                                echo "Error while inserting $categoryname. <br/>";
                            }
                        }
                        
                        
                    }
                }
            }
        }else{
            echo "0 results";
        }
        
    }
    //sort_order(0)
    //top(0)
    //column(0)
    //status(1)
    //language_id(1)
    //meta_description()
    //meta_keyword()
    //store_id(0)
    
    function getParent($id,$hpcon,$data = array()) {
        if (!empty($id)) {
            if($parentID = getOneParent($id,$hpcon)){
                $data[] = $parentID;
                return getParent($parentID,$hpcon,$data);
            }else{
                if(is_array($data)){
                     return $data;    
                }
            }
        }else{
            return $data;
        }
    }
    
    function getOneParent($catId,$hpcon){
        //$cat_id = 0;
        $userQry = "SELECT * FROM `categories` WHERE `id`=$catId";
        $userRes = mysqli_query($hpcon,$userQry);
        $rows = array();
        if(mysqli_num_rows($userRes) > 0){
            while($row = mysqli_fetch_assoc($userRes)){
                $rows[] = $row;
            }
        }
        
        if(!empty($rows)){
            foreach($rows as $key => $value){
                $cat_id =  $value['parent_id'];    
            }
        }
        return $cat_id;
    }
    
?>