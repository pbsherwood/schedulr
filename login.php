<?php include_once("header_code.php"); ?>
<?php
     if (isset($_POST['login']) && isset($_POST['password']) && $_POST['login'] != '' && $_POST['password'] != '')
     {
		// Create connection
		$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$login = $conn->real_escape_string($_POST['login']);
		$password = $conn->real_escape_string($_POST['password']);
		
		$statement = $conn->prepare("select password, first_name, last_name, phone, user_roles, schedulr_users_id from schedulr_users where email = ? ");
		$statement->bind_param("s", $login);
		$statement->execute();
		$statement->bind_result($database_password, $first_name, $last_name, $phone, $user_roles, $user_id);
		$statement->fetch();
		
		$display_message = "";
		
		if (password_verify($password, $database_password))
		{
			$_SESSION['login'] = $login;
			$_SESSION['user_prefs'] = array("id" => $user_id, "first_name" => $first_name, "last_name" => $last_name, "phone" => $phone, "user_roles" => $user_roles);
			header('Location: ' . constant("global_url"));
		}
		else
		{
			$display_message = "Wrong email or password. Try Again.";
		}
     }
?>

<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='css/login.css' rel='stylesheet' />
</head>
<body>
	<div class="wrap">
      	<img id='logo' src="img/logo.png">
		<form name=login method='POST'>
			<input type="text" placeholder="email" id="login" name="login" required>
			<div class="bar">
				<i></i>
			</div>
			<input type="password" placeholder="password" id="password" name="password" required>
			<a href="javascript:forgot_password_dialog();" class="forgot_link">forgot ?</a>
			<button>Sign in</button>
		</form>
		<span style='color:red; font-size: small;'><?= $display_message ?></span>
	</div>
	<script>
		document.getElementById('login').focus();
	</script>
</body>
</html>