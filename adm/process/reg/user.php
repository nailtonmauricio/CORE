<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</strong>"
        . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data =(object)filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;
    var_dump(
        $data
    );

    if(empty($data ->first_name)||mb_strlen($data ->first_name)<3){
        $error =true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</strong>"
            . "Nome deve ser preenchido e não deve ter menos que 4 caracteres</div>";
    } else {
        $data ->first_name = sanitizeString($data ->first_name);
    }

    if(empty($data ->last_name)||mb_strlen($data ->last_name)<3){
        $error =true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</strong>"
            . "Sobrenome deve ser preenchido e não deve ter menos que 4 caracteres</div>";
    } else {
        $data ->last_name = sanitizeString($data ->last_name);
    }

    if(!empty($data ->email)){
        $data ->email = filter_var($data ->email, FILTER_VALIDATE_EMAIL);
        if(!$data ->email){
            $error =true;
            $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</strong>"
                . "O email enviado não é um email válido</div>";
        } else {
            try {
                $stmt = $conn ->prepare("SELECT COUNT(id) AS count FROM users WHERE email =:email");
                $stmt ->bindParam(":email", $data ->email);
                $stmt ->execute();
                $stmt ->debugDumpParams();
                $res = $stmt ->fetch(PDO::FETCH_OBJ);

                var_dump($res ->count);
                if($res ->count == 1){
                    $error = true;
                    $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                        . "<button type='button' class='close' data-dismiss='alert'>"
                        . "<span aria-hidden='true'>&times;</span>"
                        . "</button><strong>Aviso!&nbsp;</strong>"
                        . "Email já cadastrado na base de dados.</div>";
                }
            } catch (PDOException $e){
                setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
            }
        }
    } else {
        $data ->email = null;
    }

    if(empty($data ->user_name)||mb_strlen($data ->user_name)<4){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</strong>"
            . "Nome de usuário deve ser preenchido e não deve ter menos que 4 caracteres</div>";
    } else {
        $data ->user_name = sanitizeString($data ->user_name);
        try {
            $stmt = $conn ->query("SELECT COUNT(id) AS count FROM users WHERE user_name =:user_name");
            $stmt ->bindParam(":user_name", $data ->user_name);
            $stmt ->execute();
            $res = $stmt ->fetch(PDO::FETCH_OBJ);

            if($res ->count == 1){
                $error = true;
                $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</strong>"
                    . "Nome de usuário deve ser preenchido e não deve ter menos que 4 caracteres</div>";
            }
        } catch (PDOException $e){
            setLog("FILE ".$e ->getFile().", LINE ".$e ->getLine().", MSG ".$e ->getMessage());
        }
    }

    if(empty($data ->user_password)||mb_strlen($data ->user_password)<6){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</strong>"
            . "A senha deve ter no mínio 6 caracteres</div>";
    } else {
        $data ->user_password = password_hash($data ->user_password, PASSWORD_DEFAULT);
    }

    if(!empty($data ->cell_phone)){
        $data ->cell_phone = preg_replace("/\D/", "", $data ->cell_phone);
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
            $stmt ->bindParam(":access_level", $data ->access_level);
            $stmt ->execute();

            if($stmt ->rowCount()){
                $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</strong>"
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
   var_dump($data);
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</strong>"
        . "Whoops! Método de acesso proibido</div>";
    $back = pg . "/register/user";
    header("Location: $back");
}