<?php

define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

session_start();

$_SESSION['uname'] = $_POST['user_name'];
$_SESSION['pword'] = sha1($salt.$_POST['user_password']);


$count = $_SESSION['login_count'];
if ($count<2){
	
	if ((isset($_POST['user_name']))&&(isset($_POST['user_password']))){
   		auth();
	}

	if ($_SESSION['authed'] == true){
		if ($debug){$old_sessionid = session_id();}
		session_regenerate_id();

		if($debug){
			$new_sessionid = session_id();
			echo "Old Session: $old_sessionid<br />";
			echo "New Session: $new_sessionid<br />";
		}

  		#include(ROOT_PATH.'includes/page_header.php');
  		#echo "you made it";
 		#include(ROOT_PATH.'includes/page_footer.php');
		header( "Location: $index" ) ;
	}else{
 		include(ROOT_PATH.'includes/page_header.php');
 	 	include(ROOT_PATH.'content/user_loginform.html');
 	 	include(ROOT_PATH.'includes/page_footer.php');
	}
}else{	
	if (!isset($_POST['code'])){
		include(ROOT_PATH.'includes/page_header.php');
		include(ROOT_PATH.'content/captcha_form.html');
		include(ROOT_PATH.'includes/page_footer.php');
	}else{
	  	include("captcha/securimage.php");
  		$img = new Securimage();
  		$valid = $img->check($_POST['code']);
  
     		auth();

  		if(($valid == true)&&($_SESSION['authed'] == true)) {
  			if ($debug){$old_sessionid = session_id();}
			session_regenerate_id();

			if($debug){
				$new_sessionid = session_id();
				echo "Old Session: $old_sessionid<br />";
				echo "New Session: $new_sessionid<br />";
			}
    		#	echo "<center>Thanks, you entered the correct code.<br />Click <a href=\"{$_SERVER['PHP_SELF']}\">here</a> to go back.</center>";
			header( "Location: $index" ) ;
  		} else {
    			include(ROOT_PATH.'includes/page_header.php');
			include(ROOT_PATH.'content/captcha_form.html');
			include(ROOT_PATH.'includes/page_footer.php');
  		}
	}
	
}
  	

?>
