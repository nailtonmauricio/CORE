<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}


if ($_SERVER ["REQUEST_METHOD"] == "POST") {
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;

    $data["start"] = convertDbDateTime($data["start"]);
    $data["end"]   = convertDbDateTime($data["end"]);

    var_dump(
        $data,
        $error
    );

    try{
        $sql = "UPDATE events SET title =:title, color =:color, start =:start, end =:end, description =:description, modified = current_timestamp WHERE id =:id";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":id", $data["id"], PDO::PARAM_INT);
        $res ->bindValue(":title", $data["title"]);
        $res ->bindValue(":color", $data["color"]);
        $res ->bindValue(":start", $data["start"]);
        $res ->bindValue(":end", $data["end"]);
        $res ->bindValue(":description", !empty($data["description"])?preg_replace("/(&#13;&#10;)/"," ", $data["description"]):NULL);
        $res ->execute();
        header("Location:".pg."/list/list_events");

    } catch (PDOException $e){
        setLog($e ->getFile());
        setLog($e ->getMessage());
        $error = true;
    }

    if($error){

        header("Location:".pg."/list/list_events");
    }
} else {
    // Este esle é chamado caso o botão cadastrar evendo não seja clicado para acionar a página.
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Seu nível de acesso não permite a execução deste recurso!</div>";
    $url_return = pg . "/list/list_events";
    header("Location: $url_return");
}