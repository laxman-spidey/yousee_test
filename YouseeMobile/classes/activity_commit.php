<?php session_start();
			if(!isset($_SESSION[ 'SESS_USER_ID']))
			{ ?>
				<?php $thispage ="commit_exec";
$regPage="";
?>

<?php 
?>

<?php
$msg=" ";
/** Validate captcha */

if (isset($_POST['newDonorCommit'])) 
{
	
    if (empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {	
        $captcha_message = "Invalid captcha";
        $style = "background-color: #FF606C";
		$msg="Captcha entered is incorrect";	 
    } 
	else {
        $captcha_message = "Valid captcha";
        $style = "background-color: #CCFF99";
		$_SESSION['POST']=$_POST;
		header("Location: commit_exec.php");
		exit();	
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<title>Registration</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="test/test.css">

<link rel="stylesheet" href="scripts/jquery-ui.css">
<script src="scripts/jquery.min.js"></script>
<script src="scripts/jquery.ui.core.js"></script>
<script src="scripts/jquery.ui.widget.js"></script>
<script src="scripts/datepicker.js"></script>
<script type="text/javascript">
		$(function() {
		$( "#dob" ).datepicker();
		$("#donor_type").change(function(){
			if(this.value=="Group")
			$("#orginfo").show();
			else{
			$("#orginfo").hide();}
			});
	});
	</script>
<script src="scripts/tabscripts.js"></script>
<script src="scripts/reg_validatorv4.js" type="text/javascript"></script>

<script type="text/javascript">
		function showDonorReg()
		{
			//alert("d");
			
				document.getElementById("donorRegScreen").style.display="block";
			
			
		}		
	</script>
</head>
<body >
<div id="wrapper">
<div style="background: #FFF;">
<?php include("header_navbar.php"); ?>
<div style="float:right;width:30%;margin-right:2em;">
<h3> Already Registered?</H3>
<fieldset>
<legend> Login </legend><form id="loginForm" name="loginForm" method="post" action="commit_exec.php">
  <table border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <td><b>Username</b></td>  <td><input name="username" type="text" title="Username" class="textfield" id="username" /></td></tr>
	 <tr> <td><b>Password</b></td>
       <td><input name="password" type="password" title="Password" class="textfield" id="password" /></td>
	  <?php $_SESSION['opp_id']=$_POST['opp_id']; ?>
	   <input name="activity_id" hidden value="<?php echo $_POST['activity_id'];?>" />
    </tr>
    <tr>
      <td><input type="submit" name="Submit" value="Login" /></td>
    </tr>
  </table>
</form></fieldset></div>
<div id="donorRegScreen" style="width:50%;">
<form name="donor" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><input
	type="hidden" name="formName" value="donorReg" /> 
<br />
<div style="min-height:300px; margin-left:100px;" >
<h3>Register</h3>
<fieldset>
 <table   border="0">
      <tr>
        <td><label for="firstName">First name*</label></td>
        <td><input name="fname" type="text" id="firstName" value=""/></td>
        <td><div class="error" id="donor_fname_errorloc"></div></td>
      </tr>
      <tr>
        <td><label for="lastName">Last name*</label></td>
        <td><input name="lname" type="text" id="lastName" value=""/></td>
        <td><div class="error" id="donor_lname_errorloc"></div></td>
      </tr>
	    <tr>
	<td><label for="Gender">Gender</label></td>
	 <td><input name="gender" type="radio" id="male" value="M"/>
       <label for="male">Male</label>
       <input name="gender" type="radio" id="female" value="F"/>
	<label for="female">Female</label></td>
	        <td><div class="error" id="donor_gender_errorloc"></div></td>

      </tr>
	  
      <tr>
          <td><label for="donor_type">Individual/Group*</label></td>
          <td>
            <select name="type" id="donor_type">
              <option value="Individual">Individual</option>
              <option value="Group">Group</option>
            </select></td>
          <td><div class="error" id="type_fname_errorloc"></div></td>
      </tr>
      <tr>
      <td><label>Date of Birth</label></td>
        <td><input name="dob" type="text" id="dob" /></td>
        </tr>     
  
    <script type="text/javascript">
 	var frmvalidator  = new Validator("donor");
	frmvalidator.EnableFocusOnError(true);
	frmvalidator.EnableOnPageErrorDisplay();
	frmvalidator.EnableMsgsTogether();

	frmvalidator.addValidation("fname","req","please enter first name");
	frmvalidator.addValidation("lname","req","please enter last name");	
	frmvalidator.addValidation("gender", "req", "*Please select gender.");

  </script>	
			<tr>
				<td>
					<label for="phone_number">Phone number*</label>
				</td>
				<td>
					<input placeholder="Enter your 10 digit Mobile no.. " type="text" maxlength="10"
					name="phno" id="phone_number" value="" />
				</td>
				<td>
					<div class="error" id="donor_phno_errorloc"></div>
				</td>
			</tr>
			<tr>
				<td>
					<label for="personal_emailid">Preferred Email ID*<br /><span style="font-size:10px">(Default Login username)</label>
				</td>
				<td>
					<input type="text" placeholder="example@yourdomain.com" value="" name="preferredEmail"
					id="preferred_emailid" />
				</td>
				<td>
					<div class="error" id="donor_preferredEmail_errorloc"></div>
				</td>
			</tr>
			<tr>
				<td ><label for="password">Password*</label> </td>
                <td><input type="password" name="password" id="password" value=""/></td>
                <td ><div class="error" id="donor_password_errorloc"></div></td>
		</tr>	
        <tr>
                <td ><label for="password">Retype Password*</label></td>
                <td><input type="password" name="repassword" id="cpassword" value=""/></td>
                <td ><div class="error" id="donor_repassword_errorloc"></div></td>
		</tr>	
					<tr>
				<td>
					<label for="donor_city">City</label>
				</td>
				<td>
					<input type="text" name="city" id="donor_city" value="" />
				</td>
			</tr>
			<tr>
			<tr>
				<td>
					<label for="donor_state">State*</label>
				</td>
				<td>
					<input type="text" name="state" id="donor_state" value="" />
				</td>
				<td>
					<div class="error" id="donor_state_errorloc"></div>
				</td>
			</tr>
				<td>
					<label for="donor_country">Country*</label>
				</td>
				<td>
					<input type="text" name="country" value="India" id="donor_country" />
				</td>
				<td>
					<div class="error" id="donor_country_errorloc"></div>
						   <input name="activity_id" hidden value="<?php echo $_POST['activity_id'];?>" />

				</td>
			</tr>
			<tbody id="orginfo" hidden>
  <tr>
    <td><label for="group_name">Org/Group Name</label></td>
    <td>
      
      <input type="text" name="orgName" id="group_name" />
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label for="group_type">Org/Group Type</label></td>
    <td>
      <select name="orgType" id="group_type">
	          <option value="">--Select--</option>
        <option value="Company">Company</option>
        <option value="Society">Cooperative Society</option>
        <option value="Family">Family</option>
        <option value="Informal">Trust</option>
        <option value="Unregistered Organisation">Unregistered Organisation</option>
      </select></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="vertical-align:top;"><label for="group_desc">Org/Group Description <span class="link"><a href="javascript: void(0)"><font face=verdana,arial,helvetica size=2>[?]</font><span>Please enter the details of your Group/Organisation(max 500 characters</span></label>
      </td>
    <td><textarea placeholder="Please enter the details of your Group/Organisation(max 500 characters)" name="orgDesc" id="group_desc" cols="45" rows="5"></textarea></td>
    <td>&nbsp;</td>
  </tr>
  </tbody>
		<tr> <td>&nbsp</td><td >
		<p><strong>Write the following word:</strong></p>
		<img src="captcha/captcha.php" id="captcha" /><br/>
		<a href="#" onclick="
		document.getElementById('captcha').src='captcha/captcha.php?'+Math.random();
		document.getElementById('captcha-form').focus();"
		id="change-image">Not readable? Change text.</a><br/><br/>	
		<input type="text" name="captcha" id="captcha-form" autocomplete="off" /><br/>
		</td>
		<td style="color:red;"><?php echo $msg; ?></td>
    </tr> 			<script type="text/javascript">
			frmvalidator.addValidation("phno", "req", "	*Please enter  your Phone Number");
			frmvalidator.addValidation("preferredEmail", "email", "	*Please enter your Email properly");
			frmvalidator.addValidation("alternateEmail", "email", "	*Please enter your Email properly");
			frmvalidator.addValidation("preferredEmail", "req", "*Please enter your Email.");
			frmvalidator.addValidation("state", "req", "	*Please enter  State");
			frmvalidator.addValidation("state", "alpha_s", "	*State must only contain characters");
			frmvalidator.addValidation("country", "req", "	*Please enter Country");
			frmvalidator.addValidation("country", "alpha_s", "	*Country must  only contain characters");
			frmvalidator.addValidation("password", "req", "	please enter your password");
	frmvalidator.addValidation("cpassword", "req", "	retype Password cannot be empty");
	frmvalidator.addValidation("password", "minlen=6", "	password should have atleast 6 characters");
	frmvalidator.addValidation("password","eqelmnt=cpassword","The confirmed password is not same as your new password");
	
		</script>
</table>
</div>
<div style="margin-left: 200px;">
<input id="register" style="visibility: visible" name="newDonorCommit" type="submit" value="Register" /></div>
</form>
</fieldset>

<br />
<br />
</div>
</form>


<br />
<br />
</div>
</div>
<div style="width: 1000px;
	margin-right: auto;
	margin-left: auto;"><?php include("footer.php"); ?>
	</div>
</body>
</html>
 <?php
			} 
			else
			{
				require_once('prod_conn.php');
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
				$donorquery="SELECT donor_id from donors WHERE donors.user_id=".$_SESSION['SESS_USER_ID'];
				$donorresult=mysql_query($donorquery);
				$donor_id = mysql_fetch_array($donorresult);
				foreach($_POST['opp_id'] as $opp){
				$commitquery="INSERT INTO volunteer_commits(opportunity_id,donor_id) values ('$opp','$donor_id[donor_id]')";
				mysql_query($commitquery);
				}
				include_once("commit_exec.php");
	commit_mail($_SESSION['SESS_USERNAME'],$_SESSION['SESS_DISPLAYNAME']);
				header('Location: donate_time.php?commit='.$_POST['activity_id']);

			} 
		?>
		
