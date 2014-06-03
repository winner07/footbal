<?php
session_start();
include_once "back-end/config/init.php";

if(isset($_SESSION["user_login"])){
	session_destroy();
	header("Location: index.php");
} else {
?>
<!doctype html>
<html>
<head>
	<meta charset="windows-1251">
	<link href="front-end/css/style.css" rel="stylesheet">
    <link href="front-end/css/page_nav.css" rel="stylesheet">
	<title>Футбол + | Вхід</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<p class="error">Ви не входили для того щоб вийти</p>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>
<?php } ?>