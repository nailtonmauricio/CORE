<?php

	$log = "[".date('d/m/Y H:i:s')."] [ERROR]: ";
	$log.= $_SERVER["REQUEST_URI"]."; ";
	$log.= $sqlString."; ";
	$log.= mysqli_error($conn).";\n";

	$directory = 'logs/';
	if(!is_dir($directory)){
		mkdir($directory, 0777, true);
		chmod($directory, 0777);
	}

	$fileName = $directory . "log".date('dmY').'.txt';
	$handle = fopen($fileName, 'a+');
	fwrite($handle, $log);
	fclose($handle);