<?php
ob_start();
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: ../../index.php");
}
?>
<div class="well content">
    <?php

        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
    ?>
    <h1 class="text-center text-capitalize">Bem Vindo(a), <?php echo $_SESSION["credentials"]["first_name"]."!"; ?></h1>
</div>
<div class="well content">
    <?php
        $sqlMsg = "SELECT COUNT(id) AS totMsg FROM posts
        WHERE recipient_id =:user_id AND verify = 0";
        $resMsg = $conn ->prepare($sqlMsg);
        $resMsg ->bindValue(":user_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
        $resMsg ->execute();
        $rowMsg = $resMsg ->fetch(PDO::FETCH_ASSOC);

        if($rowMsg["totMsg"] >= 1){
            $recado = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><a href='".pg."/list/list_recados'><strong>Aviso!&nbsp;</stron>"
            . "Você possui ".$rowMsg["totMsg"]." recado não lido.</a></div>";
            echo $recado;
        }
    unset($recado);

    /*var_dump(
            $_SESSION
    );*/
    ?>
    <div class="row">
        <div class="col-sm-4">
            <h4 class="text-center">Novidades da Versão 1.0.9</h4>
            <ul>
                <li>Módulo de mensagens entre usuários.</li>
            </ul>
        </div>
        <div class="col-sm-4">
            <h4 class="text-center">Novidades da Versão 1.0.8</h4>
            <ul>
                <li>Módulo de agenda para usuários.</li>
            </ul>

        </div>
        <div class="col-sm-4">
            <h4 class="text-center">Novidades da Versão 1.0.7</h4>
            <ul>
                <li>Logoff automático após 30 minutos de inatividade.</li>
            </ul>
        </div>
    </div>
</div>
<div class="well content">
    <?php
    include_once __DIR__."/include/footer.php";
    ?>
</div>