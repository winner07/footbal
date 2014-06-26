<?php

session_start();

if (!isset($_SESSION["admin_login"])) {
	header("Location: login.php");
}
		
include_once "../back-end/config/init.php";

//Якщо передані дані категорії для редагування
if (isset($_POST["u_id"])) {
	$u_id       = $_POST["u_id"];
	$u_login    = $_POST["u_login"];
	$u_name     = $_POST["u_name"];
	$u_surname  = $_POST["u_surname"];
	$u_email    = $_POST["u_email"];
	$u_pass     = $_POST["u_pass"];
	$u_role     = $_POST["u_role"];
	$DB_connect = new DB_connect();
	
	if ($DB_connect->db->query("UPDATE `user` SET 
														 `u_login` = '$u_login',
														 `u_name` = '$u_name',
														 `u_surname` = '$u_surname',
														 `u_email` = '$u_email',
														 `u_pass` = SHA1('$u_pass'),
														 `u_role` = '$u_role'
														 WHERE `u_id` = $u_id")) {
		echo "OK";
	}
}
else {
	echo "ERROR";
}

?>