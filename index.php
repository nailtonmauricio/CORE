<?php

session_start();

/*
 * Cria o diretório config dentro do install caso ele não exista,
 * redireciona para o index do diretório /install
 */
if(!is_dir(__DIR__."/install/config")){
    mkdir(__DIR__."/install/config", 0777, true);
    chmod(__DIR__."/install/config", 0777);
    header("Location: install/index.php");
}

/*
 * Verifica se o arquico config.txt existe dentro do diratório /install/config
 * caso não exista, redireciona para o index do diretório /install
 */
if(!in_array("config.txt", scandir(__DIR__."/install/config"))){
    header("Location: install/index.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width= device-width, inital-scale=1"/>
        <title>Project Administrative System (PAS)</title>
        <link rel="stylesheet" type="text/css" href="adm/assets/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="adm/assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="adm/assets/css/personalizado.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link rel="shortcut icon" href="adm/assets/img/logo.ico"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-4"></div>
            <div class="col-md-4 well" style="margin-top: 5%;">
                <div class="row">
                    <img src="adm/assets/img/logo.png" class="img-responsive displayed" alt="NMATEC"/><br/>
                </div>
                <div class="row center-block">
                    <?php
                    if (isset($_SESSION["msg"])) {
                        echo $_SESSION["msg"];
                        unset($_SESSION["msg"]);
                    }
                    ?>
                    <form name="formLogin" method="post" action="adm/valida_login.php">
                        <label for="user_name" class="sr-only"></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-user"></i></span>
                            <input type="text" name="user_name" id="user_name"
                                   class="form-control text-uppercase" placeholder="Nome de usuário"
                                   required="required" autofocus/>
                        </div><br/>
                        <label for="user_password" class="sr-only"></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-key"></i></span>
                            <input type="password" name="user_password" id="user_password" class="form-control"
                                   required="required" placeholder="************************"/>
                        </div><br/>
                        <div class="form-group">
                            <div class="pull-right">
                                <button class="btn btn-xs btn-success">
                                    <span class="fas fa-sign-in-alt"></span> Acessar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                        <p class="text-center"><a href="adm/recuperar_senha.php">Recuperar senha!</a></p>
                </div>
            </div>
        </div>
        <script src="adm/assets/js/bootstrap.js"></script>
        <script src="adm/assets/js/jquery-3.2.1.min.js"></script>
        <script src="adm/assets/js/bootstrap.min.js"></script>
    </body>
</html>

