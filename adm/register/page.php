<?php
// Verifica se a sessção foi iniciada, caso não tenha sido a linha 10 redireciona para a página de login.
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
?>
<div class="well content">
    <div class="pull-right">
        <a href="<?= pg . '/list/pages'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</button></a>
    </div>
    <div class="page-header">
        <!--<h1>Cadastrar Página</h1>-->
    </div>
    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }
    if(isset($_SESSION["data"])){
        var_dump(
            $_SESSION["data"]
        );
    }
    ?>
    <form name="cadPagina" method="post" action="<?php echo pg; ?>/process/reg/page" class="form-horizontal">
        <div class="form-group">
            <label for="alias" class="col-sm-2 control-label">Nome para menu</label>
            <div class="col-sm-10">
                <input type="text" name="alias" class="form-control" id="alias" placeholder="Nome que será apresentado no menú." autofocus/>
            </div>
        </div>
        <div class="form-group">
            <label for="path" class="col-sm-2 control-label">Endereço</label>
            <div class="col-sm-10">
                <input type="text" name="path" class="form-control" id="path" placeholder="diretorio/"/>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Nome do arquivo</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" id="name" placeholder="Nome que será apresentado no menú."/>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">Descrição</label>
            <div class="col-sm-10">
                <input type="text" name="description" class="form-control" id="description" placeholder="Descição do conteúdo da página cadastrada." />
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
            $("input").keypress(function (e) {
                let code = null;
                code = (e.keyCode ? e.keyCode : e.which);
                return (code == 13) ? false : true;
            });
        });
    </script>
</div>