{ "activityList" : [

<?php
// Start session
session_start ();

// Include database connection details
require_once ('prod_conn.php');
//$_POST ['activity_id'] = "4";
$opp_query = "SELECT * FROM volunteering_opportunities WHERE approval_status='A' AND to_date>'" . date ( "Y-m-d" ) . "' AND activity_id=" . $_POST ['activity_id'];
$oppresult = mysql_query ( $opp_query );
$schedule = "";
while ( $record = mysql_fetch_array ( $oppresult ) )
{
	$schedule .= "{";
	$schedule .= "\"opportunityId\":\"" . $record ['opportunity_id'] . "\", ";
	$schedule .= "\"fromDate\":\"" . gmdate ( "M d, Y", strtotime ( $record ['from_date'] ) ) . "\", ";
	$schedule .= "\"toDate\":\"" . gmdate ( "M d, Y", strtotime ( $record ['to_date'] ) ) . "\", ";

	if ($record ['from_time'] == 0)
	{
		$schedule .= "\"fromTime\":\"\", ";
	} else
	{
		$schedule .= "\"fromTime\":\"" . date ( "g:iA", strtotime ( $record ['from_time'] ) ) . "\", ";
	}

	if ($record ['to_time'] == 0)
	{
		$schedule .= "\"toTime\":\"\", ";
	} else
	{
		$schedule .= "\"toTime\":\"" . date ( "g:iA", strtotime ( $record ['to_time'] ) ) . "\", ";
	}
	$schedule .= "\"location\":\"" . $record ['location'] . "\", ";
	$schedule .= "\"city\":\"" . $record ['city'] . "\", ";
	$schedule .= "\"volReq\":\"" . $record ['num_volunteers'] . "\" ";
	$schedule .= "},";

}
$schedule = substr ( $schedule, 0, - 1 );
echo $schedule;



?>
] }
