<?php

define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

session_start();

if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{

	$device=$_GET[device];
	$dip=$_GET[ip];
	$level=$_GET[level];
	$command=$_GET[command];
	
	echo "device = $device<br>";
	echo "level = $level<br>";
	echo "command = $command<br>";
	echo "ip = $dip<br>";
	
        $userID = $_SESSION[userid];

        mysql_connect($db_host,$db_user,$db_password);
        @mysql_select_db($db_name) or die( "Unable to select database");
        $query = "select NetworkID,vpnIP from networks WHERE userID='$userID'";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        if ($debug){echo "<br>Result : $row[0]";}
        mysql_close();
	$netID=$row[0];
	$ip=$row[1];


	if (isset($level)){
    	      	mysql_connect($db_host,$db_user,$db_password);
  	      	@mysql_select_db($db_name) or die( "Unable to select database");
	      	$query = "insert into devices (networkID, deviceID, level) VALUES ( '$netID', '$device', '$level')ON DUPLICATE KEY UPDATE level=$level";
        	$result = mysql_query($query);
        	if ($debug){echo "<br>Result : $result";}
        	mysql_close();
	
		$dir='http://' . $ip  . '/sonoscontrol.php?device=' . $device . '&level=' . $level . '&ip=' . $dip;
		$str = file_get_contents($dir);
		echo "$str";
	}

	if ($command=="play"){
             $dir='http://' . $ip  . '/sonoscontrol.php?command=' . $command . '&ip=' . $dip;
             $str = file_get_contents($dir);
	}
	if ($command=="off"){
             $dir='http://' . $ip  . '/sonoscontrol.php?command=' . $command . '&ip=' . $dip;
             $str = file_get_contents($dir);
	}
	
}
?>
