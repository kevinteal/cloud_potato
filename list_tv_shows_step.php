<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$timestamp = date("Ymd");

$result = mysqli_query($con,"SELECT time FROM time_stamp");
$row = mysqli_fetch_array($result);
$time = $row['time'];


if($time!=$timestamp){

	$result = mysqli_query($con,"SELECT tvrageapi_id FROM shows where status=1");
	//need to delete the cache shows once
	mysqli_query($con,"DELETE FROM cache_shows");
	$arr = array();
	
	while($row = mysqli_fetch_array($result)) {
		$id = $row['tvrageapi_id'];
		array_push($arr,$id);
	}
	
	echo json_encode($arr);
}else{
	//zero states timestamp upto date dont run search
	echo "0";
}
mysqli_close($con);

?>