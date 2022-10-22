<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    // Pesquisar os dados da tabela nva_paginas.
    $sql = "SELECT pal.al_id, pal.menu FROM page_access_level AS pal WHERE id =:id LIMIT 1";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":id", $id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);

    var_dump(
        $row
    );

    if ($res ->rowCount()) {

        if ($row["menu"] == 1) {
            $permission = 0;
        } else {
            $permission = 1;
        }
        $sql_update = "UPDATE page_access_level AS pal SET pal.menu =:permission, modified = CURRENT_TIMESTAMP WHERE pal.id =:id";
        $res_update = $conn ->prepare($sql_update);
        $res_update ->bindValue(":permission", $permission, PDO::PARAM_INT);
        $res_update ->bindValue(":id", $id, PDO::PARAM_INT);
        $res_update ->execute();

        var_dump(
            $res_update
        );

        if ($res_update ->rowCount()) {
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Menu alterado com sucesso!</div>";
            $url_destino = pg . "/list/permissions?acid={$row["al_id"]}";
            header("Location: $url_destino");
        } else {
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Não foi possível alterar o menu!</div>";
            $url_destino = pg . "/list/permissions?acid={$row["al_id"]}";
            header("Location: $url_destino");
        }
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Não foi possível alterar o menu!</div>";
        $url_destino = pg . "/list/access_levels?id={$row["al_id"]}";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Selecione uma página!</div>";
    $url_destino = pg . "/list/access_levels?id={$row["al_id"]}";
    header("Location: $url_destino");
}