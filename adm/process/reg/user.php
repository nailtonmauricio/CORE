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
    $data =(object)filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;
    var_dump(
        $data
    );

    if(!empty($data ->email)){
        $data ->email = filter_var($data ->email, FILTER_VALIDATE_EMAIL);
        if(!$data ->email){
            $error =true;
            var_dump([
                "msg" => "Este e-mail não é válido",
                "back" => "return to register/user.php"
            ]);
        } else {
            try {
                $stmt = $conn ->prepare("SELECT COUNT(id) AS count FROM users WHERE email =:email");
                $stmt ->bindParam(":email", $data ->email);
                $stmt ->execute();
                $count = $stmt ->fetch(PDO::FETCH_OBJ);

                var_dump($count);
            } catch (PDOException $e){
                setLog("FILE -> ".$e ->getFile()." LINE -> ".$e ->getLine()." MESSAGE -> ".$e ->getMessage());
            }
        }
    } else {
        $data ->email = null;
    }

   var_dump($data);

/*    $sql_name_verify = "SELECT COUNT(id) AS count FROM users WHERE user_name =:name";
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
        $url_return = pg . "/register/user";    }
    elseif(strlen($data["nome"]) < 4){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Nome de usuário deve ter no mínimo 4 caracteres e no máximo 15.</div>";
        $url_return = pg . "/register/user";
    }

    if($error){
        //CRIAR UMA SESÃO COM OS DADOS PASSADOS PELO FORM PARA QUE POSSA SER RETORNADO NO FORMULÁRIO

        header("Location: $url_return");
    } else {
        try {
            $sql = "INSERT INTO users (first_name, last_name, email, cell_phone, user_name, user_password, access_level) VALUES (:name, :last_name, :email, :cell_phone, :user_name, :user_password, :access_level)";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":first_name", $data["first_nome"]);
            $res ->bindValue(":last_name", $data["last_nome"]);
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
                $url_return = pg . "/list/users";
               # header("Location: $url_return");
            }
        } catch (PDOException $e){
            echo $e ->getMessage();
        }
    }*/
}