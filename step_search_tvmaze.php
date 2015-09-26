<?php 

ini_set('max_execution_time', 70);//5mins
$timestamp = date("Y-m-d");
$show_id_arr = array();


$_GET['s1'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s1']);
$_GET['s2'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s2']);
$_GET['s3'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s3']);

$tv_array=array();
$tv_array_upcoming=array();



$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$arr_length = count($show_id_arr);
for($i=0;$i<$arr_length;$i++){
	
	$result = mysqli_query($con,"SELECT showname,tvmaze_id,current_season FROM shows where tvrageapi_id='".$show_id_arr[$i]."' ");

		$row = mysqli_fetch_array($result);
		$showname = $row['showname'];
		$id = $row['tvmaze_id'];
		$current_season = $row['current_season'];
		
		
$url = "http://api.tvmaze.com/shows/".$id."/episodes";
$episode = file_get_contents($url);

$json_eps = json_decode($episode,true);
$date = date("Y-m-d",strtotime('-7 days'));
$today = date("Y-m-d"); 
$oneweek = date("Y-m-d",strtotime('+7 days'));
//echo "date ". $date;
//var_dump($json_eps);
foreach ($json_eps as $value) {
	$airdate = $value['airdate'];
	if($airdate>=$date && $airdate<$today){
    	//echo "Ep Title: $value[name] - $value[season] $value[number] Airdate: $value[airdate] <br />\n";
		if($value['season']<10){
			$season_no = "0"."".$value['season'];
		}else{
			$season_no = $value['season'];
		}
		if($value['number']<10){
			$ep_no = "0"."".$value['number'];
		}else{
			$ep_no = $value['number'];
		}
		$epno = "s".$season_no."e". $ep_no;
		$airdate = str_replace("-", "", $airdate);
		$ep_array = array($id,$showname,$epno,$value['name'],$airdate);
		array_push($tv_array,$ep_array);
	}	
	
	//add to upcoming db 
	if($airdate>=$today && $airdate<$oneweek){
    	//echo "Ep Title: $value[name] - $value[season] $value[number] Airdate: $value[airdate] <br />\n";
		if($value['season']<10){
			$season_no = "0"."".$value['season'];
		}else{
			$season_no = $value['season'];
		}
		if($value['number']<10){
			$ep_no = "0"."".$value['number'];
		}else{
			$ep_no = $value['number'];
		}
		$epno = "s".$season_no."e". $ep_no;
		$airdate = str_replace("-", "", $airdate);
		$ep_array = array($id,$showname,$epno,$value['name'],$airdate);
		array_push($tv_array_upcoming,$ep_array);
	}	
	
	
	
}
}


if(count($tv_array)>=1){
				
		//update cache shows table with tv array 
		foreach($tv_array as $tvshow){
			$tvshow[3] = mysqli_real_escape_string($con, $tvshow[3]);
			//show id $tv_array[0] //show name $tvshow[1] //epno $tvshow[2] //title $tvshow[3] //airdate $tvshow[0]
		mysqli_query($con,"INSERT INTO cache_shows (show_id, showname, ep_num, title, airdate) VALUES ('".$tvshow[0]."', '".$tvshow[1]."', '".$tvshow[2]."', '".$tvshow[3]."', '".$tvshow[4]."')");
			}
		
	}//count arry
	
	//upcoming array
	if(count($tv_array_upcoming)>=1){
				
		//update cache shows table with tv array 
		foreach($tv_array_upcoming as $tvshow){
			$tvshow[3] = mysqli_real_escape_string($con, $tvshow[3]);
			//show id $tv_array[0] //show name $tvshow[1] //epno $tvshow[2] //title $tvshow[3] //airdate $tvshow[0]
		mysqli_query($con,"INSERT INTO upcoming (show_id, showname, ep_num, title, airdate) VALUES ('".$tvshow[0]."', '".$tvshow[1]."', '".$tvshow[2]."', '".$tvshow[3]."', '".$tvshow[4]."')");
			}
		
	}//count arry



$result = mysqli_query($con,"SELECT * FROM cache_shows order by airdate DESC");
	$content="";
 	$rowcount=mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)) {
		$show_id = $row['show_id'];
		$showname = $row['showname'];
		$epno = $row['ep_num'];
		$title = $row['title'];
		$airdate = $row['airdate'];
		
		$newphrase = str_replace(" ", ".", $showname);
		$search_title = str_replace(" ", ".", $title);
		$display_title=$title;
		if(strlen($title)>18){
			$display_title=substr($title,0,16);
			$display_title=$display_title."...";
		}
		
		$day = strtotime($airdate);
		$day_name = date('l',$day);
		//echo $showname;
		
	$content .= '<div class="ep">
                	<div class="ep_showname">'.$showname.'</div>
                    <div class="ep_section alt_color">'.$epno.'</div>
                    <div class="ep_section">'.$display_title.'</div>
                    <div class="ep_section alt_color center_me">'.$day_name.'</div>
                    <div class="ep_section img_hold center_me"><a target="_blank" href="https://www.google.co.uk/search?q=' . $newphrase .'+'. $epno .'+720p+torrent+'.$search_title.'&ie=UTF-8&safe=off&tbs=qdr:w"  ><img src="imgs/download-icon.png" height="42" width="42" alt="dwnld" title="dwnlad" /></a></div>
                    <div class="ep_section img_hold center_me"><a href="#history"  onclick=get_history_tvmaze('.$show_id.',"'.$newphrase.'") ><img src="imgs/folder-icon.png" height="42" width="50" alt="history" title="Show History of Tv Show" /></a></div>                   
                </div>';
			
		
	}
	

echo $content;




mysqli_close($con);

 
?>