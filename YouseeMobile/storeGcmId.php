<?php
include 'prod_conn.php';
if (isset($_POST['registrationID']))
{
	$gcmId = $_POST['registrationID'];
	//$gcmId = "fshdkjfn";
	$registerFlag = $_POST['registerFlag'];
	$gcmQuery;
	
	if ($registerFlag == "true")
	{
		$gcmQuery = "insert into gcm_registrations (gcm_registration_id) values ('$gcmId')";
		
	}
	else
	{
		$gcmQuery = "DELETE FROM gcm_registrations WHERE gcm_registration_id = '$gcmId'";
	}
	$successFlag = false;
	if (mysql_query($gcmQuery))
	{
		$successFlag = "true";
	}
	$jsonArray = array("successFlag" => "$successFlag", "Test" => $gcmQuery);
	echo json_encode($jsonArray);
}
?>