<?php

$url_host = filter_input(INPUT_SERVER, "HTTP_HOST");
define("pg", "http://$url_host/CORE/adm");

const DHBR = "d/m/Y H:i:s";
const DBR = "d/m/Y";

/*var_dump(
    scandir("../install")
);*/

$handle = fopen("../install/config/config.txt", "r");
$result = explode(";", fgets($handle));
fclose($handle);

$host = $result[0];
$db_name = $result[1];
$charset = $result[2];
$user = $result[3];
$pwd = $result[4];

//CREATE CONNECTION
try {
    $conn = new PDO("mysql:host=$host; dbname=$db_name; charset=$charset", $user, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    setLog($e ->getFile());
    setLog($e ->getLine());
    setLog($e ->getMessage());
}
