<?php

function __autoload($class){
	$file_name = $_SERVER["DOCUMENT_ROOT"] ."/back-end/class/class.". $class .".php";
	if(file_exists($file_name))
		include_once $file_name;
}

?>