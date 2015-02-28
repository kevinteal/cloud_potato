<?php
session_start();


if(isset($_SESSION['cloud_logged_in'])){
	
	if($_SESSION['cloud_logged_in']=="true"){
		echo "true";
	}else{
		echo "false";
	}
}else{
	echo "false";
}

?>