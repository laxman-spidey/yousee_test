<?php
//database host 
$dbhost = "localhost";

//Username with privileges to connect to database for querying purpose
$dbuser = "guna";

//Password of the above user account
$dbpass = "password";

//Database which will be selected before performing any insertion, updation or deletion
$dbdatabase = "ucdblive";

$link = mysql_connect ( "$dbhost", "$dbuser", "$dbpass" );

$db = mysql_select_db ( "$dbdatabase" );
?>