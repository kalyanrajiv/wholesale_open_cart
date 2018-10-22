<?php
      include("includes/config.php");
     if (!mysqli_connect_errno()){
		$query = "SELECT * FROM `colors`";
		$product_result = mysqli_query($hpcon, $query);
		if($product_result->num_rows>0){
			while($row= mysqli_fetch_array($product_result,MYSQLI_ASSOC)){
				    $colorId = $row['id'];
				    $colorName = $row['name'];
				    $status = $row['status'];
				    $created = $row['created'];
				    $modified = $row['modified'];
				    $colorcount_query = "SELECT COUNT(id) FROM `colors`";
				   //$colorcount_query_result = mysqli_query($hpcon, $colorcount_query);
				   //  $totalcolor = mysqli_fetch_array($colorcount_query_result, MYSQLI_ASSOC) ;
				     $totalcolor = mysqli_num_rows(mysqli_query($hpcon,"SELECT  * FROM `colors`"));
				   //  print_r($totalcolor);die;
				    if (!mysqli_connect_errno()){
							
						     $optionId_query = "SELECT `option_id` FROM `oc_option_description` WHERE `name` = 'color';";
						     $queryresult = mysqli_query($opcon,$optionId_query );
						     if($queryresult->num_rows>0){					  
								   while ($rowoption=mysqli_fetch_array($queryresult,MYSQLI_ASSOC)){
									     $optionid = $rowoption['option_id'];
										$option_value_id_query = mysqli_num_rows(mysqli_query($opcon,"SELECT  * FROM `oc_option_value` WHERE `option_value_id` = '$colorId'"));
									     if($option_value_id_query){
											   $oc_option_value_definition = "UPDATE  oc_option_value  SET
																`option_id` = '$optionid'
																   WHERE `option_value_id` = '$colorId' ";
															
													     $updateQuery1 = mysqli_query($opcon, $oc_option_value_definition);
													     if(mysqli_affected_rows($opcon) == 1){
													     echo "update Records(name = $colorName) in oc_option_value_description : " . mysqli_affected_rows($opcon);
													     echo "<br/>";
													     }			    	   
																    
									     }else{
											   $inserQry1 = "INSERT INTO `oc_option_value` SET
												   `option_id` = '$optionid'	  ";
												   if (mysqli_query($opcon, $inserQry1)) {
													   
													   echo $inserQry1."record insert table oc_option_value with option_id = $optionid"  ;
													   echo "<br/>";
												   } else {
															echo "Error: " . $inserQry1 . "" . mysqli_error($opcon);
												   }
										     // echo "kkaad";die;
											
									     }
								   }
							   
						     }
						     
						     
									    $count = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_option_value_description` WHERE `option_value_id` = '$colorId'"));
									    if($count){
										     echo $definition = "UPDATE  oc_option_value_description  SET
												`name` = '$colorName',	 
												`language_id` = '1',
												`option_id` = '$optionid'
												    WHERE  `option_value_id` = $colorId";
												
										     $updateQuery = mysqli_query($opcon, $definition);
										     if(mysqli_affected_rows($opcon) == 1){
										     echo "update Records(name = $colorName) in oc_option_value_description : " . mysqli_affected_rows($opcon);
										     echo "<br/>";
										     }			   
									    }else{
										     $inserQry = "INSERT INTO `oc_option_value_description` SET
														`option_value_id` = $colorId,
														    `language_id` = '1',
														    `option_id` = '$optionid',
														    `name` = '$colorName'";
										    if (mysqli_query($opcon, $inserQry)) {
											    echo "New record created successfully";
											    echo "<br/>";
										    } else {
											   echo "Error: " . $inserQry . "" . mysqli_error($opcon);
										    }
									   
						     }
				   }
			}
		}else {
			echo "0 results";
		}
	}
        
    
?> 