<?php
	session_start();
	if(!isset($_SESSION["admin_login"]))
		header("Location: login.php");
		
	include_once "../back-end/config/init.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Адмін-панель</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/ckeditor/ckeditor.js"></script>
    <script src="js/add_post.js"></script>
</head>
<body>

    <div class="wrap">
        <?php include_once "header.php"; ?>
        
        <div id="content">
            <?php include_once "left_sidebar.php"; ?>
            
            <div id="main" class="index">
            	<?php
                $user = new Profile();
                $user->get_full_info_admin($_GET['id']);
              ?>
            </div>

            <div class="clear"></div>
        </div>
    </div>

</body>
</html>