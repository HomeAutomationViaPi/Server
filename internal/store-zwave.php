<?php

define('ROOT_PATH', '../');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

session_start();

if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{

	$userID = $_SESSION[userid];

	mysql_connect($db_host,$db_user,$db_password);
      	@mysql_select_db($db_name) or die( "Unable to select database");
    	$query = "select vpnIP, NetworkID from networks WHERE userID='$userID'";
     	$result = mysql_query($query);
    	$row = mysql_fetch_row($result);
     	if ($debug){echo "<br>Result : $row[0]";}
      	mysql_close();	

	$file='';
	$ctr=2;
	while ($ctr<=255){
		$url="http://$row[0]:8083/ZWaveAPI/Run/devices[$ctr]";
		$file = file_get_contents($url, false, $context);
		$file2 = explode("}" , $file);
		if ($file2[0] === "null"){
			break;
		}
		$match='/"id":/';
		echo "device id = $ctr<br>";
		foreach ($file2 as $line){	
			if (preg_match($match, $line)){
				$class=explode(":",$line);
				$classes[]=$class[1];

			}
		}
		$CClass=implode(",", $classes);


		$url="http://" .$row[0] . ':8083/ZWaveAPI/Run/devices[' . $ctr. '].instances[0].commandClasses[0x26].data.level';
		$file = file_get_contents($url, false, $context);
		echo "$url<br>";
		echo "$file<br>";
		$file2 = explode(":" , $file);
		$file3 = explode("}", $file2[4]);
		$level = $file3[0];



	        mysql_connect($db_host,$db_user,$db_password);
                @mysql_select_db($db_name) or die( "Unable to select database");
                $query = "insert into zwave (zid, cc, NetworkID, level) VALUES ('$ctr', '$CClass', '$row[1]', $level)ON DUPLICATE KEY UPDATE cc='$CClass', NetworkID='$row[1]', level=$level";
                $result = mysql_query($query);
                if ($debug){echo "<br>Result : $result";}
                mysql_close();

		$classes='';
		echo "Command classes = $CClass<br>";
		$ctr=$ctr+1;	
	}

       	$url="http://$row[0]:8083/ZWaveAPI/Data";
    	$file = file_get_contents($url, false, $context);
	$file2 = explode("}" , $file);        
	$nextline=0;
	$ctr=1;
        foreach ($file2 as $line){
        	if (preg_match("/deviceTypeString/", $line)){
                 	$nextline=true;     
                }
		if ($nextline){
			$vars=explode(":",$line);
			$vars1=explode(",",$vars[2]);
			$vars2=explode("\"",$vars1[0]);
			echo "$vars2[1]<br>";
			$nextline=false;
			
			mysql_connect($db_host,$db_user,$db_password);
               	 	@mysql_select_db($db_name) or die( "Unable to select database");
                	$query = "insert into zwave (zid, devtype) VALUES ('$ctr', '$vars2[1]')ON DUPLICATE KEY UPDATE devtype='$vars2[1]'";
                	$result = mysql_query($query);
                	if ($debug){echo "<br>Result : $result";}
                	mysql_close();
			$ctr=$ctr+1;	


		}
        }





#var_dump($file);




	#include(ROOT_PATH.'includes/page_footer.php');

}
?>
