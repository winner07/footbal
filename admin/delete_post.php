<?php

session_start();

if(!isset($_SESSION["admin_login"]))
	header("Location: login.php");
		
include_once "../back-end/config/init.php";

//Якщо переданий ID поста для видалення
if(isset($_POST["post_id"])){
	$post_id = $_POST["post_id"];
	$DB_connect = new DB_connect();
	
	if($DB_connect->db->query("DELETE FROM `posts` WHERE `post_id` = $post_id")){
		echo "Новина видалена";
	}
} else {
	echo "Не переданий ID поста";
}

?>