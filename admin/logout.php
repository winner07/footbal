<?php

session_start();

if(isset($_SESSION["admin_login"])){
	session_destroy();
	header("Location: login.php");
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <title>�����</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div id="wrap">
        <div class="full_w">
        	<p class="error">�� �� ������� ��� ���� ��� �����</p>
        </div>
    </div>

</body>
</html>
<?php } ?>