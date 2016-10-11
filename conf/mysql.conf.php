<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$debug = false;
$serverHost = "127.0.0.1";
$mysql_user = "root";
$mysql_password = "Q^xv~3C";
$mysql_DB = "gs";

if ($debug == true){
	echo "Entered mysql.conf.php <br />";
}

$link = mysqli_connect($serverHost, $mysql_user, $mysql_password, $mysql_DB);

if ($debug == true){
    if (mysqli_connect_errno())
    {
    	echo "Failed to connect to MySql using mysqli_connect: " . mysqli_connect_error();
    }
    else {
    	echo "Successfully connected to MySql DB: " . $mysql_DB . "<br />";
    }
}