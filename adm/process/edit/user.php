<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = (object)filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;

    if($data ->first_name != $_SESSION["user_edit"] ->first_name){
        $data ->first_name = sanitizeString($data ->first_name);
        if(empty($data ->first_name)||mb_strlen($data ->first_name)<3){
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "Nome deve ser preenchido e não deve ter menos que 3 caracteres</div>";
        } else {
            try {
                $stmt = $conn ->prepare("UPDATE users SET first_name =:first_name WHERE id =:id");
                $stmt ->bindValue(":first_name", $data ->first_name);
                $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
                $stmt ->execute();

                if($stmt ->rowCount()){
                    $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Legal!&nbsp;</strong>"
                        . "Alteração realizada com sucesso</div>";
                }
            } catch (PDOException $e){
                $error = true;
                setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
            }
        }
    }

    if($data ->last_name != $_SESSION["user_edit"] ->last_name){
        $data ->last_name = sanitizeString($data ->last_name);
        if(empty($data ->last_name)||mb_strlen($data ->last_name)<3) {
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "Sobrenome deve ser preenchido e não deve ter menos que 3 caracteres</div>";
        } else {
            try {
                $stmt = $conn ->prepare("UPDATE users SET last_name =:last_name WHERE id =:id");
                $stmt ->bindValue(":last_name", $data ->last_name);
                $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
                $stmt ->execute();
                if($stmt ->rowCount()){
                    $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Legal!&nbsp;</strong>"
                        . "Alteração realizada com sucesso</div>";
                }
            } catch (PDOException $e){
                setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
            }
        }
    }

    if($data ->email != $_SESSION["user_edit"] ->email){
        $data ->email = sanitizeString($data ->email);
        if(!empty($data ->email)){
            $data ->email = filter_var($data ->email, FILTER_VALIDATE_EMAIL);
            $stmt = $conn ->prepare("SELECT COUNT(id) AS count FROM users WHERE email =:email");
            $stmt ->bindParam(":email", $data ->email);
            $stmt ->execute();
            $res = $stmt ->fetch(PDO::FETCH_OBJ);

            if($res ->count !=0){
                $error = true;
                $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Whoops!&nbsp;</strong>"
                    . "O email enviado já está cadastrado na base de dados</div>";
            } else {
                try {
                    $stmt = $conn ->prepare("UPDATE users SET email =:email WHERE id =:id");
                    $stmt ->bindValue(":email", $data ->email);
                    $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
                    $stmt ->execute();
                    if($stmt ->rowCount()){
                        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                            . "<button type='button' class='close' data-dismiss='alert'>"
                            . "<span aria-hidden='true'>&times;</span>"
                            . "</button><strong>Legal!&nbsp;</strong>"
                            . "Alteração realizada com sucesso</div>";
                    }
                } catch (PDOException $e){
                    $error = true;
                    setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
                }
            }

        } else {
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "O campo email não pode ser vazio</div>";
        }
    }

    if($data ->cell_phone != $_SESSION["user_edit"] ->cell_phone){
        if(!empty($data ->cell_phone)){
            $data ->cell_phone = preg_replace("/\D/", "", $data ->cell_phone);
            if(mb_strlen($data ->cell_phone)!=11){
                $error = true;
                $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Whoops!&nbsp;</strong>"
                    . "O número de telefone deve conter 11 dígitos</div>";
            }
        }else {
            $data ->cell_phone = null;
        }

        try {
            $stmt = $conn ->prepare("UPDATE users SET cell_phone =:cell_phone WHERE id =:id");
            $stmt ->bindValue(":cell_phone", $data ->cell_phone);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();

            if($stmt ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Legal!&nbsp;</strong>"
                    . "Alteração realizada com sucesso</div>";
            }
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if($data ->user_name != $_SESSION["user_edit"] ->user_name){
        $data ->user_name = sanitizeString($data ->user_name);
        if(empty($data ->user_name)){
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "Nome de usuário deve ser preenchido e não deve ter menos que 3 caracteres</div>";
        } else {
            $stmt = $conn ->prepare("SELECT COUNT(id) AS count FROM users WHERE user_name =:user_name");
            $stmt ->bindParam(":user_name", $data ->user_name);
            $stmt ->execute();
            $res = $stmt ->fetch(PDO::FETCH_OBJ);

            if($res ->count !=0){
                $error = true;
                $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Whoops!&nbsp;</strong>"
                    . "O nome de usuário enviado já está cadastrado na base de dados</div>";
            } else {
                try {
                    $stmt = $conn ->prepare("UPDATE users SET user_name =:user_name WHERE id =:id");
                    $stmt ->bindValue(":user_name", $data ->user_name);
                    $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
                    $stmt ->execute();

                    if($stmt ->rowCount()){
                        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                            . "<button type='button' class='close' data-dismiss='alert'>"
                            . "<span aria-hidden='true'>&times;</span>"
                            . "</button><strong>Legal!&nbsp;</strong>"
                            . "Alteração realizada com sucesso</div>";
                    }
                } catch (PDOException $e){
                    $error =true;
                    setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
                }
            }
        }
    }

    if($data ->user_password != $_SESSION["user"] ->user_password){
        $data ->user_password = sanitizeString($data ->user_password);
        if(empty($data ->user_password)){
            $error =true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "Campo senha deve ser preenchido, e deve possuir no mínimo 6 e no máximo 15 caracteres</div>";
        }
        try {
            $stmt = $conn ->prepare("UPDATE users SET user_password =:user_password WHERE id =:id");
            $stmt ->bindValue(":user_password", password_hash($data ->user_password, PASSWORD_DEFAULT));
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();

            if($stmt ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Legal!&nbsp;</strong>"
                    . "Alteração realizada com sucesso</div>";
            }
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if($data ->access_level != $_SESSION["user_edit"] ->access_level){
        try {
            $stmt = $conn ->prepare("UPDATE users SET access_level =:access_level WHERE id =:id");
            $stmt ->bindValue(":access_level", $data ->access_level);
            $stmt ->bindValue(":id", $data ->id, PDO::PARAM_INT);
            $stmt ->execute();

            if($stmt ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Legal!&nbsp;</strong>"
                    . "Alteração realizada com sucesso</div>";
            }
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if($error){
        $back = pg . "/edit/user?id=".$data ->id;
    } else {
        unset($_SESSION["user_edit"]);
        $back = pg . "/list/users";
    }
    header("Location: $back");
} elseif($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = filter_input(INPUT_GET, "id",FILTER_VALIDATE_INT);

    $stmt = $conn ->prepare("SELECT situation FROM users WHERE id =:id");
    $stmt ->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt ->execute();
    $res = $stmt ->fetch(PDO::FETCH_OBJ);

    if($res ->situation == 1){
        $stmt = $conn ->prepare("UPDATE users SET situation = 0 WHERE id =:id AND id !=".$_SESSION["credentials"]["id"]);
    } else {
        $stmt = $conn ->prepare("UPDATE users SET situation = 1 WHERE id =:id AND id !=".$_SESSION["credentials"]["id"]);
    }
    $stmt ->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt ->execute();

    if($stmt ->rowCount()){
        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Legal!&nbsp;</strong>"
            . "Alteração realizada com sucesso</div>";
        unset($_SESSION["user_edit"]);
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</strong>"
            . "Você não pode desativar seu próprio usuário</div>";
    }
    $back = pg . "/list/users";
    header("Location: $back");
}
