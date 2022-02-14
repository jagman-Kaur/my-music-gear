<?php
$ErrorMsgs = array();

//development
// $host = 'localhost';
// $db = 'musicgear';
// $user = 'root';
// $pass = '';

//remote
$host = 'sql6.freemysqlhosting.net';
$db = 'sql6472783';
$user = 'sql6472783';
$pass = 'vPIJu4bWN8';
$conn = @new mysqli($host, $user, $pass, $db);
if ($conn -> connect_errno) //if there is no error connect_errno will be 0, oterwise >0
	$ErrorMsgs[] = "The database server is not available. Error: " . $conn -> connect_errno . "-" . $conn -> connect_error;
?>