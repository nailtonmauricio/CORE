<?php
// Verifica se a sessção foi iniciada, caso não tenha sido a linha 15 redireciona para a página de login.
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!empty($id)) {
    if ($_SESSION["credentials"]["access_level"] == 1) {
        $sql_nv = "SELECT * FROM access_level WHERE id=:id";
    } else {
        $sql_nv = "SELECT * FROM access_level
                 WHERE position > '".$_SESSION["credentials"]["position"]."' AND '".$_SESSION["credentials"]["access_level"]."' =:id LIMIT 1";
    }
    $res_nv = $conn ->prepare($sql_nv);
    $res_nv ->bindValue(":id", $id, PDO::PARAM_INT);
    $res_nv ->execute();

    if ($res_nv ->rowCount()) {
        $row_nv= $res_nv ->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="well content">
            <div class="pull-right">
                <a href="<?php echo pg . '/list/access_levels'; ?>" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</a>
            </div>
            <div class="page-header">

            </div>
            <?php
            if (isset($_SESSION["msg"])) {
                echo $_SESSION["msg"];
                unset($_SESSION["msg"]);
            }
            ?>
            <form name="access_level" method="post" action="<?php echo pg; ?>/process/edit/access_level" class="form-horizontal">
                <input type="hidden" name="id" id="id" value="<?= $row_nv["id"]; ?>">
                <div class="form-group">
                    <label for="nome" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control text-capitalize" id="nome" value="<?php
                        if (isset($_SESSION["data"]["name"])) {
                            echo $_SESSION["data"]["name"];
                        } elseif (isset($row_nv["name"])) {
                            echo $row_nv["name"];
                        }
                        ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="profile" class="col-sm-2 control-label">Perfil</label>
                    <div class="col-sm-10">
                        <select name="profile" id="profile" class="form-control">
                            <option>...</option>
                            <?php
                            $stmt = $conn ->prepare("SELECT id, UPPER(name) AS name, position FROM access_level WHERE id >=:id AND situation = 1");
                            $stmt ->bindParam(":id", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
                            $stmt ->execute();
                            $res = $stmt ->fetchAll(PDO::FETCH_OBJ);
                            foreach($res as $profile):
                                ?>
                                <option value="<?=$profile ->id?>"><?=$profile ->name?></option>
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
                            <option>...</option>
                            <?php
                            $stmt = $conn ->prepare("SELECT position , UPPER(name) AS name FROM access_level WHERE id >=:id AND situation = 1");
                            $stmt ->bindValue(":id", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
                            $stmt->execute();
                            $res = $stmt ->fetchAll(PDO::FETCH_OBJ);
                            foreach($res as $position):
                                ?>
                                <option value="<?=$position ->position?>"><?=$position->name?></option>
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
        <?php
        unset($_SESSION['dados']);
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Nem um usuário encontrado(1)!</div>";
        $url_destino = pg . "/list/access_levels";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Nem um usuário encontrado(2)!</div>";
    $url_destino = pg . "/list/access_levels";
    header("Location: $url_destino");
}

