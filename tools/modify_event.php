<?php

include_once("../header_code.php");
include_once("When/src/Valid.php");
include_once("When/src/When.php");

use When\When;

$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));

function calculate_repeat_times($from, $repeat_interval, $repeat_every, $repeat_to)
{
	$r = new When();
	$r->startDate(new DateTime($from))
		->freq($repeat_interval)
		->until(new DateTime($repeat_to))
		->interval($repeat_every)
		->generateOccurrences();

	return $r->occurrences;
}

function insert_event_entry($_conn, $_title, $_from, $_to, $_guests, $_repeat_id)
{
	$query = "insert into schedulr_items (title, allDay, start, end, repeat_id, created_date, created_by) values ('" . $_title . "', 0, '" . $_from . "', '" . $_to . "', " . $_repeat_id . ", now(), " . $_SESSION['user_prefs']['id'] . ")";
	$result = $_conn->query($query);
	$insert_id = $_conn->insert_id;

	foreach ($_guests as $guest_id)
	{
		$_conn->query("insert into schedulr_guests (items_id, users_id, created_date, created_by) values (" . $insert_id . ", " . $guest_id . ", now(), " . $_SESSION['user_prefs']['id'] . ")");
	}
}

$repeat = false;
$type = $_POST["type"];
$id = $conn->real_escape_string($_POST["id"]);

$guests = json_decode($conn->real_escape_string($_POST['guests']));
$title = $conn->real_escape_string($_POST["title"]);
$from = $conn->real_escape_string($_POST["from"]);
$to = $conn->real_escape_string($_POST["to"]);

if(isset($_POST["repeat_interval"]) && isset($_POST["repeat_every"]) && isset($_POST["event_repeat_to"]))
{
	$repeat = true;
	$repeat_interval = $conn->real_escape_string($_POST["repeat_interval"]);
	$repeat_every = $conn->real_escape_string($_POST["repeat_every"]);
	$repeat_to = $conn->real_escape_string($_POST["event_repeat_to"]);
	
	$repeat_times = calculate_repeat_times($from, $repeat_interval, $repeat_every, $repeat_to);
}

if ($type == "create" && $is_admin)
{
	if ($repeat)
	{
		$datetime_from = new DateTime($from);
		$datetime_to = new DateTime($to);
		$from_to_interval = $datetime_from->diff($datetime_to);
	
		$repeat_id = intval($conn->query("select max(repeat_id) from schedulr_items")->fetch_object()->repeat_id) + 1;
		
		foreach ($repeat_times as $occurrence)
		{
			$repeat_from_string = $occurrence->format('Y/m/d H:i:s');
			$repeat_to_date = $occurrence;
			$repeat_to_date->add($from_to_interval);
			$repeat_to_string = $repeat_to_date->format('Y/m/d H:i:s');

			insert_event_entry($conn, $title, $repeat_from_string, $repeat_to_string, $guests, $repeat_id);
		}
	}
	else
	{
		insert_event_entry($conn, $title, $from, $to, $guests, 0);
	}
}
else if ($type == "delete" && $is_admin)
{
	$delete_type = $conn->real_escape_string($_POST["delete_type"]);
	$repeat_id = $conn->real_escape_string($_POST["repeat_id"]);

	if ($delete_type == "single")
	{
		$statement = $conn->prepare("delete from schedulr_items where schedulr_items_id = ?");
		$statement->bind_param("i", $id);
	}
	else if ($delete_type == "all")
	{
		$statement = $conn->prepare("delete from schedulr_items where repeat_id = ?");
		$statement->bind_param("i", $repeat_id);
	}
	$statement->execute();
}

?>