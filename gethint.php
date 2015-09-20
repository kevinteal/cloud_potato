<?php
$con=mysqli_connect("localhost","root","","cloud_potato");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


//get the q parameter from URL
$q=$_GET["q"];	
$hint="";
$result = mysqli_query($con,"SELECT * FROM shows");
$a=array();
$x=0;
while($row = mysqli_fetch_array($result))
  {
	 $a[$x]=array($row['showname'],$row['tvmaze_id']); 
	 $x++;
  }


//lookup all hints from array if length of q>0
if (strlen($q) > 0)
  {
  $hint="";
  for($i=0; $i<count($a); $i++)
    {
		
    if (strtolower($q)==strtolower(substr($a[$i][0],0,strlen($q))))
      {
		$id = $a[$i][1];
		$newphrase = str_replace(" ", ".", $a[$i][0]);
        $hint=$hint."<li><a href='#history' onclick=get_history_tvmaze($id,\"$newphrase\") >".$a[$i][0]."</a></li>";
		
        }
      }
    }
  

// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint == "")
  {
  $response="<li>No Results</li>";
  }
else
  {
  $response=$hint;
  }

//output the response
//echo "<a href='#' onclick=findshow(this.text)>$response</a>";

if($q==""){ 
$text="";
foreach($a as $show){
	$newphrase = str_replace(" ", ".",$show[0]);
	$text=$text."<li><a onclick=get_history_tvmaze($show[1],\"$show[0]\")>".$show[0]."</a></li>";
}
 $response = $text;
}
echo $response;

mysqli_close($con);
?>