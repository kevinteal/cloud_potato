<?php
session_start();

$con=mysqli_connect("localhost","root","","cloud_potato");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$username = mysqli_real_escape_string($con, $_POST['username']);
$password = mysqli_real_escape_string($con, $_POST['password']);

$remember_me=false;
if(isset($_POST['remember_me'])){
	$remember_me = true;
}




$return_result = "false";



$result = mysqli_query($con,"SELECT road FROM map where id=0");
$row = mysqli_fetch_array($result);
$salt = $row['road'];
mysqli_free_result($result);

$username = sha1($username.$salt);
$password = sha1($password.$salt);



$sql = "SELECT username FROM users where Username='".$username."' AND Password='".$password."' ";
$result = mysqli_query($con,$sql);
$rowcount=mysqli_num_rows($result);
mysqli_free_result($result);


if($rowcount == 1){
	$return_result = "true";
	//does not matter who you are, no need to store id
	$_SESSION['cloud_logged_in']="true";
}else{
	$_SESSION['cloud_logged_in']="false";
	$return_result = "false";
}

if($remember_me && $return_result=="true"){
	//set cookies here
	//on document load check if cookie exists and if it matches one from table, if it matches log user in 
	//if remember me checked here than create new cookie for user and store in tokens
	$cookie_name = "cloud_token";
	$t=time();
	$cookie_val = sha1($t.$username);
	setcookie($cookie_name, $cookie_val, time() + (86400 * 30), "/"); // 86400 = 1 day
	//delete from tokens where username = "b41cda9d815e0455e223bfc48bb3a7ce6f7b8281"
	mysqli_query($con,"DELETE FROM tokens WHERE username = '".$username."'");
	mysqli_query($con,"INSERT INTO tokens (username,token) 
VALUES ('".$username."','".$cookie_val."')");
}
echo $return_result;

?>