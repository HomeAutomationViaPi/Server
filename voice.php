<?php

define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');
session_start();


if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{


$userID = $_SESSION[userid];
include(ROOT_PATH.'includes/page_header.php');

        mysql_connect($db_host,$db_user,$db_password);
       	@mysql_select_db($db_name) or die( "Unable to select database");
        $query = "select vpnIP from networks WHERE userID='$userID'";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        mysql_close();
	$url="http://$row[0]/voiceconf.php";
        $file = file_get_contents($url);
        echo "$file";	


include(ROOT_PATH.'includes/page_footer.php');
}
?>
