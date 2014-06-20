<?php
	session_start();
	include_once "../back-end/config/init.php";
	//Подключение базы даных
	$regDb = new DB_connect();
	//Данные авторизации
	$auth_login  = isset($_POST["auth_login"]) ? strip_tags(trim($_POST["auth_login"])) : "";
	$auth_pass 	 = isset($_POST["auth_pass"])  ? strip_tags(trim($_POST["auth_pass"]))  : "";
	$auth_go	   = isset($_POST["auth_go"])    ? strip_tags(trim($_POST["auth_go"]))    : NULL;
	//Массив ошибок
	$errors 	 = array();
	//Если форма отправлена
	if(isset($auth_go)){
		//Если логин и пароль введены
		if($auth_login && strlen($auth_login) <= 100){
			$existUser = $regDb->db->query("SELECT COUNT(*) FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass')")->fetchColumn(0);

			if($existUser){
					$user_data = $regDb->db->query("SELECT * FROM `user` 
					WHERE `u_login` = '$auth_login'
				  AND `u_pass` = SHA1('$auth_pass')")->fetch();
				
					$_SESSION["admin_login"] = $user_data["u_login"];
					$_SESSION["admin_id"]    = $user_data["u_id"];
					header("Location: index.php");
			} else {
				$errors["er_auth"] = "Не верно введены данные!";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div id="wrap">
        <div class="full_w">
        	<?php
						//Если данные авторизации не переданы, или есть ошибки ввода даных
						if(!isset($r_go) || count($errors)){
					?>
            <h2>Admin Panel | Sign in</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
							<?php echo isset($errors["er_auth"]) ? "<p class=\"error\">". $errors["er_auth"] ."</p>" : NULL; ?>
              <label for="auth_login">Login:</label>
              <input id="auth_login" name="auth_login" class="text" value="<?php echo $auth_login; ?>">
              <label for="auth_pass">Password:</label>
              <input id="auth_pass" name="auth_pass" type="password" class="text">
              <div class="sep"></div>
              <button type="submit" name="auth_go" class="ok">Sign in</button>
          	</form>
          <?php } ?>
        </div>
    </div>

</body>
</html>