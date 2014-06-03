<?php
session_start();
include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="windows-1251">
	<link href="front-end/css/style.css" rel="stylesheet">
	<title>Футбол + | Реєстрація</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<?php
				//Якщо користувач авторизований - вивести повідомлення
				if(isset($_SESSION["user_login"])){
					echo "<p class=\"ok\">Ви вже зареєстровані!</p>";
				} else {
					$r_go	= isset($_POST["r_go"]) ? strip_tags(trim($_POST["r_go"])) : NULL;
					//Масив помилок
					$errors = array();
					
					//Якщо форма відправлена
					if(isset($r_go)){
						//З'єднання з базою даних
						$regDb = new DB_connect();
						//Змінні форми
						$r_login 	= isset($_POST["r_login"])    ? strip_tags(trim($_POST["r_login"]))    : "";
						$r_email 	= isset($_POST["r_email"])    ? strip_tags(trim($_POST["r_email"]))    : "";
						$r_sex 		= isset($_POST["r_sex"])      ? strip_tags(trim($_POST["r_sex"]))	   : "";
						$r_pass 	= isset($_POST["r_pass"])     ? strip_tags(trim($_POST["r_pass"]))	   : "";
						$r_repass 	= isset($_POST["r_repass"])   ? strip_tags(trim($_POST["r_repass"]))   : "";
						$r_avatar   = isset($_FILES["r_avatar"])  ? $_FILES["r_avatar"]                    : "";
						//Перевірка правильності імейлу
						if($r_email && strlen($r_email) <= 100){
							$existEmail = $regDb->db->query("SELECT COUNT(*) from `user` where `u_email` = '$r_email'");
							$existEmail = $existEmail->fetchColumn(0);
							if($existEmail){
								$errors["er_email"] = "Така e-mail-адреса вже існує!";
							}
						} else {
							$errors["er_email"] = "Неприпустима довжина e-mail-адреси!";
						}
						//Перевірка правильності пароля
						if(strlen($r_pass) >= 6 && strlen($r_pass) <= 100){
							if(strcmp($r_pass, $r_repass) != 0){
								$errors["er_pass"] = "Паролі не збігаються!";
							}
						} else {
							$errors["er_pass"] = "Пароль повинен бути не менше 6 і не більше 100 символів!";
						}
						//Перевірка правильності статі
						if($r_sex != "1" && $r_sex != "2"){
							$errors["er_sex"] = "Не вірно введено стать";
						}
						//Перевірка правильності аватара
						if($r_avatar["error"] != 4){
							switch($r_avatar["error"]){
								case 1:
									$errors["er_avatar"] = "Розмір файлу більше 100 кб";
									break;
								case 2:
									$errors["er_avatar"] = "Розмір файлу більше 100 кб";
									break;
								case 3:
									$errors["er_avatar"] = "Завантажена тільки частина файлу";
									break;
								case 6:
									$errors["er_avatar"] = "Помилка завантаження спробуйте пізніше";
									break;
								case 7:
									$errors["er_avatar"] = "Помилка завантаження спробуйте пізніше";
									break;
							}
						}
					}
					//якщо форма не відправлена ??або є помилки введення
					if(!isset($r_go) || count($errors)){
				?>
                <form method="post" action="" enctype="multipart/form-data">
                    <table class="reg_table">
                        <tr>
                            <td><strong>Логін</strong></td>
                            <td colspan="2"><input type="text" name="r_login" value="<?php echo $r_login; ?>" required max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>E-mail</strong></td>
                            <td><input type="email" name="r_email" value="<?php echo $r_email; ?>" max="100" required></td>
                            <td><?php echo isset($errors["er_email"]) ? $errors["er_email"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Стать</strong></td>
                            <td><input type="radio" name="r_sex" checked value="1" id="male"><label for="male">Чоловічий</label>
                                <br>
                                <input type="radio" name="r_sex" value="2" id="female"><label for="female">Жіночий</label>
                            </td>
                            <td><?php echo isset($errors["er_sex"]) ? $errors["er_sex"] : NULL; ?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Пароль</strong></td>
                            <td><input type="password" name="r_pass" value="<?php echo $r_pass; ?>" required></td>
                            <td><?php echo isset($errors["er_pass"]) ? $errors["er_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Повторіть пароль</strong></td>
                            <td colspan="2"><input type="password" name="r_repass" value="<?php echo $r_repass; ?>" required></td>
                        </tr>
                        <tr>
                            <td><strong>Аватар (50 * 50) 100 кб</strong></td>
                            <td>
                            	<input type="hidden" name="MAX_FILE_SIZE" value="51200">
                            	<input type="file" name="r_avatar" accept="image/*">
                            </td>
                            <td><?php echo isset($errors["er_avatar"]) ? $errors["er_avatar"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="submit" name="r_go" value="Зареєструватися"></td>
                        </tr>
                    </table>
                </form>
                <?php
					} else {
						//Переміщення аватара в підходящу папку
						if($r_avatar["error"] == 0){
							$avatar_folder = "back-end/load_img/". $r_avatar["name"];
							move_uploaded_file($r_avatar["tmp_name"], $avatar_folder);
						}
						//Запис у базу даних
						$sqlReg = "INSERT INTO `user` (`u_login`, `u_email`, `u_pass`, `u_sex`, `u_avatar`) VALUES ('$r_login', '$r_email', SHA1('$r_pass'), '$r_sex', '$avatar_folder')";
						if($regDb->db->exec($sqlReg)){
							echo "<p class=\"ok\">Реєстрація пройшла успішно, можете увійти в акаунт</p>";
						} else {
							echo "<p class=\"error\">Помилка реєстрації, повторіть пізніше</p>";
						}
					}
				}
				?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>