<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;
    var_dump(
        $data
    );

    $sql_name_verify = "SELECT COUNT(id) AS count FROM users WHERE user_name =:name";
    $res_name_verify = $conn ->prepare($sql_name_verify);
    $res_name_verify ->bindValue(":name", $data["usuario"]);
    $res_name_verify ->execute();
    $row_name_verify = $res_name_verify ->fetch(PDO::FETCH_ASSOC);

    var_dump(
        $row_name_verify
    );
    if($row_name_verify["count"] >0){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Nome de usuário não pode ser utilizado.</div>";
        $url_return = pg . "/register/reg_usuarios";    }
    elseif(strlen($data["nome"]) < 4){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Nome de usuário deve ter no mínimo 4 caracteres e no máximo 15.</div>";
        $url_return = pg . "/register/reg_usuarios";
    }

    if($error){
        //CRIAR UMA SESÃO COM OS DADOS PASSADOS PELO FORM PARA QUE POSSA SER RETORNADO NO FORMULÁRIO

        header("Location: $url_return");
    } else {
        try {
            $sql = "INSERT INTO users (name, email, cell_phone, user_name, user_password, access_level) VALUES (:name, :email, :cell_phone, :user_name, :user_password, :access_level)";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":name", $data["nome"]);
            $res ->bindValue(":email", filter_var($data["email"], FILTER_VALIDATE_EMAIL)?$data["email"]:NULL);
            $res ->bindValue(":cell_phone", !empty($data["cel"])?str_replace(["(", ")", "-", " "], "", $data["cel"]): NULL);
            $res ->bindValue("user_name", mb_strtolower($data["usuario"]));
            $res ->bindValue(":user_password", password_hash($data["senha"], PASSWORD_DEFAULT));
            $res ->bindValue(":access_level", empty($data["nva_id"])?2:$data["nva_id"], PDO::PARAM_INT);
            $res ->execute();

            if($res ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Novo usuário cadastrado com sucesso.</div>";
                $url_return = pg . "/list/list_usuarios";
                header("Location: $url_return");
            }
        } catch (PDOException $e){
            echo $e ->getMessage();
        }
    }
}

/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $erro = false;
    $dados_validos = vdados($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Necessário preencher todos os campos.</div>";
    } elseif ((strlen($dados_validos['nome'])) < 6) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Digite seu nome completo!</div>";
    } elseif (!validaEmail($dados_validos['email'])) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Email inválido!</div>";
    } elseif ((strlen($dados_validos['senha'])) < 6) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "A senha deve conter mais que 6 caracteres!!</div>";
    } elseif ((strlen($dados_validos['usuario'])) < 4) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "O nome de Usuário deve conter no mínimo 6 caracteres!</div>";
    } else {
        //Bloquear cadastros repetidos usando como parâmetro o campo USUÁRIO
        $sql = "SELECT id FROM usuarios WHERE usuario = '" . $dados_validos['usuario'] . "' LIMIT 1";
        $result = mysqli_query($conn, $sql);

         if (mysqli_num_rows($result) > 0){
          $erro = true;
          $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
          . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
          . "<span aria-hidden='true'>&times;</span>"
          . "</button><strong>Aviso!&nbsp;</stron>"
          . "Usuário já cadastrado no banco de dados!</div>";
          } 
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/register/reg_usuarios";
        header("Location: $url_destino");
    } else {
        $dados_validos['senha'] = password_hash($dados_validos['senha'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios VALUES
        (DEFAULT, '" . $dados_validos['nome'] . "', '" . $dados_validos['email'] . "', '" . $dados_validos['celular'] . "', '" . strtoupper($dados_validos['usuario']) . "', '" . $dados_validos['senha'] . "', NULL, '" . $dados_validos['nva_id'] . "', '1', '".$dados['unidade_id']."', NOW(), NULL)";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        //var_dump($sql);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Usuário cadastrado com sucesso</div>";
            $url_destino = pg . "/list/list_usuarios";
            header("Location: $url_destino");
        } else {
            $_SESSION['dados'] = $dados;
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Erro ao cadastrar usuário, campos inválidos!(1)</div>";
            $url_destino = pg . "/register/reg_usuarios";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}*/

