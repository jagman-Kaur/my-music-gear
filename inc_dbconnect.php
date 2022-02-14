<?php
$ErrorMsgs = array();
$host = '';
$db = '';
$user = '';
$charset = '';

$conn = @new mysqli($host, $user, "", $db);
if ($conn -> connect_errno) //if there is no error connect_errno will be 0, oterwise >0
	$ErrorMsgs[] = "The database server is not available. Error: " . $conn -> connect_errno . "-" . $conn -> connect_error;
?>