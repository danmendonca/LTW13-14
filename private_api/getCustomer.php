<?php
include '../classes.php';
session_start();


header('Content-type: text/html; charset=UTF-8');
?>
<fieldset>
	<legend></legend><h2>Search Results</h2></legend>
	
<?php

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


try {
	if(!isset($_SESSION['customer']))
	throw new GeneralException(new Err_Autentication());
	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));
	
	$params=$_GET["params"];
	
	
	
	$customers=Customer::getInstancesByFields($db, $params);
} catch (GeneralException $e) {
	echo '</fieldset>';
	die();
}

if($customers != NULL){
	?>
	<table id="search_results_tb">
		<tr>
		<th>Customer ID</th><th>Customer Name</th><th>Costumer Tax ID</th><th>Email address</th>
		</tr>
<?php
	foreach ($customers as $customer){
		if(isset($_SESSION['customer']) && ($_SESSION['customer']->Permission)>1){				
				echo utf8_encode('<tr>
				<td>' .$customer->CustomerID .'</td>
				<td>' .$customer->CompanyName .'</td>
				<td>' .$customer->CustomerTaxID .'</td>
				<td>' .$customer->Email.' </td>'); ?>
				<td><img src="./pictures/add.png" width="16" height="16" border="0" alt="Detailed"
					class="detail_img" id="<?php echo $customer->CustomerID;?>"/></td>
				<td><img src="./pictures/edit.png" width="16" height="16" border="0" alt="Edit Customer"
					class="edit_img" id="<?php echo $customer->CustomerID;?>"/></td>
<?php
		} 					
		echo '<tr>';	
	}
	echo '</table>
		</fieldset>';	
}
?>