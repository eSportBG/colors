<?php require_once 'include/head.php'; ?>
    
<?php require_once 'include/header.php'; ?>

        <div class="container">
            <div class="row">
		
     <?php
	
		$table1 = 'products';
		$table2 = 'products_translate';
		$table3 = 'products_images';
		$uploadFolder = $g['url'].'uploads/products/';
		$colorFolder = $g['url'].'uploads/colors/';
		
		## START IF ISSET ID ## 
		
		if(isset($_GET['id'])) {
			
			$id = shield($_GET['id']);
			
			$sql = fastQuery("SELECT * FROM `".$table2."` WHERE uniqueID = '".$id."' AND language = '".lang()."'");
			if(!empty($sql)){
				foreach($sql as $key2 => $values2){
					
					if($values2['promo_active'] == 1) {
						$price = '
								<span class="price-new">'.$values2['promo_price'].'  '.$lang['products4'].'</span> 
								<span class="price-old">'.$values2['price'].' '.$lang['products4'].'</span> ';
					}else {
						$price = '
								<span class="price-new">'.$values2['price'].' '.$lang['products4'].'</span> ';
					}

					if(preg_match('/,/', $values2['color'])) {
						$expColor = explode(', ', $values2['color']);
					} else {
						$expColor[] = $values2['color'];
					}
					
					## START QUERY FOR PICTURES ##
					
					$sqlPictures = fastQuery("SELECT * FROM `".$table3."` WHERE `uniqueID` = '".$id."'");
					
					## END QUERY FOR PICTURES ##
					
					echo '
					<div class="row" style="padding: 25px 0;">
						<center><h3 class="title"><a href="#">'.$values2['title'].'</a></h3></center>
					</div>
					<div class="col-lg-6 col-md-6 col-xs-12" style="float: right; padding: 25px 10px; border: 1px solid #ccc;">
						
						<div class="up-comming-pro gray-bg clearfix">
							<span style="font-size: 1.2em;">'.htmlspecialchars_decode(stripcslashes($values2['description'])).'</span>
						</div>
						
						Цена:
						<div class="price">
								'.$price.'
						</div>
						<hr>';

						// ========================================= COLORS ========= ###

						echo '<div class="colors"><ul>';

							foreach($expColor as $keyColor => $valColor) {
								$getColorTitle = getFastResult('colors_translate', '`uniqueID`=\''.$valColor.'\' AND `language`=\''.lang().'\'', '`uniqueID` ASC');
								$getColorTitle = $getColorTitle[0];

								$sqlColors = "SELECT * FROM `colors_images` WHERE `uniqueID` = '".$getColorTitle['uniqueID']."'";
								$resColors = mys_q($sqlColors);
								$rowColors = mys_r($resColors);

								echo '
								  <li>
								  <input type="hidden" class="getColorID" id="'.$getColorTitle['uniqueID'].'" />
								  <input type="checkbox" name="pColors" class="css-checkbox pColor" value="'.$getColorTitle['uniqueID'].'" id="cb-'.$getColorTitle['uniqueID'].'" />
								    <label for="cb-'.$getColorTitle['uniqueID'].'"><img src="'.$colorFolder.''.$rowColors['image'].'" height="50px" />
								<div class="colorsC">
								    <input type="hidden" value="0" style="width: 45px;" class="qtyC" />
								</div>
								    </label>
								  </li>';
							}
							
							// ========================================= COLORS ========= ###
					echo '</ul></div>

						<div class="cart">
                                <input name="qMinus" type="button" value="-" class="qtyMinus" field="quantity" id="'.$values2['id'].'" />
                                <input name="quantity" type="text" value="1" class="qty" style="width: 45px;" id="productID'.$values2['id'].'" />
                                <input name="qPlus" type="button" value="+" class="qtyPlus" field="quantity" id="'.$values2['id'].'" />
                                <input name="addToCart" type="submit" onClick="history.go(0)" value="Добави в количката" class="addToCart" id="'.$values2['id'].'" />
                            </form>
                                    
                                <img src="" class="cartSuccess" id="successID'.$values2['id'].'" alt="" width="16" height="16" style="display: none;" />
                            
                            <div style="clear: both;"></div><div class="clear"></div>
                        </div></div><br /><br /><div class="clear"></div>';
		
					
					## START RESULTS FOR PICTURES ##

  					echo '<div class="clear"></div><div class="col-lg-6 col-md-6 col-xs-12">';
					if(!empty($sqlPictures)) {
						$i=0;
						foreach($sqlPictures as $key3 => $value3) {
							
							echo '
								<div style="width: 90%; margin: 5px auto; overflow: hidden;">
									<a href="'.$uploadFolder.''.$value3['image'].'" class="highslide" onclick="return hs.expand(this)">
										<img src="'.$uploadFolder.'/'.$value3['image'].'" class="img-responsive">
									</a>
								</div>';
						$i++;
							if($i == 6) {
								echo '<div class="clearfix"></div>';
								$i = 0;
							}
						}
					}
					echo '</div>';
					## END RESULTS FOR PICTURES ##
				}
			}						
		}
		
	?>

				</div>
            </div>

<?php require_once 'include/bottom-js.php'; ?>