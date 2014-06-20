<header>
    <div id="top">
        <div class="left">
            <p>Ласкаво просимо, <strong><?php echo $_SESSION["admin_login"]; ?></strong> [ <a href="logout.php">Вихід</a> ]</p>
        </div>
        <div class="lang">
		    	<a href="<?php echo $_SERVER['SCRIPT_NAME'] . ($_SERVER['QUERY_STRING'] ? "?{$_SERVER['QUERY_STRING']}&" : '?') . 'lang=en'; ?>" class="en" title="English"></a>
    			<a href="<?php echo $_SERVER['SCRIPT_NAME'] . ($_SERVER['QUERY_STRING'] ? "?{$_SERVER['QUERY_STRING']}&" : '?') . 'lang=ua'; ?>" class="ua" title="Українська"></a>
		    </div>
    </div>
</header>