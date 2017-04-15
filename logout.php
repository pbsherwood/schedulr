<?php

include_once("config.php");
session_start();
session_destroy();

header('Location: ' . constant("global_url"));

?>