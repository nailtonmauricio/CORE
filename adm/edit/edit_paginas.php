<?php
// Verifica se a sessção foi iniciada, caso não tenha sido a linha 15 redireciona para a página de login.
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {

    $sql = "SELECT p.id, p.name, p.path, p.description FROM pages AS p WHERE id =:id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":id", $id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);
    var_dump(
            $row
    );
    if ($res ->rowCount()) {
        ?>
        <div class="well content">
            <div class="pull-right">
                <a href="<?php echo pg . '/list/list_paginas'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
            </div>
            <div class="page-header"></div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <form name="editPagina" method="post" action="<?php echo pg; ?>/process/edit/edit_paginas" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id" class="col-sm-2 control-label">ID</label>
                    <div class="col-sm-10">
                        <input type="text" name="id" class="form-control" id="id"  value="<?php
                        if (isset($_SESSION['dados']['id'])) {
                            echo $_SESSION['dados']['id'];
                        } elseif (isset($row['id'])) {
                            echo $row ['id'];
                        }
                        ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nome" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome Completo" value="<?php
                        if (isset($_SESSION['dados']['nome'])) {
                            echo $_SESSION['dados']['nome'];
                        } elseif (isset($row['name'])) {
                            echo $row['name'];
                        }
                        ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="endereco" class="col-sm-2 control-label">Endereço</label>
                    <div class="col-sm-10">
                        <input type="endereco" name="endereco" class="form-control" id="email" placeholder="endereco" value="<?php
                        if (isset($_SESSION['dados']['endereco'])) {
                            echo $_SESSION['dados']['endereco'];
                        } elseif (isset($row['path'])) {
                            echo $row ['path'];
                        }
                        ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="descricao" class="col-sm-2 control-label">Descrição</label>
                    <div class="col-sm-10">
                        <input type="text" name="descricao" class="form-control" id="descricao" placeholder="descrição da página" value="<?php
                        if (isset($_SESSION['dados']['descricao'])) {
                            echo $_SESSION['dados']['descricao'];
                        } elseif (isset($row['description'])) {
                            echo $row['description'];
                        }
                        ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn btn-xs btn-success pull-right">
                            <span class='glyphicon glyphicon-floppy-saved'></span> 
                            Salvar
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
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Nem uma página encontrada (msg1)!</div>";
        $url_destino = pg . "/list/list_paginas";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert''>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Nem uma página encontrada(msg2)!</div>";
    $url_destino = pg . "/list/list_paginas";
    header("Location: $url_destino");
}  
                    

