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

	

	echo "<a href=zwave.php>Zwave Controls</a> - <a href=voice.php>Voice Controls</a> - <a href=sonos.php>SONOS Controls</a>";
	

        mysql_connect($db_host,$db_user,$db_password);
       	@mysql_select_db($db_name) or die( "Unable to select database");
        $query = "select networkID,PiIP from networks WHERE userID='$userID'";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
	$netID=$row[0];



 	$query = "select * from devices WHERE networkID='$netID'";
        $result = mysql_query($query);
	echo "<br>Network Devices<br>";
	echo "<table border=1>";
	echo "<th>IP</th><th>Mac</th><th>Name</th><th>Custom Name</th><th>open ports</th>";
	echo "<tr>";
	while($row = mysql_fetch_array( $result )) {
  		echo "<td>" . $row['ip'] . "</td>" ;
  		echo "<td>" . $row['mac'] . "</td>" ;
  		echo "<td>" . $row['devicename'] . "</td>" ;
  		echo "<td>";
		echo '<form action="namechange.php" method="post">';
		echo '<input type="hidden" name="source" value="network">';
		echo '<input type="hidden" name="devID" value="' . $row['deviceID'] . '">';
		echo '<input type="text" onchange="this.form.submit()" name="customname" value="' . $row['CustomName'] . '">' ;
  		echo '</form></td>';	
		echo "<td>" . $row['openports'] . "</td>" ;
		if (strpos($row['openports'],',80,') !== false){
			echo '<td><a href="php-proxy/index.php?url=' . $row['ip'] . '">HTTP</a>   ';
		}
		if (strpos($row['openports'],',443,') !== false){
			echo '<td><a href="pulldata.php?target=' . $row['ip'] . '&proto=https">HTTPs</a>  ';
		}
  		echo "</td></tr>";
	}
	echo "</table>";


        $query = "select * from zwave WHERE networkID='$netID'";
        $result = mysql_query($query);
	echo "Zwave Devices<br>";
        echo "<table border=1>";
        echo "<th>ID</th><th>Custom Name</th><th>Device Type</th><th>Command Classes</th>";
        echo "<tr>";
        while($row = mysql_fetch_array( $result )) {
                echo "<td>" . $row['zid'] . "</td>" ;

  		echo "<td>";
		echo '<form action="namechange.php" method="post">';
		echo '<input type="hidden" name="source" value="zwave">';
		echo '<input type="hidden" name="zID" value="' . $row['zid'] . '">';
		echo '<input type="text" onchange="this.form.submit()" name="customname" value="' . $row['CustomName'] . '">' ;
  		echo '</form></td>';	

                echo "<td>" . $row['devtype'] . "</td>" ;
                echo "<td>" . $row['cc'] . "</td>" ;
                if (strpos($row['openports'],',80,') !== false){
                        echo '<td><a href="php-proxy/index.php?url=' . $row['ip'] . '">HTTP</a>   ';
                }
                if (strpos($row['openports'],',443,') !== false){
                        echo '<td><a href="pulldata.php?target=' . $row['ip'] . '&proto=https">HTTPs</a>  ';
                }
                echo "</td></tr>";
        }
        echo "</table>";


        mysql_close();
include(ROOT_PATH.'includes/page_footer.php');
}
?>
