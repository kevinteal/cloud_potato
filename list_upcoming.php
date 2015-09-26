<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$result = mysqli_query($con,"SELECT * FROM upcoming ORDER BY airdate ASC");
$array_tv = array();

while($row = mysqli_fetch_array($result)) {
 		$day = strtotime($row["airdate"]);
		$day_name = date('l',$day);
 	$arr = array('Showname' => $row["showname"], 'Ep' => $row["ep_num"],'Title'=>$row["title"],'Airdate'=>$day_name);
	array_push($array_tv,$arr);
  
}

echo json_encode($array_tv);

mysqli_close($con);

?>