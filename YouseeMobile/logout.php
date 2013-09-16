<?php

//Start session
session_start();
require_once 'prod_conn.php';
//Unset the variables stored in session
unset($_SESSION['SESS_USER_ID']);
unset($_SESSION['SESS_DONOR_ID']);
// unset session id;

$finalResult.="{";
$finalResult .= "\"success\":\"true\",";
$finalResult.="}";

echo $finalResult;
setResultHeader($TAG_SUCCESS);
?>