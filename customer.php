<?php

// display listing by Customer

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


if ($_POST){
	$orgID = $_POST['OrgID'];
	$show = $_POST['show'];
	$submit = $_POST['submit'];
	if (isset($_POST['show'])){
		switch ($_POST['show']){
			case "orders":
				$showBubbles = 'Orders: <input type="radio" name="show" value="orders" checked /><br/>Contacts: <input type="radio" name="show" value="contacts"/> ';
				break;
			case "contacts":
				$showBubbles = 'Orders: <input type="radio" name="show" value="orders" /><br/>Contacts: <input type="radio" name="show" value="contacts" checked/> ';
		}
	}
}
else{
	$submit = "inputBox";
	$show = "";
}

switch($submit){
	case "inputBox":
		echo 'Look up organization by ID #:
		<form name=custIDentry action="./customer.php" method="post">
		<input type="hidden" name="show" value="orders"/>
		ID # <input type="text" name="OrgID" />
		<input type="submit" name="submit" value="submit" />
		</form>';
		break;
	case "submit":
		include "./conf/mysql.conf.php";
		$orgQueryBuild = "SELECT * FROM `customers` WHERE `CustId` = '$orgID'";
		$orgResult = mysqli_query($link, $orgQueryBuild);
		$orgRow = mysqli_fetch_assoc($orgResult);
		$orgName = $orgRow['Organization'];
		$orgPhone = $orgRow['workphone'];
		$orgAddr = $orgRow['ShippingAddress'];
		$orgAddr2 = $orgRow['ShippingAddress2'];
		$orgCity = $orgRow['ShippingCity'];
		$orgState = $orgRow['ShippingState'];
		$orgZip = $orgRow['ShippingZip'];
		$orgPriContact = $orgRow['FirstName'] . " " . $orgRow['LastName'];

		// Check Tax Exempt status
		
		if ($orgRow['taxexempt'] == 0){
			$isTaxExempt = false;
			$teBoxString = "<input type=checkbox disabled/>";
		}
		elseif ($orgRow['taxexempt'] == 1){
			$isTaxExempt = true;
			$teBoxString = "<input type=checkbox checked disabled/>";
		}
		else {
			$isTaxExempt = false;
		}
		

		$taxID = $orgRow['teid'];
		$priContactName = $orgRow['FirstName'] . " " . $orgRow['LastName'];

		// parse Organization Type
		$typeQuery = "SELECT * FROM `organizationtypes` WHERE `OrganizationTypeID` = '{$row['Organizationtypeid']}'";
		$typeResult = mysqli_query($link, $typeQuery);
		$typeRow = mysqli_fetch_assoc($typeResult);
		$orgType = $typeRow['OrganizationTypeDescription'];

		// Display Customer Information
		echo "
		<h3>Organization Information</h3>
		<table>
		<tr><td>Organization ID:</td><td>$orgID</td></tr>
		<tr><td>Organization Name:</td><td>$orgName</td><td>Type:</td><td>$orgType</td></tr>
		<tr><td>Tax Exempt?</td><td>$teBoxString</td><td>Tax ID:</td><td>$taxID</td></tr>
		<tr><td>Primary Contact Name:</td><td>$orgPriContact</td></tr>
		<tr><td>Phone Number:</td><td>$orgPhone</td></tr>
		<tr><td>Address:</td><td>$orgAddr</td></tr>
		<tr><td>Address(continued):</td><td>$orgAddr2</td></tr>
		<tr><td>City/State/Zip</td><td>$orgCity ,$orgState $orgZip</td></tr>
		</table><br/>";
		echo "Show:
		<form name=\"Show Type\" action=\"customer.php\" method=\"post\">
		<input type=\"hidden\" name=\"OrgID\" value=\"$orgID\">
		$showBubbles
		<button type=\"submit\" name=\"submit\" value=\"submit\">Submit</button>
		</form>";

		switch($show){
			case "orders":
				buildOrdersTable($orgID);
			break;
			case "contacts":
				buildContactsTable($orgID);
				break;
		}
		break;

}

function buildOrdersTable($inOrgID){
	include "./conf/mysql.conf.php";
	// Query Build and Execute
	$orderQueryBuild = "SELECT * FROM `orders` WHERE `CustID` = '$inOrgID' ORDER BY `OrderID` ASC";
	$ordersResults = mysqli_query($link, $orderQueryBuild);

	echo '<table>';
	// Table Header, First row
	echo '<tr">
	<td>Order Number</td>
	<td>Order Date</td>
	<td>Order Amount</td>
	<tr/>';

	// Data Start
	while ($row = mysqli_fetch_assoc($ordersResults)){
		$orderID = $row['OrderID'];
		$orderDate = date("m/d/Y", strtotime($row['OrderDate']));
		$orderAmount = round($row['totalorderamount'], 2, PHP_ROUND_HALF_UP);

		echo "<tr>";
		echo '<td><form name="selectOrder" action="./order.php" target="null" method="post">
		<input type="hidden" name="orderNumber" value="'.$orderID.'"/>
		<button type="submit" name="submit" value="submit">'.$orderID.'</button></td></form>';
		echo "<td>$orderDate</td>
		<td>$ $orderAmount</td></tr>";
	}
}

function buildContactsTable($inOrgID){
	include "./conf/mysql.conf.php";
	// Query Build and Execute
	$contactQueryBuild = "SELECT * FROM `contacts` WHERE `custid` = '$inOrgID' ORDER BY `contactId` ASC";
	$contactResults = mysqli_query($link, $contactQueryBuild);

	echo '<table>';
	// Table Header, First row
	echo '<tr">
	<td>Name</td>
	<td>Title</td>
	<td>Work Phone</td>
	<td>Extension</td>
	<td>Email</td>
	<td>Fax</td>
	<tr/>';

	// Data Start
	while ($row = mysqli_fetch_assoc($contactResults)){
		$contName = $row['FirstName'] . " " . $row['Lastname'];
		$contTitle = $row['title'];
		$contWorkPh = $row['workphone'];
		$contExtension = $row['workphoneext'];
		$contEmail = $row['Email'];
		$contFax = $row['fax'];

		echo <<<HTML
			<tr>
				<td>$contName</td>
				<td>$contTitle</td>
				<td>$contWorkPh</td>
				<td>$contExtension</td>
				<td>$contEmail</td>
				<td>$contFax</td>
			</tr>

HTML;
	}
	echo "</table>";
}
