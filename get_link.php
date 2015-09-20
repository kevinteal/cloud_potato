<?php

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$showid = $_GET['showid'];

$result = mysqli_query($con,"SELECT list_link FROM shows where tvmaze_id='".$showid."'");

$row = mysqli_fetch_array($result);
	$link = $row['list_link'];
  echo "<a  target='_blank' href='".$link."'>: Wikipedia</a>";
 
mysqli_close($con);


?>