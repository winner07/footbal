<?php
	session_start();
	ob_start();
	include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="windows-1251">
	<link href="front-end/css/style.css" rel="stylesheet">
    <link href="front-end/css/page_nav.css" rel="stylesheet">
	<title>Футбол +</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<?php
            		$page_nav = new Page_navigator();
					
					try{
						$page_nav->full_post($_GET["id"]);
					} catch(Exception $e){
						echo "<p class=\"error\">". $e->getMessage() ."</p>";
					}
					
					$comments = new Comments($_GET["id"], $_SESSION["user_id"]);
					//Якщо коментар відправлений
					if(isset($_SESSION["user_login"], $_POST["comment_send"])){
						try{
							$comments->write_comment();
							header("Location:". $_SERVER["REQUEST_URI"]);
							ob_end_flush();
						} catch(Exception $e){
							echo "<p class=\"error\">". $e->getMessage() ."</p>";
						}
					}
					
					//Вивід коментарів, якщо вони є
					if($comments->comment_count){
						$comments->get_comments();
					}
					
					//Якщо користувач авторизований, показати форму додавання коментарів
					if(isset($_SESSION["user_login"])){
						$comments->print_form();
					}
				?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>