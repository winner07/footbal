<?php
session_start();
include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="windows-1251">
	<link href="front-end/css/style.css" rel="stylesheet">
    <link href="front-end/css/page_nav.css" rel="stylesheet">
	<title>������ +</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<?php
				$page_nav = new Page_navigator();
				$page_nav->print_news($_GET["category"], $_GET["page"], 10);
				$page_nav->print_nav(5);
				?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>