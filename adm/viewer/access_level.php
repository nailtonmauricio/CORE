<?php
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

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

    /*var_dump(
        $row
    );*/
    if ($res ->rowCount()) {

        ?>
        <div class="well content">
            <div class="pull-right">
                <?php
                $button_edit = load('edit/access_level', $conn);
                $button_list = load('list/access_levels', $conn);
                $button_delete = load('process/del/access_level', $conn);
                if ($button_list) {
                    echo "<a href= '" . pg . "/list/access_levels' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-list'></span> Listar</a> ";
                }
                if ($button_edit) {
                    echo "<a href= '" . pg . "/edit/access_level?id=" . $row["id"] . "' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span> Editar</a> ";
                }
                if ($button_delete) {
                    echo "<a href= '" . pg . "/process/del/access_level?id=" . $row["id"] . "'onclick=\"return confirm('Apagar nível de acesso?');\" class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span> Apagar</a> ";
                }
                ?>
            </div>
            <div class="page-header"></div>
            <dl class="dl-horizontal">
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
            </dl>
        </div>
        <?php
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Registro não encontrado!</div>";
        $url_destino = pg . "/list/access_levels";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Registro não encontrado!</div>";
    $url_destino = pg . "/list/access_levels";
    header("Location: $url_destino");
}
