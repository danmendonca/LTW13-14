<?php

include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
	
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	
	
	
	if(!isset($_POST["parameters"]) || count($_POST["parameters"])==0 ) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$parameters=$_POST["parameters"];
	
	
	
	$id=$parameters[0][1];
	if($id!="") {//update
		$customer =Customer::updateInDB($db, $parameters);
		
		echo json_encode($customer);
	}
	else{
		$customer=Customer::instatiate($db,$parameters);
		echo json_encode($customer);
	}
	
	
	
	if(isset($_POST["reloadSession"]) && strcmp ( $_POST["reloadSession"] , "true" )==0){//if the customer we are editing is the one stored in session than update it
		$_SESSION["customer"]=$customer;//change sessionCustomer
	}
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>