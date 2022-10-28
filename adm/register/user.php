<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}
?>
<div class="well content">
    <div class="pull-right">
        <a href="<?php echo pg . '/list/users'; ?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</a>
    </div>
    <div class="page-header"></div>
    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }
    ?>
    <form name="registerUser" method="post" action="<?php echo pg; ?>/process/reg/user" class="form-horizontal" autocomplete="off">
        <div class="form-group">
            <label for="first_name" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" id="first_name" name="first_name" value="<?=$_SESSION["user_register"] ->first_name??null?>" class="form-control text-uppercase" required>
            </div>
        </div>
        <div class="form-group">
            <label for="last_name" class="col-sm-2 control-label">Sobrenome</label>
            <div class="col-sm-10">
                <input type="text" id="last_name" name="last_name" value="<?=$_SESSION["user_register"] ->last_name??null?>" class="form-control text-uppercase" required>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" inputmode="email" id="email" name="email" value="<?=$_SESSION["user_register"] ->email??null?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="cell_phone" class="col-sm-2 control-label">Telefone</label>
            <div class="col-sm-10">
                <input type="tel" inputmode="tel" id="cell_phone" name="cell_phone" value="<?=$_SESSION["user_register"] ->cell_phone??null?>" class="form-control cell-phone" placeholder="(xx) xxxxx-xxxx" pattern="^\(\d{2}\)\s[9]\d{4}-\d{4}$">
            </div>
        </div>
        <div class="form-group">
            <label for="user_name" class="col-sm-2 control-label">Usuário</label>
            <div class="col-sm-10">
                <input type="text" id="user_name" name="user_name" value="<?=$_SESSION["user_register"] ->user_name??null?>" class="form-control text-uppercase" required>
            </div>
        </div>
        <div class="form-group">
            <label for="user_password" class="col-sm-2 control-label">Senha</label>
            <div class="col-sm-10">
                <input type="password" id="user_password" name="user_password" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="access_level" class="col-sm-2 control-label">Nível de Acesso</label>
            <div class="col-sm-10">
                <select id="access_level" name="access_level" class="form-control">
                    <option>...</option>
                    <?php

                    $stmt = $conn->prepare("SELECT id , UPPER(name) AS name FROM access_level WHERE position >=:position AND situation = 1");
                    $stmt ->bindValue(":position", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
                    $stmt->execute();
                    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($res as $accessLevel):
                        ?>
                        <option value="<?=$accessLevel["id"]?>"><?=$accessLevel["name"]?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class='btn btn-xs btn-success pull-right'>
                    <span class='glyphicon glyphicon-floppy-saved'></span> Salvar
                </button>
            </div>
        </div>
    </form>
    <?php
    if(isset($_SESSION["user_register"])){
        unset($_SESSION["user_register"]);
    }
    ?>
    <script>
        /*Função que impede o envio do formulário pela tecla enter acidental*/
        $(document).ready(function () {
            $('input').keypress(function (e) {
                var code = null;
                code = (e.keyCode ? e.keyCode : e.which);
                return (code == 13) ? false : true;
            });
        });
    </script>
</div>