<?php

define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/functions.php');

session_start();

if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{

	$userID = $_SESSION[userid];
	$target=$_GET[target];
	$proto=$_GET[proto];

    mysql_connect($db_host,$db_user,$db_password);
                @mysql_select_db($db_name) or die( "Unable to select database");
                $query = "select vpnIP from networks WHERE userID='$userID'";
                $result = mysql_query($query);
                $row = mysql_fetch_row($result);
                if ($debug){echo "<br>Result : $row[0]";}
                mysql_close();

                $url = "http://" .$row[0]. 

	// Create a stream
	$opts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n",
            'proxy' => 'tcp://$row[0]:80',
            )
	);

	$context = stream_context_create($opts);

var_dump($context);
	// Open the file using the HTTP headers set above
	$file = file_get_contents('$proto://$target', false, $context);

	var_dump($file);





		#include(ROOT_PATH.'includes/page_footer.php');

}
?>
