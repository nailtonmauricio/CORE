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
    $sql_nv = "SELECT id FROM usuarios WHERE nva_id = '$id' LIMIT 1";
    $result_nv = mysqli_query($conn, $sql_nv);
    if (mysqli_num_rows($result_nv) > 0) {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Nível de Acesso não pode ser apagado, existem ocorrências de usuários para este nível de acesso.</div>";
        $url_destino = pg . "/list/list_niveis_acesso";
        header("Location: $url_destino");
    } else {
        $delete = TRUE;
        if ($_SESSION['nva_user_id'] != 1) {
            $sql_nvUser = "SELECT id FROM nv_acessos WHERE ordem > (SELECT ordem FROM nv_acessos WHERE id = '" . $_SESSION['nva_user_id'] . "')";
            $result_nvUser = mysqli_query($conn, $sql_nvUser);
            if (mysqli_num_rows($result_nvUser) == 0) {
                $del = FALSE;
                $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Aviso!&nbsp;</stron>"
                        . "Este usuário não pode deletar níveis de acesso superiores!</div>";
                $url_destino = pg . "/list/list_niveis_acesso";
                header("Location: $url_destino");
            }
        }
        if ($delete) {
            //Verifica se no banco de dados há uma ordem mais alta para poder reordenar.
            $sql_reOrdenar = "SELECT id, ordem FROM nv_acessos WHERE ordem > (SELECT id FROM nv_acessos
                              WHERE id = '$id') ORDER BY ordem ASC";
            $result_reOrdenar = mysqli_query($conn, $sql_reOrdenar);
            //Apagando o nível de acesso.
            $sql_del = "DELETE FROM nv_acessos WHERE id = '$id'";
            $result_del = mysqli_query($conn, $sql_del);
            if (mysqli_affected_rows($conn)) {
                if (mysqli_num_rows($result_reOrdenar)>0){
                    while ($row_reOrdenar = mysqli_fetch_array($result_reOrdenar)){
                        $row_reOrdenar['ordem'] = $row_reOrdenar['ordem'] -1;
                        $sql_ordemUpdate = "UPDATE nv_acessos SET ordem = '".$row_reOrdenar['ordem']."' WHERE id = '".$row_reOrdenar['id']."'";
                        $result_ordemUpdate = mysqli_query($conn, $sql_ordemUpdate);
                    }
                }
                $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Aviso!&nbsp;</stron>"
                        . "Nível de acesso apagado com sucesso!</div>";
                $url_destino = pg . "/list/list_niveis_acesso";
                header("Location: $url_destino");
            }
        }
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Não é possível apagar este nível de acesso, há ocorrências de usuários para este nível!</div>";
    $url_destino = pg . "/list/list_niveis_acesso";
    header("Location: $url_destino");
}

