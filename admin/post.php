<?php

include_once "../back-end/config/init.php";

$post_db = new DB_connect();

//змінні поста
$post_title_en    = $post_db->db->quote($_POST["post_title_en"]);
$post_title_ua    = $post_db->db->quote($_POST["post_title_ua"]);
//Поділ на коротку і повну новину для англійської версії
$post_content_en  = explode("<div class=\"myBreak\">&nbsp;</div>", $_POST["post_content_en"], 2);
$short_content_en = $post_db->db->quote(trim($post_content_en[0]));
$full_content_en  = $post_db->db->quote(trim($post_content_en[1]));
//Поділ на коротку і повну новину для української версії
$post_content_ua  = explode("<div class=\"myBreak\">&nbsp;</div>", $_POST["post_content_ua"], 2);
$short_content_ua = $post_db->db->quote(trim($post_content_ua[0]));
$full_content_ua  = $post_db->db->quote(trim($post_content_ua[1]));
//ID категорії
$post_cat         = $_POST["post_cat"];
//Автор новини
$post_author      = $_POST["post_author"];
//ID новини
$post_id          = $_POST["post_id"];

//Якщо всі змінні є
if(isset($post_title_en, $post_content_en, $post_title_ua, $post_content_ua, $post_cat)){
	switch($_POST["post_status"]){
		//Якщо додати або оновити новину
		case "post" :
			//Якщо переданий ID новини, значить вона для оновлення
			if(isset($post_id)){
				echo $post_db->db->exec("UPDATE `posts` SET `post_title_en` = $post_title_en, `post_short_desc_en` = $short_content_en, `post_full_desc_en` = $full_content_en, `post_title_ua` = $post_title_ua, `post_short_desc_ua` = $short_content_ua, `post_full_desc_ua` = $full_content_ua, `post_cat_id` = $post_cat, `post_author_id` = $post_author, `post_status` = 1  WHERE `post_id` = $post_id") ? "POST" : "ERROR";
			}
			//Інакше для додавання
			else {
				echo $post_db->db->exec("INSERT INTO `posts`(`post_title_en`, `post_short_desc_en`, `post_full_desc_en`, `post_title_ua`, `post_short_desc_ua`, `post_full_desc_ua`, `post_cat_id`, `post_author_id`) VALUE($post_title_en, $short_content_en, $full_content_en, $post_title_ua, $short_content_ua, $full_content_ua, $post_cat, $post_author)") ? "POST" : "ERROR";
			}
			break;
		//Якщо зберегти новину як чорновик
		case "draft" :
			//Якщо переданий ID новини, значить вона для оновлення
			if(isset($post_id)){
				echo $post_db->db->exec("UPDATE `posts` SET `post_title_en` = $post_title_en, `post_short_desc_en` = $short_content_en, `post_full_desc_en` = $full_content_en, `post_title_ua` = $post_title_ua, `post_short_desc_ua` = $short_content_ua, `post_full_desc_ua` = $full_content_ua, `post_cat_id` = $post_cat, `post_author_id` = $post_author, `post_status` = 2  WHERE `post_id` = $post_id") ? "DRAFT" : "ERROR";
			}
			//Інакше для додавання
			else {
				echo $post_db->db->exec("INSERT INTO `posts`(`post_title_en`, `post_short_desc_en`, `post_full_desc_en`, `post_title_ua`, `post_short_desc_ua`, `post_full_desc_ua`, `post_cat_id`, `post_author_id`, `post_status`) VALUE($post_title_en, $short_content_en, $full_content_en, $post_title_ua, $short_content_ua, $full_content_ua, $post_cat, $post_author, 2)") ? "DRAFT" : "ERROR";
			}
			break;
	}
}

?>