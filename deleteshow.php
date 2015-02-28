<?php

$showid = $_POST['del_id'];
$con=mysqli_connect("localhost","root","","cloud_potato");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
mysqli_query($con,"DELETE FROM shows WHERE tvrageapi_id='".$showid."'");
mysqli_query($con,"DELETE FROM cache_shows WHERE show_id='".$showid."'");
mysqli_close($con);

?>