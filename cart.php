<?php require_once 'include/head.php'; ?>
    
<?php require_once 'include/header.php'; ?>

	<div id="container">
  
		<div class="container">
			<div class="row">
    

		<div id="content" class="col-sm-12">

		<div class="clearfix"></div>
			
					<?php
									if(isset($_GET['remove'])) {
										require_once('config/cart-remove.php');
									} else {
										if(isset($_SESSION['pID']) && isset($_SESSION['pQua'])) {
											$countProducts = @count($_SESSION['pID']);
													
												echo '<div class="table-responsive" id="cartab" style="text-align: left !important;">
												<table class="table">
												<thead>
												<tr>
													<th>No.</th>
													<th>Изображение</th>
													<th>Име На Продукта</th>
													<th>Цветове</th>
													<th>Ед. Цена</th>
													<th>Количество</th>
													<th>Кр. Цена</th>
													<th>#</th>
												</tr>
												</thead>
												<tbody>
												<form method="post" action="#finalize">';
												
												$i = 0; $allPrice = '';
												foreach($_SESSION['pID'] as $getProductID) {
													
													$sqlCart = "SELECT * FROM `products_translate` WHERE `uniqueID`='".$getProductID."' AND language = '".lang()."'";
													$resCart = mys_q($sqlCart);
													
													$sqlPic = "SELECT * FROM `products_images` WHERE `uniqueID`='".$getProductID."'";
													$resPic = mys_q($sqlPic);
													$rowPic = mys_r($resPic);
													
													if(mys_n($resCart) > 0) {
														$rowCart = mys_r($resCart);
														
														$getProductQuantity = $_SESSION['pQua'];
														@$getProductQuantity = $getProductQuantity[$i];

								// ============================================== COLORS ========================
													foreach($_SESSION['pID'] as $getPID) {
														
														$x = $getPID;
														//$_SESSION['pColors'] = array();

														if(isset($_POST['pColors'])) {
															$_SESSION['pColors'] = array();
															foreach($_POST as $colorKey => $value) {
																if ($colorKey == 'pColors') { 
																	$_SESSION['pColors'][$x][$colorKey] = $value;
																}
															}
														}
													}

														echo $x;
														echo '<pre>';
														print_r($_SESSION['pColors']);
														echo '</pre>';

								// ============================================== COLORS ========================
														
														if($rowCart['promo_active'] == 1) {
															$getPriceDisk = $rowCart['price'] / 100;
															$getPriceDisk2A = ($rowCart['price'] - $rowCart['promo_price'] / $getPriceDisk);
															
															$catRealPriceBadgeA = '<span class="round-tag">-'.$getPriceDisk2A.'%</span>';
															$realCartPrice = ''.$rowCart['promo_price'].' <small><s>'.$rowCart['price'].'</s></small>';
														} else {
															$catRealPriceBadgeA = '';
															$realCartPrice = $rowCart['price'];
														}
														settype($sumPrice, 'float');
														if ($rowCart['promo_active'] == 1) {
															@$sumPrice = $rowCart['promo_price'] * $getProductQuantity;
														}else{
															@$sumPrice = $rowCart['price'] * $getProductQuantity;
														}		
	

														echo '	<tr>
															<td>'.($i + 1).'.</td>
															<td><img src="'.$g['url'].'uploads/products/thumbs/'.$rowPic['image'].'" style="width: 55px; height: 55px;" /></td>
															<td><a href="'.$g['url'].'product/'.$rowCart['uniqueID'].'/'.convertToURL($rowCart['title']).'.html"><b>'.$rowCart['title'].'</b></a></td>
															<td>';

														foreach($_SESSION['pColors'] as $key=>$value) {
															echo ''.$x.' => '.$value.'';

															if ($key == $x) {
																echo 'Работи 2';
																$query = "SELECT * FROM colors_translate WHERE `uniqueID` IN('".$value."') ";

																var_dump($query);

																$sendQuery = mys_q($query);
																while($row = mysql_fetch_assoc($sendQuery)) {
																	echo '
																		'.$row['title'].' </br>
																	';
																}
															} else { echo ' Wrong if '; }

														}
															echo '
															</td>
															<td>'.$realCartPrice.'</td>
															<td>x'.$getProductQuantity.'</td>
															<td>'.$sumPrice.''.$lang['products4'].'</td>
															<td><a href="'.$g['url'].'cart.html?remove='.$i.'" class="remove-button">X</a></td>
															
															<input type="hidden" name="productID[]" value="'.$rowCart['uniqueID'].'" />
															<input type="hidden" name="productQuantity[]" value="'.$getProductQuantity.'" />
														</tr>';
														
														
													}
													
													$allPrice = $allPrice + $sumPrice;
													
													$i++;
												}              
												
												echo '<tr>
													<td colspan="6" >
														<button class="btn btn-success">
															<b>'.$lang['cart7'].'</b> <b>'.$i.'</b> '.$lang['cart8'].' <b>'.$allPrice.'</b>'.$lang['products4'].'
														</button>
													</td>
												</tr>';
												
												echo '</tbody>
												</table>
												</div>';
															
															$postResult = 0;
															
															if(isset($_POST['finalcart'])) {

																// Инклуудване на код за е-мейл известяване

																$orderName 		= shield($_POST['name']);
																$orderEmail 	= shield($_POST['email']);
																$orderPhone 	= shield($_POST['phone']);
																$orderCountry 	= shield($_POST['country']);
																$orderCity 		= shield($_POST['city']);
																$orderAdress 	= shield($_POST['adress']);
																$orderComment 	= shield($_POST['comment']);
																
																if(empty($orderName) || empty($orderEmail) || empty($orderPhone) || empty($orderCountry) || empty($orderCity) || empty($orderAdress)) {
																	echo alert($lang['cart10'], 'danger');
																} else {
																	$nextOrderID = mys_auto('orders');
																	
																	if((isset($_SESSION['username']) AND isset($_SESSION['password'])) AND (!empty($_SESSION['username']) AND !empty($_SESSION['password']))) {
																		$sqlOrder2 = "SELECT `id` FROM `users` WHERE `username`='".$_SESSION['username']."' AND `password`='".$_SESSION['password']."'";
																		$resOrder2 = mys_q($sqlOrder2);
																		$rowOrder2 = mys_r($resOrder2);
																		
																		$orderUserID = $rowOrder2['id'];
																	} else {
																		$orderUserID = 0;
																	}
																	
																	$sqlOrder = "INSERT INTO `orders` (`id`)VALUES ('".$nextOrderID."');";
																	$resOrder = mys_q($sqlOrder);
																	
																	$unique = mysql_insert_id();
																	
																	$sqlOrder = "INSERT INTO `orders_translate` (`id`, `uniqueID`, `language`, `name`, `email`, `phone`, `country`, `city`, `adress`, `comment`, `userID`, `time`, `status`)
																									VALUES ('".$nextOrderID."', '".$unique."', 'bg', '".$orderName."', '".$orderEmail."', '".$orderPhone."', '".$orderCountry."', '".$orderCity."', '".$orderAdress."', '".$orderComment."', '".$orderUserID."', '".time()."', '0');";
																	$resOrder = mys_q($sqlOrder);
																	
																	$ord = 0;
																	foreach($_POST['productID'] AS &$orderProductID) {
																		$nextOrderID222 = mys_auto('orders_items');
																		$orderProductID = shield($orderProductID);
																		$orderProductQuantity = shield($_POST['productQuantity'][$ord]);
																		
																		if(!empty($orderProductID) AND !empty($orderProductQuantity)) {
																			$sqlOrderItems = "INSERT INTO `orders_items` (`id`, `uniqueID`, `productID`, `quantity`)
																													VALUES ('".$nextOrderID222."', '".$unique."', '".$orderProductID."', '".$orderProductQuantity."');";
																			$resOrderItems = mys_q($sqlOrderItems);
																		}
																		
																		$ord++;
																	}
																	
																	unset($_SESSION['pID']);
																	unset($_SESSION['pQua']);
																	unset($_SESSION['ip']);
																	unset($_SESSION['ua']);
																	unset($_SESSION['pColors']);
																	
																	echo '<script>$("#cartab").hide();</script>';
																	echo '<div id="suc">'.alert(''.$lang['cart11'].'', 'success').'</div><meta http-equiv="refresh" content="3">';
																	
																	
																	$postResult = 1;
																}
															}
															
															if($postResult == 0) {
																if($_SESSION['userID'] AND $_SESSION['userName'] AND $_SESSION['userPass']) {
																	$sqlOrder3 = "SELECT * FROM `users_translate` WHERE `username`='".$_SESSION['userName']."' AND `password`='".$_SESSION['userPass']."' AND `language`='".lang()."'";
																	$resOrder3 = mys_q($sqlOrder3);
																	$rowOrder3 = mys_r($resOrder3);
																	
																	echo '<div class="col-md-6">
																		'.$lang['cart13'].' *<br />
																		<input name="name" type="text" class="form-control" placeholder="" value="'.$rowOrder3['realname'].'"  style=" color: #666666;" />
																	</div>
																	<div class="col-md-6">
																		'.$lang['cart14'].' *<br />
																		<input name="email" type="text" class="form-control" placeholder="" value="'.$rowOrder3['email'].'"  style=" color: #666666;" />
																	</div>
																	
																	<br /><br />
																	
																	<div class="col-md-6">
																		'.$lang['cart15'].' *<br />
																		<input name="phone" type="text" class="form-control" placeholder="" value="'.$rowOrder3['phone'].'" style=" color: #666666;" />
																	</div>
																	<div class="col-md-6">
																		'.$lang['cart16'].' *<br />
																		<input name="country" type="text" class="form-control" placeholder="" value="'.$rowOrder3['country'].'" style=" color: #666666;" />
																	</div>
																	
																	<br />
																	
																	<div class="col-md-6">
																		'.$lang['cart17'].' *<br />
																		<input name="city" type="text" class="form-control" placeholder="" value="'.$rowOrder3['city'].'" style=" color: #666666;" />
																	</div>
																	<div class="col-md-6">
																		'.$lang['cart18'].' *<br />
																		<input name="adress" type="text" class="form-control" placeholder="" value="'.$rowOrder3['address'].'" style=" color: #666666;" />
																	</div>
																	
																	<br />
																	
																	<div class="col-md-12" style="margin-bottom: 10px;">
																		'.$lang['cart19'].'...<br />
																		<textarea name="comment" class="form-control" placeholder="..." style="height: 125px; color: #666666;"></textarea>
																	</div>
																	
																	<div class="col-md-12">
																		<center><input name="finalcart" type="submit" value="'.$lang['cart20'].'" class="form-control" style="width: 25%;" /></center>
																	</div>
																	
																	<div style="clear: both;"></div>';
																} else {
																	echo '<div class="col-md-6">
																		'.$lang['cart13'].' *<br />
																		<input name="name" type="text" class="form-control btn-primary" style="width:100%;background:none; color: #666666;"/>
																	</div>
																	<div class="col-md-6">
																		'.$lang['cart14'].' *<br />
																		<input name="email" type="text" class="form-control btn-primary"  style="width:100%;background:none; color: #666666;"/>
																	</div>
																	
																	<br /><hr>
																	
																	<div class="col-md-6">
																		'.$lang['cart15'].' *<br />
																		<input name="phone" type="text" class="form-control btn-primary"  style="width:100%;background:none; color: #666666;"/>
																	</div>
																	<div class="col-md-6">
																		'.$lang['cart16'].' *<br />
																		<input name="country" type="text" class="form-control btn-primary"  value="България" style="width:100%;background:none; color: #666666;"/>
																	</div>
																	
																	<br />
																	
																	<div class="col-md-6">
																		Град *<br />
																		<input name="city" type="text" class="form-control btn-primary"  style="width:100%;background:none; color: #666666;"/>
																	</div>
																	<div class="col-md-6">
																		Адрес *<br />
																		<input name="adress" type="text" class="form-control btn-primary"  style="width:100%;background:none; color: #666666;"/>
																	</div>
																	
																	<br />
																	
																	<div class="col-md-12" style="margin-bottom: 10px;">
																		Коментар...<br />
																		<textarea name="comment" rows="10" class="form-control btn-primary" placeholder="Коментар..." style="width:100%;background:none; color: #666666;"></textarea>
																	</div>
																	
																	<div class="col-md-12">
																		<center><input name="finalcart" type="submit" value="'.$lang['cart20'].'" class="btn btn-primary" style="width:100%"; /></center>
																	</div>
																	
																	<div style="clear: both;"></div>';
																}
																
																echo '</form>
																<br /><br />';
															}
										} else {
											echo '<br /><br />';
											echo alert('Няма артикули в количката!', 'info');
											echo '<br /><br />';
										}
									}
								?>
				
		
         
        </div>
     
			</div>
		</div>
    </div>
</div>
  