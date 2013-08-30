<?php
//Start session
session_start();


//Include database connection details
require_once('prod_conn.php');

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Connect to mysql server
$link = mysql_connect("$dbhost","$dbuser","$dbpass");
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}

//Select database
$db = mysql_select_db("$dbdatabase");
if(!$db) {
	die("Unable to select database");
}

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
	$str = @trim($str);
	if(get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysql_real_escape_string($str);
}

//Sanitize the POST values
$username = clean($_POST['username']);
$password = md5(clean($_POST['password']));

//Input Validations
if($username == '') {
	$errmsg_arr[] = 'Username missing';
	$errflag = true;
}
if($password == '') {
	$errmsg_arr[] = 'Password missing';
	$errflag = true;
}

//If there are input validations, redirect back to the login form
if($errflag) {
	$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
	session_write_close();
	header("location: login-form.php");
	exit();
}

//Create query
$query="SELECT * FROM users WHERE username='$username' AND password='$password'";

$result=mysql_query($query);
//Check whether the query was successful or not
if ($result) {
	if(mysql_num_rows($result) == 1) {
		//Login Successful
		session_regenerate_id();
		$user = mysql_fetch_assoc($result);
		$_SESSION['SESS_USER_ID'] = $user['user_id'];
		$_SESSION['SESS_USER_TYPE'] = $user['user_type_id'];
		$_SESSION['SESS_USERNAME'] = $user['username'];
		//if($result['regStatus']=="A")
			setRequiredInfo();
			session_write_close();
			header("location: date_update.php");	
			exit();
	
	}
	else 
	{
		//Login failed
		header("location: login_failed.php");
		exit();
	}
}else {
	die("Query failed");
}
function setRequiredInfo()
{
	if ($_SESSION['SESS_USER_TYPE'] == "D")
	{
	
		$query = "SELECT displayname, donor_id
          				FROM donors
          				WHERE user_id=".$_SESSION['SESS_USER_ID'];
		$result=mysql_query($query);
		if ($result)
		{
			if(mysql_num_rows($result) == 1)
			{
				$donor = mysql_fetch_assoc($result);
				$_SESSION['SESS_DONOR_ID'] = $donor['donor_id'];
				$_SESSION['SESS_DISPLAYNAME'] = $donor['displayname'];
				
			}
					
		}
	}
	if ($_SESSION['SESS_USER_TYPE'] == "N")
	{
		$query = "SELECT name
          				FROM project_partners
          				WHERE user_id=".$_SESSION['SESS_USER_ID'];
		$result=mysql_query($query);
		if ($result)
		{
			if(mysql_num_rows($result) == 1)
			{
				$ngo = mysql_fetch_assoc($result);
				$_SESSION['SESS_DISPLAYNAME'] = $ngo['name'];
				session_write_close();
			}
		}
	}
	if ($_SESSION['SESS_USER_TYPE'] == "A")
	{
		$_SESSION['SESS_DISPLAYNAME'] = $user['username'];
	}
}
?>
<?php
/* Change log

02-Jun-2013 - Vivek - Password Encryption.

*/
?>	