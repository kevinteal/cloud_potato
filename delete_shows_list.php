<?php

//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$result = mysqli_query($con,"SELECT * FROM shows");
$content = "";
while($row = mysqli_fetch_array($result)) {
	$id = $row['tvmaze_id'];
	$content.="<div class='tvrage_list2'><input type='radio' onclick='get_del_id($id,this)' name='deleshow' ><label>".$row['showname']."</label></div>";

}

echo $content;

mysqli_close($con);

?>

