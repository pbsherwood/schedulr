<?php

$is_admin = false;
include_once("../config.php");
if (constant("global_advanced_login_display") != '1')
{
	include_once("../header_code.php");
}
$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));

$term = "%" . $conn->real_escape_string($_GET["term"]) . "%";

if ($is_admin || constant("global_advanced_login_display") == '1')
{
	$result = $conn->query("select schedulr_items_id as id, title, start, end, repeat_id as resourceId from schedulr_items");
}
else
{
	$result = $conn->query("select schedulr_items_id as id, title, start, end, repeat_id as resourceId from schedulr_items where id in (select items_id from schedulr_quests where users_id = " . $_SESSION['user_prefs']['id'] . " or created_by = " . $_SESSION['user_prefs']['id'] . ")");
}

$data = array();
while ($row = $result->fetch_assoc())
{
	$guests = array();
	$query = "SELECT first_name, last_name FROM schedulr_users WHERE schedulr_users_id in (select users_id from schedulr_guests where items_id = " . $row['id'] . ")";
	$result2 = $conn->query($query);

	while ($row2 = $result2->fetch_assoc())
	{
		$guests[] = $row2;
	}
	$new_data = $row;
	$new_data['guests'] = $guests;
	$data[] = $new_data;
}
$conn->close();

header('content-type: application/json; charset=utf-8');
echo json_encode($data);

?>