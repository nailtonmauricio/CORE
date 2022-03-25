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
        $sql_pages = "CREATE TABLE `pages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(60) DEFAULT NULL,
          `path` varchar(220) NOT NULL UNIQUE,
          `description` varchar(500) DEFAULT NULL,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn ->exec($sql_pages);
        $insert_pages ="INSERT INTO `pages` (`id`, `name`, `path`, `description`, `created`, `modified`) VALUES
        (1, 'home', 'home', NULL, '2022-02-02 05:36:32', NULL),
        (2, 'paginas', 'list/list_paginas', NULL, '2022-02-03 01:09:02', NULL),
        (3, NULL, 'register/reg_paginas', NULL, '2022-02-03 04:13:09', NULL),
        (4, NULL, 'edit/edit_paginas', NULL, '2022-02-03 04:15:26', NULL),
        (5, NULL, 'viewer/view_paginas', NULL, '2022-02-03 04:20:00', NULL),
        (6, NULL, 'process/reg/reg_paginas', NULL, '2022-02-03 19:25:35', NULL),
        (7, NULL, 'process/edit/edit_paginas', NULL, '2022-02-03 19:35:35', NULL),
        (8, 'níveis acesso', 'list/list_niveis_acesso', NULL, '2022-02-04 17:29:08', NULL),
        (9, 'permissões', 'list/list_permissoes', NULL, '2022-02-04 18:32:07', NULL),
        (10, NULL, 'register/reg_niveis_acesso', NULL, '2022-02-05 01:39:06', NULL),
        (11, NULL, 'edit/edit_niveis_acesso', NULL, '2022-02-05 01:56:29', NULL),
        (12, NULL, 'viewer/view_niveis_acesso', NULL, '2022-02-05 01:57:35', NULL),
        (13, NULL, 'process/reg/reg_niveis_acesso', NULL, '2022-02-05 01:58:56', NULL),
        (14, NULL, 'process/edit/edit_niveis_acesso', NULL, '2022-02-05 01:59:37', NULL),
        (15, NULL, 'process/edit/edit_menu', NULL, '2022-02-07 20:51:34', NULL),
        (16, NULL, 'process/edit/edit_permissao', NULL, '2022-02-07 20:52:16', NULL),
        (17, 'usuários', 'list/list_usuarios', NULL, '2022-02-08 22:39:27', NULL),
        (18, NULL, 'register/reg_usuarios', NULL, '2022-02-08 23:09:22', NULL),
        (19, NULL, 'viewer/view_usuarios', NULL, '2022-02-08 23:12:08', NULL),
        (20, NULL, 'edit/edit_usuarios', NULL, '2022-02-08 23:12:32', NULL),
        (21, NULL, 'process/reg/reg_usuarios', NULL, '2022-02-09 01:46:00', NULL),
        (22, NULL, 'process/edit/proc_edit_usuario', NULL, '2022-02-09 01:46:25', NULL),
        (23, NULL, 'process/del/del_usuario', NULL, '2022-02-09 01:47:19', NULL),
        (24, NULL, 'backup', NULL, '2022-02-09 08:31:56', NULL),
        (25, 'agenda', 'list/list_events', NULL, '2022-02-10 00:15:00', NULL),
        (26, NULL, 'process/reg/reg_events', NULL, '2022-02-10 01:12:13', NULL),
        (27, '', 'register/reg_recados', NULL, '2022-02-23 16:38:23', NULL),
        (28, 'correio', 'list/list_recados', NULL, '2022-02-23 16:40:34', NULL),
        (29, NULL, 'process/reg/proc_reg_recados', NULL, '2022-02-24 21:09:26', NULL),
        (30, NULL, 'process/edit/edit_events', NULL, '2022-03-03 13:49:26', NULL),
        (31, NULL, 'process/edit/proc_edit_recados', NULL, '2022-03-04 16:49:25', NULL),
        (32, NULL, 'viewer/view_recado', NULL, '2022-03-04 19:26:28', NULL),
        (33, 'synchronize', 'process/synchronize/synchronize', NULL, '2022-03-24 17:53:53', NULL);";
        $conn ->exec($insert_pages);

        //Table access_level
        $sql_access_level = "CREATE TABLE `access_level` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(60) NOT NULL UNIQUE,
          `position` int(11) NOT NULL,
          `privilege` tinyint(1) DEFAULT 0,
          `situation` tinyint(1) DEFAULT 1,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn ->exec($sql_access_level);
        $insert_access_level = "INSERT INTO `access_level` (`id`, `name`, `position`, `privilege`,`situation`, `created`, `modified`) VALUES
        (1, 'admin', 1, 1, 1,'2022-02-01 19:20:11', NULL),
        (2, 'default', 2, 0, 1, '2022-02-01 19:20:11', NULL)";
        $conn ->exec($insert_access_level);

        //Table page_access_level
        $sql_page_access_level = "CREATE TABLE `page_access_level` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `al_id` int(11) NOT NULL,
          `page_id` int(11) NOT NULL,
          `access` tinyint(1) DEFAULT 0,
          `menu` tinyint(1) DEFAULT 0,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`al_id`) REFERENCES access_level (`id`),
          FOREIGN KEY (`page_id`) REFERENCES pages (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn ->exec($sql_page_access_level);
        $insert_page_access_level = "INSERT INTO `page_access_level` (`id`, `al_id`, `page_id`, `access`, `menu`, `created`, `modified`) VALUES
        (1, 1, 1, 1, 1, '2022-02-02 19:14:27', NULL),
        (2, 1, 2, 1, 1, '2022-02-02 19:33:48', NULL),
        (3, 1, 3, 1, 0, '2022-02-03 01:14:01', NULL),
        (4, 1, 4, 1, 0, '2022-02-03 01:16:08', NULL),
        (5, 1, 5, 1, 0, '2022-02-03 01:20:56', NULL),
        (6, 1, 6, 1, 0, '2022-02-03 01:24:28', NULL),
        (7, 1, 7, 1, 0, '2022-02-03 16:26:13', NULL),
        (8, 1, 8, 1, 1, '2022-02-04 14:30:18', NULL),
        (9, 1, 9, 1, 0, '2022-02-04 15:32:07', NULL),
        (10, 1, 10, 1, 0, '2022-02-04 22:39:06', NULL),
        (11, 1, 11, 1, 0, '2022-02-04 22:56:29', NULL),
        (12, 1, 12, 1, 0, '2022-02-04 22:57:35', NULL),
        (13, 1, 13, 1, 0, '2022-02-04 22:58:56', NULL),
        (14, 1, 14, 1, 0, '2022-02-04 22:59:37', NULL),
        (15, 1, 15, 1, 0, '2022-02-04 22:59:37', NULL),
        (16, 1, 16, 1, 0, '2022-02-04 22:59:37', NULL),
        (17, 1, 17, 1, 1, '2022-02-08 19:39:27', NULL),
        (18, 1, 18, 1, 0, '2022-02-08 20:09:22', NULL),
        (19, 1, 19, 1, 0, '2022-02-08 20:12:08', NULL),
        (20, 1, 20, 1, 0, '2022-02-08 20:12:32', NULL),
        (21, 1, 21, 1, 0, '2022-02-08 22:46:00', NULL),
        (22, 1, 22, 1, 0, '2022-02-08 22:46:25', NULL),
        (23, 1, 23, 1, 0, '2022-02-08 22:47:19', NULL),
        (24, 1, 24, 1, 0, '2022-02-09 05:31:56', NULL),
        (25, 1, 25, 1, 1, '2022-02-09 21:15:00', NULL),
        (26, 1, 26, 1, 0, '2022-02-09 22:12:13', NULL),
        (27, 1, 27, 1, 0, '2022-02-23 13:41:50', NULL),
        (28, 1, 28, 1, 1, '2022-02-23 13:41:50', NULL),
        (29, 1, 29, 1, 0, '2022-02-24 18:09:26', NULL),
        (30, 1, 30, 1, 0, '2022-03-03 10:49:26', NULL),
        (31, 1, 31, 1, 0, '2022-03-04 13:49:25', NULL),
        (32, 1, 32, 1, 0, '2022-03-04 16:26:28', NULL),
        (33, 1, 33, 1, 0, '2022-03-04 16:26:28', NULL),
        (34, 2, 1, 1, 1, '2022-02-02 22:14:27', NULL),
        (35, 2, 2, 0, 0, '2022-02-02 22:33:48', NULL),
        (36, 2, 3, 0, 0, '2022-02-03 04:14:01', NULL),
        (37, 2, 4, 0, 0, '2022-02-03 04:16:08', NULL),
        (38, 2, 5, 0, 0, '2022-02-03 04:20:56', NULL),
        (39, 2, 6, 0, 0, '2022-02-03 04:24:28', NULL),
        (40, 2, 7, 0, 0, '2022-02-03 19:26:13', NULL),
        (41, 2, 8, 0, 0, '2022-02-04 17:30:18', NULL),
        (42, 2, 9, 0, 0, '2022-02-04 18:32:07', NULL),
        (43, 2, 10, 0, 0, '2022-02-05 01:39:06', NULL),
        (44, 2, 11, 0, 0, '2022-02-05 01:56:29', NULL),
        (45, 2, 12, 0, 0, '2022-02-05 01:57:35', NULL),
        (46, 2, 13, 0, 0, '2022-02-05 01:58:56', NULL),
        (47, 2, 14, 0, 0, '2022-02-05 01:59:37', NULL),
        (48, 2, 15, 0, 0, '2022-02-05 01:59:37', NULL),
        (49, 2, 16, 0, 0, '2022-02-05 01:59:37', NULL),
        (50, 2, 17, 0, 0, '2022-02-08 22:39:27', NULL),
        (51, 2, 18, 0, 0, '2022-02-08 23:09:22', NULL),
        (52, 2, 19, 1, 0, '2022-02-08 23:12:08', NULL),
        (53, 2, 20, 1, 0, '2022-02-08 23:12:32', NULL),
        (54, 2, 21, 0, 0, '2022-02-09 01:46:00', NULL),
        (55, 2, 22, 1, 0, '2022-02-09 01:46:25', NULL),
        (56, 2, 23, 0, 0, '2022-02-09 01:47:19', NULL),
        (57, 2, 24, 1, 0, '2022-02-09 08:31:56', NULL),
        (58, 2, 25, 1, 1, '2022-02-10 00:15:00', NULL),
        (59, 2, 26, 1, 0, '2022-02-10 01:12:13', NULL),
        (60, 2, 27, 1, 0, '2022-02-23 16:41:50', NULL),
        (61, 2, 28, 1, 1, '2022-02-23 16:41:50', NULL),
        (62, 2, 29, 1, 0, '2022-02-24 21:09:26', NULL),
        (63, 2, 30, 1, 0, '2022-03-03 13:49:26', NULL),
        (64, 2, 31, 1, 0, '2022-03-04 16:49:25', NULL),
        (65, 2, 32, 1, 0, '2022-03-04 19:26:28', NULL),
        (66, 2, 33, 0, 0, '2022-03-04 19:26:28', NULL)";
        $conn ->exec($insert_page_access_level);

        //Table users
        $sql_users = "CREATE TABLE `users` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(220) NOT NULL,
          `email` varchar(220) DEFAULT NULL UNIQUE ,
          `cell_phone` varchar(11) DEFAULT NULL,
          `user_name` varchar(20) NOT NULL UNIQUE,
          `user_password` varchar(220) NOT NULL,
          `password_recover` varchar(220) DEFAULT NULL,
          `situation` tinyint(1) DEFAULT 1,
          `access_level` int(11) NOT NULL,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`access_level`) REFERENCES access_level (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn ->exec($sql_users);
        $insert_users ="INSERT INTO `users` (`id`, `name`, `email`, `cell_phone`, `user_name`, `user_password`, `password_recover`, `situation`, `access_level`, `created`, `modified`) VALUES
(1, 'system admin', 'suporte@nmatec.com.br', '83993348144', 'root', '$2y$10\$r2s9nIM3PUimirknEP19huOz0jWnMuWE8BBcyLiK061jtkOsNmSSe', NULL, 1, 1, '2022-02-01 19:18:57', NULL)";
        $conn ->exec($insert_users);

        //Table posts
        $sql_posts = "CREATE TABLE `posts` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `sender_id` int(11) NOT NULL,
          `recipient_id` int(11) NOT NULL,
          `message` varchar(500) NOT NULL,
          `verify` boolean DEFAULT false,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified`  timestamp NULL DEFAULT NULL, 
          PRIMARY KEY (`id`),
          FOREIGN KEY (`sender_id`) REFERENCES users (`id`),
          FOREIGN KEY (`recipient_id`) REFERENCES users (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn ->exec($sql_posts);

        //Table events
        $sql_events = "CREATE TABLE `events` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(220) NOT NULL,
          `color` varchar(10) NOT NULL,
          `start` datetime NOT NULL,
          `end` datetime NOT NULL,
          `user_id` int(11) NOT NULL,
          `description` text DEFAULT NULL,
          `created` timestamp NOT NULL DEFAULT current_timestamp(),
          `modified` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES users (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn ->exec($sql_events);


        header("Location: ../index.php");

    } catch (PDOException $e){
        echo $e ->getMessage();
    }
    $conn = null;
}
