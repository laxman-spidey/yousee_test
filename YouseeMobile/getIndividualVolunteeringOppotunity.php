<?php

// Include database connection details
require_once ('prod_conn.php');

$activityID;
$_POST['activity_id'] = 4;
if (isset($_POST['activity_id']))
{
	$activityObj = array();
	$scheduleList = array();
	$activityID = $_POST['activity_id'];
	$activityQuery = "select * from volunteering_activity where activity_id = '$activityID'";
	$actResult = mysql_query($activityQuery);
	if ($actResult)
	{
		$activity = mysql_fetch_array($actResult);
		$activityObj['id'] = $activity['activity_id'];
		$activityObj['title'] = $activity['activity'];
		$activityObj['type'] = $activity['vertical'];
		$activityObj['description'] = $activity['activity_details'];
		$projectPartnerId = $activity['partner_id'];
		$parterNameQuery = "select name from project_partners where partner_id = '$projectPartnerId' ";
		$partnerResult = mysql_query($parterNameQuery);
		if ($partnerResult)
		{
			$partner = $actResult = mysql_query($activityQuery);
			$activityObj['partnerName'] = $activity['activity_details'];
		}

		// getting activity Schedule
		$appendWhere = "";
		if (isset($_POST['userId']))
		{
			$appendWhere = "AND ";
		}
		$opp_query = "SELECT * FROM volunteering_opportunities WHERE approval_status='A' AND to_date>'" . date("Y-m-d") . "' AND activity_id=" . $_POST['activity_id'];
		$oppresult = mysql_query($opp_query);

		while ($record = mysql_fetch_array($oppresult))
		{
			$scheduleObj = array();
			$scheduleObj['opportunityId'] = $record['opportunity_id'];
			$scheduleObj['fromDate'] = gmdate("M d, Y", strtotime($record['from_date']));
			$scheduleObj['toDate'] = gmdate("M d, Y", strtotime($record['to_date']));

			if ($record['from_time'] == 0)
			{
				$schedule['fromTime'] = "";
			}
			else
			{
				$schedule['fromTime'] = date("g:iA", strtotime($record['from_time']));
			}

			if ($record['to_time'] == 0)
			{
				$schedule['toTime'] = "";
			}
			else
			{
				$schedule['toTime'] = date("g:iA", strtotime($record['to_time']));
			}
			$scheduleObj['location'] = $record['location'];
			$scheduleObj['city'] = $record['city'];
			$scheduleObj['volReq'] = $record['num_volunteers'];
			$committed = "false";
			$test = "";

			if (isset($_POST['userId']))
			{

				$userId = $_POST['userId'];
				$donorquery = "SELECT donor_id from donors WHERE donors.user_id='" . $userId . "'";
				$donorresult = mysql_query($donorquery);
				$donor_id = mysql_fetch_array($donorresult);
				$opquery = "SELECT opportunity_id from volunteer_commits WHERE donor_id=" . $donor_id['donor_id'] . " AND opportunity_id=" . $record['opportunity_id'];
				$opresult = mysql_query($opquery);
				$opcount = mysql_num_rows($opresult);
				if ($opcount >= 1)
				{
					$test .= "oppcount if...";
					$oppid = mysql_fetch_array($opresult);
					if ($oppid['opportunity_id'] == $record['opportunity_id'])
					{
						$committed = "true";
					}
				}
			}
			$scheduleObj['committed'] = $committed;

			$scheduleList[] = $scheduleObj;
		}

		$activityObj['activitySchedule'] = $scheduleList;
	}
	echo json_encode($activityObj);
}
?>