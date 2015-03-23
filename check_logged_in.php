<?php
session_start();

$con=mysqli_connect("localhost","root","","cloud_potato");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//serach for cookie and compare with db.



if(isset($_SESSION['cloud_logged_in'])){
	
	if($_SESSION['cloud_logged_in']=="true"){
		echo "true";
	}else{
		echo "false";
	}
}else{
	if(isset($_COOKIE['cloud_token'])){
	$cookie_token =  $_COOKIE['cloud_token'];
	$cookie_token = mysqli_real_escape_string($con, $cookie_token);
	$result = mysqli_query($con,"SELECT username FROM tokens where token='".$cookie_token."'");
	$rowcount=mysqli_num_rows($result);
	mysqli_free_result($result);
		if($rowcount==1){
			echo "true";
		}else{
			echo "false";
		}
	}else{
		echo "false";
	}
}

?>