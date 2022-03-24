<?php

    //DDL
    try{
        $stmt = "DROP TABLE IF EXISTS `table_name`";
        $conn ->exec($stmt);

        $stmt = "CREATE TABLE IF NOT EXISTS `table_name`";
        $conn ->exec($stmt);
    } catch (PDOException $e){
        $e ->getFile();
        $e ->getLine();
        $e ->getMessage();
        setLog($e);

        var_dump(
            $e ->getFile(),
            $e ->getLine(),
            $e ->getMessage()
        );
    }

    //DML