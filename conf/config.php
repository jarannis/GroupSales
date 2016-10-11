<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include getcwd() . "/resource/debug.php";

// Show debug messages as they appear (TRUE) or log only to variable $debug.log (FALSE)
$showDebugMessagesAsOccur = true;

$debug = false;
$log = "";
$usesDB = true;
$dbType = "mysql";


$log = "Successfully found and loaded ./conf/config.php <br />";
if ($debug == true) echo $log;
$log = "Current Working Directory = " . getcwd() . "<br />";
if ($debug == true) echo $log;

$confLocation = getcwd() . "/conf";


if($usesDB == true){
    if($dbType == "mysql"){
        include $confLocation."/mysql.conf.php";
    }
}

$log = "Returned to config.php<br/>";
if ($debug == true){
	echo $log;
}

