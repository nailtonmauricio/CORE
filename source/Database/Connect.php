<?php

namespace source\Database;
use PDO;
use PDOException;

class Connect
{
    private const HOST = "localhost";
    private const USER = "root";
    private const DBNAME = "core";
    private const PASSWD = "";
    private const OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ];

    private static PDO $instance;


    /**
     * @return PDO
     */
    public static function getInstance():PDO
    {
        if(empty(self::$instance)){
            try {
                self::$instance = new PDO(
                    "mysql:host=".self::HOST.";dbname=".self::DBNAME,
                    self::USER,
                    self::PASSWD,
                    self::OPTIONS
                );
            } catch (PDOException $e){
                var_dump($e ->getMessage());
                die("Erro ao conectar!");
            }
        }
        return self::$instance;
    }

    //PREVINE A CONSTRUÇÃO DE UM NOVO OBJETO PDO
    final private function __construct()
    {
    }

    //PREVINE O CLONE DA CLASSE PDO
    final private function __clone()
    {
    }
}