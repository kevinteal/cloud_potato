<?php
$value = $_GET["value"];
//$value = "lost";
$value = urlencode($value);

$url = "http://api.tvmaze.com/search/shows?q=".$value;
$episode = file_get_contents($url);
$json_eps = json_decode($episode,true);
//var_dump($json_eps);
$content = "";
foreach ($json_eps as $value) {
	
	//echo $value["show"]["name"] . " " . $value["show"]["id"] ."<br/>"; 
	
	$id = $value["show"]["id"];
	$temp = urlencode($value["show"]["name"]);
	$name = $value["show"]["name"];
	
	$content.="<div class='tvrage_list'><input type='radio' onclick='getshowid($id,\"$temp\",this)' name='show' value='".$id."'><label>".$name."</label></div>";
}

echo $content;

?>