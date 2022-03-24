<?php

function security (){
    if ((!isset($_SESSION["check"])) && (!isset($_SESSION["credentials"]["access_level"]))){
        $_SESSION ['msg'] = "<div class='alert alert-warning alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
        header("Location: ../index.php");
    } 
}
