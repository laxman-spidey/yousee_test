<?php
class LoginExec
{


	private $successFlag=NULL;
	private $sessionId=NULL;
	private $userId = null;
	private $userTypeId = null;
	private $errorMsg = null;
	private $errorCode = null;
	private $finalResult=NULL;


	function LoginExec()
	{


		require_once ('prod_conn.php');

	}

	function preProcess($username, $password)
	{
		// Sanitize the POST values
		$this->username = $this->clean ( $username );
		$this->password = md5 ( $this->clean ( $password ) );

	}

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

	function exec($username, $password)
	{
		// Create query

		$this->preProcess($username, $password);
		//echo "jhgdfgljdffhgfksjdbgof  $this->username , $this->password";
		$query = "SELECT * FROM users WHERE username='$this->username' AND password='$this->password'";

		$result = mysql_query ( $query );
		// Check whether the query was successful or not
		if ($result)
		{

			if (mysql_num_rows ( $result ) == 1)
			{
				// Login Successful
				//session_regenerate_id ();
				$user = mysql_fetch_assoc ( $result );
				$_SESSION['SESS_USER_ID'] = $user ['user_id'];
				$_SESSION['SESS_USER_TYPE'] = $user ['user_type_id'];

				$_SESSION['SESS_USERNAME'] = $user ['username'];
				// setRequiredInfo 			();
				session_write_close ();
				$this->successFlag = "true";
				$this->sessionId = session_id ();
				$this->userId = $user ['user_id'];
				$this->userTypeId = $user ['user_type_id'];
				return true;
			} else
			{
				$this->successFlag = "false";
			}
		} else
		{
			$this->successFlag = "false";
			$this->errorMsg = "Username or Password invalid.";
			return false;
		}
	}

	function generateFinalResult()
	{
		$this->finalResult .= "\"successFlag\":\"$this->successFlag\",";
		$this->finalResult .= "\"sessionId\":\"$this->sessionId\",";
		$this->finalResult .= "\"userId\":\"$this->userId\",";
		$this->finalResult .= "\"userTypeId\":\"$this->userTypeId\"";

		return $this->finalResult;

	}
	function setSessionData()
	{
		$_SESSION['SESS_USER_ID'] = $this->userId;
		$_SESSION['SESS_USER_TYPE'] = $this->userTypeId;

	}

}