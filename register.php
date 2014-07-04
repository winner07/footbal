<?php
include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="urf-8">
	<link href="front-end/css/style.css" rel="stylesheet">
	<script src="front-end/js/jquery-1.10.2.min.js"></script>
	<script src="front-end/js/validate.js"></script>
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
								if (isset($_SESSION["user_login"])) {
									echo "<p class=\"ok\">You are already member!</p>";
								} 
								else {
									$r_go	= isset($_POST["r_go"]) ? strip_tags(trim($_POST["r_go"])) : NULL;
									//Масив помилок
									$errors = array();
									
									//Якщо форма відправлена
									if (isset($r_go)) {
										//З'єднання з базою даних
										$reg_db = new DB_connect();
										//Змінні форми
										$r_login 	  = strip_tags(trim($_POST["r_login"]));
										$r_email   	= strip_tags(trim($_POST["r_email"]));
										$r_pass 	  = strip_tags(trim($_POST["r_pass"]));
										$r_repass   = strip_tags(trim($_POST["r_repass"]));
										$r_avatar   = $_FILES["r_avatar"];

										//Перевірка правильності логіна
										if ($r_login && strlen($r_login) <= 30) {
											$existlogin = $reg_db->db->query("SELECT COUNT(*) from `user` where `u_login` = '$r_login'")->fetchColumn(0);
											if ($existlogin) {
												$errors["er_login"] = "This login is exists!";
											}
										} else {
											$errors["er_login"] = "Too long login!";
										}
										//Перевірка правильності імейлу
										if ($r_email && strlen($r_email) <= 100) {
											$existEmail = $reg_db->db->query("SELECT COUNT(*) from `user` where `u_email` = '$r_email'")->fetchColumn(0);
											if ($existEmail) {
												$errors["er_email"] = "This e-mail is exists!";
											}
										} else {
											$errors["er_email"] = "Too long e-mail!";
										}
										//Перевірка правильності пароля
										if (strlen($r_pass) >= 6 && strlen($r_pass) <= 100) {
											if (strcmp($r_pass, $r_repass) != 0) {
												$errors["er_pass"] = "Passwords do not match!!";
											}
										}
										else {
											$errors["er_pass"] = "The password must be at least 6 and no more than 100 characters!";
										}
										//Перевірка правильності аватара
										if ($r_avatar["error"] != 4) {
											switch ($r_avatar["error"]) {
												case 1:
													$errors["er_avatar"] = "File size more than 100 kb";
													break;
												case 2:
													$errors["er_avatar"] = "File size more than 100 kb";
													break;
												case 3:
													$errors["er_avatar"] = "Uploaded only part of the file";
													break;
												case 6:
													$errors["er_avatar"] = "Error loading please try later";
													break;
												case 7:
													$errors["er_avatar"] = "Error loading please try later";
													break;
											}
										}
									}
									//якщо форма не відправлена або є помилки введення
									if (!isset($r_go) || count($errors)) {
								?>
                <form method="post" action="" enctype="multipart/form-data" id="register_form">
                    <table class="reg_table">
                        <tr>
                            <td><strong>Login</strong></td>
                            <td><input type="text" name="r_login" id="r_login" value="<?php echo $r_login; ?>" required max="30"></td>
                            <td><?php echo isset($errors["er_login"]) ? $errors["er_login"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>E-mail</strong></td>
                            <td><input type="text" name="r_email" id="r_email" value="<?php echo $r_email; ?>" max="100" required></td>
                            <td><?php echo isset($errors["er_email"]) ? $errors["er_email"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Password</strong></td>
                            <td><input type="password" name="r_pass" id="r_pass" value="<?php echo $r_pass; ?>" required></td>
                            <td><?php echo isset($errors["er_pass"]) ? $errors["er_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Repeat password</strong></td>
                            <td colspan="2"><input type="password" name="r_repass" id="r_repass" value="<?php echo $r_repass; ?>" required></td>
                        </tr>
                        <tr>
                            <td><strong>Avatar (150 * 150) 100 kb</strong></td>
                            <td>
                            	<input type="hidden" name="MAX_FILE_SIZE" value="51200">
                            	<input type="file" name="r_avatar" accept="image/*">
                            </td>
                            <td><?php echo isset($errors["er_avatar"]) ? $errors["er_avatar"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="submit" name="r_go" value="Register"></td>
                        </tr>
                    </table>
                </form>
                <?php
					} else {
						//Переміщення аватара в підходящу папку
						if ($r_avatar && $r_avatar["error"] == 0) {
							$avatar_src = "back-end/load_img/". $r_avatar["name"];
							move_uploaded_file($r_avatar["tmp_name"], $avatar_src);
						}
						//Запис у базу даних
						$sql_reg = "INSERT INTO `user` (`u_login`, `u_email`, `u_pass`, `u_avatar`, `u_date_reg`) VALUES ('$r_login', '$r_email', SHA1('$r_pass'), " . ($avatar_src ? "'$avatar_src'" : "DEFAULT") . ", NOW())";

						if ($reg_db->db->exec($sql_reg)) {
							echo "<p class=\"ok\">Registration was successful, you can log in to your account</p>";
						} else {
							echo "<p class=\"error\">Error registering again later</p>";
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
