<?php

include_once "../back-end/config/init.php";

$post_db = new DB_connect();

//змінні поста
$post_title     = $post_db->db->quote(iconv("UTF-8", "windows-1251", $_POST["post_title"]));
//Поділ на коротку і повну новину
$post_content  = iconv("UTF-8", "windows-1251", $_POST["post_content"]);
$post_content  = explode("<div class=\"myBreak\">&nbsp;</div>", $post_content, 2);
$short_content = $post_db->db->quote(trim($post_content[0]));
$full_content  = $post_db->db->quote(trim($post_content[1]));
//ID категорії
$post_cat      = $_POST["post_cat"];
//Автор новини
$post_author   = $_POST["post_author"];
//ID новини
$post_id       = $_POST["post_id"];

//Якщо всі змінні є
if(isset($post_title, $post_content, $post_cat)){
	switch($_POST["post_status"]){
		//Якщо додати або оновити новину
		case "post" :
			//Якщо переданий ID новини, значить вона для оновлення
			if(isset($post_id)){
				echo $post_db->db->exec("UPDATE `posts` SET `post_title` = $post_title, `post_short_desc` = $short_content, `post_full_desc` = $full_content, `post_cat_id` = $post_cat, `post_author_id` = $post_author, `post_status` = 1  WHERE `post_id` = $post_id") ? "POST" : "ERROR";
			}
			//Інакше для додавання
			else {
				echo $post_db->db->exec("INSERT INTO `posts`(`post_title`, `post_short_desc`, `post_full_desc`, `post_cat_id`, `post_date`, `post_author_id`) VALUE($post_title, $short_content, $full_content, $post_cat, NOW(), $post_author)") ? "POST" : "ERROR";
			}
			break;
		//Якщо зберегти новину як чорновик
		case "draft" :
			//Якщо переданий ID новини, значить вона для оновлення
			if(isset($post_id)){
				echo $post_db->db->exec("UPDATE `posts` SET `post_title` = $post_title, `post_short_desc` = $short_content, `post_full_desc` = $full_content, `post_cat_id` = $post_cat, `post_author_id` = $post_author, `post_status` = 2  WHERE `post_id` = $post_id") ? "DRAFT" : "ERROR";
			}
			//Інакше для додавання
			else {
				echo $post_db->db->exec("INSERT INTO `posts`(`post_title`, `post_short_desc`, `post_full_desc`, `post_cat_id`, `post_date`, `post_author_id`, `post_status`) VALUE($post_title, $short_content, $full_content, $post_cat, NOW(), $post_author, 2)") ? "DRAFT" : "ERROR";
			}
			break;
	}
}

?>