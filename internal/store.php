<?php

define('ROOT_PATH', '../');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

$sourceIP = $_SERVER['REMOTE_ADDR'];

$valid = preg_match('/10.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $sourceIP);
# check to make sure this is non-routable VPN internal IP
if ($valid){
		

	if ($_POST[source]=="network"){
		$ip=$_POST[ip];
		$inter=$_POST[inter];
		$cidr=$_POST[cidr];
		$brdc=$_POST[brdc];
		
		echo "$ip, $inter, $cidr, $brdc";	

	}


	if ($_POST[source]=="nmap"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			if (strpos($line,'report') !== false) {	
				$pieces = explode(" ", $line);
				$ip=$pieces[4];
				echo "$ip,";	

			}
			if (strpos($line,'open') !== false) {	
				$pieces = explode("/", $line);
				$port=$pieces[0];
				echo "$port,";	
			}
		}
	}
	if ($_POST[source]=="nbt"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			$pieces = explode(",", $line);
			$ip=$pieces[0];
			$mac=$pieces[1];
			echo "$ip,$mac";	
		}
	}
	if ($_POST[source]=="arp"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			$pieces = explode("\t", $line);
			$ip=$pieces[0];
			$mac=$pieces[1];
			echo "$ip,$mac";	
		}
	}
	if ($_POST[source]=="sonos"){
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $_POST[data]) as $line){
			$pieces = preg_split('/\s+/', $line);
			$ip=$pieces[1];
			$room=$pieces[2];
			echo "$ip, $room ";	
		}
	}
	
	
}else{
	if($debug){echo "invalid IP";}
}
?>
