<?php

session_start();

if (!isset($_SESSION["user_id"])) {
	header("Location: index.php");
}
		
include_once "back-end/config/init.php";

$DB_connect = new DB_connect();
$user_id    = $_GET["user_id"];

if ($DB_connect->db->query("DELETE FROM `user` WHERE `u_id` = $user_id")) {
	echo "OK: Your profile has been deleted";
}
else {
	echo "ERROR: Error delete";
}

?>