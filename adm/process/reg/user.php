<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</strong>"
        . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data =(object)filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;

    $data ->first_name = sanitizeString($data ->first_name);
    if(empty($data ->first_name)||mb_strlen($data ->first_name)<3){
        $error =true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</strong>"
            . "Nome deve ser preenchido e não deve ter menos que 4 caracteres</div>";
    }

    $data ->last_name = sanitizeString($data ->last_name);
    if(empty($data ->last_name)||mb_strlen($data ->last_name)<3){
        $error =true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</strong>"
            . "Sobrenome deve ser preenchido e não deve ter menos que 4 caracteres</div>";
    }

    if(!empty($data ->email)){
        $data ->email = filter_var($data ->email, FILTER_VALIDATE_EMAIL);
        $data ->email = sanitizeString($data ->email);
        if(!$data ->email){
            $error =true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "O email enviado não é um email válido</div>";
        } else {
            try {
                $stmt = $conn ->prepare("SELECT COUNT(id) AS count FROM users WHERE email =:email");
                $stmt ->bindParam(":email", $data ->email);
                $stmt ->execute();
                $res = $stmt ->fetch(PDO::FETCH_OBJ);

                if($res ->count == 1){
                    $error = true;
                    $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Whoops!&nbsp;</strong>"
                        . "Email já cadastrado na base de dados.</div>";
                }
            } catch (PDOException $e){
                setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
            }
        }
    } else {
        $data ->email = null;
    }

    $data ->user_name = sanitizeString($data ->user_name);
    if(empty($data ->user_name)||mb_strlen($data ->user_name)<4){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</strong>"
            . "Nome de usuário deve ser preenchido e não deve ter menos que 4 caracteres</div>";
    } else {
        try {
            $stmt = $conn ->prepare("SELECT COUNT(id) AS count FROM users WHERE user_name =:user_name");
            $stmt ->bindParam(":user_name", $data ->user_name);
            $stmt ->execute();
            $stmt ->debugDumpParams();
            $res = $stmt ->fetch(PDO::FETCH_OBJ);

            if($res ->count == 1){
                $error = true;
                $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Whoops!&nbsp;</strong>"
                    . "Nome de usuário já cadastrado na base de dados</div>";
            }
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    $data ->user_password = sanitizeString($data ->user_password);
    if(empty($data ->user_password)||mb_strlen($data ->user_password)<6){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</strong>"
            . "A senha deve ter no mínio 6 caracteres</div>";
    } else {
        $data ->user_password = password_hash($data ->user_password, PASSWORD_DEFAULT);
    }

    if(!empty($data ->cell_phone)){
        if(mb_strlen($data ->cell_phone)==11){
            $data ->cell_phone = preg_replace("/\D/", "", $data ->cell_phone);
        } else {
            $error = true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</strong>"
                . "O número de telefone deve contar 11 dígitos</div>";
        }
    } else {
        $data ->cell_phone = null;
    }

    if($error){
        $_SESSION["user_register"] = $data;
        $back = pg . "/register/user";
        header("Location: $back");
    } else {
        try {
            $stmt = $conn ->prepare("INSERT INTO users (first_name, last_name, email, cell_phone, user_name, user_password, access_level) VALUES (:first_name, :last_name, :email, :cell_phone, :user_name, :user_password, :access_level)");
            $stmt ->bindParam(":first_name", $data ->first_name);
            $stmt ->bindParam(":last_name", $data ->last_name);
            $stmt ->bindParam(":email", $data ->email);
            $stmt ->bindParam(":cell_phone", $data ->cell_phone);
            $stmt ->bindParam(":user_name", $data ->user_name);
            $stmt ->bindParam(":user_password", $data ->user_password);
            $stmt ->bindParam(":access_level", empty($data ->access_level)?2:$data ->access_level);
            $stmt ->execute();

            if($stmt ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center dismiss'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Legal!&nbsp;</strong>"
                    . "Novo usuário cadastrado com sucesso.</div>";
                $back = pg . "/list/users";
                header("Location: $back");

                if(isset($_SESSION["user_register"])){
                    unset($_SESSION["user_register"]);
                }
            }
        } catch (PDOException $e){
            $_SESSION["user_register"] = $data;
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</strong>"
        . "Método de acesso proibido</div>";
    $back = pg . "/register/user";
    header("Location: $back");
}