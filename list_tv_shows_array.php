<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$result = mysqli_query($con,"SELECT * FROM shows where status=1");

$tv_shows = array();

while($row = mysqli_fetch_array($result)) {
	$id = $row['tvmaze_id'];
 	$name = $row['showname'];
	$show = array('id' => $id, 'name' => $name);
	array_push($tv_shows,$show);
}
echo json_encode($tv_shows);


mysqli_close($con);

?>