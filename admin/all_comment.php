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
    <title>Адмін-панель | Менеджер коментарів</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="js/jquery-ui-1.10.4.custom/css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="js/delete_comment.js"></script>
</head>
<body>

    <div class="wrap">
        <?php include_once "header.php"; ?>

        <div id="content">
            <?php include_once "left_sidebar.php"; ?>
            
            <div id="main" class="full">
                <div class="full_w">
                    <div class="h_title">Comment manager</div>
                    <table width="860">
                    	<tr>
                        	<th width="15%">Topic</th><th width="35%">Message</th><th width="7%">User</th><th width="13%">Comment date</th><th width="30%">Post</th>
                      </tr>
                      <?php
                      	$comment_db = new DB_connect();

                        if ($profile->check_permission($_SESSION["admin_id"], "p_edit_comment")) {
        								  $comments_sel = $comment_db->db->query("SELECT * FROM `comments`, `user`, `posts` WHERE `comment_post_id` = `post_id` AND `comment_user_id` = `u_id` ORDER BY `comment_date` DESC;");
                        }
        								
        								while($comment = $comments_sel->fetch()){
        									echo "<tr>
        											<td><span>{$comment['comment_topic']}</span></td>
        											<td><span>{$comment['comment_text']}</span>
                                <div class=\"edit_block\">
                                  <a href=\"delete_post.php\" class=\"delete\" data-comment-id=\"{$comment["comment_id"]}\">Видалити</a>
                                </div>
                              </td>
        											<td><a href=\"profile.php?id={$comment['u_id']}\">{$comment['u_login']}</a></td>
        											<td>{$comment['comment_date']}</td>
        											<td>{$comment['post_title_en']}</td>
        										</tr>";
        								}
        							?>
                    </table>
                    <div id="del_comm_dialog" title="Delete comment">
                      <p>Are you sure to delete this comment?</p>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
