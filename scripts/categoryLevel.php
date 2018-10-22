<?php
include("includes/config.php");
    if (!mysqli_connect_errno()){
        $query = "SELECT * FROM `categories` WHERE DATE(`modified`) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        $result = mysqli_query($hpcon,$query);//echo'<pre>';print_r($result);die;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $categoryId = $row['id'];
                $categoryname = $row['category'];
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
                $selQry = "SELECT * FROM `oc_category_path` WHERE `category_id`='$categoryId'";
                $count = mysqli_num_rows(mysqli_query($opcon,$selQry));
                    if($count){
                        //category id already exist in opencart table
                        //foreach($parentChildArr as $level => $child_path_id){
                        //    $updtQry = "UPDATE `oc_category_path` SET `path_id`='$child_path_id', `level`='$level' WHERE `category_id`=$categoryId";
                        //    
                        //    if(mysqli_query($opcon, $updtQry)) {
                        //        echo "$categoryname updated successfully.<br/>";
                        //    }else{
                        //        echo $updtQry.'<br/>';
                        //        echo "Error updating $categoryname. <br/>";
                        //    }
                        //}
                        
                    }else{
                        //category inserted in opencart table
                        foreach($parentChildArr as $level => $child_path_id){
                            $inserQry = "INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES ('$categoryId','$child_path_id','$level');";
                            //echo $inserQry;die;
                            if(mysqli_query($opcon, $inserQry)) {
                                echo "$categoryname inserted successfully. <br/>";
                            }else{
                                echo "Error while inserting $categoryname. <br/>";
                            }
                        }
                        
                        
                    }
            }
        }
    }
    
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