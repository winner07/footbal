<?php
	session_start();
	if(!isset($_SESSION["admin_login"]))
		header("Location: login.php");
		
	include_once "../back-end/config/init.php";
	
	$info_DB = new DB_connect();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <title>����-������</title>
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
            	<div class="info_block last_posts">
                	<h2 class="title">������ ������</h2>
                    <?php
                    	$last_posts = $info_DB->db->query("SELECT `post_title`, `post_date`, `cat_name`, `u_login` FROM `posts`, `categories`, `user` WHERE `post_cat_id` = `cat_id` AND `post_author_id` = `u_id` ORDER BY `post_date` DESC LIMIT 5");
		
						echo "<section>";
						while($post = $last_posts->fetch(PDO::FETCH_ASSOC)){
							echo <<<POST
								<article class="post">
									<header>
										<strong class="author">{$post["u_login"]}</strong>
										<time datetime="{$post["post_date"]}">{$post["post_date"]}</time>
										<h3 class="cat_name">{$post["cat_name"]}</h3>
										<span class="arrow">&rarr;</span>
										<h3>{$post["post_title"]}</h3>
									</header>
								</article>
POST;
						}
						echo "</section>";
					?>
                    <a href="all_post.php" class="all_link">�� ������</a>
                </div>
                
                <div class="info_block last_comments">
                	<h2 class="title">������ ��������</h2>
                    <?php
                    	$last_comments = $info_DB->db->query("SELECT `comment_text`, `comment_date`, `post_title`, `u_login` FROM `comments`, `user`, `posts` WHERE `comment_post_id` = `post_id` AND `comment_user_id` = `u_id` ORDER BY `comment_date` DESC LIMIT 5");
		
						echo "<section>";
						while($comment = $last_comments->fetch(PDO::FETCH_ASSOC)){
							echo <<<COMMENT
								<article class="comment">
									<header>
										<strong class="author">{$comment["u_login"]}</strong>
										<time datetime="{$comment["comment_date"]}">{$comment["comment_date"]} | </time>
										<a href="#">{$comment["post_title"]}</a>
									</header>
									<div class="comment_text">{$comment["comment_text"]}</div>
								</article>
COMMENT;
						}
						echo "</section>";
					?>
                    <a href="comments.php" class="all_link">�� ��������</a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

</body>
</html>