<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$day = $_GET["day"];
$day =  mysqli_real_escape_string($con, $day);

$result = mysqli_query($con,"SELECT showname,tvmaze_id FROM shows where scheduled='".$day."'");

while($row = mysqli_fetch_array($result)) {
	$id = $row['tvmaze_id'];
	$showname = $row["showname"];
	$newphrase = str_replace(" ", ".", $showname);
  echo "<li><a href='#history' onClick=get_history_tvmaze($id,'".$newphrase."') >";
  echo $row['showname'];
  echo "</a></li>";
}



mysqli_close($con);

?>