<?php
	session_start();
	include_once "../back-end/config/init.php";
	//З'єднання з базою даних
	$regDb = new DB_connect();
	//Змінні форми
	$auth_login  = isset($_POST["auth_login"]) ? strip_tags(trim($_POST["auth_login"])) : "";
	$auth_pass 	 = isset($_POST["auth_pass"])  ? strip_tags(trim($_POST["auth_pass"]))  : "";
	$auth_go	 = isset($_POST["auth_go"])    ? strip_tags(trim($_POST["auth_go"]))    : NULL;
	//Масив помилок
	$errors 	 = array();
	//Якщо форма відправлена
	if(isset($auth_go)){
		//Перевірка правильності імейлу
		if($auth_login && strlen($auth_login) <= 100){
			$authSql = "SELECT COUNT(*) FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass')";
			$existUser = $regDb->db->query($authSql);
			$existUser = $existUser->fetchColumn(0);
			if($existUser){
				$adminSql = "SELECT * FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass') AND `u_role` = 'admin'";
				$adminRes = $regDb->db->query($adminSql);
				$adminRes = $adminRes->fetch();
				
				//Якщо адміністратор, створити змінні сеансу
				if($adminRes["u_role"] == "admin"){
					$_SESSION["admin_login"] = $adminRes["u_login"];
					$_SESSION["admin_id"]   = $adminRes["u_id"];
					header("Location: index.php");
				} else {
					$errors["er_auth"] = "Ви не маєте прав адміністратора!";
				}
			} else {
				$errors["er_auth"] = "Помилка авторизації, перевірте дані!";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <title>Авторизація</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div id="wrap">
        <div class="full_w">
        	<?php
				//Якщо форма не відправлена ??або є помилки введення
				if(!isset($r_go) || count($errors)){
			?>
            <h2>Вхід в адмін-панель</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
				<?php echo isset($errors["er_auth"]) ? "<p class=\"error\">". $errors["er_auth"] ."</p>" : NULL; ?>
                <label for="auth_login">Логін:</label>
                <input id="auth_login" name="auth_login" class="text" value="<?php echo $auth_login; ?>">
                <label for="auth_pass">Пароль:</label>
                <input id="auth_pass" name="auth_pass" type="password" class="text">
                <div class="sep"></div>
                <button type="submit" name="auth_go" class="ok">Вхід</button>
            </form>
            <?php } ?>
        </div>
    </div>

</body>
</html>