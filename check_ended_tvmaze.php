<?php
ini_set('max_execution_time', 70);//5mins
$show_id_arr = array();
$return_data = "";


$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
		

		
$_GET['s1'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s1']);
$_GET['s2'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s2']);
$_GET['s3'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s3']);
		
		
$arr_length = count($show_id_arr);
for($i=0;$i<$arr_length;$i++){
				$result = mysqli_query($con,"SELECT showname FROM shows where tvmaze_id='".$show_id_arr[$i]."' ");
				$row = mysqli_fetch_array($result);
				$showname = $row['showname'];
				
		$sid = $show_id_arr[$i];
		
$url = "http://api.tvmaze.com/shows/".$sid;
$episode = file_get_contents($url);
$json_eps = json_decode($episode,true);
		$status =  $json_eps["status"];
		
		if($status=="Ended"){
				$sql = "UPDATE shows SET status=0 WHERE tvmaze_id=".$sid."";
				mysqli_query($con,$sql);
				$return_data .= "Ended ".$showname . ": ".$sid."<br/>";
		}else{
				$return_data .= "Airing ".$showname . ": ".$sid."<br/>";
			}
		
}
echo $return_data;
mysqli_close($con);
?>