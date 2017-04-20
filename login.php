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
	
	$css_path = "css/login.css";
	if (constant("global_advanced_login_display") == '1')
	{
		$css_path = "css/login_advanced.css";;
	}
?>

<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='<?= $css_path ?>' rel='stylesheet' />
</head>
<body>
	<div class="wrap">
      	<img id='logo' src="img/logo.png">
		<form name='login_form' method='POST'>
			<input type="text" placeholder="email" id="login" name="login" required>
			<div class="bar">
				<i></i>
			</div>
			<input type="password" placeholder="password" id="password" name="password" required>
			<button>Sign in</button>
		</form>
		<span style='color:red; font-size: small;'><?= $display_message ?></span>
	</div>
	<script>
		document.getElementById('login').focus();
	</script>
	
	<?php
	
		if (constant("global_advanced_login_display") == '1')
		{
			echo "
				<style>
					#calendar {
						max-width: 1200px;
						margin: 50px auto;
						padding: 0px 20px;
					}
					.table {
						display:table;
					}
					.row {
						display:table-row;
					}
					.cell {
						display:table-cell;
					}
				</style>
				<link href='css/fullcalendar.min.css' rel='stylesheet' />
				<link href='css/jquery-ui.css' rel='stylesheet' />
				<script src='js/moment.min.js'></script>
				<script src='js/jquery.min.js'></script>
				<script src='js/fullcalendar.min.js'></script>
				<script src='js/jquery-ui.js'></script>
				<script>
					$(function() { // document ready
					
						$('#dialog-event_content').dialog({ autoOpen: false, modal: true, width:350 });
					
						$('#calendar').fullCalendar({
							eventSources: {
								url: 'tools/resources.php'
							},
							editable: 'false',
							aspectRatio: 1.8,
							scrollTime: '00:00', // undo default 6am scrollTime
							header: {
								left: 'today prev,next',
								center: 'title',
								right: 'agendaWeek,month,listWeek'
							},
							defaultView: 'month',
							eventRender: function (event, element) {
								element.attr('href', 'javascript:void(0);');
								element.click(function() {
									$('#event-startTime').html(moment(event.start).format('MMM Do h:mm A'));
									$('#event-endTime').html(moment(event.end).format('MMM Do h:mm A'));
									$('#event-guests').html(function() {
										var output = '';
										$.each( event.guests, function( key, value ) {
											output = output + '<div>' + value['first_name'] + ' ' + value['last_name'] + '</div>';
										});
										return output;
									});
									$('#dialog-event_content').dialog({title: event.title});
									$('#dialog-event_content').dialog( 'open' );
								});
							}
						});
					});
				</script>
				<div id='calendar'></div>
				<div id='dialog-event_content'>
					<div class='table'>
						<div class='row'><div class='cell'>Start:</div><div class='cell'><div id='event-startTime'></div></div></div>
						<div class='row'><div class='cell'>End:</div><div class='cell'><div id='event-endTime'></div></div></div>
						<div class='row'><div class='cell'>Guests:</div><div class='cell'><div id='event-guests'></div></div></div>
					</div>
				</div>
			";
		}
		
	?>
	
</body>
</html>