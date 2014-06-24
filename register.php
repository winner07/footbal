<?php
include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="urf-8">
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
								if (isset($_SESSION["user_login"])) {
									echo "<p class=\"ok\">You are already member!</p>";
								} else {
									$r_go	= isset($_POST["r_go"]) ? strip_tags(trim($_POST["r_go"])) : NULL;
									//Масив помилок
									$errors = array();
									
									//Якщо форма відправлена
									if (isset($r_go)) {
										//З'єднання з базою даних
										$regDb = new DB_connect();
										//Змінні форми
										$r_login 	  = strip_tags(trim($_POST["r_login"]));
										$r_name 	  = strip_tags(trim($_POST["r_name"]));
										$r_surname 	= strip_tags(trim($_POST["r_surname"]));
										$r_email   	= strip_tags(trim($_POST["r_email"]));
										$r_sex 		  = strip_tags(trim($_POST["r_sex"]));
										$r_pass 	  = strip_tags(trim($_POST["r_pass"]));
										$r_repass   = strip_tags(trim($_POST["r_repass"]));
										$r_avatar   = $_FILES["r_avatar"];

										//Перевірка правильності логіна
										if ($r_login && strlen($r_login) <= 30) {
											$existlogin = $regDb->db->query("SELECT COUNT(*) from `user` where `u_login` = '$r_login'")->fetchColumn(0);
											if ($existlogin) {
												$errors["er_login"] = "This login is exists!";
											}
										} else {
											$errors["er_login"] = "Too long login!";
										}
										//Перевірка правильності імейлу
										if ($r_email && strlen($r_email) <= 100) {
											$existEmail = $regDb->db->query("SELECT COUNT(*) from `user` where `u_email` = '$r_email'")->fetchColumn(0);
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
										} else {
											$errors["er_pass"] = "The password must be at least 6 and no more than 100 characters!";
										}
										//Перевірка правильності статі
										if ($r_sex != "1" && $r_sex != "2") {
											$errors["er_sex"] = "Do not put sex right";
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
                <form method="post" action="" enctype="multipart/form-data">
                    <table class="reg_table">
                        <tr>
                            <td><strong>Login</strong></td>
                            <td><input type="text" name="r_login" value="<?php echo $r_login; ?>" required max="30"></td>
                            <td><?php echo isset($errors["er_login"]) ? $errors["er_login"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Name</strong></td>
                            <td colspan="2"><input type="text" name="r_name" value="<?php echo $r_name; ?>" required max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>Surname</strong></td>
                            <td colspan="2"><input type="text" name="r_surname" value="<?php echo $r_surname; ?>" required max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>E-mail</strong></td>
                            <td><input type="email" name="r_email" value="<?php echo $r_email; ?>" max="100" required></td>
                            <td><?php echo isset($errors["er_email"]) ? $errors["er_email"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Sex</strong></td>
                            <td><input type="radio" name="r_sex" checked value="1" id="male"><label for="male">Male</label>
                                <br>
                                <input type="radio" name="r_sex" value="2" id="female"><label for="female">Female</label>
                            </td>
                            <td><?php echo isset($errors["er_sex"]) ? $errors["er_sex"] : NULL; ?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Password</strong></td>
                            <td><input type="password" name="r_pass" value="<?php echo $r_pass; ?>" required></td>
                            <td><?php echo isset($errors["er_pass"]) ? $errors["er_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Repeat password</strong></td>
                            <td colspan="2"><input type="password" name="r_repass" value="<?php echo $r_repass; ?>" required></td>
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
						$sqlReg = "INSERT INTO `user` (`u_login`, `u_name`, `u_surname`, `u_email`, `u_pass`, `u_sex`, `u_avatar`, `u_date_reg`) VALUES ('$r_login', '$r_name', '$r_surname', '$r_email', SHA1('$r_pass'), '$r_sex', " . ($avatar_src ? "'$avatar_src'" : "DEFAULT") . ", NOW())";
						if ($regDb->db->exec($sqlReg)) {
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
