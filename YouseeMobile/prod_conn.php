<?php
//database host 
$dbhost = "localhost";

//Username with privileges to connect to database for querying purpose
$dbuser = "guna";

//Password of the above user account
$dbpass = "guna";

//Database which will be selected before performing any insertion, updation or deletion
$dbdatabase = "ucdblive";

$TAG_REQUEST_CODE = "requestCode";
$TAG_RESULT_CODE = "resultCode";
$TAG_SUCCESS = "1";
$TAG_FAILED = "0";

$link = mysql_connect ( "$dbhost", "$dbuser", "$dbpass" );

$db = mysql_select_db ( "$dbdatabase" );


$requestCode = $_POST[$TAG_REQUEST_CODE];

header("$TAG_REQUEST_CODE : ".$requestCode."");


session_start();



?>