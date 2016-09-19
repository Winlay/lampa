<?php

$hostname='mysql1.alwaysdata.com'; 
$username='winlay_winlay';
$password='Winlay4123';
$dbname='winlay_palam'; 

$link=mysql_connect($hostname,$username,$password);
if (!$link) {
   die('Impossible de se connecter : ' . mysql_error());
}
//$db=mysql_select_db($dbname);
$db_selected = mysql_select_db($dbname,$link);
if (!$db_selected) {
   die ('Impossible de selectionner la base de donnees : ' . mysql_error());
}
mysql_query("SET NAMES UTF8");


 ?>
 