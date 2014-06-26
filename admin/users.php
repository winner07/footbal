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
  <title>Адмін-панель | Менеджер новин</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="js/jquery-ui-1.10.4.custom/css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
  <script src="js/jquery-1.10.2.min.js"></script>
  <script src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
  <script src="js/edit_user.js"></script>
  <script src="js/delete_user.js"></script>
</head>
<body>

    <div class="wrap">
        <?php include_once "header.php"; ?>

        <div id="content">
            <?php include_once "left_sidebar.php"; ?>
            
            <div id="main" class="full">
                <div class="full_w">
                    <div class="h_title">Users manager</div>
                    <table width="860">
                    	<tr>
                        	<th width="15%">Login</th><th width="15%">Name</th><th width="15%">Surname</th><th width="20%">Email</th><th width="5%">Role</th><th width="15%">Registration date</th>
                            <th width="15%">Last logged</th>
                            <?php
                            	$user_db   = new DB_connect();
                              $role_sel  = $user_db->db->query("SELECT `p_role` FROM `permission`");
              								$users_sel = $user_db->db->query("SELECT * FROM `user` ORDER BY `u_date_reg`");
              								
              								while($user = $users_sel->fetch()){
              									echo "<tr data-user-id=\"{$user["u_id"]}\">
                  											<td><span class=\"u_login\"><a href=\"profile.php?id={$user["u_id"]}\">{$user["u_login"]}</a></span>
                  											<div class=\"edit_block\">
                  												<a href=\"delete_user.php\" class=\"delete\">Delete</a>
                  												<a href=\"edit_user.php\" class=\"edit\">Edit</a>
                  											</div>
                  											</td>
                  											<td><span class=\"u_name\">{$user["u_name"]}</span></td>
                  											<td><span class=\"u_surname\">{$user["u_surname"]}</span></td>
                  											<td><span class=\"u_email\">{$user["u_email"]}</span></td>
                  											<td><span class=\"u_role\">{$user["u_role"]}</span></td>
                                        <td>{$user["u_date_reg"]}</td>
                                        <td>{$user["u_date_logged"]}</td>
                  										</tr>";
              								}
              							?>
                    </table>
                    <div id="edit_dialog" title="Edit user">
                      <form>
                        <label for="u_new_login">Login</label>
                        <input type="text" id="u_new_login">
                        <label for="u_new_login">Name</label>
                        <input type="text" id="u_new_name">
                        <label for="u_new_login">Surname</label>
                        <input type="text" id="u_new_surname">
                        <label for="u_new_login">Email</label>
                        <input type="email" id="u_new_email">
                        <label for="u_new_pass">Password</label>
                        <input type="password" id="u_new_pass">
                        <label for="u_new_role">Role</label>
                        <select id="u_new_role">
                          <?php
                            while($role = $role_sel->fetch()){
                              echo "<option value=\"{$role['p_role']}\">{$role['p_role']}</option>";
                            }
                          ?>
                        </select>
                        <input type="hidden" id="u_id">
                      </form>
                    </div>
                    <div id="del_user_dialog" title="Delete user">
                    	<p>Are you sure to delete this profile?</p>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
