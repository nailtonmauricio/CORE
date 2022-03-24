<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    if ($_SESSION['nva_user_id'] == 1) {
        $sql = "SELECT usuarios.id, usuarios.nome, usuarios.usuario,usuarios.email, usuarios.nva_id, nv_acessos.nome                        AS nv_nome FROM usuarios JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id ORDER BY usuarios.id";
    } else {
        $sql = "SELECT usuarios.id, usuarios.nome, usuarios.usuario,usuarios.email, usuarios.nva_id, nv_acessos.nome                        AS nv_nome FROM usuarios JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id 
                WHERE ordem > '".$_SESSION['ordem']."' AND usuarios.id = '$id'
                ORDER BY usuarios.id";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        //Apagando o usuário.
        $sql_del = "DELETE FROM usuarios WHERE id = '$id'";
        $result_del = mysqli_query($conn, $sql_del);
        if (mysqli_affected_rows($conn) > 0) {
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Usuário deletado com sucesso!</div>";
            $url_destino = pg . "/list/list_usuarios";
            header("Location: $url_destino");
        }
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Usuário não pode ser deletado!</div>";
        $url_destino = pg . "/list/list_usuarios";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Registro de usuário não encontrado!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}

