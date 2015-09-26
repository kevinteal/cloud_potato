<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$result = mysqli_query($con,"SELECT count(*) as total FROM upcoming");


$row = mysqli_fetch_array($result);
echo $row["total"];

mysqli_close($con);

?>