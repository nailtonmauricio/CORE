<?php

if (!isset($_SESSION["check"])) {
  $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
  . "<button type='button' class='close' data-dismiss='alert'>"
  . "<span aria-hidden='true'>&times;</span>"
  . "</button><strong>Aviso!&nbsp;</stron>"
  . "Área restrita, faça login para acessar.</div>";
  header("Location: index.php");
}
?>
<div class="well conteudo">
    <?php
        var_dump(
                $_SESSION
        );
    ?>
</div>

