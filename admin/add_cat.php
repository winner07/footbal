<?php

include_once "../back-end/config/init.php";

//���� ����� ��������� �������
$add_cat_parent = iconv("UTF-8", "windows-1251", $_POST["add_cat_parent"]);
$add_cat_name   = iconv("UTF-8", "windows-1251", $_POST["add_cat_name"]);
$add_cat_class  = iconv("UTF-8", "windows-1251", $_POST["add_cat_class"]);

//���� �� ���� �
if(isset($add_cat_name)){
	$cat_db = new DB_connect();
	//�������� ��������� ������� � ����� ���������� �������, ��� ���������� ��������
	$is_double_cat = $cat_db->db->query("SELECT COUNT(*) FROM `categories` WHERE `cat_name` = '$add_cat_name' AND `cat_parent` = $add_cat_parent")->fetchColumn(0);
	//���� �������� �, ������� �����������
	if($is_double_cat){
		echo "DOUBLE CAT";
	} else {
		echo $cat_db->db->exec("INSERT INTO `categories`(`cat_name`, `cat_parent`, `cat_class`) VALUE('$add_cat_name', $add_cat_parent, ". (!empty($add_cat_class) ? "'$add_cat_class'" : "NULL") .")") ? "CAT" : "ERROR";
	}
}

?>