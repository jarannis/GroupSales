<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// GLOBAL DEBUG SETTING.
$debug = true;

if($usesDB == true){
    if($dbType == "mysql"){
        require_once "./mysql.conf.php";
    }
}
