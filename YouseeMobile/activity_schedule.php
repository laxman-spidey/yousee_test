{ "activityList" : [

<?php
// Start session

// Include database connection details
require_once ('prod_conn.php');
//$_POST ['activity_id'] = "4";
$appendWhere = "";
if (isset($_POST['userId']))
{
	$appendWhere = "AND ";
}

$opp_query = "SELECT * FROM volunteering_opportunities WHERE approval_status='A' AND to_date>'" . date("Y-m-d") . "' AND activity_id=" . $_POST['activity_id'];
$oppresult = mysql_query($opp_query);
$schedule = "";
while ($record = mysql_fetch_array($oppresult))
{
	$schedule .= "{";
	$schedule .= "\"opportunityId\":\"" . $record['opportunity_id'] . "\", ";
	$schedule .= "\"fromDate\":\"" . gmdate("M d, Y", strtotime($record['from_date'])) . "\", ";
	$schedule .= "\"toDate\":\"" . gmdate("M d, Y", strtotime($record['to_date'])) . "\", ";

	if ($record['from_time'] == 0)
	{
		$schedule .= "\"fromTime\":\"\", ";
	}
	else
	{
		$schedule .= "\"fromTime\":\"" . date("g:iA", strtotime($record['from_time'])) . "\", ";
	}

	if ($record['to_time'] == 0)
	{
		$schedule .= "\"toTime\":\"\", ";
	}
	else
	{
		$schedule .= "\"toTime\":\"" . date("g:iA", strtotime($record['to_time'])) . "\", ";
	}
	$schedule .= "\"location\":\"" . $record['location'] . "\", ";
	$schedule .= "\"city\":\"" . $record['city'] . "\", ";
	$schedule .= "\"volReq\":\"" . $record['num_volunteers'] . "\", ";
	$committed = "false";
	$test = "";

	if (isset($_POST['userId']))
	{

		$userId = $_POST['userId'];
		$donorquery = "SELECT donor_id from donors WHERE donors.user_id='" . $userId . "'";
		$donorresult = mysql_query($donorquery);
		$donor_id = mysql_fetch_array($donorresult);

		$test .= "userid undhi....";
		$opquery = "SELECT opportunity_id from volunteer_commits WHERE donor_id=" . $donor_id['donor_id'] . " AND opportunity_id=" . $record['opportunity_id'];
		$test .= $opquery . "..........";
		$opresult = mysql_query($opquery);
		$opcount = mysql_num_rows($opresult);
		$test .= "-----oppotunityCount : " . $opcount . "..........";
		if ($opcount >= 1)
		{
			$test .= "oppcount if...";
			$oppid = mysql_fetch_array($opresult);
			if ($oppid['opportunity_id'] == $record['opportunity_id'])
			{
				$test .= "fjskjhfjksdghjhfg........";
				$committed = "true";
			}
		}
	}
	//$schedule .= "\"test\":\"$test\",";
	$schedule .= "\"committed\":\"$committed\"";

	$schedule .= "},";

}
$schedule = substr($schedule, 0, -1);
echo $schedule;
?>
] }
