<?php

define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

session_start();

if (isset($_POST[PiID])){

	$_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['PiID'] = $_POST[PiID];

	PiIP();

	if ($_SESSION['PiFound']){
 		include(ROOT_PATH.'includes/page_header.php');
 		include(ROOT_PATH.'content/setup_loginform.html');
 		include(ROOT_PATH.'includes/page_footer.php');
	
	}else{
 		include(ROOT_PATH.'includes/page_header.php');
 		include(ROOT_PATH.'content/PleaseResetPi.html');
 		include(ROOT_PATH.'includes/page_footer.php');
	}
}else{

 	include(ROOT_PATH.'includes/page_header.php');
 	include(ROOT_PATH.'content/getPiID.html');	
	include(ROOT_PATH.'includes/page_footer.php');
	

}
  	

?>
