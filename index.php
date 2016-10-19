<?php
session_start();
if(!isset($_SESSION['isloggedin'])){	
	header( 'Location: ./login.php?fromlocation=index' );
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo <<<HTML
<head>
<link rel="stylesheet" type="text/css" href="./resource/style/tableStyle.css">
</head>

HTML;

echo <<<HTML
<form name="returnToSearch" action="index.php">
<button type="submit">Return to Search</button>
</form>
HTML;

include "./conf/config.php";
if ($_POST){
	$searchType = $_POST['sType'];
}
else {
	$searchType = "none";
}

switch ($searchType){
	// if NO SEARCH TYPE IS FOUND
	case "none":
		echo 'Legacy Group Sales Search: <br/>';
		echo 'Search: <br/> ';
		echo '<form name="SearchType" action="index.php" method="post">';
		echo '<input type="radio" name="sType" value="custByZip"> Customer by Zip Code<br/>';
		echo '<input type="radio" name="sType" value="custByName"> Customer By Name<br/>';
		echo '<input type="radio" name="sType" value="custByContName"> Customer By Contact Name<br/>';
		echo '<input type="submit" name="submit" value="Submit" />';
		echo '</form><form name="orderRedirect" action="order.php" method="post">
		<button type="submit" name="submit" value="showForm">Search by Order instead</button>
		</form>';
		break;
	// if searching Customer by Zip Code:
	case "custByZip":
		if (isset($_POST['zipRangeStart'])){
			$searchStartRange = $_POST['zipRangeStart'];
		}
		else {
			$searchStartRange = "";
		}
		if (isset($_POST['zipRangeEnd'])){
			$searchEndRange = $_POST['zipRangeEnd'];
		}
		else {
			$searchEndRange = "";
		}
		if (isset($_POST['zipCode'])){
			$searchSingleZip = $_POST['zipCode'];
		}
		else {
			$searchSingleZip = "";
		}	
		if (isset($_POST['zipSearchType'])){
			$zipSearchType = $_POST['zipSearchType'];
		}
		else {
			$zipSearchType = "";
		}


		echo 'Search by Zip:<br/>';
		// BEGIN FORM
		echo '<form name="searchByZip" action="index.php" method="post">';
		echo '<input type="hidden" name="sType" value="custByZip" />';
				// radio button default if search type "range"
		if ($zipSearchType == "range"){
			echo '<input type="radio" name="zipSearchType" value="range" checked="checked" /> Zip Code Range ';
		}
		else {
			echo '<input type="radio" name="zipSearchType" value="range" /> Zip Code Range ';
		}
		echo '<input type="text" name="zipRangeStart" value="'.$searchStartRange.'" /> - <input type="text" name="zipRangeEnd" value="'.$searchEndRange.'" /><br/><br/>';
		if ($zipSearchType == "singleZip"){
			echo '<input type="radio" name="zipSearchType" value="singleZip" checked="checked" /> Single Zip Code ';
		}
		else {
			echo '<input type="radio" name="zipSearchType" value="singleZip" /> Single Zip Code ';	
		}
		echo '<input type="text" name="zipCode" value="'.$searchSingleZip.'" /><br/><br/>';
		echo '<input type="submit" name="submit" value="Submit"/>';
		echo '</form><br/><br/>';
		// END FORM BEGIN RESULTS
		switch ($zipSearchType){
			case "range":
				$buildQuery = "SELECT * FROM `customers` WHERE `ShippingZip` BETWEEN '$searchStartRange' AND '$searchEndRange' ORDER BY `CustID` ASC";
				$results = mysqli_query($link, $buildQuery);
				buildCustomersTable($results);
				break;
			case "singleZip":
				$buildQuery = "SELECT * FROM `customers` WHERE `ShippingZip` = '$searchSingleZip' ORDER BY `Organization` ASC";
				$results = mysqli_query($link, $buildQuery);
				buildCustomersTable($results);
				break;
			default:
				//NOTHING HERE
				break;
		}
		

		break;
	// Customer by Name
	case "custByName":
		if(isset($_POST['custSearchName'])){
			$custSearchName = $_POST['custSearchName'];
			$searchQuery = "SELECT * FROM `customers` WHERE `Organization` LIKE '%$custSearchName%' ORDER BY `Organization` ASC";
			$searchResult = mysqli_query($link, $searchQuery);
			if(mysqli_num_rows($searchResult) == 0){
				echo "<h2>No Results Returned</h2>";
				echo '<form name="returnToSearchByCust action="./index.php" method="post">
				<input type="hidden" name="sType" value="custByName" />
				<button type="submit">Return to Search by Name</button>
				</form>';
			}
			else{
				buildCustomersTable($searchResult);
			}
		}
		else{
			echo <<<HTML
			<h3>Search by Customer:</h3>
			<form name="searchByCust" action="index.php" method="post">
			<input type="hidden" name="sType" value="custByName" />
			Partial Organization Name<br/>
			<input type="text" name="custSearchName" />
			<button type="submit" name="submit" value="Submit">Search</button>
			</form>
HTML;
		}
		break;
	case "custByContName":
		if(isset($_POST['custSearchName'])){
			$custSearchName = $_POST['custSearchName'];
			$searchQuery = "SELECT * FROM `customers` WHERE `FirstName` LIKE '%$custSearchName%' ORDER BY `Organization` ASC";
			$searchResult = mysqli_query($link, $searchQuery);
			$affectedRows = mysqli_num_rows($searchResult);
			if($affectedRows !== 0){
				echo "<h3>Results in First Name</h3>";
				buildCustomersTable($searchResult);
			}
			$searchQuery = "SELECT * FROM `customers` WHERE `LastName` LIKE '%$custSearchName%' ORDER BY `Organization` ASC";
			$searchResult = mysqli_query($link, $searchQuery);
			$affectedRows += mysqli_num_rows($searchResult);
			if(mysqli_num_rows($searchResult) == 0){
				if($affectedRows == 0){
					echo "<h2>No Results Found</h2>";
				}
				echo '<form name="returnToSearchByCust action="./index.php" method="post">
				<input type="hidden" name="sType" value="custByContName" />
				<button type="submit">Return to Search by Contact Name</button>
				</form>';
			}
			else{
				echo "<h3>Results in Last Name</h3>";
				buildCustomersTable($searchResult);
			}

		}
		else{
			echo <<<HTML
			<h3>Search by Contact Name:</h3>
			<form name="searchByCust" action="index.php" method="post">
			<input type="hidden" name="sType" value="custByContName" />
			Partial Customer First or Last Name<br/>
			<input type="text" name="custSearchName" />
			<button type="submit" name="submit" value="Submit">Search</button>
			</form>
HTML;
		}
		break;

// END OF SWITCH ($SearchType)
}

function buildCustomersTable($customerResults){
	include "./conf/mysql.conf.php";
	echo '<table>';
	// Table Header, First row
	echo '<tr">
	<th>ID</th>
	<th>Organization Type</th>
	<th>Organization Name</th>
	<th>Contact Name</th>
	<th>City</th>
	<th>Zip Code</th>
	<tr/>';

	// Data Start
	while ($row = mysqli_fetch_assoc($customerResults)){
		$typeID = $row['Organizationtypeid'];
		$typeQuery = "SELECT * FROM `organizationtypes` WHERE `OrganizationTypeID` = '$typeID'";
		$typeResult = mysqli_query($link, $typeQuery);
		$typeRow = mysqli_fetch_assoc($typeResult);
		$orgType = $typeRow['OrganizationTypeDescription'];
		$orgName = $row['Organization'];
		$orgID = $row['CustId'];
		$contName = $row['FirstName'] . " " . $row['LastName'];
		echo "<tr>";
		echo "<td>{$row['CustId']}</td>
		<td>$orgType</td>";
		// build Organization Button Form
		echo '<td>
		<form name="'.$orgName.'" action="customer.php" method="post">
		<input type="hidden" name="OrgID" value="'.$orgID.'" />
		<input type="hidden" name="show" value="orders" />
		<button type="submit" name="submit" value="submit">'.$orgName.'</button>
		</form></td>';
		// Resume Listing Results
		echo "<td>$contName</td>
		<td>{$row['ShippingCity']}</td>
		<td>{$row['ShippingZip']}</td>
		</tr>";
	}
	echo "</table>";
}