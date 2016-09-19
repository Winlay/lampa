<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
header("Access-Control-Allow-Origin: *");
header('X-Frame-Options:ALL');
include './db_connexion.php';
include './db_getList.php';
$category = $_GET['category'];
 majdb();
?>
