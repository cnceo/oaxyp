<?php
/*  
  Copyright (c) 2010-02 Game
  Game All Rights Reserved. 
  Author QQ: 1234567
  Author: Version:1.0
  Date:2011-12-18
*/
if (!defined("Copyright"))
define('Copyright', 'Author QQ: 1234567');
if (!defined("ROOT_PATH"))
define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"].'/');
include_once ROOT_PATH.'functioned/cheCookie.php';
$dateTime = date('Y-m-d H:i:s');
$a = date('Y-m-d ').'23:55:01';
global $stratGamejxssc, $endGamejxssc;
$_SESSION['cpopen'] = 3;
if ( strtotime($dateTime) < strtotime($stratGamejxssc) || strtotime($dateTime) > strtotime($endGamejxssc))
{
 
//exit("$dateTime < $stratGamejxssc || $dateTime > $endGamejxssc");
markPos("前台-极速时时彩封盘页");
	header("Location: ./right.php"); exit;
}
?>