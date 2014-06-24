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
            			//Об'єкт користувача
									$profile = new Profile($_SESSION['user_id']);
									//Змінна відправки форми
									$e_go    = $_POST["e_go"];
									//Масив помилок
									$errors  = array();
									
									//Якщо форма відправлена
									if (isset($e_go)) {
										//Змінні форми
										$e_name       = strip_tags(trim($_POST["e_name"]));
										$e_surname    = strip_tags(trim($_POST["e_surname"]));
										$e_email      = strip_tags(trim($_POST["e_email"]));
										$e_sex        = strip_tags(trim($_POST["e_sex"]));
										$e_old_pass   = strip_tags(trim($_POST["e_old_pass"]));
										$e_new_pass   = strip_tags(trim($_POST["e_new_pass"]));
										$e_new_repass = strip_tags(trim($_POST["e_new_repass"]));
										$e_avatar     = $_FILES["e_avatar"];

										//Перевірка правильності імені
										if (strlen($e_login) < 2) {
											$errors["er_name"] = "Too short name!";
										}
										if (strlen($e_login) >= 100) {
											$errors["er_name"] = "Too long name!";
										}
										//Перевірка правильності фамілії
										if (strlen($e_surname) < 2) {
											$errors["er_name"] = "Too short surname!";
										}
										if (strlen($e_surname) >= 100) {
											$errors["er_name"] = "Too long surname!";
										}
										//Перевірка правильності імейлу
										if ($e_email && strlen($e_email) <= 100) {
											$exist_email = $edit_db->db->query("SELECT COUNT(*) from `user` where `u_email` = '$e_email'")->fetchColumn(0);
											if ($exist_email) {
												$errors["er_email"] = "This e-mail is exists!";
											}
										}
										else {
											$errors["er_email"] = "Too long e-mail!";
										}
										//Перевірка правильності пароля
										if (strlen($e_pass) >= 6 && strlen($e_pass) <= 100) {
											if (strcmp($e_pass, $e_repass) != 0) {
												$errors["er_pass"] = "Passwords do not match!!";
											}
										}
										else {
											$errors["er_pass"] = "The password must be at least 6 and no more than 100 characters!";
										}
										//Перевірка правильності статі
										if ($e_sex != "1" && $e_sex != "2") {
											$errors["er_sex"] = "Do not put sex right";
										}
										//Перевірка правильності аватара
										if ($e_avatar["error"] != 4) {
											switch ($e_avatar["error"]) {
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
								?>
                <form method="post" action="" enctype="multipart/form-data">
                    <table class="reg_table">
                        <tr>
                            <td><strong>Name</strong></td>
                            <td colspan="2"><input type="text" name="e_name" value="<?php echo $profile->user_info['u_name']; ?>" required max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>Surname</strong></td>
                            <td colspan="2"><input type="text" name="e_surname" value="<?php echo $profile->user_info['u_surname']; ?>" required max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>E-mail</strong></td>
                            <td><input type="email" name="e_email" value="<?php echo $profile->user_info['u_email']; ?>" max="100" required></td>
                            <td><?php echo isset($errors["er_email"]) ? $errors["er_email"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Sex</strong></td>
                            <td><input type="radio" name="e_sex" <?php echo $profile->user_info['u_sex'] == "Male" ? "checked" : NULL; ?> value="1" id="male"><label for="male">Male</label>
                                <br>
                                <input type="radio" name="e_sex" <?php echo $profile->user_info['u_sex'] == "Female" ? "checked" : NULL; ?> value="2" id="female"><label for="female">Female</label>
                            </td>
                            <td><?php echo isset($errors["er_sex"]) ? $errors["er_sex"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Old password</strong></td>
                            <td><input type="password" name="e_old_pass" value="<?php echo $e_old_pass; ?>" required></td>
                            <td><?php echo isset($errors["er_old_pass"]) ? $errors["er_old_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>New password</strong></td>
                            <td><input type="password" name="e_new_pass" value="<?php echo $e_new_pass; ?>" required></td>
                            <td><?php echo isset($errors["er_new_pass"]) ? $errors["er_new_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Repeat password</strong></td>
                            <td colspan="2"><input type="password" name="e_new_repass" value="<?php echo $e_new_repass; ?>" required></td>
                        </tr>
                        <tr>
                            <td><strong>Avatar (150 * 150) 100 kb</strong></td>
                            <td>
                            	<input type="hidden" name="MAX_FILE_SIZE" value="51200">
                            	<input type="file" name="e_avatar" accept="image/*">
                            </td>
                            <td><?php echo isset($errors["er_avatar"]) ? $errors["er_avatar"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="submit" name="e_go" value="Edit"></td>
                        </tr>
                    </table>
                </form>
                <?php
					/*} else {
						//Переміщення аватара в підходящу папку
						if ($e_avatar && $e_avatar["error"] == 0) {
							$avatar_src = "back-end/load_img/". $e_avatar["name"];
							move_uploaded_file($e_avatar["tmp_name"], $avatar_src);
						}
						//Запис у базу даних
						$sqlReg = "INSERT INTO `user` (`u_login`, `u_name`, `u_surname`, `u_email`, `u_pass`, `u_sex`, `u_avatar`, `u_date_reg`) VALUES ('$e_login', '$e_name', '$e_surname', '$e_email', SHA1('$e_pass'), '$e_sex', " . ($avatar_src ? "'$avatar_src'" : "DEFAULT") . ", NOW())";
						if ($edit_db->db->exec($sqlReg)) {
							echo "<p class=\"ok\">Registration was successful, you can log in to your account</p>";
						} else {
							echo "<p class=\"error\">Error registering again later</p>";
						}
					}
				}*/
				?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>
