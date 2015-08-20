<?php

define('ROOT_PATH', '../');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

$sourceIP = $_SERVER['REMOTE_ADDR'];
$id=$_POST[ID];

$valid = preg_match('/10.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $sourceIP);
# check to make sure this is non-routable VPN internal IP
if ($valid){
		

	if ($_POST[source]=="network"){
		$ip=$_POST[ip];
		$inter=$_POST[inter];
		$cidr=$_POST[cidr];
		$brdc=$_POST[brdc];
		
		if ($debug){echo "$ip, $inter, $cidr, $brdc";}
		if ($debug){echo "$db_host,$db_user,$db_password";}
                mysql_connect($db_host,$db_user,$db_password);
                @mysql_select_db($db_name) or die( "Unable to select database");
                $query = "insert into networks (PiID, PiIP, interface, cidr, brdc, vpnIP) VALUES ('$id', '$ip', '$inter', '$cidr', '$brdc', '$sourceIP')ON DUPLICATE KEY UPDATE PiIP='$ip', interface='$inter', cidr='$cidr', brdc='$brdc', vpnIP='$sourceIP'";
                $result = mysql_query($query);
                if ($debug){echo "<br>Result : $result";}
                mysql_close();	

	}


	if ($_POST[source]=="nmap"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			if (strpos($line,'report') !== false) {	
				if (isset($ip2)){
					$ports=implode(",", $ports);
					#if ($debug){echo "($ip2) ,$ports";}
					#if ($debug){echo "$db_host,$db_user,$db_password";}
                			mysql_connect($db_host,$db_user,$db_password);
           				@mysql_select_db($db_name) or die( "Unable to select database");
                			$query = "insert into devices (PiID, ip, openports) VALUES ('$id', '$ip2', '$ports')ON DUPLICATE KEY UPDATE openports='$ports'";
                			$result = mysql_query($query);
                			if ($debug){echo "<br>Result : $result";}
                			mysql_close();
	
				}
				$pieces = explode(" ", $line);
				$ip2=$pieces[4];
				$ports='';

			}
			if (strpos($line,'open') !== false) {	
				$pieces = explode("/", $line);
				$ports[]=$pieces[0];
			}
	
		}
	}
	if ($_POST[source]=="nbt"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			$pieces = explode(",", $line);
			$ip3=$pieces[0];
			$name=$pieces[1];
			if ($debug){echo "$ip,$name";}
			mysql_connect($db_host,$db_user,$db_password);
                  	@mysql_select_db($db_name) or die( "Unable to select database");
    	                $query = "insert into devices (PiID, ip, devicename) VALUES ('$id', '$ip3', '$name')ON DUPLICATE KEY UPDATE devicename='$name'";
                        $result = mysql_query($query);
                        if ($debug){echo "<br>Result : $result";}
                        mysql_close();
	
		}
	}
	if ($_POST[source]=="arp"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			$pieces = explode("\t", $line);
			$ip4=$pieces[0];
			$mac=$pieces[1];
			if ($debug){echo "$ip,$mac";}
                        mysql_connect($db_host,$db_user,$db_password);
                        @mysql_select_db($db_name) or die( "Unable to select database");
                        $query = "insert into devices (PiID, ip, mac) VALUES ('$id', '$ip4', '$mac')ON DUPLICATE KEY UPDATE mac='$mac'";
                        $result = mysql_query($query);
                        if ($debug){echo "<br>Result : $result";}
                        mysql_close();
	
		}
	}
	if ($_POST[source]=="sonos"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			$pieces = preg_split('/\s+/', $line);
			$ip5=$pieces[1];
			$room="SONOS:$pieces[2]";
			if ($debug){echo "$ip, $room ";}
                        mysql_connect($db_host,$db_user,$db_password);
                        @mysql_select_db($db_name) or die( "Unable to select database");
                        $query = "insert into devices (PiID, ip, devicename) VALUES ('$id', '$ip5', '$room')ON DUPLICATE KEY UPDATE devicename='$room'";
                        $result = mysql_query($query);
                        if ($debug){echo "<br>Result : $result";}
                        mysql_close();

	
		}
	}

       	mysql_connect($db_host,$db_user,$db_password);
     	@mysql_select_db($db_name) or die( "Unable to select database");
     	
	$query = "select networkID from networks WHERE PiID='$id'";
   	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
   	if ($debug){echo "<br>Result : $row[0]";}

        $query = "update devices SET networkID='$row[0]' WHERE PiID='$id'";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        if ($debug){echo "<br>Result : $row[0]";}
        





    	mysql_close();

	
	
}else{
	if($debug){echo "invalid IP";}
}
?>
