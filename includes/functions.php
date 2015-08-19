<?php

if (!defined('ROOT_PATH')) {
  die("Security violation");
}

function auth() {
      include(ROOT_PATH.'global.php');
	
      if(isset($_SESSION['login_count'])){
      		$count = $_SESSION['login_count']+1;
      		$_SESSION['login_count']=$count;
      }else{
      		$_SESSION['login_count']=1;
      }

      if($count >=2){ #Run the captcha code
      		
      }

      $username = $_SESSION['uname'];    
      $password = $_SESSION['pword'];
      if ($debug){echo "$db_host,$db_user,$db_password<br>username=$username<br>password=$password";}
      mysql_connect($db_host,$db_user,$db_password);
      @mysql_select_db($db_name) or die( "Unable to select database");
      $query = "select * from user where name='$username' and password='$password'";
      $result = mysql_query($query);
      if ($debug){echo "Result : $result";}
      if(mysql_num_rows($result) == 1){
        $_SESSION['authed'] = true;
	$_SESSION[‘csrfToken’] = base64_encode(openssl_random_pseudo_bytes(32));

      } else {
        //echo invalid user
        $_SESSION['authed'] = false;
      }
      mysql_query($query);
      mysql_close();
}


function PiIP() {
      include(ROOT_PATH.'global.php');

      $UserIP = $_SESSION['UserIP'];
      $PiID = $_SESSION['PiID'];

      if ($debug){echo "$db_host,$db_user,$db_password<br>UserIp=$UserIP";}
      mysql_connect($db_host,$db_user,$db_password);
      @mysql_select_db($db_name) or die( "Unable to select database");
      $query = "select IP from NewPi where IP='$UserIP' AND ID='$PiID'";
      $result = mysql_query($query);
      if ($debug){echo "<br>Result : $result";}
      if(mysql_num_rows($result) == 1){
      	if ($debug){echo "<br>FOUND ONE";}
        $_SESSION['PiFound'] = true;
      } else {
        //echo invalid user
        $_SESSION['PiFound'] = false;
      }
      mysql_close();
}





?>
