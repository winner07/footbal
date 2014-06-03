<?php
	session_start();
	if(!isset($_SESSION["admin_login"]))
		header("Location: login.php");
		
	include_once "../back-end/config/init.php";
	
	$categories = new Menu();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <title>Адмін-панель | Категорії</title>
    <link href="js/jquery-ui-1.10.4.custom/css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="js/edit_cat.js"></script>
    <script src="js/delete_cat.js"></script>
    <script src="js/add_cat.js"></script>
</head>
<body>

    <div class="wrap">
        <?php include_once "header.php"; ?>
        
        <div id="content">
            <?php include_once "left_sidebar.php"; ?>
            
            <div id="main" class="full">
                <div class="full_w">
                    <h2 class="h_title">Категорії</h2>
                    <table width="400" id="manage_cats">
                    	<tr>
                        	<th width="80%">Назва</th><th width="20%">Клас</th>
                            <?php
								$categories->manage_categories();
							?>
                    </table>
                    <div id="add_category">
                    	<div class="title">Додати категорію</div>
                        <form action="add_cat.php" method="post">
                        	<label for="add_parent_cat">Батьківська категорія</label>
							<?php
                                $categories->parent_categories();
                            ?>
                        	<label for="add_cat_name">Назва</label>
                            <input type="text" id="add_cat_name" name="add_cat_name">
                            <label for="add_cat_class">Клас</label>
                            <input type="text" id="add_cat_class" name="add_cat_class">
                            <input type="button" id="add_cat" value="Додати">
                        </form>
                    </div>
                    <div id="del_cat_dialog" title="Видалити категорію">
                    	<p>Дійсно видалити категорію?</p>
                        <p><strong id="del_cat_name"></strong></p>
                    </div>
                    <div id="edit_dialog" title="Редагувати категорію">
                    	<form action="edit_cat.php" method="post">
                        	<label for="cat_name">Назва</label>
                            <input type="text" id="cat_name" name="cat_name">
                            <label for="cat_class">Клас</label>
                            <input type="text" id="cat_class" name="cat_class">
                            <input type="hidden" id="cat_id" name="cat_id">
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
