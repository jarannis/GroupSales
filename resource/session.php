<?php
$thisdir = dirname(__FILE__);
$sessKillURL = $thisdir . "killsession.php";

$debugpath .= "Entered Session.php <br/>";

session_start();
// Check if user is logged in, if not, print login form:
if(!isset($_SESSION['isloggedin']) || $_SESSION['isloggedin'] == false){
	$debugpath .= "Session NOT found/Logged In. <br/>";
	$loggedIn = false;
	// 
	$userForm = <<<HTML
<form name="gslogin" action="login.php" method="post">
Username: <input type="text" name="gsloginuname"/>
Password: <input type="password" name="gsloginpass"/>
<input type="hidden" name="fromlocation" value="$currentURL"/>
<button name="submit" type="submit" value="submit">Login</button>
HTML;
}
else{
	$loggedIn = true;
	$debugpath .= "Session WAS found, User logged in. <br/>";
}
// If they ARE logged in, retrieve user info and permissions from the database and print their customized User Management Menu.
if($loggedIn == true){
	$userFName = $_SESSION['loggedfriendlyname'];
	$debugpath .= "Entered Logged In Loop. <br/>";
	echo "<h3>$userFName</h3> <a href=\"$sessKillURL\">Log Out</a>";
	echo "";
}