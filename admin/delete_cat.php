<?php

session_start();

if(!isset($_SESSION["admin_login"]))
	header("Location: login.php");
		
include_once "../back-end/config/init.php";

//���� ��������� ID ������� ��� ���������
if(isset($_POST["cat_id"])){
	$cat_id = $_POST["cat_id"];
	$DB_connect = new DB_connect();
	
	if($DB_connect->db->query("DELETE FROM `categories` WHERE `cat_id` = $cat_id")){
		echo "�������� ��������";
	}
} else {
	echo "�� ��������� ID �������";
}

?>