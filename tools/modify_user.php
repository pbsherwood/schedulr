<?php

include_once("../header_code.php");
$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));

$type = $_POST["type"];
$id = $conn->real_escape_string($_POST["id"]);

$email = $conn->real_escape_string($_POST["email"]);
$first_name = $conn->real_escape_string($_POST["fname"]);
$last_name = $conn->real_escape_string($_POST["lname"]);
$password = password_hash($conn->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
$phone = $conn->real_escape_string($_POST["phone"]);
$user_roles = $conn->real_escape_string($_POST["role"]); // Allowed: admin || user

if ($type == "create" && $is_admin)
{
	$statement = $conn->prepare("insert into schedulr_users (email, first_name, last_name, password, phone, user_roles) values (?, ?, ?, ?, ?, ?)");
	$statement->bind_param("ssssss", $email, $first_name, $last_name, $password, $phone, $user_roles);
	$statement->execute();
}
else if ($type == "edit" && ($is_admin || (trim($id) == trim($_SESSION['user_prefs']['id']))))
{
	$statement = $conn->prepare("update schedulr_users set first_name = ?, last_name = ?, phone = ?, user_roles = ? where schedulr_users_id = ?");
	$statement->bind_param("ssssi", $first_name, $last_name, $phone, $user_roles, $id);
	$statement->execute();
}
else if ($type == "delete" && $is_admin)
{
	$statement = $conn->prepare("delete from schedulr_users where schedulr_users_id = ?");
	$statement->bind_param("i", $id);
	$statement->execute();
}

$conn->close();
?>