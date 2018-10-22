<?php
	include("includes/config.php");
	if (!mysqli_connect_errno()){
		$query = "SELECT * FROM `products` ORDER BY `id` ASC LIMIT 11000";
		$product_result = mysqli_query($hpcon, $query);
		$vatQry = "SELECT `attribute_value` FROM `settings` WHERE `attribute_name`='vat'";
		$vatRes = mysqli_query($hpcon,$vatQry);
		if($vatRes->num_rows>0){
			while($rows = mysqli_fetch_array($vatRes,MYSQLI_ASSOC)){
				$vat = $rows['attribute_value'];
			}
		}else{
			$vat = 20;
		}
		if($product_result->num_rows>0){
			while($row = mysqli_fetch_array($product_result,MYSQLI_ASSOC)){
				//echo'<pre>';print_r($row);
				$productId = $row['id'];
				$productName = mysqli_real_escape_string($hpcon,$row['product']);
				$quantity = $row['quantity'];
				$productDes = $row['description'];
				$location = $row['location'];
				$category_id = $row['category_id'];
				$cost_price = $row['cost_price'];
				$lu_cp = $row['lu_cp'];
				$vat_excluded_wholesale_price = $row['vat_excluded_wholesale_price'];
				$vat_exclude_retail_price = $row['vat_exclude_retail_price'];
				$retail_cost_price = $row['retail_cost_price'];
				$lu_rcp = $row['lu_rcp'];
				$selling_price = $row['selling_price'];
				//$vat_price = $selling_price*$vat/100;
				$price = $selling_price/(1+$vat/100);//selling price/(1+vat/100)
				$lu_sp = $row['lu_sp'];
				$retail_selling_price = $row['retail_selling_price'];
				$lu_rsp = $row['lu_rsp'];
				$brand_id = $row['brand_id'];
				
				if(!empty($row['model'])){
					$model = $row['model'];
				}else{
					$model = "No Model";
				}
				$manufacturing_date = $row['manufacturing_date'];
				$sku = $row['sku'];
				$country_make = $row['country_make'];
				$product_code = $row['product_code'];
				$weight = $row['weight'];
				$color = $row['color'];
				$user_id = $row['user_id'];
				$featured = $row['featured'];
				$discount = $row['discount'];
				$retail_discount = $row['retail_discount'];
				$discount_status = $row['discount_status'];
				$rt_discount_status = $row['rt_discount_status'];
				$max_discount = $row['max_discount'];
				$min_discount = $row['min_discount'];
				$special_offer = $row['special_offer'];
				$festival_offer = $row['festival_offer'];
				$retail_special_offer = $row['retail_special_offer'];
				$image_id = $row['image_id'];
				 
				$image = $row['image']; 
				 
				 
					  // $image = 'catalog/demo/'.$image;
				$image_dir = $row['image_dir'];
				$manufacturer = $row['manufacturer'];
				$modified_by = $row['modified_by'];
				$last_updated = $row['last_updated'];
				$qty_modified = $row['qty_modified'];
				$status = $row['status'];
				$last_import = $row['last_import'];
				$created = $row['created'];
				$modified = $row['modified'];
				$productcount_query = "SELECT COUNT(id) FROM `products`";
				$productcount_query_result = mysqli_query($hpcon, $productcount_query);
				$totalproduct = mysqli_fetch_array($productcount_query_result, MYSQLI_ASSOC);
				if (!mysqli_connect_errno()){
						   
					$count1 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product` WHERE `product_id`='$productId'"));
					if($count1){
						$definition1 = "UPDATE  `oc_product`  SET
						
							`model` = '$model',
							  `sku` = '$sku' ,
							  `upc` = '$product_code',
							  `ean`= '1',
							  `jan`= '1',
							  `isbn`= '1',
							  `mpn`= '1',
							  `location` = '$location' ,
							  `quantity` = '$quantity',
							  `stock_status_id` ='1',
							  `manufacturer_id` =  '$brand_id' ,
							  `shipping`= '1',
							  `price` = '$price' ,
							  `points`= '0',
							  `tax_class_id`= '9',
							  `date_available`= '0000-00-00',
							  `weight` = '$weight' ,
							  `image` =   '$image' ,
							   `length`= '1',
							  `width`= '1',
							  `height`= '1',
							  `subtract`= '0',
							  `minimum`= '1',
							  `sort_order`= '0',
							  `status`=  '$status' ,
							  `viewed`= '1',
							  `date_added` = '$created' ,
							  `date_modified`= '$modified' WHERE `product_id`=$productId";
						$updateQuery1 = mysqli_query($opcon, $definition1);
						if(mysqli_affected_rows($opcon) == 1){
							echo "update Records(product_id = $productId) in oc_products : " . mysqli_affected_rows($opcon);
							echo "<br/>";
						}
								
					}else{
						$inserQry1 = "INSERT INTO `oc_product` SET
							  `product_id` = '$productId',
							  `model` = '$model',
							  `sku` = '$sku' ,
							  `upc` = '$product_code',
							  `ean`= '1',
							  `jan`= '1',
							  `isbn`= '1',
							  `mpn`= '1',
							  `location` = '$location' ,
							  `quantity` = '$quantity',
							  `stock_status_id` ='1',
							  `manufacturer_id` =  '$brand_id' ,
							  `shipping`= '1',
							  `price` = '$price' ,
							  `points`= '0',
							  `tax_class_id`= '9',
							  `date_available`= '',
							  `weight` = '$weight' ,
							  `image` =   '$image' ,
							  `length`= '1',
							  `width`= '1',
							  `height`= '1',
							  `subtract`= '0',
							  `minimum`= '1',
							  `sort_order`= '0',
							  `status`=  '$status' ,
							  `viewed`= '1',
							  `date_added` = '$created' ,
							  `date_modified`= '$modified'";
						if (mysqli_query($opcon, $inserQry1)) {
							echo "New record created successfully";
							echo "<br/>";
						} else {
							echo "Error: " . $inserQry1 . "" . mysqli_error($opcon);
						}
					}
							  //oc_product_description table
					$count2 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_description` WHERE `product_id`='$productId'"));
					if($count2){
						$oc_product_description = " UPDATE `oc_product_description` SET
							 `language_id` = '1',
							 `name` = '$productName',
							 `description` = '$productDes',
							 `meta_title` = '$productName',
							 `meta_description` = '',
							 `meta_keyword` = ''
						   WHERE `product_id` = '$productId' ";
						$updateQuery2 = mysqli_query($opcon, $oc_product_description);
						if(mysqli_affected_rows($opcon) == 1){
							echo "update Records(product_id = $productId) in oc_product_description : " . mysqli_affected_rows($opcon);
							echo "<br/>";
						} 
								
					}else{
						$insert_oc_product_description_Qry = "INSERT INTO `oc_product_description` SET
						   `product_id`= '$productId' ,
						   `language_id`= '1',
						   `name`= '$productName',
						   `description` = ' ',
						   `tag` = '',
						   `meta_title` = '$productName',
						   `meta_description` = '',
						   `meta_keyword` = ''";
											//$productDes
						if (mysqli_query($opcon, $insert_oc_product_description_Qry)) {
							echo "New record created successfully";
							echo "<br/>";
						   
						} else {
							echo "Error: " . $insert_oc_product_description_Qry . "" . mysqli_error($opcon);
						}
					}
							  // oc_product_to_category  Table
					$count3 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_to_category` WHERE `product_id`='$productId'"));
					if($count3){
						$oc_product_to_category_query = "UPDATE `oc_product_to_category` SET
													`category_id` = '$category_id' WHERE `product_id` = '$productId' ";
						$updateQuery3 = mysqli_query($opcon, $oc_product_to_category_query);
						if(mysqli_affected_rows($opcon) == 1){
							echo "update Records(product_id = $productId) in oc_product_to_category : " . mysqli_affected_rows($opcon);
							echo "<br/>";
						}else{
							     
						}
								
					}else{
						$insert_oc_product_to_category_Qry = " INSERT INTO `oc_product_to_category` SET
							`product_id`='$productId',
						     `category_id`='$category_id' ;"; 
						if (mysqli_query($opcon, $insert_oc_product_to_category_Qry)) {
							echo "New record created successfully";
							echo "<br/>";
						   
						} else {
							echo "Error: " . $insert_oc_product_to_category_Qry . "" . mysqli_error($opcon);
						}
					}
							  //add color
							   
					$count4 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_option` WHERE `product_id`='$productId'"));
					$optionId_query = "SELECT `option_id` FROM `oc_option_description` WHERE `name` = 'color';";
					$queryresult = mysqli_query($opcon,$optionId_query );
					if($queryresult->num_rows>0){					  
						while ($rowoption=mysqli_fetch_array($queryresult,MYSQLI_ASSOC)){
							$optionid = $rowoption['option_id'];
							if($count4){
								$oc_product_color = " UPDATE `oc_product_option` SET
														  `option_id` = '$optionid'
														   WHERE `product_id` = '$productId' ";
								$updateQuery4 = mysqli_query($opcon, $oc_product_color);
								if(mysqli_affected_rows($opcon) == 1){
									echo "update Records(product_id = $productId) in oc_product_option : " . mysqli_affected_rows($opcon);
									echo "<br/>";
								   }else{
										
								}
						
							}else{
								$insert_oc_product_color_Qry = "INSERT INTO `oc_product_option` SET `product_id`= '$productId' ,
									 `option_id` = '$optionid'"; 
								if (mysqli_query($opcon, $insert_oc_product_color_Qry)) {
									echo "New record created successfully";
									echo "<br/>";
								   
								} else {
									echo "Error: " . $insert_oc_product_color_Qry . "" . mysqli_error($opcon);
								}
							}
						}
						    
					}
							   ///oc_product_option_value
					$count5 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_option_value` WHERE `product_id`='$productId'"));
					$optionId_query = "SELECT * FROM `oc_product_option` WHERE `product_id`='$productId'";
					$queryresult = mysqli_query($opcon,$optionId_query );
					if($queryresult->num_rows>0){					  
						while ($rowoption=mysqli_fetch_array($queryresult,MYSQLI_ASSOC)){
							$optionid = $rowoption['option_id'];
							$product_option_id = $rowoption['product_option_id'];
							$colorid_query ="SELECT * FROM `oc_option_value_description` WHERE `name` = '$color'" ;
							$colorresult = mysqli_query($opcon,$colorid_query );		
							if($colorresult->num_rows>0){
								while ($coloroption=mysqli_fetch_array($colorresult,MYSQLI_ASSOC)){
									$option_value_id = $coloroption['option_value_id'];
									if($count5){
										// echo "update";
										$oc_product_color = " UPDATE`oc_product_option_value` SET
										`product_option_id` = '$product_option_id',
										`option_id` = '$optionid',
										`option_value_id` = '$option_value_id',
										`quantity`  = '$quantity',
										`subtract`  = '0',
										`price`  = '0',
										`price_prefix` = '' ,
										`points` = '0',
										`points_prefix` = 'Point' ,
										`weight` = '$weight' ,
										`weight_prefix` = 'weight' ,
										`created`  = '$created',
										`modified`  = '$modified'
										WHERE `product_id` = '$productId' ";
										$updateQuery5 = mysqli_query($opcon, $oc_product_color);
										if(mysqli_affected_rows($opcon) == 1){
											echo "update Records(product_id = $productId) in oc_product_option_value : " . mysqli_affected_rows($opcon);
											echo "<br/>";
										}else{
											     
										}
								
									}else{
										//echo "insert";echo "<br/>";die;
										 $insert_oc_product_color_Qry = "INSERT INTO `oc_product_option_value` SET
										 `product_option_id` = '$product_option_id',
										 `product_id`= '$productId' ,
										 `option_id` = '$optionid',
										 `option_value_id` = '$option_value_id',
										 `quantity`  = '$quantity',
										 `subtract`  = '0',
										 `price`  = '0',
										 `price_prefix` = 'Price' ,
										 `points` = '0',
										 `points_prefix` = 'Points' ,
										 `weight` = '$weight' ,
										 `weight_prefix` = 'weight' 
										"; 
										if (mysqli_query($opcon, $insert_oc_product_color_Qry)) {
										echo "New record created successfully";
										echo "<br/>";
										   
										} else {
											echo "Error: " . $insert_oc_product_color_Qry . "" . mysqli_error($opcon);
										}
									}
								}
							}
						}
						    
					}
							  // oc_product_to_store
					$count6 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_to_store` WHERE `product_id`='$productId'"));
					if($count6){
						$oc_product_store = " UPDATE `oc_product_to_store` SET
							 `store_id` = '0'
						   WHERE `product_id` = '$productId' ";
						$updateQuery6 = mysqli_query($opcon, $oc_product_store);
						if(mysqli_affected_rows($opcon) == 1){
							echo "update Records(product_id = $productId) in oc_product_to_store : " . mysqli_affected_rows($opcon);
							echo "<br/>";
						} 
								
					}else{
						$insert_oc_product_store = "INSERT INTO `oc_product_to_store` SET
							`product_id`= '$productId' ,
							`store_id` = '0'";
											//$productDes
						if (mysqli_query($opcon, $insert_oc_product_store)) {
							echo "New record created successfully";
							echo "<br/>";
						   
						} else {
							echo "Error: " . $insert_oc_product_store . "" . mysqli_error($opcon);
						}
					}
							$count8 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_discount` WHERE `product_id`='$productId'"));
							$without_vat_price_retail = ($retail_selling_price/(1+$vat/100));
							$without_vat_price = ($selling_price/(1+$vat/100));
							if($count8){
									
								$oc_product_discount = " UPDATE `oc_product_discount` SET
												`customer_group_id` ='1',
																	`quantity` ='1',
																	`priority` = '1'																 
																	 `price`  ='$without_vat_price_retail' 
											WHERE `product_id` = '$productId' ";
								$updateQuery8 = mysqli_query($opcon, $oc_product_discount);
								if(mysqli_affected_rows($opcon) == 1){
									echo "update Records(product_id = $productId) in oc_product_discount : " . mysqli_affected_rows($opcon);
									echo "<br/>";
								}
								$oc_product_discount = " UPDATE `oc_product_discount` SET
														`customer_group_id` ='2',
																			`quantity` ='1',
																			`priority` = '1'																 
																			 `price`  ='$without_vat_price' 
													WHERE `product_id` = '$productId' ";
								$updateQuery8 = mysqli_query($opcon, $oc_product_discount);
								if(mysqli_affected_rows($opcon) == 1){
									echo "update Records(product_id = $productId) in oc_product_discount : " . mysqli_affected_rows($opcon);
									echo "<br/>";
								}
										
							}else{
								$insert_oc_product_discount = "INSERT INTO `oc_product_discount` SET
									`product_id`= '$productId' ,
									`customer_group_id` ='1',
													`quantity` ='1',
													`priority` = '1',
													`price`  ='$without_vat_price_retail'";
													//$productDes
								if (mysqli_query($opcon, $insert_oc_product_discount)) {
									echo "New record created successfully";
									echo "<br/>";
								   
								} else {
									echo "Error: " . $insert_oc_product_discount . "" . mysqli_error($opcon);
								}
								
								$insert_oc_product_discount = "INSERT INTO `oc_product_discount` SET
											`product_id`= '$productId' ,
											`customer_group_id` ='2',
															`quantity` ='1',
															`priority` = '1',
															`price`  ='$without_vat_price'";
															//$productDes
								if (mysqli_query($opcon, $insert_oc_product_discount)) {
									echo "New record created successfully";
									echo "<br/>";
								   
								} else {
									echo "Error: " . $insert_oc_product_discount . "" . mysqli_error($opcon);
								}
							}
							
							//if($rt_discount_status == 1){
							//	$count9 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_special` WHERE `product_id`='$productId' AND `customer_group_id`=1"));
							//	$customergroupId = 1;
							//	$retaildiscountprice = $without_vat_price_retail-($without_vat_price_retail*$retail_discount)/100;
							//	if($count9){
							//									//echo "update";die;
							//		$oc_product_special = " UPDATE `oc_product_special` SET
							//									   `customer_group_id` =1,
							//									   `priority` = '1',															 
							//									   `price`  ='$retaildiscountprice',
							//									   WHERE `product_id` = '$productId' ";
							//									   
							//		$updateQuery9 = mysqli_query($opcon, $oc_product_special);
							//		if(mysqli_affected_rows($opcon) == 1){
							//			echo "update Records(product_id = $productId) in oc_product_special : " . mysqli_affected_rows($opcon);
							//			echo "<br/>";
							//		} 
							//	}else{
							//	   ///echo "insert";die;
							//		$insert_oc_product_special = "INSERT INTO `oc_product_special` SET
							//								   `product_id`= '$productId' ,
							//								   `customer_group_id` ='1',											
							//								   `priority` = '1',
							//								   `price`  ='$retaildiscountprice'	";
							//						  //$productDes
							//		if (mysqli_query($opcon, $insert_oc_product_special)) {
							//			echo "New record created successfully";
							//			
							//			echo "<br/>";
							//		   
							//		} else {
							//			echo "Error: " . $insert_oc_product_special . "" . mysqli_error($opcon);
							//		}
							//	}
							//}else{
							//	$count9 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_special` WHERE `product_id`='$productId' AND `customer_group_id`=1"));
							//	$customergroupId = 1;
							//	$retaildiscountprice = $without_vat_price_retail;
							//	if($count9){
							//									//echo "update";die;
							//		$oc_product_special = " UPDATE `oc_product_special` SET
							//									   `customer_group_id` =1,
							//									   `priority` = '1',															 
							//									   `price`  ='$retaildiscountprice',
							//									   WHERE `product_id` = '$productId' ";
							//									   
							//		$updateQuery9 = mysqli_query($opcon, $oc_product_special);
							//		if(mysqli_affected_rows($opcon) == 1){
							//			echo "update Records(product_id = $productId) in oc_product_special : " . mysqli_affected_rows($opcon);
							//			echo "<br/>";
							//		} 
							//	}else{
							//	   ///echo "insert";die;
							//		$insert_oc_product_special = "INSERT INTO `oc_product_special` SET
							//								   `product_id`= '$productId' ,
							//								   `customer_group_id` ='1',											
							//								   `priority` = '1',
							//								   `price`  ='$retaildiscountprice'	";
							//						  //$productDes
							//		if (mysqli_query($opcon, $insert_oc_product_special)) {
							//			echo "New record created successfully";
							//			
							//			echo "<br/>";
							//		   
							//		} else {
							//			echo "Error: " . $insert_oc_product_special . "" . mysqli_error($opcon);
							//		}
							//	}
							//}
							
							//if($discount_status == 1){
							//	$count9 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_special` WHERE `product_id`='$productId' AND `customer_group_id`=2"));
							//	$discountprice = $without_vat_price-($without_vat_price*$discount)/100;
							//	$customergroupId = 2;
							//	if($count9){
							//									//echo "update";die;
							//		$oc_product_special = " UPDATE `oc_product_special` SET
							//									   `customer_group_id` =2,
							//									   `priority` = '1',															 
							//									   `price`  ='$discountprice',
							//									   WHERE `product_id` = '$productId' ";
							//									   
							//		$updateQuery9 = mysqli_query($opcon, $oc_product_special);
							//		if(mysqli_affected_rows($opcon) == 1){
							//			echo "update Records(product_id = $productId) in oc_product_special : " . mysqli_affected_rows($opcon);
							//			echo "<br/>";
							//		} 
							//	}else{
							//	   ///echo "insert";die;
							//		$insert_oc_product_special = "INSERT INTO `oc_product_special` SET
							//								   `product_id`= '$productId' ,
							//								   `customer_group_id` ='2',											
							//								   `priority` = '1',
							//								   `price`  ='$discountprice'	";
							//						  //$productDes
							//		if (mysqli_query($opcon, $insert_oc_product_special)) {
							//			echo "New record created successfully";
							//			
							//			echo "<br/>";
							//		   
							//		} else {
							//			echo "Error: " . $insert_oc_product_special . "" . mysqli_error($opcon);
							//		}
							//	}	
							//}else{
							//	$count9 = mysqli_num_rows(mysqli_query($opcon,"SELECT * FROM `oc_product_special` WHERE `product_id`='$productId' AND `customer_group_id`=2"));
							//	$discountprice = $without_vat_price;
							//	$customergroupId = 2;
							//	if($count9){
							//									//echo "update";die;
							//		$oc_product_special = " UPDATE `oc_product_special` SET
							//									   `customer_group_id` =2,
							//									   `priority` = '1',															 
							//									   `price`  ='$discountprice',
							//									   WHERE `product_id` = '$productId' ";
							//									   
							//		$updateQuery9 = mysqli_query($opcon, $oc_product_special);
							//		if(mysqli_affected_rows($opcon) == 1){
							//			echo "update Records(product_id = $productId) in oc_product_special : " . mysqli_affected_rows($opcon);
							//			echo "<br/>";
							//		} 
							//	}else{
							//	   ///echo "insert";die;
							//		$insert_oc_product_special = "INSERT INTO `oc_product_special` SET
							//								   `product_id`= '$productId' ,
							//								   `customer_group_id` ='2',											
							//								   `priority` = '1',
							//								   `price`  ='$discountprice'	";
							//						  //$productDes
							//		if (mysqli_query($opcon, $insert_oc_product_special)) {
							//			echo "New record created successfully";
							//			
							//			echo "<br/>";
							//		   
							//		} else {
							//			echo "Error: " . $insert_oc_product_special . "" . mysqli_error($opcon);
							//		}
							//	}
							//}
					
				}else{
				    echo "connection error";
				}
			}
		} else {
		    echo "0 results";
		}
	    
	}
    
?>