<?php

// Include database connection details
require_once ('prod_conn.php');



// variables
$area = "";
$domain = "";
$city = "";
$activity_type = "";
$where = "";
$activityQuery;
$result;
$resultCount;
$totalquery;
$totalresult;
$totalCount;
$count;

// main program
if (isset ( $_POST ["firstTime"] ))
{
	// echo "recent list";
	getRecentList ();
} else if ($_POST ["update"])
{
	// echo "filtered list";
	getFilteredList ();
}
executeQuery ( $activityQuery );

buildJSON ( $resultCount, $totalCount, $result );

echo $finalResult;


// functions
function getRecentList()
{
	global $activityQuery;
	//echo "get recent list";
	$activityQuery = "SELECT * FROM project_partners p
				JOIN volunteering_activity v ON p.partner_id=v.partner_id
				JOIN volunteering_opportunities o ON v.activity_id=o.activity_id
				WHERE o.approval_status='A' AND o.to_date>'" . date ( "Y-m-d" ) . "' GROUP BY o.activity_id  LIMIT 0,10";
}
function getFilteredList()
{
	global $area, $domain, $city, $activity_type, $where, $activityQuery;
	// echo "get filtered list";
	
	if (isset ( $_POST ['Area'] ))
	{
		$area = $_POST ['Area'];
		$areaArray = explode ( ',', $area );
		foreach ( $areaArray as $value )
		{
			$where .= " AND v.vertical='" . $value."'";
		}
	}
	if (isset ( $_POST ['Domain'] ))
	{
		$domain = $_POST ['Domain'];
		$domainArray = explode ( ',', $domain );
		foreach ( $domainArray as $value )
		{
			$where .= " AND v.domain='" . $value."'";
		}
	}
	if (isset ( $_POST ['City'] ))
	{
		$city = $_POST ['City'];
		$cityArray = explode ( ',', $city );
		foreach ( $cityArray as &$value )
		{
			$where .= " AND o.city='" . $value."'";
		}
	}
	if (isset ( $_POST ['Activity_Type'] ))
	{
		$activity_type = $_POST ['Activity_Type'];
		$activityArray = explode ( ',', $activity_type );
		foreach ( $activityArray as &$value )
		{
			$where .= " AND v.onsite_offsite='" . $value."'";
		}
	}
	//echo $where;
	$sort;
	$activityQuery = "SELECT * FROM project_partners p
				JOIN volunteering_activity v ON p.partner_id=v.partner_id 
				JOIN volunteering_opportunities o ON v.activity_id=o.activity_id 
				WHERE o.approval_status='A' AND o.to_date>'" . date ( "Y-m-d" ) . "' " . $where . " GROUP BY o.activity_id";
	// echo $activityQuery;
}
function executeQuery($activityQuery)
{
	global $result, $resultCount, $totalquery, $totalresult, $totalCount, $count, $where;
	
	$result = mysql_query ( $activityQuery );
	$resultCount = mysql_num_rows ( $result );
	$totalquery = "SELECT o.activity_id FROM project_partners p
				JOIN volunteering_activity v ON p.partner_id=v.partner_id 
				JOIN volunteering_opportunities o ON v.activity_id=o.activity_id
				WHERE o.approval_status='A' AND o.to_date>'" . date ( "Y-m-d" ) . "' " . $where . " GROUP BY o.activity_id ";
	
	$totalresult = mysql_query ( $totalquery );
	$totalCount = mysql_num_rows ( $totalresult );
	$count = 0;
}

// output
function buildJSON($resultCount, $totalCount, $result)
{
	global $finalResult;
	
	if ($resultCount > 0)
	{
		// echo $asdf;
		// JSON String entry point
		
		$finalResult .= "{";
		$finalResult .= "\"resultCount\":\"$resultCount\",";
		$finalResult .= "\"totalCount\":\"$totalCount\",";
		$finalResult .= "\"list\":[";
		while ( $row = mysql_fetch_array ( $result ) )
		{
			
			$finalResult .= "{";
			$finalResult .= "\"id\":\"" . $row ['activity_id'] . "\",";
			$finalResult .= "\"title\":\"" . $row ['activity'] . "\",";
			$finalResult .= "\"type\":\"" . $row ['vertical'] . "\",";
			$finalResult .= "\"description\":\"" . $row ['activity_details'] . "\"";
			$finalResult .= "},";
		}
		
		$finalResult = substr ( $finalResult, 0, - 1 );
		$finalResult .= "]";
		$finalResult .= "}";
		$finalResult .= "}";
		//setResultHeader($TAG_SUCCESS);
		
		// $finalResult.="}";
	}
}
?>