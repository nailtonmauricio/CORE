<?php

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
        <a href="<?php echo pg . '/list/list_niveis_acesso'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
    </div>
    <div class="page-header"></div>
    <?php
        if (isset($_SESSION["msg"])){
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
    ?>
    <form name="cadNvAcessos" method="post" action="<?php echo pg; ?>/process/reg/reg_niveis_acesso" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control text-uppercase" id="name" placeholder="Nome do Nível de Acesso" value="<?php if(isset($_SESSION["data"]["name"])){ echo $_SESSION["data"]["name"];}?>" autofocus required/>
            </div>
        </div>
        <div class="form-group">
            <label for="profile" class="col-sm-2 control-label">Perfil</label>
            <div class="col-sm-10">
                <select name="profile" id="profile" class="form-control">
                    <option value="">[SELECIONE]</option>
                    <?php
                        $sql_profile = "SELECT id, UPPER(name) AS name, position FROM access_level WHERE id >=:id AND situation = 1";
                        $res_profile = $conn->prepare($sql_profile);
                        $res_profile ->bindValue(":id", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
                        $res_profile ->execute();
                        $row_profile = $res_profile ->fetchAll(PDO::FETCH_ASSOC);
                        foreach($row_profile as $profile):
                    ?>
                        <option value="<?=$profile["id"]?>"><?=$profile["name"]?></option>
                    <?php
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="position" class="col-sm-2 control-label">Posição</label>
            <div class="col-sm-10">
                <select name="position" id="position" class="form-control">
                    <option value="">[SELECIONE]</option>
                    <?php
                    $sql_position = "SELECT position , UPPER(name) AS name FROM access_level WHERE id >=:id AND situation = 1";
                    $res_position = $conn->prepare($sql_position);
                    $res_position ->bindValue(":id", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
                    $res_position->execute();
                    $row_position = $res_position ->fetchAll(PDO::FETCH_ASSOC);
                    foreach($row_position as $position):
                        ?>
                        <option value="<?=$position["position"]?>"><?=$position["name"]?></option>
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
