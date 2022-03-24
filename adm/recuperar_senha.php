<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if(!empty($email)){
        include_once("config/config.php");
        $sql = "SELECT id, name, email FROM users WHERE email =:email";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":email", $email, PDO::PARAM_STR);
        $res ->execute();
        $row = $res ->fetch(PDO::FETCH_ASSOC);
       
        if ($res ->rowCount()){
            $key= md5($row ["id"] . $row ["email"] . date("Y-m-d H:i:s"));
            $sql_update = "UPDATE users SET
                          password_recover =:key,
                          modified = CURRENT_TIMESTAMP 
                          WHERE id =:id";
            $res_update = $conn ->prepare($sql_update);
            $res_update ->bindValue(":key", $key, PDO::PARAM_STR);
            $res_update ->bindValue(":id", $row["id"], PDO::PARAM_INT);
            $res_update ->execute();

            if($res_update ->rowCount()){

                include_once("config/config.php");
                
                $url = pg."/atualizar_senha.php?key=".$key;

                /*
                 * IMPLEMENTAR AQUI AS CONFIGURAÇÕES DE DISPARO DE E-MAIL DE ACORCO COM SEU PROVEDOR
                 */
            }

       } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "E-mail para recuperação inválido.</div>";
       }

    } else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "E-mail deve ser preenchido!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width= device-width, inital-scale=1"/>
        <title>Recuperar Senha</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/personalizado.css"/>
        <link rel="shortcut icon" href="assets/img/logo.ico"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-4"></div>
            <div class="col-md-4 well" style="margin-top: 5%;">
                <div>
                    <img src="assets/img/logo.png" class="img-responsive displayed" alt="LAB FDINIZ"/><br>
                    <?php
                        if (isset($_SESSION["msg"])) {
                            echo $_SESSION["msg"];
                            unset($_SESSION["msg"]);
                        }
                    ?>
                </div>
                <form name="formRecuperarSenha" method="post" action="recuperar_senha.php">
                    <label for="senha" class="sr-only"></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control"
                               required="required" placeholder="usuario@dominio.com.br"/>
                    </div><br/>
                    <div class="form-group"> 
                        <div class="pull-right">   
                            <button class="btn btn-xs btn-success">
                                <span class='glyphicon glyphicon-floppy-saved'></span> 
                                Enviar
                            </button>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <p class="text-center"><a href="../index.php">Retornar ao Login</a></p>
                    </div>
                </form>
                <script src="assets/js/bootstrap.js"></script>
                <script src="assets/js/jquery-3.2.1.min.js"></script>
                <script src="assets/js/bootstrap.min.js"></script>
            </div>
        </div>
    </body>
</html>

