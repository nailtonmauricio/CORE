<?php

/**
 * @param $path
 * @param $conn
 * @return bool
 */

function load ($path, $conn):bool
{
    $sql = "SELECT p.id, pal.page_id FROM page_access_level AS pal
    JOIN pages AS p ON p.id =  pal.page_id
    WHERE p.path =:url AND pal.access = 1
    AND pal.al_id =:nva_user_id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":url", $path, PDO::PARAM_STR);
    $res ->bindParam(":nva_user_id", $_SESSION["credentials"]["access_level"], PDO::PARAM_INT);
    $res ->execute();

    if ($res ->rowCount()) {
        return true;
    } else {
        return false;
    }
}

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
    return implode("-", array_reverse(explode("/", $date)))." ".$time;
}

/**
 * @param string $params
 * @param string $entity
 * @param PDO $conn
 * @return array
 */
function paginator(string $params, string $entity, PDO $conn):array
{
    $stmt = $conn ->query("SELECT {$params} FROM {$entity}");
    $stmt ->execute();
    return $stmt ->fetchAll(PDO::FETCH_OBJ);
}

/**
 * @param string $a
 * @return string
 */
function sanitizeString(string $a):string
{
    return rtrim(trim(mb_strtolower($a)));
}