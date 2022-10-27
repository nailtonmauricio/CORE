<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = (object)filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;
    var_dump($data);

    if($data ->first_name != $_SESSION["user_edit"] ->first_name){
        if(empty($data ->first_name)||mb_strlen($data ->first_name)<3){
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</strong>"
                . "Nome deve ser preenchido e não deve ter menos que 4 caracteres</div>";
        } else {
            try {
                $stmt = $conn ->prepare("UPDATE users SET first_name =:first_name WHERE id =:id");
                $stmt ->bindValue(":first_name", $data ->first_name);
                $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
                $stmt ->execute();
            } catch (PDOException $e){
                $error = true;
                setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
            }
        }
    }

    if($data ->last_name != $_SESSION["user_edit"] ->last_name){
        if(empty($data ->last_name)||mb_strlen($data ->last_name)<3) {
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</strong>"
                . "Sobrenome deve ser preenchido e não deve ter menos que 4 caracteres</div>";
        } else {
            try {
                $stmt = $conn ->prepare("UPDATE users SET last_name =:last_name WHERE id =:id");
                $stmt ->bindValue(":last_name", $data ->last_name);
                $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
                $stmt ->execute();
            } catch (PDOException $e){
                setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
            }
        }
    }

    if($data ->email != $_SESSION["user_edit"] ->email){

        try {
            $stmt = $conn ->prepare("UPDATE users SET email =:email WHERE id =:id");
            $stmt ->bindValue(":email", $data ->email);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();
        } catch (PDOException $e){
            $error = true;
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if($data ->cell_phone != $_SESSION["user_edit"] ->cell_phone){
        if(!empty($data ->cell_phone)){
            $data ->cell_phone = preg_replace("/\D/", "", $data ->cell_phone);
            if(empty($data ->cell_phone)){
                $data ->cell_phone = null;
            }
        }

        try {
            $stmt = $conn ->prepare("UPDATE users SET cell_phone =:cell_phone WHERE id =:id");
            $stmt ->bindValue(":cell_phone", $data ->cell_phone);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();
            $stmt ->debugDumpParams();
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if($data ->user_name != $_SESSION["user_edit"] ->user_name){
        try {
            $stmt = $conn ->prepare("UPDATE users SET user_name =:user_name WHERE id =:id");
            $stmt ->bindValue(":user_name", $data ->user_name);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }
    /*if($data ->user_password != $_SESSION["user"] ->user_password){
        try {
            $stmt = $conn ->prepare("UPDATE users SET user_password =:user_password WHERE id =:id");
            $stmt ->bindValue(":user_password", $data ->user_password);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }*/

    if($data ->access_level != $_SESSION["user_edit"] ->access_level){
        try {
            $stmt = $conn ->prepare("UPDATE users SET access_level =:access_level WHERE id =:id");
            $stmt ->bindValue(":access_level", $data ->access_level);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if($error){
        $back = pg . "/register/user";
        header("Location: $back");
    } else {
        unset($_SESSION["user_edit"]);
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET"){
    $id = filter_input(INPUT_GET, "id",FILTER_VALIDATE_INT);
    var_dump($id);
    $stmt = $conn ->prepare("SELECT situation FROM users WHERE id =:id");
    $stmt ->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt ->execute();
    $res = $stmt ->fetch(PDO::FETCH_OBJ);

    if($res ->situation == 1){
        $stmt = $conn ->prepare("UPDATE users SET situation = 0 WHERE id =:id");
    } else {
        $stmt = $conn ->prepare("UPDATE users SET situation = 1 WHERE id =:id");
    }
    $stmt ->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt ->execute();

    if($stmt ->rowCount()){
        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</strong>"
            . "Usuário alterado com sucesso</div>";
        $back = pg . "/list/users";
        unset($_SESSION["user_register"]);
        header("Location: $back");
    }

} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Whoops! Método de acesso proibido!</div>";
    $back = pg . "/edit/user";
    header("Location: $back");
}





/*
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = (object)filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $error = false;
    var_dump(
        $data
    );
    if(empty($data ->id)||empty($data ->first_name)||empty($data ->last_name)||empty($data ->user)){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Necessário preencher todos os campos.</div>";

    }
    var_dump($_SESSION["msg"], $error);
    if(empty($data["id"]) || empty($data["nome"]) || empty($data["usuario"])){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Necessário preencher todos os campos.</div>";
    }
    elseif ((strlen($data["nome"])) < 4) {
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Digite seu nome completo!</div>";
    }

    if (!empty($data["senha"]) && (strlen($data["senha"])) < 6) {
        $error = true;
        $_SESSION["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "A senha deve conter mais que 6 caracteres!!</div>";
    }

    if ($error) {
        //$_SESSION['dados'] = $dados;
        $url_return = pg . "/edit/user?id='" . $data["id"] . "'";
        header("Location: $url_return");
    } else {
        if(!empty($data["senha"])){
            $data["senha"] = password_hash($data["senha"], PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET
                       name = '" . $data["nome"] . "',
                       email = '" . $data["email"] . "',
                       user_name = '" . $data["usuario"] . "',
                       user_password = '" . $data["senha"] . "',
                       access_level = '" . $data["nva_id"] . "',
                       modified = CURRENT_TIMESTAMP WHERE id =:id";
        } else {
            $sql_update = "UPDATE users SET
                       name = '" . $data["nome"] . "',
                       email = '" . $data["email"] . "',
                       user_name = '" . $data["usuario"] . "',
                       access_level = '" . $data["nva_id"] . "',
                       modified = CURRENT_TIMESTAMP WHERE id =:id";
        }

        $res_update = $conn ->prepare($sql_update);
        $res_update ->bindValue(":id", $data["id"], PDO::PARAM_INT);
        $res_update ->execute();

        if ($res_update ->rowCount()) {
            unset($_SESSION["dados"]);
            $_SESSION ["msg"]= "<div class='alert alert-success alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Usuário editado com sucesso</div>";
            $url_return = pg . "/list/users";
            header("Location: $url_return");
        } else {
            //Criar log de tentativa de acesso e redirecinar
            $log = "[".date("d/m/Y H:i:s")."] [ERROR]: ".mysqli_error($conn)."\n";
            //Diretório onde os arquivos de log devem ser gravados
            $directory = "logs/";
            if(!is_dir($directory)){
                mkdir($directory, 0777, true);
                chmod($directory, 0777);
            }

            //Nome do arquivo de log
            $fileName = $directory . "PAS".date("dmY").".txt";
            $handle = fopen($fileName, "a+");
            fwrite($handle, $log);
            fclose($handle);

            $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button>$log.</div>";
            $url_return = pg . "/list/users";
            header("Location: $url_return");
        }
    }
} elseif($_SERVER["REQUEST_METHOD"] == "GET"){
    $data = filter_input(INPUT_GET, "id",FILTER_VALIDATE_INT);

    var_dump(
        $data
    );

    $sql_verify = "SELECT situation FROM users WHERE id =:id";
    $res_verify = $conn->prepare($sql_verify);
    $res_verify ->bindValue(":id", $data);
    $res_verify ->execute();
    $row_verify = $res_verify ->fetch(PDO::FETCH_ASSOC);

    var_dump(
        $row_verify
    );

    try {
        $sql_update = "UPDATE users SET situation =:situation WHERE id =:id";
        $res_update = $conn ->prepare($sql_update);
        $res_update ->bindValue(":situation", $row_verify["situation"] == 1?0:1, PDO::PARAM_INT);
        $res_update ->bindValue(":id", $data, PDO::PARAM_INT);
        $res_update ->execute();

        if($res_update ->rowCount()){
            switch ($row_verify["situation"]){
                case "0":
                    $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Woops!&nbsp;</stron>"
                        . "Usuário liberado com sucesso!</div>";
                    break;
                case "1":
                    $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Woops!&nbsp;</stron>"
                        . "Usuário bloqueado com sucesso!</div>";
                    break;
            }
            $url_return = pg . "/list/users";
            header("Location: $url_return");
        }
    } catch (PDOException $e){
        echo $e ->getMessage();
    }
} else
{
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Woops!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_return = pg . "/list/users";
    header("Location: $url_return");
}*/
