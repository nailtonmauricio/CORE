<?php
session_start();
ob_start();
include_once("../config/config.php");
require_once ("functions/myFunctions.php");



//Este if testa se a variável global $_SESSION['check'] foi iniciada indicando que o usuário fez login.
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
    <meta charset="utf-8">
    <meta name="viewport" content="width= device-width, initial-scale=1">
    <title>Project Administrative System (PAS)</title>
    <link rel="stylesheet" type="text/css" href="<?=pg?>/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?=pg?>/assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="<?=pg?>/assets/css/personalizado.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <!-- FullCalendar -->
    <link href='<?=pg?>/assets/css/fullcalendar.min.css' rel='stylesheet'>
    <link href='<?=pg?>/assets/css/fullcalendar.print.min.css' rel='stylesheet' media='print'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="<?=pg?>/assets/js/bootstrap.min.js"></script>
    <!-- FullCalendar -->
    <script src="<?=pg?>/assets/js/moment.min.js"></script>
    <script src="<?=pg?>/assets/js/fullcalendar.js"></script>
    <script src="<?=pg?>/assets/js/pt-br.js"></script>

    <!--Inputmask -->
    <script src="<?=pg?>/assets/js/jquery.mask.min.js"></script>
    <script src="<?=pg?>/assets/js/jquery.maskMoney.min.js"></script>
    <!--Inputmask -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo.ico">
    <link href="<?=pg?>/assets/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="<?=pg?>/assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?=pg?>/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=pg?>/assets/js/bootstrap-datepicker.pt-BR.min.js"></script>
    <script src="<?=pg?>/assets/js/bootstrap-datetimepicker.pt-BR.js"></script>

    <!--Plotagem dos gráficos -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- Remove mensagem após 50 segundos
    ====================================== -->
    <script>
        $(document).ready(function () {

            window.setTimeout(function () {
                $(".alert").fadeTo(1000, 0).slideUp(1000, function () {
                    $(this).remove();
                });
            }, 5000);

        });
    </script>

    <!-- Mostra o countdown
    ====================================== -->
    <script>
        let tempo = new Number();
        // Tempo em segundos
        tempo = 1800;

        function startCountdown(){

            // Se o tempo não for zerado
            if((tempo - 1) >= 0){

                // Pega a parte inteira dos minutos
                let min = parseInt(tempo/60);
                // Calcula os segundos restantes
                let seg = tempo%60;

                // Formata o número menor que dez, ex: 08, 07, ...
                if(min < 10){
                    min = "0"+min;
                    min = min.substr(0, 2);
                }
                if(seg <=9){
                    seg = "0"+seg;
                }

                // Cria a variável para formatar no estilo hora/cronômetro
                horaImprimivel = '00:' + min + ':' + seg;
                //JQuery pra setar o valor
                $("#clock").html(horaImprimivel);

                // Define que a função será executada novamente em 1000ms = 1 segundo
                setTimeout('startCountdown()',1000);

                // diminui o tempo
                tempo--;

                // Quando o contador chegar a zero faz logoff e encerra a sessão
            } else {
                location.href="<?php echo pg; ?>/sair.php";
            }
        }
        // Chama a função ao carregar a tela
        startCountdown();
    </script>
</head>
<body style="background-color: #fafafa;" onload="startTime()">
<?php
include_once("include/header.php");
include_once ("include/menu.php");
?>
<div class="col-sm-10" style="margin-top: 5px">

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
        $row = $res->fetchAll(PDO::FETCH_OBJ);

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
</body>
</html>