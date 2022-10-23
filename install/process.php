<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

    //criar validações para os campos

    //criar o banco de dados
    try {
        $conn = new PDO("mysql:host={$data["host_name"]}; charset={$data["charset"]}", $data["user_name"], $data["password"]);
        // set the PDO error mode to exception
        $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE {$data["db_name"]}";
        // use exec() because no results are returned
        $conn ->exec($sql);
        //echo "Database created successfully<br>";

    } catch(PDOException $e) {
        echo $e ->getMessage();
    } finally {
        //Cria o arquivo de configuração em /config/config.php
        if(!is_file(__DIR__ . "/config/config.txt")){
            $stmt = array("{$data["host_name"]}", "{$data["db_name"]}", "{$data["charset"]}", "{$data["user_name"]}", "{$data["password"]}");
            $config_file = implode(";", $stmt);

            $folder = __DIR__."/config/";
            if(!is_dir($folder)){
                mkdir($folder, 0777, true);
                chmod($folder, 0777);
            }
            //Criação do arquivo config.txt com os dados vindos pelo formulário de instalação
            $file = "{$folder}config.txt";
            $handle = fopen($file, "a+");
            fwrite($handle, $config_file);
            fclose($handle);
        }
    }
    try{
        //CREATE CONNECTION
        try {
            $conn = new PDO("mysql:host={$data["host_name"]}; dbname={$data["db_name"]}; charset={$data["charset"]}", $data["user_name"], $data["password"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e ->getMessage();
        }

        //Table pages
        try {
            $conn ->exec("CREATE TABLE IF NOT EXISTS `pages` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) DEFAULT NULL,
              `path` varchar(220) NOT NULL UNIQUE,
              `description` varchar(500) DEFAULT NULL,
              `created` timestamp NOT NULL DEFAULT current_timestamp(),
              `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

            try {
                $conn ->exec("INSERT INTO `pages` (`id`, `name`, `path`, `description`, `created`, `modified`) VALUES
                (1, 'home', 'home', DEFAULT, DEFAULT, DEFAULT),
                (2, 'paginas', 'list/pages', DEFAULT, DEFAULT, DEFAULT),
                (3, DEFAULT, 'register/page', DEFAULT, DEFAULT, DEFAULT),
                (4, DEFAULT, 'edit/page', DEFAULT, DEFAULT, DEFAULT),
                (5, DEFAULT, 'viewer/page', DEFAULT, DEFAULT, DEFAULT),
                (6, DEFAULT, 'process/reg/page', DEFAULT, DEFAULT, DEFAULT),
                (7, DEFAULT, 'process/edit/page', DEFAULT, DEFAULT, DEFAULT),
                (8, 'níveis acesso', 'list/access_levels', DEFAULT, DEFAULT, DEFAULT),
                (9, 'permissões', 'list/permissions', DEFAULT, DEFAULT, DEFAULT),
                (10, DEFAULT, 'register/access_level', DEFAULT, DEFAULT, DEFAULT),
                (11, DEFAULT, 'edit/access_level', DEFAULT, DEFAULT, DEFAULT),
                (12, DEFAULT, 'viewer/access_level', DEFAULT, DEFAULT, DEFAULT),
                (13, DEFAULT, 'process/reg/access_level', DEFAULT, DEFAULT, DEFAULT),
                (14, DEFAULT, 'process/edit/access_level', DEFAULT, DEFAULT, DEFAULT),
                (15, DEFAULT, 'process/edit/menu', DEFAULT, DEFAULT, DEFAULT),
                (16, DEFAULT, 'process/edit/permission', DEFAULT, DEFAULT, DEFAULT),
                (17, 'usuários', 'list/users', DEFAULT, DEFAULT, DEFAULT),
                (18, DEFAULT, 'register/user', DEFAULT, DEFAULT, DEFAULT),
                (19, DEFAULT, 'viewer/user', DEFAULT, DEFAULT, DEFAULT),
                (20, DEFAULT, 'edit/user', DEFAULT, DEFAULT, DEFAULT),
                (21, DEFAULT, 'process/reg/user', DEFAULT, DEFAULT, DEFAULT),
                (22, DEFAULT, 'process/edit/user', DEFAULT, DEFAULT, DEFAULT),
                (23, DEFAULT, 'backup', DEFAULT, DEFAULT, DEFAULT),
                (24, 'agenda', 'list/list_events', DEFAULT, DEFAULT, DEFAULT),
                (25, DEFAULT, 'process/reg/reg_events', DEFAULT, DEFAULT, DEFAULT),
                (26, DEFAULT, 'register/reg_recados', DEFAULT, DEFAULT, DEFAULT),
                (27, 'correio', 'list/list_recados', DEFAULT, DEFAULT, DEFAULT),
                (28, DEFAULT, 'process/reg/proc_reg_recados', DEFAULT, DEFAULT, DEFAULT),
                (29, DEFAULT, 'process/edit/edit_events', DEFAULT, DEFAULT, DEFAULT),
                (30, DEFAULT, 'process/edit/proc_edit_recados', DEFAULT, DEFAULT, DEFAULT),
                (31, DEFAULT, 'viewer/view_recado', DEFAULT, DEFAULT, DEFAULT),
                (32, 'synchronize', 'process/synchronize/synchronize', DEFAULT, DEFAULT, DEFAULT);");
            } catch (PDOException $e){
                setLog(
                    $e ->getMessage()
                );
            }
        } catch (PDOException $e){
            setLog(
                $e ->getMessage()
            );
        }

        //Table access_level
        try {
            $conn ->exec("CREATE TABLE IF NOT EXISTS `access_level` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(60) NOT NULL UNIQUE,
          `position` int(11) NOT NULL,
          `privilege` tinyint(1) DEFAULT 0,
          `situation` tinyint(1) DEFAULT 1,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            try{
                $conn ->exec("INSERT INTO `access_level` (`id`, `name`, `position`, `privilege`,`situation`, `created`, `modified`) VALUES
                (1, 'admin', 1, 1, 1,DEFAULT, DEFAULT),
                (2, 'default', 2, 0, 1, DEFAULT, DEFAULT);");
            } catch (PDOException $e){
                setLog(
                    $e ->getLine()
                );
            }
        } catch (PDOException $e){
            setLog(
                $e ->getMessage()
            );
        }

        //Table page_access_level
        try {
            $conn ->exec("CREATE TABLE IF NOT EXISTS `page_access_level` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `al_id` int(11) NOT NULL,
              `page_id` int(11) NOT NULL,
              `access` tinyint(1) DEFAULT 0,
              `menu` tinyint(1) DEFAULT 0,
              `created` timestamp NOT NULL DEFAULT current_timestamp(),
              `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              FOREIGN KEY (`al_id`) REFERENCES access_level (`id`),
              FOREIGN KEY (`page_id`) REFERENCES pages (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            try {
                $conn ->exec("INSERT INTO `page_access_level` (`id`, `al_id`, `page_id`, `access`, `menu`, `created`, `modified`) VALUES
                (1, 1, 1, 1, 1, DEFAULT, DEFAULT),
                (2, 1, 2, 1, 1, DEFAULT, DEFAULT),
                (3, 1, 3, 1, 0, DEFAULT, DEFAULT),
                (4, 1, 4, 1, 0, DEFAULT, DEFAULT),
                (5, 1, 5, 1, 0, DEFAULT, DEFAULT),
                (6, 1, 6, 1, 0, DEFAULT, DEFAULT),
                (7, 1, 7, 1, 0, DEFAULT, DEFAULT),
                (8, 1, 8, 1, 1, DEFAULT, DEFAULT),
                (9, 1, 9, 1, 0, DEFAULT, DEFAULT),
                (10, 1, 10, 1, 0, DEFAULT, DEFAULT),
                (11, 1, 11, 1, 0, DEFAULT, DEFAULT),
                (12, 1, 12, 1, 0, DEFAULT, DEFAULT),
                (13, 1, 13, 1, 0, DEFAULT, DEFAULT),
                (14, 1, 14, 1, 0, DEFAULT, DEFAULT),
                (15, 1, 15, 1, 0, DEFAULT, DEFAULT),
                (16, 1, 16, 1, 0, DEFAULT, DEFAULT),
                (17, 1, 17, 1, 1, DEFAULT, DEFAULT),
                (18, 1, 18, 1, 0, DEFAULT, DEFAULT),
                (19, 1, 19, 1, 0, DEFAULT, DEFAULT),
                (20, 1, 20, 1, 0, DEFAULT, DEFAULT),
                (21, 1, 21, 1, 0, DEFAULT, DEFAULT),
                (22, 1, 22, 1, 0, DEFAULT, DEFAULT),
                (23, 1, 23, 1, 0, DEFAULT, DEFAULT),
                (24, 1, 24, 1, 1, DEFAULT, DEFAULT),
                (25, 1, 25, 1, 0, DEFAULT, DEFAULT),
                (26, 1, 26, 1, 0, DEFAULT, DEFAULT),
                (27, 1, 27, 1, 1, DEFAULT, DEFAULT),
                (28, 1, 28, 1, 0, DEFAULT, DEFAULT),
                (29, 1, 29, 1, 0, DEFAULT, DEFAULT),
                (30, 1, 30, 1, 0, DEFAULT, DEFAULT),
                (31, 1, 31, 1, 0, DEFAULT, DEFAULT),
                (32, 1, 32, 1, 1, DEFAULT, DEFAULT),
                (33, 2, 1, 1, 1, DEFAULT, DEFAULT),
                (34, 2, 2, 0, 0, DEFAULT, DEFAULT),
                (35, 2, 3, 0, 0, DEFAULT, DEFAULT),
                (36, 2, 4, 0, 0, DEFAULT, DEFAULT),
                (37, 2, 5, 0, 0, DEFAULT, DEFAULT),
                (38, 2, 6, 0, 0, DEFAULT, DEFAULT),
                (39, 2, 7, 0, 0, DEFAULT, DEFAULT),
                (40, 2, 8, 0, 0, DEFAULT, DEFAULT),
                (41, 2, 9, 0, 0, DEFAULT, DEFAULT),
                (42, 2, 10, 0, 0, DEFAULT, DEFAULT),
                (43, 2, 11, 0, 0, DEFAULT, DEFAULT),
                (44, 2, 12, 0, 0, DEFAULT, DEFAULT),
                (45, 2, 13, 0, 0, DEFAULT, DEFAULT),
                (46, 2, 14, 0, 0, DEFAULT, DEFAULT),
                (47, 2, 15, 0, 0, DEFAULT, DEFAULT),
                (48, 2, 16, 0, 0, DEFAULT, DEFAULT),
                (49, 2, 17, 0, 0, DEFAULT, DEFAULT),
                (50, 2, 18, 0, 0, DEFAULT, DEFAULT),
                (51, 2, 19, 1, 0, DEFAULT, DEFAULT),
                (52, 2, 20, 1, 0, DEFAULT, DEFAULT),
                (53, 2, 21, 0, 0, DEFAULT, DEFAULT),
                (54, 2, 22, 1, 0, DEFAULT, DEFAULT),
                (55, 2, 23, 1, 0, DEFAULT, DEFAULT),
                (56, 2, 24, 1, 1, DEFAULT, DEFAULT),
                (57, 2, 25, 1, 0, DEFAULT, DEFAULT),
                (58, 2, 26, 1, 0, DEFAULT, DEFAULT),
                (59, 2, 27, 1, 1, DEFAULT, DEFAULT),
                (60, 2, 28, 1, 0, DEFAULT, DEFAULT),
                (61, 2, 29, 1, 0, DEFAULT, DEFAULT),
                (62, 2, 30, 1, 0, DEFAULT, DEFAULT),
                (63, 2, 31, 1, 0, DEFAULT, DEFAULT),
                (64, 2, 32, 0, 0, DEFAULT, DEFAULT);");
            } catch (PDOException $e){
                setLog(
                    $e ->getMessage()
                );
            }
        } catch (PDOException $e){
            setLog(
                $e ->getMessage()
            );
        }

        //Table users
        try {
            $conn ->exec("CREATE TABLE IF NOT EXISTS `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `first_name` varchar(220) NOT NULL,
              `last_name` varchar(220) NOT NULL,
              `email` varchar(220) NOT NULL UNIQUE ,
              `cell_phone` varchar(11) DEFAULT NULL,
              `user_name` varchar(20) NOT NULL UNIQUE,
              `user_password` varchar(220) NOT NULL,
              `password_recover` varchar(220) DEFAULT NULL,
              `situation` tinyint(1) DEFAULT 1,
              `access_level` int(11) NOT NULL,
              `created` timestamp NOT NULL DEFAULT current_timestamp(),
              `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              FOREIGN KEY (`access_level`) REFERENCES access_level (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

            try {
                $conn ->exec("INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `cell_phone`, `user_name`, `user_password`, `password_recover`, `situation`, `access_level`, `created`, `modified`) VALUES
                (1, 'root', 'system admin', 'dev@nmatec.com.br', DEFAULT, 'root', '$2y$10\$r2s9nIM3PUimirknEP19huOz0jWnMuWE8BBcyLiK061jtkOsNmSSe', DEFAULT, DEFAULT, 1, DEFAULT, DEFAULT);");
            } catch (PDOException $e){
                setLog(
                    $e ->getMessage()
                );
            }
        } catch (PDOException $e){
            setLog(
                $e ->getMessage()
            );
        }

        //Table posts
        try {
            $conn ->exec("CREATE TABLE IF NOT EXISTS `posts` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `sender_id` int(11) NOT NULL,
              `recipient_id` int(11) NOT NULL,
              `message` varchar(500) NOT NULL,
              `verify` boolean DEFAULT false,
              `created` timestamp NOT NULL DEFAULT current_timestamp(),
              `modified`  timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(), 
              PRIMARY KEY (`id`),
              FOREIGN KEY (`sender_id`) REFERENCES users (`id`),
              FOREIGN KEY (`recipient_id`) REFERENCES users (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        } catch (PDOException $e){
            setLog(
                $e ->getMessage()
            );
        }

        //Table events
        try {
            $conn ->exec("CREATE TABLE `events` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(220) NOT NULL,
              `color` varchar(10) NOT NULL,
              `start` datetime NOT NULL,
              `end` datetime NOT NULL,
              `user_id` int(11) NOT NULL,
              `description` text DEFAULT NULL,
              `created` timestamp NOT NULL DEFAULT current_timestamp(),
              `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              FOREIGN KEY (`user_id`) REFERENCES users (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        } catch (PDOException $e){
            setLog(
                $e ->getMessage()
            );
        }

        header("Location: ../index.php");

    } catch (PDOException $e){
        echo $e ->getMessage();
    }
    $conn = null;
}
