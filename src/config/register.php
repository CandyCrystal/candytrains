<?php
require_once("./candyDirectory.php");
if (isset($_POST['submit'])) {
	$userName = $_POST['userName'];
	$userPassword = $_POST['userPassword'];

	$options = array("cost" => 4);
	$hashPassword = password_hash($userPassword, PASSWORD_BCRYPT, $options);
	$userName = mysqli_real_escape_string($this->databaseConnection, $userName);
	$hashPassword = mysqli_real_escape_string($this->databaseConnection, $hashPassword);

	$sql = "INSERT INTO users (userName, userPassword) values ('$userName', '$hashPassword')";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		echo "Registration successfully";
	} else {
		echo "pop";
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CandyCryst - Register</title>
</head>

<body>

</body>

</html>
<h1>Registration Form (deactivated)</h1>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<input type="text" name="userName" value="" placeholder="Username">
	<input type="password" name="userPassword" value="" placeholder="Password">
	<button type="submit" name="submit">Submit</buttom>
</form>