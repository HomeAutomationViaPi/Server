<?php

#define('GET_CACHES', 1);
define('ROOT_PATH', './');
#define('GET_USER_ONLINE', 1);
include(ROOT_PATH.'global.php');
#require(ROOT_PATH.'includes/sessions.php');
require(ROOT_PATH.'includes/functions.php');
#$user_access = get_permission();
session_start();


if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{


$userID = $_SESSION[userid];
include(ROOT_PATH.'includes/page_header.php');

        mysql_connect($db_host,$db_user,$db_password);
       	@mysql_select_db($db_name) or die( "Unable to select database");
        $query = "select networkID from networks WHERE userID='$userID'";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        if ($debug){echo "<br>Result : $row[0]";}


 	$query = "select * from devices WHERE networkID='$row[0]'";
        $result = mysql_query($query);
	echo "<table border=1><tr>";
	while($row = mysql_fetch_array( $result )) {
  		echo "<td>" . $row['ip'] . "</td>" ;
  		echo "<td>" . $row['mac'] . "</td>" ;
  		echo "<td>" . $row['devicename'] . "</td>" ;
  		echo "<td>" . $row['openports'] . "</td>" ;
  		echo "</tr>";
	}
	echo "</table>";
        mysql_close();



include(ROOT_PATH.'includes/page_footer.php');
}
?>
