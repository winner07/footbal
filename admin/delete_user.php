<?php

session_start();

if (!isset($_SESSION["admin_login"])) {
	header("Location: login.php");
}
		
include_once "../back-end/config/init.php";

//Якщо переданий ID поста для видалення
if (isset($_POST["user_id"])) {
	$user_id = $_POST["user_id"];
	$DB_connect = new DB_connect();
	
	if ($DB_connect->db->query("DELETE FROM `user` WHERE `u_id` = $user_id")) {
		echo "OK";
	}
}
else {
	exit();
}

?>