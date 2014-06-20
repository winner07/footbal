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
    <meta charset="utf-8">
    <title>Вихід</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div id="wrap">
        <div class="full_w">
        	<p class="error">Ви не входили для того щоб вийти</p>
        </div>
    </div>

</body>
</html>
<?php } ?>