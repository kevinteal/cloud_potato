<?php
//list_tv_shows

$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

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