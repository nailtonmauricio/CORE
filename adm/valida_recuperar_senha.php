<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include_once("config/conn.php");
    include_once("config/config.php");
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


    $newSenha = $dados['senha'];
    $key = $dados['key'];

    if (strlen($newSenha) < 6) {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "A senha deve ter mais que seis caracteres.</div>";
        header("Location: atualizar_senha.php");
    } else {

        if (!empty($key)) {

            $sql = "SELECT id FROM usuarios WHERE recuperar_senha = '$key' LIMIT 1";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
        }

        $dados ['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

        if(strlen($dados['senha']) < 6){

            $_SESSION['dados'] = $dados;
            $_SESSION['msg_key'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Aviso!&nbsp;</stron>"
                        . "Senha deve ter no mínimo seis caracteres.</div>";
            $url_destino = pg . "/atualizar_senha.php";
            header("Location: $url_destino");
        } else {

            $sql = "UPDATE usuarios SET
                    senha ='" . $dados['senha'] . "',
                    recuperar_senha = NULL,
                    modified = NOW()
                    WHERE id='" . $row['id'] . "'";
            $result = mysqli_query($conn, $sql);

            if ($result) {

                unset($_SESSION['dados']);
                $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Aviso!&nbsp;</stron>"
                        . "Senha atualizada com sucesso.</div>";
                $url_destino = pg . "/index.php";
                header("Location: $url_destino");
            }
        }
    }
}