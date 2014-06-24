<?php
include_once "back-end/config/init.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="urf-8">
	<link href="front-end/css/style.css" rel="stylesheet">
    <link href="front-end/css/page_nav.css" rel="stylesheet">
	<title>Football + | Profile</title>
</head>

<body>

	<div id="wrap">
    	<?php include_once "header.php"; ?>
        
        <div id="inner_wrap">
        	<?php include_once "left_sidebar.php"; ?>
            
            <div id="content">
              <?php
                $user = new Profile($_GET['id']);
                $user->get_full_info();
              ?>
            </div>
            
            <?php include_once "right_sidebar.php"; ?>
        </div>
    </div>

</body>
</html>