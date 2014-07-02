<?php
	session_start();
	if (!isset($_SESSION["admin_login"])) {
		header("Location: login.php");
    }
		
	include_once "../back-end/config/init.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Адмін-панель | Менеджер новин</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="js/jquery-ui-1.10.4.custom/css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="js/delete_post.js"></script>
</head>
<body>

    <div class="wrap">
        <?php include_once "header.php"; ?>

        <div id="content">
            <?php include_once "left_sidebar.php"; ?>
            
            <div id="main" class="full">
                <div class="full_w">
                    <div class="h_title">Менеджер новин</div>
                    <table width="860">
                    	<tr>
                        	<th width="35%">Тема</th><th width="15%">Статус</th><th width="20%">Категорія</th><th width="17%">Дата</th><th width="13%">Автор</th>
                      </tr>
                      <?php
                      	$postsDb = new DB_connect();

                        if ($profile->check_permission($_SESSION["admin_id"], "p_edit_all_post")) {
        								$post    = $postsDb->db->query("SELECT * FROM `posts`, `categories`, `user` WHERE `post_cat_id` = `cat_id` AND `post_author_id` = `u_id` ORDER BY `posts`.`post_date` DESC;");
                        }
                        elseif ($profile->check_permission($_SESSION["admin_id"], "p_edit_own_post")) {
                        $post    = $postsDb->db->query("SELECT * FROM `posts`, `categories`, `user` WHERE `post_cat_id` = `cat_id` AND `post_author_id` = {$_SESSION['admin_id']} ORDER BY `posts`.`post_date` DESC;");
                        }
        								
        								while($postRow = $post->fetch()){
        									echo "<tr>
        											<td><span>{$postRow["post_title_{$_SESSION['user_lang']}"]}</span>
        											<div class=\"edit_block\">
        												<a href=\"delete_post.php\" class=\"delete\" data-post-id=\"{$postRow["post_id"]}\">Видалити</a>
        												<a href=\"edit_post.php?post_id={$postRow["post_id"]}\" class=\"edit\">Редагувати</a>
        											</div>
        											</td>
        											<td>". ($postRow["post_status"] == "post" ? "Опубліковано" : "Чорновик") ."</td>
        											<td>{$postRow["cat_name_{$_SESSION['user_lang']}"]}</td>
        											<td>{$postRow["post_date"]}</td>
        											<td><a href=\"profile.php?id={$postRow["u_id"]}\">{$postRow["u_login"]}</a></td>
        										</tr>";
        								}
        							?>
                    </table>
                    <div id="del_post_dialog" title="Видалити новину">
                    	<p>Дійсно видалити цю новину?</p>
                        <p><strong id="del_post_name"></strong></p>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
