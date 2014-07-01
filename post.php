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
					
								try {
									$page_nav->full_post($_GET["id"]);
								}
								catch (Exception $e) {
									echo "<p class=\"error\">". $e->getMessage() ."</p>";
								}
								
								$comments = new Comments($_GET["id"], $_SESSION["user_id"]);
								//Якщо коментар відправлений
								if ($profile->check_permission($_SESSION["user_id"], "p_comment") && isset($_SESSION["user_id"], $_POST["comment_send"])) {
									try {
										$comments->write_comment();
										header("Location:". $_SERVER["REQUEST_URI"]);
										ob_end_flush();
									}
									catch(Exception $e) {
										echo "<p class=\"error\">". $e->getMessage() ."</p>";
									}
								}
								
								//Вивід коментарів, якщо вони є
								if ($comments->comment_count) {
									$comments->get_comments();
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