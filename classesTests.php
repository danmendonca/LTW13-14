<?php
include 'classes.php';

//$customer=new Customer(null, 5555555, "Fransisco", "maluco@gmail.com", 1234, 1);
//$customer_json='{"CustomerTaxID":12314335,"CompanyName":"Sonae","Email":"sonae@gmail.com","Password":1234,"Permission":1,"BillingAddress":{"AddressDetail":"Rua dos Clerigos","PostalCode1":4200,"PostalCode2":222,"City":"Porto","Country":"Portugal"}}';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

/*
$invoice= new Invoice(null, "2013-12-12", "2013-12-12", "0");
$customer=Customer::getInstancesByFields($db, array(array("CustomerID",array(3),"equal")))[0];
$invoice->Customer=$customer;

$invoice->insertIntoDB($db)
*/
try{
	

	$invoice=Invoice::getInstancesByFields($db,	array(array("InvoiceNo",array(1),"equal")))[0];
	$invoices=array($invoice);
	
	$profXML=simplexml_load_file("./SAFT-PT_XML_PROF.xml");
	
	Invoice::importSAFT_File($db, $profXML->asXML());
	
}
catch(GeneralException $e){
	echo $e;
}
?>
