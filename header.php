<?php include_once("header_code.php"); ?>

<html>
<head>
	<title><?= constant("global_title") ?></title>
	<link href='css/main.css' rel='stylesheet' />
</head>
<body>
	<div id='header'>
		<img id='logo' class='center' src='img/logo.png'>
		<nav id="nav">
			<a class="link" href="<?= constant("global_url") ?>">Home</a>
			<?php
			if($is_admin)
				echo "<a class='link' id='create_event' href='javascript:void(0)'>Add Event</a>";
			?>
			<a class='link' id='list_users' href='javascript:void(0)'>Users</a>
			<a class="link" id="logout" href="javascript:void(0)">Logout</a>
		</nav>
	</div>
	<div id='padding'></div>