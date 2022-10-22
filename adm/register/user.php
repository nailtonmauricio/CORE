<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
?>
<div class="well content">
    <div class="pull-right">
        <a href="<?php echo pg . '/list/users'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</button></a>
    </div>
    <div class="page-header"></div>
    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }
    ?>
    <form name="cadUsuarios" method="post" action="<?php echo pg; ?>/process/reg/user" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control text-uppercase" id="nome" placeholder="Nome Completo" value="<?php
                if (isset($_SESSION['dados']['nome'])) {
                    echo $_SESSION['dados']['nome'];
                }
                ?>" autofocus/>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" class="form-control text-lowercase" id="email" placeholder="E-mail" value="<?php
                if (isset($_SESSION['dados']['email'])) {
                    echo $_SESSION['dados']['email'];
                }
                ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="cel" class="control-label col-sm-2">Celular</label>
            <div class="col-sm-10">
                <input type="text" name="cel" id="cel" class="form-control celular" placeholder="(99) 99999-9999"/>
            </div>
        </div>
        <div class="form-group">
            <label for="usuario" class="col-sm-2 control-label">Usuário</label>
            <div class="col-sm-10">
                <input type="text" name="usuario" class="form-control text-uppercase" id="usuario" placeholder="Nome de Usuário" value="<?php
                if (isset($_SESSION['dados']['usuario'])) {
                    echo $_SESSION['dados']['usuario'];
                }
                ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="senha" class="col-sm-2 control-label">Senha</label>
            <div class="col-sm-10">
                <input type="password" name="senha" class="form-control" id="senha" placeholder="Password"/>
            </div>
        </div>
        <div class="row form-group">
            <label for="nivelAcesso" class="col-sm-2 control-label">Nível de Acesso</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <select class="form-control" name="nva_id">
                        <option value="">[Selecione]</option>
                        <?php
                        $sql = "SELECT id , UPPER(name) AS name FROM access_level WHERE id >=:id AND situation = 1";
                        $res = $conn->prepare($sql);
                        $res ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
                        $res->execute();
                        $row = $res->fetchAll(PDO::FETCH_ASSOC);
                        foreach($row as $access):
                            ?>
                            <option value="<?=$access["id"]?>"><?=$access["name"]?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="button">
                            <a href="<?php echo pg; ?>/register/reg_niveis_acesso" style="color: #ffffff;">
                                <span class="glyphicon glyphicon-option-horizontal"></span>
                            </a>
                        </button>
                    </span>
                </div>

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-xs btn-success pull-right">
                    <span class="glyphicon glyphicon-floppy-saved"></span>
                    Cadastrar
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