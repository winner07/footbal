<?php

if($_FILES["upload"]){
	if(($_FILES["upload"] == "none") || (empty($_FILES["upload"]["name"]))){
		$message = "�� �� ������ ����";
	} else if($_FILES["upload"]["size"] == 0 || $_FILES["upload"]["size"] == 2050000){
		$message = "����� ����� �� ������� ������";
	} else if(($_FILES["upload"]["type"] != "image/jpeg") && ($_FILES["upload"]["type"] != "image/png")){
		$message = "����������� ������������ ����� �������� JPG � PNG.";
	} else if (!is_uploaded_file($_FILES["upload"]["tmp_name"])){
		$message = "���� ���� �� ���. ��������� ����������� ���� �� ���.";
	} else {
		$name = rand(1, 9000) ."-". $_FILES["upload"]["name"];
		move_uploaded_file($_FILES["upload"]["tmp_name"], "../back-end/load_img/". $name);
		$full_path = "http://". $_SERVER["SERVER_NAME"] ."/back-end/load_img/". $name;
		$message = "���� ". $_FILES["upload"]["name"] ." ������������";
	}
	$callback = $_REQUEST["CKEditorFuncNum"];
	echo "<script>window.parent.CKEDITOR.tools.callFunction('".$callback."', '".$full_path."', '".$message."');</script>";
}

?>
