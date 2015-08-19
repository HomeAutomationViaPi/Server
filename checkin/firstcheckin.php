<?php

      define('ROOT_PATH', '../');
      include(ROOT_PATH.'global.php');

      $PIIP = $_SERVER['REMOTE_ADDR'];
      $ID = "test12312312";

      if ($debug){echo "$db_host,$db_user,$db_password<br>PiIp=$PIIP";}
      mysql_connect($db_host,$db_user,$db_password);
      @mysql_select_db($db_name) or die( "Unable to select database");
      $query = "insert into NewPi (IP, ID) VALUES ('$PIIP', '$ID')";
      $result = mysql_query($query);
      if ($debug){echo "<br>Result : $result";}
      mysql_close();





?>
