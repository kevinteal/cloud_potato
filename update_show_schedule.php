<?php
//update day it airs.


$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT showname,tvmaze_id FROM shows");

while($row = mysqli_fetch_array($result)) {
	$id = $row['tvmaze_id'];
		$showname = $row['showname'];
$url = "http://api.tvmaze.com/shows/".$id;
$episode = file_get_contents($url);
$json_eps = json_decode($episode,true);

$value = $json_eps;


echo "id: ".$value['id'];

$days = $value["schedule"]["days"];
$airday = "none";
if(empty($days)){
	$airday = "Sunday";
}else{
	$airday = $value["schedule"]["days"][0];
}

$sql = "UPDATE shows SET scheduled=\"".$airday."\" WHERE tvmaze_id=".$id."";
//mysqli_query($con,$sql);
if (!mysqli_query($con,$sql))
  {
  echo("Error description: " . mysqli_error($con));
  }



echo $showname . " airs on " .$airday . "<br/>"; 







	
}

?>
