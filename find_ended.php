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
	
		$sid = $show_id_arr[$i];
		$url ="http://services.tvrage.com/feeds/showinfo.php?sid=".$sid;
		$xml = @simplexml_load_file($url);
				
		if(!$xml){
			//no xml loaded
			//echo "no";
		}else{
			
			$result = mysqli_query($con,"SELECT showname FROM shows where tvrageapi_id='".$show_id_arr[$i]."' ");

				$row = mysqli_fetch_array($result);
				$showname = $row['showname'];
			
			$end = $xml->ended;
			if($end!="")
			{
				//show has ended, or end date.
				//update row in db to status = 0 
				$sql = "UPDATE shows SET status=0 WHERE tvrageapi_id=".$sid."";
				mysqli_query($con,$sql);
				$return_data .= "Ended ".$showname . ": ".$sid."<br/>";
			}else{
				$return_data .= "Airing ".$showname . ": ".$sid."<br/>";
			}
			
		}
}
echo $return_data;
mysqli_close($con);
?>