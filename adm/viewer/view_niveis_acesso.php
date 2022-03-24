<?php
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    if ($_SESSION["credentials"]["access_level"] == 1) {
        $sql = "SELECT id, name, position, situation, created, modified FROM access_level WHERE id =:id";
        $res = $conn->prepare($sql);
        $res ->bindParam(":id", $id, PDO::PARAM_INT);
        $res ->execute();
    } else {
        $sql = "SELECT id, name, position, situation, created, modified FROM access_level WHERE position >{$_SESSION["credentials"]["position"]} AND id =:id";
        $res = $conn->prepare($sql);
        $res ->bindParam(":id", $id, PDO::PARAM_INT);
        $res ->execute();
    }

    $row = $res ->fetch(PDO::FETCH_ASSOC);

    var_dump(
        $row
    );
    if ($res ->rowCount()) {

        ?>
        <div class="well content">
            <div class="pull-right">
                <?php
                $button_edit = load('edit/edit_niveis_acesso', $conn);
                $button_list = load('list/list_niveis_acesso', $conn);
                $button_delete = load('process/del/del_niveis_acesso', $conn);
                if ($button_list) {
                    echo "<a href= '" . pg . "/list/list_niveis_acesso'><button type='button' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-list'></span> Listar</button></a> ";
                }
                if ($button_edit) {
                    echo "<a href= '" . pg . "/edit/edit_niveis_acesso?id=" . $row["id"] . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span> Editar</button></a> ";
                }
                if ($button_delete) {
                    echo "<a href= '" . pg . "/process/del/del_niveis_acesso?id=" . $row["id"] . "'onclick=\"return confirm('Apagar nível de acesso?');\"><button type='button' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span> Apagar</button></a> ";
                }
                ?>
            </div>
            <div class="page-header"></div>
            <div class="dl-horizontal">
                <dt>Id</dt>
                <dd><?php echo $row["id"]; ?></dd>
                <dt>Nome</dt>
                <dd><?php echo $row["name"]; ?></dd>
                <dt>Ordem</dt>
                <dd><?php echo $row["position"]; ?></dd>
                <dt>Data Criação</dt>
                <dd><?php echo date(DHBR, strtotime($row["created"])); ?></dd>
                <dt>Ultima Modificação</dt>
                <dd><?php
                    if (!empty($row["modified"])) {
                        echo date(DBR, strtotime($row["modified"]));
                    } else {
                        echo $row["modified"];
                    }
                    ?></dd>
            </div>
        </div>
        <?php
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Registro não encontrado!</div>";
        $url_destino = pg . "/list/list_niveis_acesso";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Registro não encontrado!</div>";
    $url_destino = pg . "/list/list_niveis_acesso";
    header("Location: $url_destino");
}

