<?php
	session_start();
	if (!isset($_SESSION["admin_login"])) {
		header("Location: login.php");
  }
		
	include_once "../back-end/config/init.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin panel | Add translate</title>
    <link href="js/jquery-ui-1.10.4.custom/css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="js/edit_cat.js"></script>
    <script src="js/delete_cat.js"></script>
    <script src="js/add_cat.js"></script>
</head>
<body>

    <div class="wrap">
        <?php include_once "header.php"; ?>
        
        <div id="content">
            <?php include_once "left_sidebar.php"; ?>
            
            <div id="main" class="full">
                <div class="full_w">
                    <h2 class="h_title">Add translate</h2>
                    <?php
                      if (isset($_POST["go"])) {
                        try {
                          if ($translate->add_word()) {
                            echo "<p class=\"ok\">Word has been added</p>";
                          }
                        }
                        catch (Exception $e) {
                          echo "<p class=\"error\">". $e->getMessage() ."</p>";
                        }
                      }

                      $translate->form_add_word();
                    ?>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>

</body>
</html>
