<?php
include 'prod_conn.php';
if(isset($_POST['gcmId']))
{
	$gcmId = $_POST['gcmId'];
	$gcmQuery = "insert into gcm_registrations (gcm_registration_id) values ('$gcmId')";
	$successFlag = "true";
	$jsonArray = array("successFlag" => "$successFlag");
	echo json_encode($jsonArray);
}
	
?>