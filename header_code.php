<?php

include_once("config.php");

session_start();

if (!isset($_SESSION['login'])) 
{
    $script = $_SERVER["SCRIPT_NAME"];
    $pos = strpos($script,"login.php");

    if($pos === false) 
	{
       echo "<script language=javascript>";
       echo "window.location='" . constant("global_url") . "login.php';";
       echo "</script>";
		exit;
    }
}

$is_admin = false;
if(strpos($_SESSION['user_prefs']['user_roles'], "admin") !== false)
{
	$is_admin = true;
}

?>