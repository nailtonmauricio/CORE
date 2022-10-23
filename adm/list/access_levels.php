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
?>
<div class="well content">
    <?php
    $button_cad = load("register/access_level", $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/access_level'; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
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
    <table id="accessLevel" class="display table table-stripped table-hover">
        <thead>
        <tr>
            <th>NOME</th>
            <th>ORDEM</th>
            <th>CRIADO</th>
            <th>MODIFICADO</th>
            <th>OPÇÕES</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $row = paginator("id, name, position, created, modified", "access_level", $conn);
        foreach($row as $level):
            ?>
            <tr>
                <td class="text-uppercase"><?= $level ->name?></td>
                <td class="text-uppercase"><span class="badge"><?= $level ->position?></span></td>
                <td><?= $level ->created?></td>
                <td><?= $level ->modified?></td>
                <td>
                    <?php
                    if ($lin_executadas == 1) {
                        echo "<button type='button' class='btn btn-default btn-xs hidden-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button> ";
                    } else {
                        echo "<a href = '" . pg . "/process/edit/edit_ordem?ordem=" . $level ->position . "'><button type='button' class='btn btn-default btn-xs hidden-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button></a> ";
                    }
                    $lin_executadas ++;
                    if ($button_perm) {
                        echo "<a href= '" . pg . "/list/permissions?acid=" . $level ->id . "'><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Listar Permissões'><span class='glyphicon  glyphicon glyphicon-check'></span></button></a> ";
                    }
                    if ($button_view) {
                        echo "<a href= '" . pg . "/viewer/access_level?id=" . $level ->id . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Nível de Acesso'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                    }
                    if ($button_edit) {
                        echo "<a href= '" . pg . "/edit/access_level?id=" . $level ->id . "'><button type='button' class='btn btn-xs btn-warning hidden-xs' data-toggle='tooltip' data-placement='top' title='Editar Nível de Acesso'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                    }
                    if ($button_delete) {
                        echo "<a href= '" . pg . "/process/del/access_level?id=" . $level ->id . "'onclick=\"return confirm('Apagar nível de acesso?');\"><button type='button' class='btn btn-xs btn-danger hidden-xs' data-toggle='tooltip' data-placement='top' title='Remover Nível de Acesso'><span class='glyphicon glyphicon-remove'></span></button></a> ";
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
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#accessLevel').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                }
            });
        } );
    </script>
</div>