<?php

// Include database connection details
require_once ('prod_conn.php');

$activityID;
$registrationIds;
$_POST['activity_id'] = 4;
if (isset($_POST['activity_id']))
{
	$activityObj = array();
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

	}
	//echo json_encode($activityObj);
	$registrationIdArray = getRegistrationIdsFromDatabase();
	//$registrationIdArray = array("APA91bFkrdDHTrhGBCAWg-fl-nl8aEjHI_T5ZjC-wU1OCe2j-nQR9bmLC7CYKJY62jKBajE67Py_RfKVZHFFC-Bd4M_i5Sptfxkr");
	$data = array(
		"title" => "fdbjsdhflksdnfhgsdfjgkldfgjhuij",
		"Description" => "UC provides comprehensive information to all the stake holders with regards to every project it supports. Volunteers can pick the project of their choice and help capture information related to project outcomes through onsite visits and subsequent documentation of the project.UC provides comprehensive information to all the stake holders with regards to every project it supports. Volunteers can pick the project of their choice and help capture information related to project outcomes through onsi",
		"type" => "Environment",
		"id" => "1"
	);
	implementGCM($registrationIdArray, $activityObj);

}

function implementGCM($registrationIdArray, $data)
{
	$URL = "https://android.googleapis.com/gcm/send";
	//$API_KEY = "AIzaSyA3HjWvDdq5hleMFK6E24t8FGopNyBWo7w";    // server API key
	$API_KEY = "AIzaSyCPLh7JjVCZSs0Sh9QZ8zbhS9qMKqALXe0";

	$finalJsonObject = array(
		"data" => $data,
		"registration_ids" => $registrationIdArray
	);
	$string = json_encode($finalJsonObject);
	echo "<br />String: " . $string;
	$header = array(
		"Authorization: key= " . $API_KEY,
		"Content-Type: application/json",
	);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $URL);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $string);
	curl_setopt($curl, CURLOPT_VERBOSE, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$resultBody = curl_exec($curl);

	$resultHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$logData = curl_errno($curl);
	$logData .= " " . curl_error($curl);
	writeToLog($logData);
	//echo $resultHttpCode;
	//echo $resultBody;
	switch ($resultHttpCode)
	{
		case "200" :
			echo "All fine. Continue response processing.";
			break;

		case "400" :
			echo "Malformd Reequest";
			//throw new Exception('Malformed request. ' . $resultBody, Exception::MALFORMED_REQUEST);
			break;

		case "401" :
			echo "Authentication Error";
			//throw new Exception('Authentication Error. ' . $resultBody, Exception::AUTHENTICATION_ERROR);
			break;

		default :
			echo "UNknown error";
			//TODO: Retry-after
			//echo "fjksadjfskdfjlsad";
			//throw new Exception("Unknown error. " . $resultBody, Exception::UNKNOWN_ERROR);
			break;
	}

	//$response = new Response($message, $resultBody);
	curl_close($curl);

}

function getRegistrationIdsFromDatabase()
{
	$gcmQuery = "SELECT * FROM gcm_registrations";
	$registrationIds = array();
	$gcmResult = mysql_query($gcmQuery);
	if ($gcmResult)
	{
		while ($row = mysql_fetch_array($gcmResult))
		{
			$registrationIds[] = $row['gcm_registration_id'];
		}
	}
	return $registrationIds;
}

function writeToLog($logData)
{
	$fp = fopen("gcmLog.txt", "a+");
	if ($fp != null)
	{
		fwrite($fp, $logData . "</n>");
	}
	fclose($fp);
}
?>