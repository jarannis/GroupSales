<?
if(isset($_POST['submit'])){
	if(isset($_POST['newUName']) && isset($_POST['newPass']) && $isset($_POST['newFName'])){ // Entered new user's credentials

		if(isset($_POST['adminUName']) && $isset($_POST['adminPass'])){ // Entered admin credentials
			if(verifyAdmin($_POST['adminUName'],$_POST['adminPass'])){ // Admin Credentials Correct
				include "./mysql.conf.php";
				
				$newUName = $_POST['newUName'];
				$newPass = $_POST['newPass'];
				$encPass = base64_encode($newPass);
				$hashedPass = md5($newPass);
				$newFName = $_POST['newFName'];


				$insertQuery = "INSERT INTO `users` (`username`, `password`, `friendlyName`) VALUES ('$newUName', '$hashedPass', '$newFName')";
				$result = mysqli_query($insertQuery);
				if(mysqli_affected_rows($result) == 1){ // succeeded creating a user
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
	$buildVerQuer = "SELECT * FROM `users` WHERE `username` , `password` = '$aUser' , '$aPassword' ";
	$buildResult = mysqli_query($link, $buildVerQuer);
	$row = mysqli_fetch_array($buildResult);

	$isadmin = $buildResult['admin'];

	return $isadmin;
}