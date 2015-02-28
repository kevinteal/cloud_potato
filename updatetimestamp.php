<?php
$con=mysqli_connect("localhost","root","","cloud_potato");

$timestamp = date("Ymd");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


//mysqli_query($con,"UPDATE time_stamp SET time=20141008");
mysqli_query($con,"UPDATE time_stamp SET time='".$timestamp."'");

mysqli_close($con);

?>