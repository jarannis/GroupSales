<?php
include "./mysql.conf.php";

switch $_POST['action']{
	case "NULL":
		actionMenu();
		break;
	case "newUser":
		createMenu();
		break
	case "modUser":
		userList();
		break;
	case default:
		actionMenu();
		break;
}

function buildHeader(){
	$header = <<<HTML
<head>
<link rel="stylesheet" type="text/css" href="./resource/style/tableStyle.css">
<form name="returnToSearch" action="index.php">
<button type="submit">Return to Search</button>
</form>
</head>
HTML;

}

function buildFooter(){

}

function actionMenu(){
	$body += 
}

function buildHTML(){
	$output = buildHeader();
	$output .= $body;
	$output .= buildFooter();

	echo $output;
}