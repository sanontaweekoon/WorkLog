<?php
$con= mysqli_connect("localhost","root","","worklog_db") or die("Error: " . mysqli_error());
mysqli_query($con, "SET NAMES 'utf8' ");
error_reporting( error_reporting() & ~E_NOTICE );
date_default_timezone_set('Asia/Bangkok');
 
?>