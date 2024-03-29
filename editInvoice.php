<?php
include './classes.php';
session_start();
header('Content-type: text/html');


$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$params=array(
		array("InvoiceNo",array($_REQUEST["param"]),"equal")
);



$invoices = Invoice::getInstancesByFields($db, $params);
$invoice= $invoices[0];


if(isset($_SESSION['customer']) && $_SESSION['customer']->Permission>1 ||
								 $_SESSION['customer']->CustomerID == $invoice->getCustomerId()){ 
	


	$lines= $invoice->getLines();
	
		
	/********************************************
	 * 				Invoice Fields				*
	 * 											*
	 * 1- $invoice->getLines()					*
	 * 2- $invoice->getCustomerId()				*
	 * 3- $invoice->CompanyName					*
	 * 4- $invoice->GrossTotal					*
	 * 5- $invoice->InvoiceDate					*
	 * 6- $invoice->InvoiceNo					*
	 * 											*
	 ********************************************/
	$params=array(
		array("CustomerID", array($invoice->getCustomerId()), "equal")
	);
	
	$customers= Customer::getInstancesByFields($db, $params);
	$customer=$customers[0];
	 
	 echo '
<div class="update_div" name="Invoice" id="false">
		<br><br>
		<form action="updateMyInvoice.php" method="post" class="update_form" name="InvoiceNo" id="'.$invoice->InvoiceNo.'">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				
				<div class="permanent">
					<label>Company Name</label>
					<label class="to_ident">'.$customer->CompanyName.'</label>
				</div><br>
						
				<div class="permanent">
					<label  class="to_ident">Tax ID</label>
					<label  class="to_ident">'.$customer->CustomerTaxID.'</label>
				</div><br>

				<div class="permanent">
					<label class ="to_ident">Invoice Nr.</label>
					<label class="to_ident">'.$invoice->InvoiceNo.'</label>
				</div><br>
							

				<div class="permanent">
					<label >Invoice Date</label>
					<label class="to_ident">'.$invoice->StartDate.'</label>
				</div><br>
				
				<table class="search_results_tb">
	<tr>
	<th>Product Code:</th>
			<th>Product Description:</th>
			<th>UN</th>
			<th>Quantity</th>
			<th>Unit Price</th>
			<th>Tax</th>
			<th>Total Price</th>
		</tr>';

	
	
	for($i=0;$i<count($lines);$i++){
		$line=$lines[$i];
		
		$productQueryParams=array(
		array("ProductCode",array($line->Product->ProductCode),"equal")
		);
		
		$products=Product::getInstancesByFields($db, $productQueryParams);
		$product=$products[0];
		
		
		echo'<tr>';
		echo '<td>'.$line->Product->ProductCode.'</td>';
		echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
		echo utf8_encode('<td>'.$product->UnitOfMeasure.'</td>');
		echo utf8_encode('<td>'.$line->Quantity.'</td>');
		echo utf8_encode('<td>'.($line->Product->UnitPrice/100).' &euro; </td>');
		echo utf8_encode('<td>'.$line->Tax->TaxPercentage.'</td>');
		echo utf8_encode('<td>'.((int)($line->CreditAmount*($line->Tax->TaxPercentage/100+1))/100).' &euro;</td>');
		if($invoice->Status==0)
			echo '<td><img src="./pictures/minus.png" width="16" height="16" border="0" alt="Remove from cart"
					class="rem_line"  id="'.$line->LineNo.'" name="'.$invoice->InvoiceNo.'"/></td>';
		echo '</tr>';
	}
	echo '</table>'
	;
	
	if($_SESSION['customer']->Permission >1){
		
		$status = $invoice->Status;
		$statusDesc;
		$the_other_state=0;
		$the_other_statusDesc = "Open";
		
		if($status == 0){
			$statusDesc="Open";
			$the_other_state = 1;
			$the_other_statusDesc = "Closed";
		}
		else{
			$statusDesc="Closed";
		}
		
		echo '
		<select name="Status" id="Status">
			<option id="'.$status.'" value="'.$status.'" label="'.$statusDesc.'">'.$statusDesc.'</option> 
			<option id="'.$the_other_state.'" value="'.$the_other_state.'" label="'.$the_other_statusDesc.'">'.$the_other_statusDesc.'</option>
		</select>
					
		<input type="button" class="change_inv_state" id="'.$invoice->InvoiceNo.'" name="change_inv_state" value="save">
		</form>
	</div>'
		;
		}
}
	
?>