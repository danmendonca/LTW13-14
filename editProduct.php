<?php
include './classes.php';
session_start();
header('Content-type: text/html');

if(!isset($_SESSION['customer']) || $_SESSION['customer']->Permission<2) 
	header("Location: onlineInvoiceSystem.php");



$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$products= array();



	$params=array(
			array("ProductCode",array($_REQUEST["param"]),"equal")
	);
	
	$products= Product::getInstancesByFields($db, $params);
	if(count($products)!=1)throw new GeneralException(new Err_Not_Found("products"));
	
	$product= $products[0];
	
	echo '
		<div class="update_div" name="Product" id="false">
				<br><br>
			<form action="updateProduct.php" method="post" class="update_form" name="ProductCode" id="'.$product->ProductCode.'">
				<fieldset>
					<legend><h2>Edit Product Information</h2></legend>
						<div id="ProductDescription">
							<label class="to_ident" for="ProductDescription">Product Description</label>
							<input type="text" name="ProductDescription" id="ProductDescription" 
							placeholder="'.$product->ProductDescription.'" value="'.$product->ProductDescription.'"><br>
						</div><br>
						
						<div id="ProductTypeID">
							<label class="to_ident" for="ProductTypeID">Product Type</label>
							<input type="text" name="ProductTypeID" id="ProductTypeID" 
							placeholder="'.$product->ProductTypeID.'" value="'.$product->ProductTypeID.'"><br>
						</div><br>
									
			
						<div id="UnitOfMeasure">
							<label class="to_ident" for="UnitOfMeasure"> Unit of measure</label>
							<input type="text" name="UnitOfMeasure" id="UnitOfMeasure" 
							placeholder="'.$product->UnitOfMeasure.'" value="'.$product->UnitOfMeasure.'"><br>
						</div><br>
									
						<div id="UnitPrice">
							<label class="to_ident" for="UnitPrice">Unit Price</label>
							<input type="text" name="UnitPrice" id="UnitPrice" 
							placeholder="'.$product->UnitPrice.'" value="'.$product->UnitPrice.'"><br>
						</div><br>
						<input type="button" id="save_edit" value="save">
				</fieldset>
			</form>
		</div>';
?>
					
'