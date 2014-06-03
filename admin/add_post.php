<?php
	session_start();
	if(!isset($_SESSION["admin_login"]))
		header("Location: login.php");
		
	include_once "../back-end/config/init.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <title>Адмін-панель | Додавання новини</title>
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
            
            <div id="main">
                <div class="clear"></div>
                
                <div class="full_w">
                    <div class="h_title">Додавання новини</div>
                    <form method="post" action="post.php" id="form_post">
                    	<input type="text" placeholder="Введіть заголовок" name="post_title" id="post_title" class="post_title" max="255">
                    	<textarea name="post_content" id="post_content"></textarea>
                        <input type="hidden" name="post_author" value="<?php echo $_SESSION["admin_id"]; ?>">
                    </form>
                </div>
            </div>
            
            <aside class="sidebar" id="right_sidebar">
                <div class="box">
                    <div class="h_title">&#8250; Опублікувати</div>
                    <div class="box_content">
                        <button class="btn_draft" form="form_post" name="post_status" value="draft">Чорновик</button>
                        <button class="btn_post" form="form_post" name="post_status" value="post">Опублікувати</button>
                    </div>
                </div>
                
                <div class="box">
                    <div class="h_title">&#8250; Чемпіонати</div>
                    <div class="box_content categories">
                        <?php
                        	$category = new Menu();
							$category->select_categorie(0, NULL);
						?>
                    </div>
                </div>
            </aside>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
