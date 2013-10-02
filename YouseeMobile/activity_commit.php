<?php

require_once 'prod_conn.php';
session_start();
$success = "true";
$success .=session_id();
$userId = $_POST['userId'];
$donorquery="SELECT donor_id from donors WHERE donors.user_id='".$userId."'";
$success.=$donorquery;
$donorresult=mysql_query($donorquery);

//$success .= $_POST['opp_id'];
$donor_id = mysql_fetch_array($donorresult);
$opp_id = explode(",", $_POST['opp_id']);
//$success.=$opp_id[0];
foreach($opp_id as $opp)
{
	$success.= "dsfjyudjfhjksd".$donor_id['donor_id'];
	$commitquery="INSERT INTO volunteer_commits(opportunity_id,donor_id) values ('$opp','".$donor_id['donor_id']."')";
	$success.=$commitquery;
	if(!mysql_query($commitquery))
	{
		//$success = "false1,";
	}
	else 
	{
		//$success .= "false2,";
	}
}

$finalResult.="{";
$finalResult .= "\"success\":\"$success\"";
$finalResult.="}";
echo $finalResult;
?>