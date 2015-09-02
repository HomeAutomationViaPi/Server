<?php

define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');
session_start();


if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{

	
	$userID = $_SESSION[userid];
	$devID = $_POST[devID];
	$zID = $_POST[zID];
	$source = $_POST[source];
	$customname= filter_var($_POST[customname], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

	
	if ($source=="network"){

   	     	mysql_connect($db_host,$db_user,$db_password);
	  	@mysql_select_db($db_name) or die( "Unable to select database");
  	      	$query = "select networkID,PiIP from networks WHERE userID='$userID'";
        	$result = mysql_query($query);
        	$row = mysql_fetch_row($result);
        	$netID=$row[0];
		$ip3=$row[1];

     		mysql_connect($db_host,$db_user,$db_password);
     		@mysql_select_db($db_name) or die( "Unable to select database");
     		$query = "insert into devices (networkID, deviceID, CustomName) VALUES ('$netid', '$devID', '$customname')ON DUPLICATE KEY UPDATE CustomName='$customname'";
     		$result = mysql_query($query);
      		if ($debug){echo "<br>Result : $result";}
      		mysql_close();
	}

	
	if ($source=="zwave"){

                mysql_connect($db_host,$db_user,$db_password);
                @mysql_select_db($db_name) or die( "Unable to select database");
                $query = "select networkID,PiIP from networks WHERE userID='$userID'";
                $result = mysql_query($query);
                $row = mysql_fetch_row($result);
                $netID=$row[0];
                $ip3=$row[1];

                mysql_connect($db_host,$db_user,$db_password);
                @mysql_select_db($db_name) or die( "Unable to select database");
                $query = "insert into zwave (networkID, zid, CustomName) VALUES ('$netid', '$zID', '$customname')ON DUPLICATE KEY UPDATE CustomName='$customname'";
                $result = mysql_query($query);
                if ($debug){echo "<br>Result : $result";}
                mysql_close();


	}

   header( "Location: $login_page" ) ;



}
?>
