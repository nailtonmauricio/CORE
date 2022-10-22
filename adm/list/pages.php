<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$button_edit = load("edit/page", $conn);
$button_view = load("viewer/page", $conn);
?>
<div class="well content">
    <?php
    $button_cad = load("register/page", $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/page'; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
        </div>
        <?php
    }
    ?>
    <div class="page-header">
        <?php
        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
        ?>
    </div>
    <table id="pages" class="display table table-stripped table-hover">
        <thead>
        <tr>
            <th>ENDEREÇO</th>
            <th>MENU</th>
            <th>DESCRIÇÃO</th>
            <th>OPÇÕES</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $row = paginator("id, path, name, description", "pages", $conn);
        foreach($row as $page):
            ?>
            <tr>
                <td><?= $page ->path?></td>
                <td class="text-uppercase"><?= !is_null($page ->name)?$page ->name: "NULL"?></td>
                <td><?= !is_null($page ->description)?$page ->description: "NULL"?></td>
                <td>
                    <?php
                    if ($button_view) {
                        echo "<a href= '" . pg . "/viewer/page?id=" . $page ->id . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                    }
                    if ($button_edit) {
                        echo "<a href= '" . pg . "/edit/page?id=" . $page ->id . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                    }
                    ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#pages').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                }
            });
        } );
    </script>
</div>