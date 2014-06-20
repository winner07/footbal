<?php

include_once "../back-end/config/init.php";

//Змінні форми додавання категорії
$add_cat_parent  = $_POST["add_cat_parent"];
$add_cat_name_en = $_POST["add_cat_name_en"];
$add_cat_name_ua = $_POST["add_cat_name_ua"];
$add_cat_class   = $_POST["add_cat_class"];

//Якщо всі змінні є
if(isset($add_cat_name_en, $add_cat_name_ua)){
	$cat_db = new DB_connect();
	//Перевірка існування категорії в певній батьківській категорії, для запобігання дублікатів
	$is_double_cat = $cat_db->db->query("SELECT COUNT(*) FROM `categories` WHERE `cat_name_en` = '$add_cat_name_en' AND `cat_parent` = $add_cat_parent")->fetchColumn(0);
	//Якщо категорія є, вивести повідомлення
	if($is_double_cat){
		echo "DOUBLE CAT";
	} else {
		echo $cat_db->db->exec("INSERT INTO `categories`(`cat_name_en`, `cat_name_ua`, `cat_parent`, `cat_class`) VALUE('$add_cat_name_en', '$add_cat_name_ua', $add_cat_parent, ". (!empty($add_cat_class) ? "'$add_cat_class'" : "NULL") .")") ? "CAT" : "ERROR";
	}
}

?>