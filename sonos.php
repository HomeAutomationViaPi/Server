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
        $query = "select networkID,PiIP from networks WHERE userID='$userID'";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
	$netID=$row[0];
	$PIip=$row[1];
	echo "$ip";


 	$query = "select * from devices WHERE networkID='$netID'";
        $result = mysql_query($query);
	echo "<br>Sonos Devices<br>";
	echo "<table border=1>";
	echo "<th>IP</th><th>Mac</th><th>Name</th><th>Custom Name</th>";
	echo "<tr>";
	while($row = mysql_fetch_array( $result )) {
		if (strpos($row['devicename'],'SONOS') !== false){
                        echo '<form action="sonoscontrol.php" method="GET">';
	  		echo "<td>" . $row['ip'] . "</td>" ;
  			echo "<td>" . $row['mac'] . "</td>" ;
  			echo "<td>" . $row['devicename'] . "</td>" ;
  			echo "<td>" . $row['CustomName'] . "</td>" ;
			echo '<td><a href="sonoscontrol.php?command=play&ip=' . $row['ip'] . '">play</a></td>   ';
			echo '<td><a href="sonoscontrol.php?command=off&ip=' . $row['ip'] . '">stop</a></td>   ';
			echo '<input type="hidden" name="ip" value="' . $row['ip'] . '">';
			echo '<input type="hidden" name="device" value="' . $row['deviceID'] . '">';
			echo '<td><label for="fader">Volume</label><input name=level onchange="this.form.submit()" type="range" min="0" max="100" value="' . $row['level'] . '" id="level"></td>';
	#		echo '<td><a href="http://' . $PIip . '/sonos.php?command=play">Volume UP</a></td>   ';
	#		echo '<td><a href="http://' . $PIip . '/sonos.php?command=play">Volume DOWN</a></td>   ';
  			echo '</form>';


			echo "</td></tr>";
		}
	}
	echo "</table>";



        mysql_close();
include(ROOT_PATH.'includes/page_footer.php');
}
?>
