<?php
require_once 'classes/login.php';
login();


function login()
{
	
	$finalResult = "";
	$username = $_POST['username'];
	$password = $_POST['password'];
	$loginUtil = new LoginExec();
	$loginUtil->exec($username,$password);
	$finalResult .= "{";
	$finalResult .=$loginUtil->generateFinalResult();
	$finalResult .= "}";

	echo $finalResult;


}
?>