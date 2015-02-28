<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$showname = mysqli_real_escape_string($con,$_POST['name']);
$url = mysqli_real_escape_string($con,$_POST['ep_url']);
$tvrage_id = $_POST['tvrage_id'];
$ep_date = $_POST['ep_date'];
$ep_status = $_POST['ep_status'];

$status = 1;
if($ep_status=="Ended"){
	$status=0;
}


$result = mysqli_query($con,"SELECT * FROM shows");

$sql="INSERT INTO shows (showname, list_link, tvrageapi_id, status, scheduled)
VALUES ('".$showname."', '".$url."', '".$tvrage_id."', $status, '".$ep_date."')";

if (!mysqli_query($con,$sql)) {
	echo "error";
  die('Error: ' . mysqli_error($con));
}else{
	echo "added";
}



mysqli_close($con);



?>