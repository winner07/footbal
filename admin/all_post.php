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
    <title>����-������ | �������� �����</title>
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
                    <div class="h_title">�������� �����</div>
                    <table width="860">
                    	<tr>
                        	<th width="35%">����</th><th width="15%">������</th><th width="20%">��������</th><th width="17%">����</th><th width="13%">�����</th>
                            <?php
                            	$postsDb = new DB_connect();
								$post    = $postsDb->db->query("SELECT * FROM `posts`, `categories`, `user` WHERE `post_cat_id` = `cat_id` AND `post_author_id` = `u_id` ORDER BY `posts`.`post_date` DESC;");
								
								while($postRow = $post->fetch()){
									echo "<tr>
											<td><span>{$postRow["post_title"]}</span>
											<div class=\"edit_block\">
												<a href=\"delete_post.php\" class=\"delete\" data-post-id=\"{$postRow["post_id"]}\">��������</a>
												<a href=\"edit_post.php?post_id={$postRow["post_id"]}\" class=\"edit\">����������</a>
											</div>
											</td>
											<td>". ($postRow["post_status"] == "post" ? "�����������" : "��������") ."</td>
											<td>{$postRow["cat_name"]}</td>
											<td>{$postRow["post_date"]}</td>
											<td>{$postRow["u_login"]}</td>
										</tr>";
								}
							?>
                    </table>
                    <div id="del_post_dialog" title="�������� ������">
                    	<p>ĳ���� �������� �� ������?</p>
                        <p><strong id="del_post_name"></strong></p>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
