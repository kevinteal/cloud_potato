<?php
session_start();


$username = $_POST['username'];
$password = $_POST['password'];

$result = "false";

if($username == "kevin" && $password == "admin"){
	$result = "true";
	$_SESSION['cloud_logged_in']="true";
}else{
	$_SESSION['cloud_logged_in']="false";
	$result = "false";
}

echo $result;

?>