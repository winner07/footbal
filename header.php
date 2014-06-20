<header>
    <a href="/" class="logo"></a>
    <div class="lang">
    	<a href="<?php echo $_SERVER['SCRIPT_NAME'] . ($_SERVER['QUERY_STRING'] ? "?{$_SERVER['QUERY_STRING']}&" : '?') . 'lang=en'; ?>" class="en" title="English"></a>
    	<a href="<?php echo $_SERVER['SCRIPT_NAME'] . ($_SERVER['QUERY_STRING'] ? "?{$_SERVER['QUERY_STRING']}&" : '?') . 'lang=ua'; ?>" class="ua" title="Українська"></a>
    </div>
</header>