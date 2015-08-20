<?php

#define('GET_CACHES', 1);
define('ROOT_PATH', './');
#define('GET_USER_ONLINE', 1);
include(ROOT_PATH.'global.php');
#require(ROOT_PATH.'includes/sessions.php');
require(ROOT_PATH.'includes/functions.php');
#$user_access = get_permission();
session_start();


if ($_SESSION['authed'] != true){
   header( "Location: $login_page" ) ;
}else{

include(ROOT_PATH.'includes/page_header.php');

echo "YOU ARE IN@@@@@@@@@@@@@@@@@@";

include(ROOT_PATH.'includes/page_footer.php');
}
?>
