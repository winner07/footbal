<?php
	session_start();
	if(!isset($_SESSION["admin_login"]))
		header("Location: login.php");
		
	include_once "../back-end/config/init.php";
	
	//Вибір поста для редагування
	$post_id         = $_GET["post_id"];
	$edit_post_DB    = new DB_connect();
	$edit_post       = $edit_post_DB->db->query("SELECT * FROM `posts` WHERE `post_id` = $post_id LIMIT 1")->fetch(PDO::FETCH_ASSOC);
	$post_title      = htmlspecialchars($edit_post["post_title"]);
	$post_short_desc = $edit_post["post_short_desc"];
	$post_full_desc  = $edit_post["post_full_desc"];
	$post_author_id  = $edit_post["post_author_id"];
	$post_cat_id     = $edit_post["post_cat_id"];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <title>Адмін-панель | Редагування новини</title>
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
                    <div class="h_title">Редагування новини</div>
                    <form method="post" action="post.php" id="form_post">
                    	<input type="text" placeholder="Введіть заголовок" name="post_title" id="post_title" class="post_title" max="255" value="<?php echo $post_title ?>">
                    	<textarea name="post_content" id="post_content">
                        	<?php
                            	echo $post_short_desc;
								
								if(!empty($post_full_desc)){
									echo "<div class=\"myBreak\"></div>";
									echo $post_full_desc;
								}
							?>
                        </textarea>
                        <input type="hidden" name="post_author" value="<?php echo $post_author_id; ?>">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
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
							$category->select_categorie(0, $post_cat_id);
						?>
                    </div>
                </div>
            </aside>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
