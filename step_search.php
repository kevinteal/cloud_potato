<?php
ini_set('max_execution_time', 70);//5mins
$timestamp = date("Ymd");
$last_week = $_GET['last_week'];
$show_id_arr = array();


$_GET['s1'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s1']);
$_GET['s2'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s2']);
$_GET['s3'] == "undefined" ? "" : array_push($show_id_arr,$_GET['s3']);

/*$show_id_arr[0] = $_GET['s1'];
$show_id_arr[1] = $_GET['s2'];
$show_id_arr[2] = $_GET['s3'];*/
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

$arr_length = count($show_id_arr);
for($i=0;$i<$arr_length;$i++){

	
	$result = mysqli_query($con,"SELECT showname,tvrageapi_id,current_season FROM shows where tvrageapi_id='".$show_id_arr[$i]."' ");

		$row = mysqli_fetch_array($result);
		$showname = $row['showname'];
		$id = $row['tvrageapi_id'];
		$current_season = $row['current_season'];
		
				
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
		}//for
	
	if(count($tv_array)>=1){
				
		//update cache shows table with tv array 
		foreach($tv_array as $tvshow){
			$tvshow[3] = mysqli_real_escape_string($con, $tvshow[3]);
			//show id $tv_array[0] //show name $tvshow[1] //epno $tvshow[2] //title $tvshow[3] //airdate $tvshow[0]
		mysqli_query($con,"INSERT INTO cache_shows (show_id, showname, ep_num, title, airdate) VALUES ('".$tvshow[0]."', '".$tvshow[1]."', '".$tvshow[2]."', '".$tvshow[3]."', '".$tvshow[4]."')");
			}
		
	}//count arry

	
	
	
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
	

echo $content;




mysqli_close($con);
?>