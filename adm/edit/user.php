<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!empty($id)) {
    $sql = "SELECT u.first_name, u.last_name, u.email, u.cell_phone, u.user_name, u.user_password, u.access_level, UPPER(al.name) AS access_level_name FROM users AS u JOIN access_level AS al ON u.access_level = al.id  WHERE u.id =:user_id";
    $res = $conn ->prepare($sql);
    $res ->bindParam(":user_id", $id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_OBJ);

    if ($res ->rowCount()) {
        $_SESSION["user"] = $row;
        var_dump($_SESSION["user"]);
        ?>
        <div class="well content">
            <div class="pull-right">
                <a href="<?php echo pg . '/list/users'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
            </div>
            <div class="page-header">
                <?php
                if (isset($_SESSION["msg"])) {
                    echo $_SESSION["msg"];
                    unset($_SESSION["msg"]);
                }
                ?>
            </div>
            <form name="editUser" method="post" action="<?php echo pg; ?>/process/edit/user" class="form-horizontal" autocomplete="off">
                <input type="hidden" name="id" id="id" value="<?= $id ?>"/>
                <div class="form-group">
                    <label for="first_name" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" id="first_name" name="first_name" value="<?=$row ->first_name?>" class="form-control text-uppercase" placeholder="Nome Completo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name" class="col-sm-2 control-label">Sobrenome</label>
                    <div class="col-sm-10">
                        <input type="text" id="last_name" name="last_name" value="<?=$row ->last_name?>" class="form-control text-uppercase" placeholder="Nome Completo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" inputmode="email" id="email" name="email" value="<?=$row ->email?>" class="form-control" placeholder="E-mail">
                    </div>
                </div>
                <div class="form-group">
                    <label for="cell_phone" class="col-sm-2 control-label">Telefone</label>
                    <div class="col-sm-10">
                        <input type="tel" inputmode="tel" id="cell_phone" name="cell_phone" value="<?=$row ->cell_phone ?>" class="form-control" placeholder="(xx) xxxxx-xxxx" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_name" class="col-sm-2 control-label">Usuário</label>
                    <div class="col-sm-10">
                        <input type="text" id="user_name" name="user_name" value="<?=$row ->user_name ?>" class="form-control text-uppercase" placeholder="Nome de Usuário">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_password" class="col-sm-2 control-label">Senha</label>
                    <div class="col-sm-10">
                        <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Senha">
                    </div>
                </div>
                <div class="form-group">
                    <label for="access_level" class="col-sm-2 control-label">Nível de Acesso</label>
                    <div class="col-sm-10">
                        <select id="access_level" name="access_level" class="form-control">
                            <?php
                            echo "<option value='" . $row ->access_level . "' selected>" . $row ->access_level_name. "</option>";
                            $sqlNva = "SELECT id, UPPER(name) AS name FROM access_level WHERE id != :access_level_id ORDER BY name";
                            $resNva = $conn ->prepare($sqlNva);
                            $resNva ->bindValue(":access_level_id", $row ->access_level_id, PDO::PARAM_INT);
                            $resNva ->execute();
                            $rowNva = $resNva ->fetchAll(PDO::FETCH_OBJ);
                            foreach($rowNva as $nva){
                                echo "<option value= " . $nva ->id . ">" . $nva ->name . "</option>";
                            }
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
            <script type="text/javascript">
                /*Função que impede o envio do formulário pela tecla enter acidental*/
                $(document).ready(function () {
                    $('input').keypress(function (e) {
                        let code = null;
                        code = (e.keyCode ? e.keyCode : e.which);
                        return (code == 13) ? false : true;
                    });
                });
            </script>
        </div>
        <?php
        #unset($_SESSION["user"]);
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert''>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Nenhum usuário encontrado!</div>";
        $url_destino = pg . "/list/users";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Nenhum usuário encontrado!</div>";
    $url_destino = pg . "/list/users";
    header("Location: $url_destino");
}

