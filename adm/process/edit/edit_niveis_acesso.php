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
    $sql_search = "SELECT COUNT(id) AS count FROM access_level WHERE name =:name";
    $res_search = $conn ->prepare($sql_search);
    $res_search ->bindValue(":name", $data["nome"]);
    $res_search ->execute();
    $row_search = $res_search ->fetch(PDO::FETCH_ASSOC);

    var_dump(
        $row_search
    );

    if($row_search["count"]){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Nível de acessesso já cadastrado no banco de dados!</div>";
        $url_return = pg . "/list/list_niveis_acesso";
    } else {
        if(empty($data["nome"])|strlen($data["nome"])<4){
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</stron>"
                . "Novo nome deve conter no mínimo 4 caracters</div>";
            $url_return = pg . "/edit/edit_niveis_acesso?id={$data["id"]}";
        } else {
            try{
                $sql_update = "UPDATE access_level SET name =:name, modified = CURRENT_TIMESTAMP WHERE id =:id";
                $res_update = $conn ->prepare($sql_update);
                $res_update ->bindValue(":name", $data["nome"]);
                $res_update ->bindValue(":id", $data["id"], PDO::PARAM_INT);
                $res_update ->execute();

                if($res_update ->rowCount()){
                    $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Aviso!&nbsp;</stron>"
                        . "Nome do nível de acesso alterado com sucesso</div>";
                    $url_return = pg . "/list/list_niveis_acesso";
                    header("Location: $url_return");
                }
            } catch (PDOException $e){
                //Criar log
                //Criar log de erro
                $log = "[".date("d/m/Y H:i:s")."] [ERROR]: ".$e ->getMessage()."\n";
                //Diretório onde os arquivos de log devem ser gravados
                $directory = 'logs/';
                if(!is_dir($directory)){
                    mkdir($directory, 0777, true);
                    chmod($directory, 0777);
                }

                //Nome do arquivo de log
                $fileName = $directory . "SCD".date('dmY').'.txt';
                $handle = fopen($fileName, 'a+');
                fwrite($handle, $log);
                fclose($handle);

                $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Whoops!&nbsp;</stron>"
                    . "Algo errado ocorreu, verifique o arquivo de log.</div>";
                $url_return = pg . "/list/list_niveis_acesso";
                $error = true;
            }
        }
    }

    if($error){
        header("Location: $url_return");
    }
}

/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $erro = false;
    $dados_validos = vdados($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Necessário preencher todos os campos.</div>";
    } elseif ((strlen($dados_validos['nome'])) < 6) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Digite seu nome completo!</div>";
    }  else {
        //Bloquear cadastros repetidos usando como parâmetro o campo USUÁRIO
        $sql = "SELECT id FROM usuarios WHERE usuario = '" . $dados_validos['usuario'] . "' AND id <> '".$dados['id']."' LIMIT 1";
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
         $url_destino = pg . "/edit/edit_usuarios?id='".$dados['id']."'";
        header("Location: $url_destino");
    } else {
        echo "update habilitado";

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