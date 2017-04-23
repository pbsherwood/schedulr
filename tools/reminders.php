<?php

include_once(dirname(__FILE__) . "/../config.php");

if(constant("global_schedulr_reminders") == "1")
{
	if(php_sapi_name() == "cli" && empty($_SERVER["REMOTE_ADDR"]))
	{
		$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));
		$result = $conn->query("SELECT * FROM schedulr_users")or die("<span class='error_span'><u>MySQL error:</u> " . htmlspecialchars(mysqli_error()) . "</span>");

		while($user = $result->fetch_assoc())
		{
			$user_id = $user["schedulr_users_id"];

			$result2 = $conn->query("SELECT title, start, end, TIME(start) as time FROM schedulr_items where DATE(start) = CURDATE() and schedulr_items_id in (select distinct(items_id) from schedulr_guests where users_id = " . $user_id . ")")or die("<span class='error_span'><u>MySQL error:</u> " . htmlspecialchars(mysql_error()) . "</span>");

			if($result2->num_rows > 0)
			{
				$event_list = "";
				$time = "";

				while($event = $result2->fetch_assoc())
				{	
					$time .= $event["time"] . ", ";
					$event_list .= $event["title"] . " : " . $event["start"] . " - " . $event["end"] . "\r\n";
				}

				$time = rtrim($time, ", ");
				$subject = "Event reminder";
				$message = "This is a reminder for events you are scheduled for today.\r\n\nYou are scheduled for the following time(s): " . $time . "\r\n\nA full list of the events is below.\r\n\n" . $event_list . "\r\n\n" . constant("global_url");
				$headers = "From: " . constant("global_title") . " <" . constant("global_schedulr_reminders_email") . ">\r\n";
				$headers .= "Reply-To: " . constant("global_schedulr_reminders_email") . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/plain; charset=utf-8\r\n";

				mail($user["email"], "=?UTF-8?B?".base64_encode($subject)."?=", $message, $headers);
			}
		}
	}
}

?>
