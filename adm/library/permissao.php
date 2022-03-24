<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: ../../index.php");
}


function load ($endereco, $conn){
    $sql = "SELECT p.id, pal.page_id FROM page_access_level AS pal
    JOIN pages AS p ON p.id =  pal.page_id
    WHERE p.path =:url AND pal.access = 1
    AND pal.al_id =:nva_user_id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":url", $endereco, PDO::PARAM_STR);
    $res ->bindParam(":nva_user_id", $_SESSION["credentials"]["access_level"], PDO::PARAM_INT);
    $res ->execute();

    if ($res ->rowCount()) {
        return true;
    } else {
        return false;
    }
}
