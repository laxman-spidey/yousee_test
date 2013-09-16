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
	$this->finalResult .= "{";
	$loginUtil->generateFinalResult();
	$this->finalResult .= "}";

	echo $finalResult;


}
?>