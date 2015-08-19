<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: logout.php                                           *
 *        Copyright: (C) 2002-2009 Jan Sorgalla                           *
 *            Email: jan@4homepages.de                                    *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.7.7                                                *
 *                                                                        *
 *    Never released without support from: Nicky (http://www.nicky.net)   *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) f�r weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/

$main_template = 0;

$nozip = 1;
define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/sessions.php');

if (($user_info['user_level'] != GUEST && $user_info['user_level'] != USER_AWAITING)) {
    $site_sess->logout($user_info['user_id']);
}

if (!ereg("index.php", $url) && !ereg("lightbox.php", $url) && !ereg("login.php", $url) && !ereg("register.php", $url) && !ereg("member.php", $url)) {
  redirect($url);
}
else {
  redirect("index.php");
}
?>
