<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    $sql = "SELECT u.name, u.email, u.cell_phone, u.user_name, u.user_password, u.situation, u.access_level, UPPER(al.name) AS nva, al.id AS nva_id FROM users AS u JOIN access_level AS al ON u.access_level = al.id  WHERE u.id =:user_id";
    $res = $conn ->prepare($sql);
    $res ->bindParam(":user_id", $id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);

    var_dump(
            $row
    );

    if ($res ->rowCount()) {
        $_SESSION["user_edit"] = $row;
        ?>
        <div class="well content">
            <div class="pull-right">
                <a href="<?php echo pg . '/list/list_usuarios'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
            </div>
            <div class="page-header">
                <?php
                    if (isset($_SESSION["msg"])) {
                        echo $_SESSION["msg"];
                        unset($_SESSION["msg"]);
                    }
                ?>
            </div>
            <form name="editUsuarios" method="post" action="<?php echo pg; ?>/process/edit/proc_edit_usuario" class="form-horizontal">
                <input type="hidden" name="id" id="id" value="<?= isset($dados["id"])?$dados["id"]:$id ?>"/>
                <div class="form-group">
                    <label for="nome" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control text-uppercase" id="nome" placeholder="Nome Completo" value="<?= isset($_SESSION["dados"]["nome"])?$_SESSION["dados"]["nome"]:$row["name"] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" value="<?= isset($_SESSION["dados"]["email"])?$_SESSION["dados"]["email"]:$row["email"] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="usuario" class="col-sm-2 control-label">Usuário</label>
                    <div class="col-sm-10">
                        <input type="text" name="usuario" class="form-control text-uppercase" id="usuario" placeholder="Nome de Usuário" value="<?= isset($_SESSION["dados"]["usuario"])?$_SESSION["dados"]["usuario"]:$row["user_name"] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="senha" class="col-sm-2 control-label">Senha</label>
                    <div class="col-sm-10">
                        <input type="password" name="senha" class="form-control" id="senha" placeholder="Password" value="<?= isset($_SESSION["dados"]["senha"])?$_SESSION["dados"]["senha"]: null ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nva_id" class="col-sm-2 control-label">Nível de Acesso</label>
                    <div class="col-sm-10">
                        <select name="nva_id" id="nva_id" class="form-control">
                            <?php
                                echo "<option value='" . $row["access_level"] . "' selected>" . $row["nva"] . "</option>";
                            $sqlNva = "SELECT id, UPPER(name) AS nome FROM access_level WHERE id != :nva_id ORDER BY nome";
                            $resNva = $conn ->prepare($sqlNva);
                            $resNva ->bindValue(":nva_id", $row["nva_id"], PDO::PARAM_INT);
                            $resNva ->execute();
                            $rowNva = $resNva ->fetchAll(PDO::FETCH_ASSOC);
                            foreach($rowNva as $nva){
                                echo "<option value= " . $nva["id"] . ">" . $nva["nome"] . "</option>";
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
                        var code = null;
                        code = (e.keyCode ? e.keyCode : e.which);                
                        return (code == 13) ? false : true;
                   });
                });
            </script>
        </div>
        <?php
        unset($_SESSION['dados']);
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert''>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Whoops!&nbsp;</stron>"
                . "Nem um usuário encontrado!</div>";
        $url_destino = pg . "/list/list_usuarios";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Nem um usuário encontrado!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}  


