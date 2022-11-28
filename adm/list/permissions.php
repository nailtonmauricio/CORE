<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça 'login' para acessar.</div>";
    header("Location: index.php");
}
$button_perm = load("list/permissions", $conn);
$button_edit = load("edit/access_level", $conn);
$button_view = load("viewer/access_level", $conn);
$button_delete = load("process/del/access_level", $conn);
$id = filter_input(INPUT_GET, 'acid', FILTER_VALIDATE_INT);
?>
<div class="well content">
    <?php
    $button_list = load("list/access_levels", $conn);
    try {
        $stmt = $conn ->prepare("SELECT name FROM access_level WHERE id =:id");
        $stmt ->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt ->execute();
        $permission = $stmt ->fetch(PDO::FETCH_OBJ);

    } catch (PDOException $e){
        var_dump(
                $e ->getMessage()
        );
    }
    if ($button_list) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/list/access_levels'; ?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</a>
        </div>
        <div class="pull-left">
            <span class="h3 text-uppercase">PERMISSÕES - <?= $permission ->name?></span>
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
    <table id="permissions" class="display table table-stripped table-hover">
        <thead>
        <tr>
            <th>ENDEREÇO</th>
            <th>MENU</th>
            <th>OPÇÕES</th>
        </tr>
        </thead>
        <tbody>
        <?php

        try{
            $sql = "SELECT pal.id, pal.page_id, pal.access, pal.menu,al.name AS nva_acesso, al.position, p.path, p.name, p.description FROM page_access_level AS pal JOIN pages as p ON pal.page_id =  p.id JOIN access_level AS al ON pal.al_id = al.id WHERE pal.al_id =:id";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":id", $id, PDO::PARAM_INT);
            $res ->execute();
            $row = $res ->fetchAll(PDO::FETCH_OBJ);
            #var_dump($row);
        } catch(PDOException $e){
            var_dump($e ->getMessage());
        }

        foreach($row as $permission):
            ?>
            <tr>
                <td><?= $permission ->path?></td>
                <td class="text-uppercase"><?= $permission ->name?></td>
                <td>
                    <?php
                    if ($permission ->access == 1) {
                        echo "<a href='" . pg . "/process/edit/permission?id=" . $permission ->id . "' class='btn btn-xs btn-success'><span class='fa fa-unlock' data-toggle='tooltip' data-placement='top' title='Bloquear o acesso à página'></span></a>";
                    } else {
                        echo "<a href='" . pg . "/process/edit/permission?id=" . $permission ->id . "' class='btn btn-xs btn-danger'><span class='fa fa-lock' data-toggle='tooltip' data-placement='top' title='Liberar o acesso à página'></span></a>";
                    }

                    if ($permission ->menu == 1) {
                        echo "<a href='" . pg . "/process/edit/menu?id=" . $permission ->id . "' class='btn btn-xs btn-success'><span class='fa fa-unlock' data-toggle='tooltip' data-placement='top' title='Bloquear para o Menu'></span></a>";
                    } else {
                        echo "<a href='" . pg . "/process/edit/menu?id=" . $permission ->id . "' class='btn btn-xs btn-danger'><span class='fa fa-lock' data-toggle='tooltip' data-placement='top' title='Liberar para o Menu'></span></a>";
                    }

                    echo "<span class='badge' data-toggle='tooltip' data-placement='top' title='Ordem da Linha'>".$permission ->id."</span>";
                    if ($lin_executadas == 1) {
                        echo "<button type='button' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button> ";
                    } else {
                        echo "<a href = '" . pg . "/process/edit/menu?ordem=" . $permission ->id . "' class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span></a> ";
                    }
                    $lin_executadas ++;
                    ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#permissions').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                }
            });
        } );
    </script>
</div>