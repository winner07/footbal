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
                        <fieldset id="sel_lang">
                            <input type="radio" id="en" name="lang" checked="checked" value="en"><label for="en">En</label>
                            <input type="radio" id="ua" name="lang" value="ua"><label for="ua">Ua</label>
                        </fieldset>
                        <fieldset id="edit_en" class="editor_post_block">
                    	   <input type="text" placeholder="Input title" name="post_title_en" id="post_title_en" class="post_title" max="255">
                           <textarea name="post_content_en" id="post_content_en"></textarea>
                        </fieldset>
                        <fieldset id="edit_ua" class="editor_post_block">
                            <input type="text" placeholder="Введіть заголовок" name="post_title_ua" id="post_title_ua" class="post_title" max="255">
                            <textarea name="post_content_ua" id="post_content_ua"></textarea>
                        </fieldset>
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
                        	$category = new Menu($_SESSION['user_lang']);
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