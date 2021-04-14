<?php
include('candyDirectory.php');
session_start();
?>
<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login - CandyTrains</title>
	<?php echo $style; ?>
</head>

<body>
	<?php echo $header; ?>
	<div class="content">
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
			<table>
				<tr>
					<th colspan="2">Login</th>
				</tr>
				<tr>
					<td>
						<input type="text" name="userName" value="" placeholder="username">
					</td>
					<td>
						<input type="password" name="userPassword" value="" placeholder="Password">
					</td>
				</tr>
				<tr>
					<th>
						<button type="submit" name="submit">Submit</button>
					</th>
					<th>
						<?php
						if (isset($_SESSION['login_user'])) {
							header("location: https://trains.candycryst.com");
							exit;
						}

						require_once("candyDirectory.php");
						if (isset($_POST['submit'])) {
							$userName = trim($_POST['userName']);
							$userPassword = trim($_POST['userPassword']);

							$sql = "SELECT * FROM users WHERE userName = '$userName'";
							$rs = mysqli_query($candyDirectoryConnection, $sql);
							$numRows = mysqli_num_rows($rs);

							if ($numRows  == 1) {
								$row = mysqli_fetch_assoc($rs);
								if (password_verify($userPassword, $row['userPassword'])) {
									if ($row['userIsActive'] == 1) {
										echo "Password verified";
										$_SESSION['user_id'] = $row['id'];
										$_SESSION['login_user'] = $userName;
										header("location: https://trains.candycryst.com");
									} else {
										echo "Your account has not yet been verified.";
									}
								} else {
									echo "Wrong Password";
								}
							} else {
								echo "No User found";
							}
						} ?>
					</th>
				</tr>
			</table>
		</form>
	</div>
</body>

</html>