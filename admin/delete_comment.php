<?php

session_start();

if (!isset($_SESSION['admin_login']) && $profile->check_permission($_SESSION['admin_id'], 'p_edit_comment')) {
	header('Location: login.php');
}
		
include_once '../back-end/config/init.php';

//Якщо переданий ID поста для видалення
if (isset($_POST['comment_id'])) {
	$comment_id = $_POST['comment_id'];
	$DB_connect = new DB_connect();
	
	if ($DB_connect->db->query("DELETE FROM `comments` WHERE `comment_id` = $comment_id")) {
		echo 'Comment has been deleted';
	}
}
else {
	echo 'Comment ID has not been passed';
}

?>