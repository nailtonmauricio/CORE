<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
    var_dump(
        $data
    );

    $sql_info = "SELECT name , path, description FROM pages WHERE id =:id";
    $res_info = $conn ->prepare($sql_info);
    $res_info ->bindValue(":id", $data["id"], PDO::PARAM_INT);
    $res_info ->execute();
    $row_info = $res_info ->fetch(PDO::FETCH_ASSOC);
    var_dump(
        $row_info
    );

    if(empty($data["name"])?NULL:$data["name"] !== $row_info["name"]){
        $sql = "UPDATE pages SET name =:name, modified = CURRENT_TIMESTAMP WHERE id =:id";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":name", empty($data["name"])?NULL:$data["name"]);
        $res ->bindValue(":id", $data["id"], PDO::PARAM_INT);
        $res ->execute();
        $res ->debugDumpParams();
    }
    elseif ($data["path"] !== $row_info["path"]){
        $sql = "UPDATE pages SET path =:path, modified = CURRENT_TIMESTAMP WHERE id =:id";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":path", empty($data["path"])?NULL:$data["path"]);
        $res ->bindValue(":id", $data["id"], PDO::PARAM_INT);
        $res ->execute();
        $res ->debugDumpParams();
    }
    elseif (empty($data["description"])?NULL:$data["description"] !== $row_info["description"]){
        $sql = "UPDATE pages SET description =:description, modified = CURRENT_TIMESTAMP WHERE id =:id";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":description", empty($data["description"])?NULL:$data["description"]);
        $res ->bindValue(":id", $data["id"], PDO::PARAM_INT);
        $res ->execute();
        $res ->debugDumpParams();
    }

    $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Página atualizada com sucesso!</div>";
    $url_return = pg . "/list/list_paginas";
    header("Location: $url_return");
}