<?php

$firstName = "";
$lastName="";
$email="";
$password="";
$dob="";
$city="";
$phNo="";
$finalResult="";
$test="";
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
	$password =$_POST['password'];
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
	global $firstName,$lastName, $email, $password, $dob, $city, $phNo , $finalResult,$test;

	if (!isEmailAvailable($email))
	{
		
		sendEmailAlreadyExistsError();
	}
	else
	{

		$finalResult .= "{";
		$finalResult .= insertUserIntoDatabase();
		//$test.="\npassword : ".$password;
		//$finalResult .= "\"test\":\"".$test."\",";
		
		$finalResult .= login($email, $password);
		$finalResult .= "}";
	}
	echo $finalResult;


}

function insertUserIntoDatabase()
{
	global $firstName,$lastName, $email, $password, $dob, $city, $phNo,$TAG_SUCCESS, $EMAIL_ALREADY_TAKEN_ERROR_CODE,$USERNAME_EXISTS_ERROR_CODE, $REGISTRATION_SUCCESS_CODE,$TAG_RESULT_CODE,$TAG_FAILED,$PHONE_NUMBER_ALREADY_EXISTS,$test;
	$encryptedPassword = md5 ($password);
	$userValues="'D','$email','$encryptedPassword' ,'A'";
	$userInsertAtts = "user_type_id, username, password, registration_status";
	$insertUserQuery="INSERT INTO users($userInsertAtts) VALUES($userValues)";
	//echo $insertUserQuery;
	//$test.="Query:  ".$insertUserQuery;
	if (!mysql_query($insertUserQuery))
	{
		//$test.="...failed..";
		//setResultHeader($TAG_FAILED);

		return "\"$TAG_RESULT_CODE\":\"".$TAG_FAILED. "\",";
	}

	$userid = mysql_insert_id();
	$donorInsertAtts = "type_of_donor, first_name, last_name, user_id, village_town, mobile_phone_no";
	$donorValues = "'Individual','$firstName','$lastName', '$userid' ,'$city','$phNo'";
	$insertDonorQuery="INSERT INTO donors($donorInsertAtts) VALUES($donorValues)";
	//$test.="Donor Query:  ".$insertDonorQuery;
	//echo $insertDonorQuery;
	if (!mysql_query($insertDonorQuery))
	{
		//$test.="...failed..";
		return "\"$TAG_RESULT_CODE\":\"".$TAG_FAILED. "\",";

	}
	//$test.="...success..";
	return "\"$TAG_RESULT_CODE\":\"".$TAG_SUCCESS. "\",";


}
function isEmailAvailable($emailId)
{
	global $test;
	
	$emailAvailabilityCheckQuery = "SELECT user_id FROM users WHERE username = '".$emailId."'";
	$result = mysql_query($emailAvailabilityCheckQuery);
	$test .= $emailAvailabilityCheckQuery;
	if(mysql_num_rows($result) > 0 )
	{
		//$test.="..false..";
		return false;
	}
	//$test.="..true..";
	return true;

}
function sendEmailAlreadyExistsError()
{
	global $finalResult,$TAG_RESULT_CODE,$EMAIL_ALREADY_TAKEN_ERROR_CODE,$test;
	//setResultHeader($EMAIL_ALREADY_TAKEN_ERROR_CODE);
	$finalResult .= "{";
	$finalResult .= "\"test\":\"".$test."\",";
	$finalResult .= "\"$TAG_RESULT_CODE\":\"".$EMAIL_ALREADY_TAKEN_ERROR_CODE. "\"";
	$finalResult .= "}";


}
function login($email, $password)
{
	$loginUtil = new LoginExec();
	//$loginUtil->debug = true;
	
	$loginUtil->exec($email,$password);
	return $loginUtil->generateFinalResult();

}
//echo $insertUserQuery;
?>