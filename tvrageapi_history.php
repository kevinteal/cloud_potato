<?php
header('Content-type: text/xml');


if(isset($_GET["option"])){
$option = $_GET["option"];
}
$value = $_GET["value"];


$url = "http://services.tvrage.com/feeds/full_show_info.php?sid=".$value;
print file_get_contents($url);
//$xml = new SimpleXMLElement($result);


//echo $xml;
?>