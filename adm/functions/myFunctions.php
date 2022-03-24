<?php


/**
 * @param $msg
 * @return void
 */
function setLog($msg): void
{
    $log = "[" . date("d/m/Y H:i:s") . "] [ERROR]: " . $msg . "\n";
    $dir_name = __DIR__ . "/../../logs/";
    if (!is_dir($dir_name)) {
        mkdir($dir_name, 0777, true);
        chmod($dir_name, 0777);
    }

    $file_name = $dir_name . date("dmY") . ".txt";
    $handle = fopen($file_name, "a+");
    fwrite($handle, $log);
    fclose($handle);
}


/**
 * @param $a
 * @return string
 */
function convertDbDateTime($a): string
{
    list($date, $time) = explode(" ", $a);
    $db_date_time = implode("-", array_reverse(explode("/", $date)))." ".$time;
    return $db_date_time;
}