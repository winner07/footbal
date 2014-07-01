<?php

session_start();

//set language
if (!isset($_SESSION['user_lang'])) {
	$_SESSION['user_lang'] = 'en';
} elseif (isset($_GET['lang'])) {
	$_SESSION['user_lang'] = $_GET['lang'];
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$translate = new Translate($_SESSION['user_lang']);

//auto load class
function __autoload($class) {
	$file_name = $_SERVER["DOCUMENT_ROOT"] . "/back-end/class/class." . $class . ".php";
	if(file_exists($file_name))
		include_once $file_name;
}

//user profile
$profile = new Profile();

?>
