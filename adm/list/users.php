<?php
$button_edit = load("edit/user", $conn);
$button_view = load("viewer/user", $conn);
$button_delete = load("process/del/user", $conn);//APAGAR ESSE ARQUIVO DEPOIS, NÃO DEVE SER POSSÍVEL APAGAR USUÁRIOS
?>
<div class="well content">
    <?php
    $button_cad = load("register/user", $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/user'; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
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
    <table id="customers" class="display table table-stripped table-hover">
        <thead>
        <tr>
            <th>NOME</th>
            <th>SOBRENOME</th>
            <th>DATA</th>
            <th>OPÇÕES</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $row = paginator("id, first_name, last_name, created", "users", $conn);
        foreach($row as $user):
            ?>
            <tr>
                <td class="text-uppercase"><?= $user ->first_name?></td>
                <td class="text-uppercase"><?= $user ->last_name?></td>
                <td><?= date("d/m/Y", strtotime($user ->created)) ?></td>
                <td>
                    <?php
                    if ($button_view) {
                        echo "<a href= '" . pg . "/viewer/user?id=" . $user ->id . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                    }
                    if ($button_edit) {
                        echo "<a href= '" . pg . "/edit/user?id=" . $user ->id. "'><button type='button' class='btn btn-xs btn-warning hidden-xs'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                    }
                    if ($button_delete) {
                        echo "<a href= '" . pg . "/process/del/user?id=" . $user ->id. "' onclick=\"return confirm('Apagar registro?');\"><button type='button' class='btn btn-xs btn-danger hidden-xs'><span class='glyphicon glyphicon-remove'></span></button></a> ";
                    }
                    #inicio da alteração
                    if ($user ->situation == 1) {

                        echo "<a href='" . pg . "/process/edit/user?id=" . $user ->id . "'>
                                          	<button type='button' class='btn btn-xs btn-default'><span class='fa fa-unlock'></span></button>
                                          </a>";
                    } else {

                        echo "<a href='" . pg . "/process/edit/user?id=" . $user ->id . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-lock'></span></button>
                                          </a>";

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
            $('#customers').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                }
            });
        } );
    </script>
</div>