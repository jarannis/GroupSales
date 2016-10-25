<?php
session_start();
$redirect = "index";
if(isset($_GET['fromlocation'])){
	$fromlocation = $_GET['fromlocation'];
}
if(isset($_POST['fromlocation'])){
	$fromlocation = $_POST['fromlocation'];
}

if(isset($fromlocation)){
	$redirect = $fromlocation;
	switch($fromlocation){
		case "index":
			$redirectloc = "index.php";
			break;
		case "customer":
			$redirectloc = "customer.php";
			break;
		case "order":
			$redirectloc = "order.php";
			break;
	}
}
else{
	$redirectloc = "index.php";
}

if(!isset($_POST['gsloginuname'])){
	printMenu($redirect);
}
else {
	// process login
	if(isset($_POST['gsloginuname'])){
		$username = $_POST['gsloginuname'];
	}
	if(isset($_POST['gsloginpass'])){
		$password = $_POST['gsloginpass'];
	}

	// only if both set, proceed to database for lookup
	if(isset($username) && isset($password)){
		include "./conf/mysql.conf.php";

		$password = md5(base64_encode($password));
		$query = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
		$result = mysqli_query($link, $query);
		echo mysqli_error($link);
		$count = mysqli_num_rows($result);
		if($count > 0){
			$row = mysqli_fetch_assoc($result);
			$friendlyName = $row['friendlyName'];
			$admin = $row['admin'];
			echo "Welcome $friendlyName";
			$_SESSION['isloggedin'] = true;
			$_SESSION['loggedfriendlyname'] = $friendlyName;
			$_SESSION['admin'] = $admin;
			header('Location: ' . $redirectloc);
		}
		else{
			echo "No account matching those credentials, Try again.<br/><br/>";
			printMenu($redirect);
		}
	}
	else{
		printMenu($redirect);
	}
}

function printMenu($redirect){

echo <<<HTML

<form name="gslogin" action="login.php" method="post">
Username: <input type="text" name="gsloginuname"/>
Password: <input type="password" name="gsloginpass"/>
<input type="hidden" name="fromlocation" value="$redirect"/>
<button name="submit" type="submit" value="submit">Login</button>


HTML;
}