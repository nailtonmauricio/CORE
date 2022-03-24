<?php

//Criar log de erro
$log = "[".date('d/m/Y H:i:s')."] [ERROR]: ".$erroLog."\n";
//Diretório onde os arquivos de log devem ser gravados
$directory = 'logs/';
if(!is_dir($directory)){
	mkdir($directory, 0777, true);
	chmod($directory, 0777);
}

//Nome do arquivo de log
$fileName = $directory . "SCD".date('dmY').'.txt';
$handle = fopen($fileName, 'a+');
fwrite($handle, $log);
fclose($handle);