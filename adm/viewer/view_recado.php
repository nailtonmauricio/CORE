<?php
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$post_id = filter_input(INPUT_GET, "post_id", FILTER_SANITIZE_NUMBER_INT);
$sender_id = filter_input(INPUT_GET, "sender_id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($post_id)) {
    $sql ="SELECT p.id, p.sender_id, p.message FROM posts AS p  WHERE p.id =:post_id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":post_id", $post_id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);

    var_dump(
        $row
    );
    if ($res ->rowCount()) {
        //$row = mysqli_fetch_assoc($result);
        ?>
        <div class="well content">
            <div class="pull-right">
                <?php
                $button_response = load('edit/edit_paginas', $conn);
                $button_list = load('list/list_recados', $conn);

                if ($button_list) {
                    echo "<a href= '" . pg . "/list/list_recados'><button type='button' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-list'></span> Listar</button></a> ";
                }
                if ($button_response) {
                    echo "<a href= '" . pg . "//register/reg_recados?post_id=".$post_id."&sender_id=" . $sender_id . "'><button type='button' class='btn btn-xs btn-warning'><span class='fas fa-reply'></span> Responder</button></a> ";
                }

                ?>
            </div>
            <div class="page-header"></div>
            <div class="dl-horizontal">
                <dt>Mensagem</dt>
                <dd><?= $row["message"]; ?></dd>
            </div>
        </div>
        <?php
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Registro não encontrado!</div>";
        header("Location: ".pg . "/list/list_recados");
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Registro não encontrado!</div>";
    header("Location: ".pg . "/list/list_recados");
}

