<?php
ob_start();
include_once "back-end/config/init.php";

function auth() {
	if (isset($_SESSION["user_login"])) {
		throw new Exception("Ви вже авторизовались");
	} else {
		//Якщо форма відправлена
		if (isset($_POST["auth_go"])) {
			//Дані авторизації
			$auth_login = strip_tags(trim($_POST["auth_login"]));
			$auth_pass  = strip_tags(trim($_POST["auth_pass"]));
			//З'єднання з базою даних
			$reg_db = new DB_connect();
			//Перевірка існування логіна
			$existLogin = $reg_db->db->query("SELECT COUNT(*) from `user` where `u_login` = '$auth_login'")->fetchColumn(0);
			
			if ($existLogin) {
				//Перевірка логіна і пароля
				$auth_accesss = $reg_db->db->query("SELECT COUNT(*) FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass');")->fetchColumn(0);

				if ($auth_accesss) {
					//Перевірка прав авторизації
					$login_id = $reg_db->db->query("SELECT `u_id` FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass');")->fetchColumn(0);
					$profile = new Profile();

					if ($profile->check_permission($login_id, "p_auth")) {
						$reg_db->db->exec("UPDATE `user` SET `u_date_logged` = NOW() WHERE `u_login` = '$auth_login'");
						$_SESSION["user_login"] = $auth_login;
						$_SESSION["user_id"] = $login_id;
						header("Location: index.php");
					}
					else {
						throw new Exception("Ваш профіль заблоковано", 1);
					}
				}
				else {
					throw new Exception("Невірний пароль", 1);
				}
			}
			else {
				throw new Exception("Невірний логін", 1);
			}
		}
		else {
			throw new Exception("Ви не відправили логін і пароль", 1);
		}
	}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="urf-8">
	<link href="front-end/css/style.css" rel="stylesheet">
	<title>Футбол + | Авторизація</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<?php
								try {
									auth();
									ob_end_flush();
								} catch(Exception $e) {
									if ($e->getCode() == 1) {
										echo "<p class=\"error\">". $e->getMessage() ."</p>";
									} else {
										echo "<p class=\"ok\">". $e->getMessage() ."</p>";
									}
								}
							?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>