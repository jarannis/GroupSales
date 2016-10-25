<?php
echo ('<a href="../index.php">Go Back</a><br/>');
if(isset($_POST['submit'])){
	if(isset($_POST['newUName']) && isset($_POST['newPass']) && isset($_POST['newFName']) && isset($_POST['verPass'])){ // Entered new user's credentials
		if($_POST['newPass'] != $_POST['verPass']){
			echo "Passwords do not match, please try again.<br/>";
			addMenu();
			die();
		}
		if(isset($_POST['adminUName']) && isset($_POST['adminPass'])){ // Entered admin credentials
			if(verifyAdmin($_POST['adminUName'],$_POST['adminPass'])){ // Admin Credentials Correct

				include "./mysql.conf.php";

				$newUName = $_POST['newUName'];

				// Check whether username is taken
				$unamecheck = "SELECT * FROM `users` WHERE `username` = '$newUName' ";
				$checkResult = mysqli_query($link, $unamecheck);
				echo mysqli_error($link);
				if(mysqli_affected_rows($link) > 0){
					echo "A user already exists with that username, please try again.";
					addMenu();
					die();
				}

				$newPass = $_POST['newPass'];
				echo $newPass."<br/>";
				$encPass = base64_encode($newPass);
				echo $encPass."<br/>";
				$hashedPass = md5($encPass);
				echo $hashedPass."<br/>";
				$newFName = $_POST['newFName'];


				$insertQuery = "INSERT INTO `users` (`username`, `password`, `friendlyName`) VALUES ('$newUName', '$hashedPass', '$newFName')";
				$result = mysqli_query($link, $insertQuery);
				if(mysqli_affected_rows($link) == 1){ // succeeded creating a user
					echo "Successfully created user.";
					addMenu();
				}
				else{ // Bad username or password
					echo "Something happened during user creation.<br/>";
					echo mysqli_error($link);
					echo "<br/>Please try again<br/>";
					addMenu();
				}
			}
			else { // Incorrect Admin Credentials
				echo "Admin Credentials Incorrect<br/>";
				echo "<br/>Please try again<br/>";
				addMenu();
			}
		}
		else { // No Admin Credentials entered.
			echo "You must enter an admin's username and password to create a user<br/>";
			echo "Please try again<br/>";
			addMenu();
		}
	}
}
else { // didn't enter ANY credentials.
	addMenu();
}

function addMenu(){
	echo <<<HTML
<form name="addUser" action="./makeuser.php" method="post">
Login Name: <input type="text" name="newUName" /><br/>
Password: <input type="password" name="newPass" /><br/>
Verify Password: <input type="password" name="verPass"/><br/>
Friendly Name: <input type="text" name="newFName"><br/><br/>

Admin Username : <input type="text" name="adminUName"><br/>
Admin Password : <input type="password" name="adminPass"><br/>
<button type="submit" name="submit" value="submit">Create User</button>
</form>

HTML;

}

function verifyAdmin($aUser, $aPassword){
	include "./mysql.conf.php";

	$aPassword = base64_encode($aPassword);
	$aPassword = md5($aPassword);
	$buildVerQuer = "SELECT * FROM `users` WHERE (`username`, `password`) = ('$aUser' , '$aPassword') ";
	$buildResult = mysqli_query($link, $buildVerQuer);
	echo mysqli_error($link);
	$row = mysqli_fetch_assoc($buildResult);

	$isadmin = $row['admin'];

	return $isadmin;
}