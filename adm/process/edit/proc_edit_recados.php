<?php
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
if(!empty($id)){
    $sql = "SELECT COUNT(p.id) AS t FROM posts AS p WHERE p.id = :id AND p.recipient_id = :recipient_id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":id", $id, PDO::PARAM_INT);
    $res ->bindValue(":recipient_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);

    if($row["t"] == 1){
        try{
            $sql = "UPDATE posts SET verify = true, modified = CURRENT_TIMESTAMP WHERE id =:id";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":id", $id, PDO::PARAM_INT);
            $res ->execute();

            if($res ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Leitura confirmada com sucesso!</div>";
                header("Location: ".pg . "/list/list_recados");
            }
        } catch (PDOException $e){
            setLog($e ->getFile());
            setLog($e ->getMessage());
        }
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "O id de registro não pertence ao usuário logado</div>";
        $url_return = pg . "/list/list_recados";
        header("Location: $url_return");
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Registro de mensagem não encontrado!</div>";
    $url_return = pg . "/list/list_recados";
    header("Location: $url_return");
}