<aside class="left_sb">
	<div class="widget">
    	<h2>����</h2>
    	<?php
		if(isset($_SESSION["user_login"])){
			$connect_db = new DB_connect();
			$avatar = $connect_db->db->query("SELECT `u_avatar` FROM `user` WHERE `u_login` = '{$_SESSION["user_login"]}'")->fetchColumn(0);
			$is_admin = $connect_db->db->query("SELECT `u_role` FROM `user` WHERE `u_login` = '{$_SESSION["user_login"]}'")->fetchColumn(0);
			
			echo "<ul>
					<li><img src=\"$avatar\" alt=\"������\"></li>
					<li><strong>{$_SESSION["user_login"]}</strong></li>"
					.($is_admin == "admin" ? "<li><a href=\"admin\\\" target=\"_blank\">����-������</a></li>" : NULL).
					"<li><a href=\"profile.php?id={$_SESSION["user_id"]}\">�������</a></li>
					<li><a href=\"logout.php\">�����</a></li>
				  <ul>";
		} else {
		?>
        <form action="login.php" method="post" class="authorization">
        	<fieldset>
            	<label for="auth_login">����</label>
                <input type="text" name="auth_login" id="auth_login">
            </fieldset>
            <fieldset>
            	<label for="auth_pass">������</label>
                <input type="password" name="auth_pass" id="auth_pass">
            </fieldset>
            <fieldset>
            	<input type="submit" name="auth_go" id="auth_enter" value="����">
            	<a href="register.php">���������</a>
            </fieldset>
        </form>
        <?php
		}
		?>
    </div>
    
    <div class="widget">
        <nav class="champs">
            <h2>���������</h2>
            <?php
			$menu = new Menu();
			$menu->print_categories();
            ?>
        </nav>
    </div>
</aside>