<?php
session_start();
ob_start();
include_once "back-end/config/init.php";

function auth(){
	if(isset($_SESSION["user_login"])){
		throw new Exception("�� ��� ��������������");
	} else {
		//���� ����� ����������
		if(isset($_POST["auth_go"])){
			//��� �����������
			$auth_login = strip_tags(trim($_POST["auth_login"]));
			$auth_pass  = strip_tags(trim($_POST["auth_pass"]));
			//�'������� � ����� �����
			$reg_db = new DB_connect();
			//�������� ��������� �����
			$existLogin = $reg_db->db->query("SELECT COUNT(*) from `user` where `u_login` = '$auth_login'")->fetchColumn(0);
			if($existLogin){
				//�������� ����� � ������
				$auth_accesss = $reg_db->db->query("SELECT COUNT(*) FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass');")->fetchColumn(0);
				if($auth_accesss){
					$_SESSION["user_login"] = $reg_db->db->query("SELECT `u_login` FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass');")->fetchColumn(0);
					$_SESSION["user_id"] = $reg_db->db->query("SELECT `u_id` FROM `user` WHERE `u_login` = '$auth_login' AND `u_pass` = SHA1('$auth_pass');")->fetchColumn(0);
					header("Location: index.php");
				} else {
					throw new Exception("������� ������", 1);
				}
			}
			else {
				throw new Exception("������� ����", 1);
			}
		} else {
			throw new Exception("�� �� ��������� ���� � ������", 1);
		}
	}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="windows-1251">
	<link href="front-end/css/style.css" rel="stylesheet">
	<title>������ + | �����������</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
            	<?php
				try{
					auth();
					ob_end_flush();
				} catch(Exception $e){
					if($e->getCode() == 1){
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