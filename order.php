<?php
	// Order viewing window. Opens in a pop-up. Not accessible directly.

echo <<<HTML
<head>
<link rel="stylesheet" type="text/css" href="./resource/style/tableStyle.css">
</head>

HTML;

if($_POST){
	if(isset($_POST['submit'])){
		$submit = $_POST['submit'];
	}
	if(isset($_POST['orderNumber'])){
		$orderID = $_POST['orderNumber'];
	}
	else{
		$orderID = "0";
	}
}
else{
	$submit = "showForm";
}

if ($submit == "submit"){
	include "./conf/mysql.conf.php";
	$QueryOrder = "SELECT * FROM `orders` WHERE `OrderID` = '$orderID'";
	$querResult = mysqli_query($link, $QueryOrder);
	$querRow = mysqli_fetch_assoc($querResult);
	$OrgID = $querRow['CustID'];
	$orderDate = date("m/d/Y", strtotime($querRow['OrderDate']));
	$nameQuery = "SELECT * FROM `customers` WHERE `CustID` = '$OrgID'";
	$nameResult = mysqli_query($link, $nameQuery);
	$nameRow = mysqli_fetch_assoc($nameResult);
	$organizationName = $nameRow['Organization'];

	// get List Items
	$liQuery = "SELECT * FROM `lineitems` WHERE `OrderID` = '$orderID' ORDER BY `ItemID` ASC";
	$liResult = mysqli_query($link, $liQuery);

	// Build Table Header
	echo <<<HTML
	<table>
		<tr>
			<th>Order Number: $orderID</th>
			<th>Organization Name: $organizationName</th>
			<th>Date: $orderDate</th>
		</tr>
		<tr>
			<th>Item Count</th>
			<th>Item Description</th>
			<th>Quantity</th>
			<th>Price/ea</th>
			<th>Total Item Price</th>
		</tr>
HTML;
$itemCount = 0;
$orderTotal = 0;
	while ($row = mysqli_fetch_assoc($liResult)){
		$itemCount ++;
		$itemDescription = $row['itemdescription'];
		$itemQuantity = $row['Quantity'];
		$itemPrice = $row ['Price'];
		$itemTotalPrice = $itemQuantity * $itemPrice;
		echo <<<HTML
		<tr>
			<td>$itemCount</td>
			<td>$itemDescription</td>
			<td>$itemQuantity</td>
			<td>$$itemPrice</td>
			<td>$$itemTotalPrice</td>
		</tr>
HTML;
		$orderTotal += $itemTotalPrice;
	}
	echo "<tr><td>Order Total</td><td>$$orderTotal</td></tr></table>";
	echo '<h3>Return to Search By Order Number</h3>
	<form name="return" action="order.php" method="post">
	<button type="submit" action="submit" value="showForm">Search by Order</button>
	</form>';
}
else{
	echo <<<HTML
	<h2>Order Search by Number:</h2>
	<form name="OrderQuery" action="order.php" method="post">
	<input type="text" name="orderNumber"/>
	<button type="submit" name="submit" value="submit">Submit</button>
	</form>
	<form method="post" action="./index.php">
	<button type="submit">Return to Customer Search</button>
	</form>
HTML;

}