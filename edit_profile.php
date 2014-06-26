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
            			$edit_db = new DB_connect();
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
										$e_old_pass   = strip_tags(trim($_POST["e_old_pass"]));
										$e_new_pass   = strip_tags(trim($_POST["e_new_pass"]));
										$e_new_repass = strip_tags(trim($_POST["e_new_repass"]));
										$e_avatar     = $_FILES["e_avatar"];
										$set_new_em   = false;
										$set_new_pass = false;
										$set_new_av   = false;

										//Перевірка правильності імені
										if (strlen($e_name) < 2) {
											$errors["er_name"] = "Too short name!";
										}
										if (strlen($e_name) >= 100) {
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
											$not_cur_email = $edit_db->db->query("SELECT COUNT(*) from `user` where `u_email` = '$e_email' AND `u_id` = {$_SESSION['user_id']}")->fetchColumn(0);
											if (!$not_cur_email) {
												$exist_email = $edit_db->db->query("SELECT COUNT(*) from `user` where `u_email` = '$e_email'")->fetchColumn(0);
												if ($exist_email) {
													$errors["er_email"] = "This e-mail is exists!";
												} else {
													$set_new_em = !$set_new_em;
												}
											}
										}
										else {
											$errors["er_email"] = "Too long e-mail!";
										}
										//Перевірка правильності пароля
										if ($e_old_pass && strlen($e_old_pass) >= 6 && strlen($e_old_pass) <= 100) {
											$exist_pass = $edit_db->db->query("SELECT COUNT(*) from `user` where `u_pass` = SHA1('$e_old_pass')")->fetchColumn(0);
											if ($exist_pass) {
												if (strlen($e_new_pass) >= 6 && strlen($e_new_pass) <= 100) {
													if (strcmp($e_new_pass, $e_new_repass) == 0) {
														$set_new_pass = !$set_new_pass;
													}
													else {
														$errors["er_new_pass"] = "Passwords do not match";
													}
												}
												else {
													$errors["er_new_pass"] = "The password must be at least 6 and no more than 100 characters!";
												}
											}
											else {
												$errors["er_pass"] = "Password is incorrect";
											}
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

										//Відправка змін в базу
										if (!count($errors)) {
											//Переміщення аватара в підходящу папку
											if ($e_avatar && $e_avatar["error"] == 0) {
												$avatar_src = "back-end/load_img/". $e_avatar["name"];
												move_uploaded_file($e_avatar["tmp_name"], $avatar_src);
												$set_new_av = !$set_new_av;
											}
											//Запис у базу даних
											$sql_edit = "UPDATE `user` SET `u_name` = '$e_name', `u_surname` = '$e_surname'" .
												($set_new_em ? ", `u_email` = '$e_email'" : NULL) .
												($set_new_pass ? ", `u_pass` = SHA1('$e_new_pass')" : NULL) . 
											  ($set_new_av ? ", `u_avatar` = '$avatar_src'" : NULL) . " WHERE `u_id` = {$_SESSION['user_id']}";

											if ($edit_db->db->exec($sql_edit)) {
												echo "<p class=\"ok\">Updated has been successful</p>";
											} else {
												echo "<p class=\"error\">Updated has been error</p>";
											}
										}
									}
								?>
                <form method="post" action="" enctype="multipart/form-data">
                    <table class="reg_table">
                        <tr>
                            <td><strong>Name</strong></td>
                            <td colspan="2"><input type="text" name="e_name" value="<?php echo $profile->user_info['u_name']; ?>" max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>Surname</strong></td>
                            <td colspan="2"><input type="text" name="e_surname" value="<?php echo $profile->user_info['u_surname']; ?>" max="100"></td>
                        </tr>
                        <tr>
                            <td><strong>E-mail</strong></td>
                            <td><input type="email" name="e_email" value="<?php echo $profile->user_info['u_email']; ?>" max="100"></td>
                            <td><?php echo isset($errors["er_email"]) ? $errors["er_email"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Old password</strong></td>
                            <td><input type="password" name="e_old_pass" value=""></td>
                            <td><?php echo isset($errors["er_old_pass"]) ? $errors["er_old_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>New password</strong></td>
                            <td><input type="password" name="e_new_pass" value=""></td>
                            <td><?php echo isset($errors["er_new_pass"]) ? $errors["er_new_pass"] : NULL; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Repeat password</strong></td>
                            <td colspan="2"><input type="password" name="e_new_repass" value=""></td>
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
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>
