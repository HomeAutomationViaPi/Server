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
	$ip=$row[1];
	echo "Zwave Devices<br>";
	echo "<a href=http://" . $ip . "/zwaveadd.php>Add Device</a>--<a href=http://" . $row[1] . "/zwaveremove.php>Remove Failed Device</a> ";

        $query = "select * from zwave WHERE networkID='$netID'";
        $result = mysql_query($query);
        echo "<table border=1>";
        echo "<th>ID</th><th>Custom Name</th><th>Device Type</th><th>Command Classes</th>";
        echo "<tr>";
	
	while($row = mysql_fetch_array( $result )) {
          
	       	echo "<td>" . $row['zid'] . "</td>" ;
                echo "<td>" . $row['CustomName'] . "</td>" ;
                echo "<td>" . $row['devtype'] . "</td>" ;
               	echo "<td>" . $row['cc'] . "</td>" ;
                if (strpos($row['devtype'],'Multilevel Scene Switch') !== false){
			echo '<form action="zwavecontrol.php" method="POST">';
			echo '<input type="hidden" name="device" value="' . $row['zid'] . '">';
			echo '<input type="hidden" name="Clevel" value="' . $row['level'] . '">';
    			echo '<td><label for="fader">Level</label><input name=level onchange="this.form.submit()" type="range" min="0" max="100" value="' . $row['level'] . '" id="level"></td>';
                        echo '<td><a href="http://'.$ip.':8083/ZWaveAPI/Run/devices['.$row['zid'].'].instances[0].commandClasses[0x26].Set(100,0)">on</a></td>   ';
                        echo '<td><a href="http://'.$ip.':8083/ZWaveAPI/Run/devices['.$row['zid'].'].instances[0].commandClasses[0x26].Set(0,0)">off</a></td>   ';
			echo '<noscript><input type="submit" value="Submit"></noscript>';
			echo '</form>';
               	}
                echo "</td></tr>";
        }
        echo "</table>";

        mysql_close();
include(ROOT_PATH.'includes/page_footer.php');
}
?>
