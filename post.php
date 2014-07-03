<?php
ob_start();
include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="urf-8">
	<link href="front-end/css/style.css" rel="stylesheet">
  <link href="front-end/css/page_nav.css" rel="stylesheet">
	<title>Football +</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<?php
            		$page_nav = new Page_navigator($_SESSION['user_lang']);
								$comments = new Comments($_GET["id"], $_SESSION["user_id"]);

								//Якщо коментар відправлений
								if ($profile->check_permission($_SESSION["user_id"], "p_comment") && isset($_SESSION["user_id"], $_POST["comment_send"])) {
									try {
										$comments->write_comment();
										header("Location:". $_SERVER["REQUEST_URI"]);
										exit();
										ob_end_flush();
									}
									catch(Exception $e) {
										echo "<p class=\"error\">". $e->getMessage() ."</p>";
									}
								}

								//Якщо оцінка голосування відправлена
								if ($profile->check_permission($_SESSION["user_id"], "p_vote") && isset($_SESSION["user_id"], $_POST["rate_go"])) {
									try {
										$page_nav->write_rate();
										$_SESSION["user_rate"] = "Y";
										header("Location:". $_SERVER["REQUEST_URI"]);
										exit();
									}
									catch (Exception $e) {
										echo "<p class=\"error\">". $e->getMessage() ."</p>";
									}
								}

								//Якщо видалити оцінку
								if ($profile->check_permission($_SESSION["user_id"], "p_vote") && isset($_SESSION["user_id"], $_POST["rate_del"])) {
									$page_nav->delete_rate();
									header("Location:". $_SERVER["REQUEST_URI"]);
									exit();
								}

								//Якщо видалити всі оцінки за матеріла
								if ($profile->check_permission($_SESSION["user_id"], "p_vote") && isset($_SESSION["user_id"], $_POST["rate_del_all"])) {
									$page_nav->delete_all_rate();
									header("Location:". $_SERVER["REQUEST_URI"]);
									exit();
								}

								//Вивід новини
								try {
									$page_nav->message_rate();
									$page_nav->av_rate();
									$page_nav->full_post($_GET["id"], $profile->check_permission($_SESSION["user_id"], "p_vote"));
								}
								catch (Exception $e) {
									echo "<p class=\"error\">". $e->getMessage() ."</p>";
								}
								
								//Вивід коментарів, якщо вони є
								if ($comments->comment_count) {
									$comments->get_comments($_GET["comment_page"], 10);
									$comments->print_nav(5);
								}
								
								//Якщо користувач авторизований і має права коментувати, показати форму додавання коментарів
								if ($profile->check_permission($_SESSION["user_id"], "p_comment")) {
									$comments->print_form();
								}
							?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>