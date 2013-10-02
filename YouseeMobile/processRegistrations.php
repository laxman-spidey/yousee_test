<?php

$firstName = "";
$lastName="";
$email="";
$password="";
$dob="";
$city="";
$phNo="";
$finalResult="";
require_once 'classes/login.php';
require_once 'prod_conn.php';

$EMAIL_ALREADY_TAKEN_ERROR_CODE = 120;
$USERNAME_EXISTS_ERROR_CODE = 121;
$PHONE_NUMBER_ALREADY_EXISTS = 122;



// getting post variables
if(isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dob']) && isset($_POST['phNo']) && isset($_POST['city']))
{
	$firstName = $_POST['firstName'];
	$lastName  = $_POST['lastName'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$dob = $_POST['dob'];
	$phNo = $_POST['phNo'];
	$city = $_POST['city'];
	require_once 'prod_conn.php';
	executeRegQuery();

}
else
{
	$firstName = "hfuasdfh";
	$lastName  = "sdhfjkhaskdf";
	$email = "fdsh.afds@kjga.com";
	$password = "hfjsdf";
	$dob = "ghdfkj";
	require_once 'prod_conn.php';
	executeRegQuery();
	echo "biscuit........";
}
//$password=md5($_POST['password']);

function executeRegQuery()
{
	global $firstName,$lastName, $email, $password, $dob, $city, $phNo , $finalResult;

	if (isEmailAvailable($email))
	{
		sendEmailAlreadyExistsError();
	}
	else
	{

		$finalResult .= "{";
		$finalResult .= insertUserIntoDatabase();
		$finalResult .= login();
		$finalResult .= "}";
	}
	echo $finalResult;


}

function insertUserIntoDatabase()
{
	global $firstName,$lastName, $email, $password, $dob, $city, $phNo, $EMAIL_ALREADY_TAKEN_ERROR_CODE,$USERNAME_EXISTS_ERROR_CODE, $REGISTRATION_SUCCESS_CODE,$TAG_RESULT_CODE,$TAG_FAILED,$PHONE_NUMBER_ALREADY_EXISTS;
	$userValues="'D','$email','$password','A'";
	$userInsertAtts = "user_type_id, username, password, registration_status";
	$insertUserQuery="INSERT INTO users($userInsertAtts) VALUES($userValues)";
	echo $insertUserQuery;
	if (!mysql_query($insertUserQuery))
	{
		//setResultHeader($TAG_FAILED);

		return "\"$TAG_RESULT_CODE\":\"".$TAG_FAILED. "\",";
	}

	$userid = mysql_insert_id();
	$donorInsertAtts = "type_of_donor, first_name, last_name, user_id";
	$donorValues = "'Individual','$firstName','$lastName', '$userid'";
	$insertDonorQuery="INSERT INTO donors($donorInsertAtts) VALUES($donorValues)";
	echo $insertDonorQuery;
	if (!mysql_query($insertDonorQuery))
	{

		return "\"$TAG_RESULT_CODE\":\"".$TAG_FAILED. "\",";

	}
	return "\"$TAG_RESULT_CODE\":\"".$TAG_SUCCESS. "\",";


}
function isEmailAvailable($emailId)
{
	$emailAvailabilityCheckQuery = "SELECT user_id FROM users WHERE email = '".$email."'";
	$result = mysql_query($emailAvailabilityCheckQuery);
	if(mysql_num_rows($result) > 1 )
	{
		return false;
	}
	return true;

}
function sendEmailAlreadyExistsError()
{
	global $finalResult,$TAG_RESULT_CODE,$EMAIL_ALREADY_TAKEN_ERROR_CODE;
	//setResultHeader($EMAIL_ALREADY_TAKEN_ERROR_CODE);
	$finalResult .= "{";

	$finalResult .= "\"$TAG_RESULT_CODE\":\"".$EMAIL_ALREADY_TAKEN_ERROR_CODE. "\"";
	$finalResult .= "}";


}
function login()
{
	global $email, $password;
	$loginUtil = new LoginExec();
	$loginUtil->exec($email,$password);
	return $loginUtil->generateFinalResult();

}
//echo $insertUserQuery;
?>