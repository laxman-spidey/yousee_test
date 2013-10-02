<?php

//Start session

require_once 'prod_conn.php';
//Unset the variables stored in session

session_destroy();
// unset session id;

$finalResult.="{";
$finalResult .= "\"success\":\"true\",";
$finalResult.="}";

echo $finalResult;

?>