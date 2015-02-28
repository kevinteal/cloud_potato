<?php
ini_set('max_execution_time', 300);//5mins
$timestamp = date("Ymd");
$last_week = $_GET['last_week'];
$day_name = $_GET['day'];
//$last_week=20140728;

$tv_array=array();

//compare timestamp with db
//if timestamp is not equal then search tvrageapoi
//if timestamp is equal then use cache table
//if use tvrage succusful then create new timestamp and enter data into cache table


$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/*
$result = mysqli_query($con,"SELECT time FROM time_stamp");

while($row = mysqli_fetch_array($result)) {
	$time = $row['time'];
}
$result = mysqli_query($con,"SELECT show_id FROM cache_shows");
$x=0;
while($row = mysqli_fetch_array($result)) {
	$x = $row['show_id'];
}
*/


if(1==1){
	//echo "old db";
	//get new records from tvrage api
	//get tvshows from list.
	//mysqli_query($con,"UPDATE time_stamp SET time='".$timestamp."'");
	
	$result = mysqli_query($con,"SELECT showname,status,tvrageapi_id,status,current_season FROM shows where scheduled ='".$day_name."' ");

	while($row = mysqli_fetch_array($result)) {
		$showname = $row['showname'];
		$status = $row['status'];
		$tvrageapi_id = $row['tvrageapi_id'];
		$status = $row['status']; //0=ended, 1=airing
		$current_season = $row['current_season'];
	
		$id=$tvrageapi_id;
		
		//check if status is 0 if 0 then show has ended do not search it
				
		if($status==1){		
				
		$url ="http://services.tvrage.com/feeds/episode_list.php?sid=".$id;
		
		$xml = @simplexml_load_file($url);
		
		if(!$xml){
			//no xml loaded
			//echo "no";
		}else{
			//echo "yes";
		//$xml = new SimpleXMLElement($result);
		//we know showname from sql
		//another check for ended $xml->ended
		//if has lenght update db and move on to next show
		
		foreach($xml->Episodelist->Season as $season){
			
			//echo $showname;
			$seasonno = $season['no'];
			if($seasonno<10){
				$seasonno="0".$seasonno;
			}
			
			if($season['no']>=$current_season){
				//echo "********".$showname ." ". $seasonno."---------<br/>";
			foreach($season->episode as $episode){
				
				$airdate = $episode->airdate;
				$airdate = str_replace('-', '', $airdate);		
				if($airdate>$last_week && $airdate<$timestamp){
					$epno = "s".$seasonno."e".$episode->seasonnum;
					$ep_array = array($id,$showname,$epno,$episode->title,$airdate);
					array_push($tv_array,$ep_array);
					//echo "<br/>";
					//echo $airdate." ".$showname." s".$seasonno."e".$episode->seasonnum." ".$episode->title;
				}
			}//foreach	
			
			}else{
				//echo "not searching ". $showname . " " . $seasonno."<br/>";
				}
		}//foreach $xml->Episodelist->Season as $season
		}//xml
		}//status
	}//while
	
	
	if(count($tv_array)>=1){
		//only if the xml found will array contain items if so update timestamp to skip search and update the db
		mysqli_query($con,"UPDATE time_stamp SET time='".$timestamp."'");
		//remove current rows
		mysqli_query($con,"DELETE FROM cache_shows");
		
		//update cache shows table with tv array 
		foreach($tv_array as $tvshow){
			$tvshow[3] = mysqli_real_escape_string($con, $tvshow[3]);
			//show id $tv_array[0] //show name $tvshow[1] //epno $tvshow[2] //title $tvshow[3] //airdate $tvshow[0]
		mysqli_query($con,"INSERT INTO cache_shows (show_id, showname, ep_num, title, airdate) VALUES ('".$tvshow[0]."', '".$tvshow[1]."', '".$tvshow[2]."', '".$tvshow[3]."', '".$tvshow[4]."')");
			}
		
	}//count arry

	
	
	}//timestamp
	//upto date get cache table info
	
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
                    <div class="ep_section img_hold center_me"><a href="#history"  onclick="get_history('.$show_id.')" ><img src="imgs/folder-icon.png" height="42" width="50" alt="history" title="Show History of Tv Show" /></a></div>                   
                </div>';
			
		
	}
	if($rowcount==0){
		$content = "No New Eps";
		mysqli_query($con,"INSERT INTO cache_shows (show_id, showname, ep_num, title, airdate) VALUES ('0', 'No New Eps', '0', '0', '0')");
	}


if ($content == ""){$content="Empty";}
echo $content;




mysqli_close($con);
?>