<?php
session_start();
ob_start();
include_once("../config/config.php");
include_once ("library/permissao.php");
require_once ("functions/myFunctions.php");



//Este if testa se a veriável global $_SESSION['check'] foi iniciada indicando que o usuário fez login.
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width= device-width, initial-scale=1"/>
        <title>Project Administrative System (PAS)</title>
        <link rel="stylesheet" type="text/css" href="<?=pg?>/assets/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="<?=pg?>/assets/css/bootstrap-datetimepicker.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?=pg?>/assets/css/personalizado.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous"/>
        <!-- FullCalendar -->
        <link href='<?=pg?>/assets/css/fullcalendar.min.css' rel='stylesheet'/>
        <link href='<?=pg?>/assets/css/fullcalendar.print.min.css' rel='stylesheet' media='print'/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="<?=pg?>/assets/js/bootstrap.min.js"></script>
        <!-- FullCalendar -->
        <script src="<?=pg?>/assets/js/moment.min.js"></script>
        <script src="<?=pg?>/assets/js/fullcalendar.js"></script>
        <script src="<?=pg?>/assets/js/pt-br.js"></script>
        
        <!--Inputmask -->
        <script src="<?=pg?>/assets/js/jquery.mask.min.js"></script>
        <script src="<?=pg?>/assets/js/jquery.maskMoney.min.js" type="text/javascript"></script>
        <!--Inputmask -->
        <link rel="shortcut icon" href="<?php echo pg; ?>/assets/img/logo.ico"/>
        <link href="<?=pg?>/assets/css/bootstrap-datepicker.css" rel="stylesheet"/>
        <script src="<?=pg?>/assets/js/bootstrap-datepicker.min.js"></script>
        <script src="<?=pg?>/assets/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?=pg?>/assets/js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script>
        <script src="<?=pg?>/assets/js/bootstrap-datetimepicker.pt-BR.js" charset="UTF-8"></script>

        <!--Plotagem dos gráficos -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    </head>
    <body style="background-color: #fafafa;" onload="startTime()">
        <?php
        include_once("include/header.php");
        include_once ("include/menu.php");
        ?>
        <div class="col-sm-10">           

            <?php

            $url = filter_input(INPUT_GET, "url", FILTER_SANITIZE_URL);
            $file = (!empty($url))?$url:"home";
            $nva_user_id = $_SESSION["credentials"]["access_level"];
            $sql = "SELECT pages.id, pal.page_id, pal.access FROM pages JOIN page_access_level as pal ON pal.page_id = pages.id WHERE pal.page_id = pages.id AND pal.al_id =:session_user_id AND pages.path =:file AND pal.access = 1 LIMIT 1";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":session_user_id", $nva_user_id, PDO::PARAM_INT);
            $res ->bindValue(":file", $file, PDO::PARAM_STR);
            $res ->execute();

            if($res->rowCount()){
                $file = $file .".php";
                $row = $res->fetchAll(PDO::FETCH_ASSOC);

                if(file_exists($file)){
                    include $file;
                } else {
                    include_once("home.php");
                }
            } else {
                $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Whoops!&nbsp;</stron>"
                    . "Seu nível de acesso não permite este recurso.</div>";
                include_once("home.php");
            }
            ?>
        </div>
    <?php
    include_once ("include/rodape.php");
    ?>
</body>
</html>