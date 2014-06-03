<?php

session_start();

if(!isset($_SESSION["admin_login"]))
	header("Location: login.php");
		
include_once "../back-end/config/init.php";

//Якщо передані дані категорії для редагування
if(isset($_POST["cat_name"], $_POST["cat_class"], $_POST["cat_id"])){
	$cat_name   = iconv("UTF-8", "windows-1251", trim($_POST["cat_name"]));
	$cat_class  = iconv("UTF-8", "windows-1251", trim($_POST["cat_class"]));
	$cat_id     = $_POST["cat_id"];
	$DB_connect = new DB_connect();
	
	if($DB_connect->db->query("UPDATE `categories` SET `cat_name` = '$cat_name', `cat_class` = '$cat_class' WHERE `cat_id` = $cat_id")){
		echo "OK";
	}
} else {
	echo "ERROR";
}

?>