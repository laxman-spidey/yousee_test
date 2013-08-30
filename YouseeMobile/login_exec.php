<?php

// Start session
session_start ();

// Include database connection details
require_once ('prod_conn.php');

// Array to store validation errors
$errmsg_arr = array ();

// Validation error flag
$errflag = false;


// Function to sanitize values received from the form. Prevents SQL injection
function clean($str)
{
	$str = @trim ( $str );
	if (get_magic_quotes_gpc ())
	{
		$str = stripslashes ( $str );
	}
	return mysql_real_escape_string ( $str );
}

// Sanitize the POST values
$username = clean ( $_POST ['username'] );
$password = md5 ( clean ( $_POST ['password'] ) );
// Input Validations
if ($username == '')
{
	$errmsg_arr [] = 'Username missing';
	$errflag = true;
}
if ($password == '')
{
	$errmsg_arr [] = 'Password missing';
	$errflag = true;
}

// If there are input validations, redirect back to the login form
if ($errflag)
{
	$_SESSION ['ERRMSG_ARR'] = $errmsg_arr;
	session_write_close ();
	header ( "location: login-form.php" );
	exit ();
}

// Create query
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
//echo $query;
$result = mysql_query ( $query );
// Check whether the query was successful or not
$successFlag = null;
$sessionId = null;
$userId = null;
$userTypeId = null;
$errorMsg = null;
$errorCode = null;
if ($result)
{
	if (mysql_num_rows ( $result ) == 1)
	{
		// Login Successful
		session_regenerate_id ();
		$user = mysql_fetch_assoc ( $result );
		$_SESSION ['SESS_USER_ID'] = $user ['user_id'];
		$_SESSION ['SESS_USER_TYPE'] = $user ['user_type_id'];
		
		$_SESSION ['SESS_USERNAME'] = $user ['username'];
		// setRequiredInfo ();
		session_write_close ();
		$successFlag = "true";
		$sessionId = session_id ();
		$userId = $user ['user_id'];
		$userTypeId = $user ['user_type_id'];
	} else
	{
		$successFlag = "false";	
	}
} else
{
	$successFlag = "false";
	$errorMsg = "Username or Password invalid.";
}
/*
 * { "successFlag":"daskhdjas" "sessionId":"fgjkasgkjdas" "userId":"fdjkshfjk" "userTypeId":"oiiywejfnsdnfjsdgf" }
 */
?>

{
	"successFlag":"<?php echo $successFlag; ?>",
	"sessionId":"<?php echo $sessionId; ?>",
	"userId":"<?php echo $userId; ?>",
	"userTypeId":"<?php echo $userTypeId; ?>",
}
