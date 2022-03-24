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
    <?php
    $button_cad = load("register/reg_niveis_acesso", $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/reg_niveis_acesso'; ?>"><button type="button" class="btn btn-xs btn-success"><span class='glyphicon glyphicon-floppy-saved'></span> Cadastrar</button></a>
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
        /* Verificar botoes */
        $button_perm = load("list/list_permissoes", $conn);
        $button_edit = load("edit/edit_niveis_acesso", $conn);
        $button_view = load("viewer/view_niveis_acesso", $conn);
        $button_delete = load("process/del/del_niveis_acesso", $conn);

        // Início da paginação 
        $pg_rec = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;
        // Fim da paginação.

        if ($_SESSION["credentials"]["id"] == 1) {
            $sql = "SELECT al.id, al.name, al.position, al.created, al.modified FROM access_level AS al LIMIT :ini_pag, :result_pg";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
            $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
            $res ->execute();


        } else {

            try{
                $sql = "SELECT al.id, al.name, al.position, al.created, al.modified FROM access_level AS al
                    WHERE al.position >  '" . $_SESSION["credentials"]["position"] . "' 
                    ORDER BY al.position LIMIT :ini_pag, :result_pg";
                $res = $conn ->prepare($sql);
                $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
                $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
                $res ->execute();
            } catch (PDOException $e){
                setLog($e ->getFile());
                setLog($e ->getLine());
                setLog($e ->getMessage());
            }
        }
        $row = $res ->fetchAll(PDO::FETCH_ASSOC);
        var_dump(
            $row
        );
        ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th class="text-center hidden-xs">Ordem</th>
                        <th class="text-center hidden-xs">Criação</th>
                        <th class="text-center hidden-xs">Modificação</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lin_executadas = 1;
                    foreach($row as $access_level):
                        ?>
                        <tr>
                            <td><?php echo $access_level["id"]; ?></td>
                            <td><?php echo $access_level["name"]; ?></td>
                            <td class="text-center hidden-xs">
                                <?php 
                                    echo "<span class='badge'>".$access_level["position"]."</span>";
                                ?>
                            </td>
                            <td class="text-center hidden-xs"><?php echo date(DBR, strtotime($access_level['created'])); ?></td>
                            <td class="text-center hidden-xs"><?php
                    if (!empty($access_level["modified"])) {
                        echo date(DHBR, strtotime($access_level['modified']));
                    }
                        ?>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($lin_executadas == 1) {
                                    echo "<button type='button' class='btn btn-default btn-xs hidden-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button> ";
                                } else {
                                    echo "<a href = '" . pg . "/process/edit/edit_ordem?ordem=" . $access_level["position"] . "'><button type='button' class='btn btn-default btn-xs hidden-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button></a> ";
                                }
                                $lin_executadas ++;
                                if ($button_perm) {
                                    echo "<a href= '" . pg . "/list/list_permissoes?id=" . $access_level["id"] . "'><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Listar Permissões'><span class='glyphicon  glyphicon glyphicon-check'></span></button></a> ";
                                }
                                if ($button_view) {
                                    echo "<a href= '" . pg . "/viewer/view_niveis_acesso?id=" . $access_level["id"] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Nível de Acesso'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                                }
                                if ($button_edit) {
                                    echo "<a href= '" . pg . "/edit/edit_niveis_acesso?id=" . $access_level["id"] . "'><button type='button' class='btn btn-xs btn-warning hidden-xs' data-toggle='tooltip' data-placement='top' title='Editar Nível de Acesso'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                                }
                                if ($button_delete) {
                                    echo "<a href= '" . pg . "/process/del/del_niveis_acesso?id=" . $access_level["id"] . "'onclick=\"return confirm('Apagar nível de acesso?');\"><button type='button' class='btn btn-xs btn-danger hidden-xs' data-toggle='tooltip' data-placement='top' title='Remover Nível de Acesso'><span class='glyphicon glyphicon-remove'></span></button></a> ";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                    ?>
                </tbody>
            </table>
            <!-- Início da paginação-->
            <?php
            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM access_level";
            $result_pag = $conn ->prepare($sql_pag);
            $result_pag ->execute();
            $row_pag = $result_pag ->fetch(PDO::FETCH_ASSOC);
            $qnt_pag = ceil($row_pag['qnt_id'] / $result_pg);

            $maxlink = 5;
            echo "<nav class='text-right'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/list/list_niveis_acesso?pg=1 aria label='Previous'><span aria-hidden='true'>&laquo</span></a> ";
            for ($ipag = $pg - $maxlink; $ipag <= $pg - 1; $ipag++) {
                if ($ipag >= 1) {
                    echo "<li><a href='" . pg . "/list/list_niveis_acesso?pg=$ipag'>$ipag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pg <span class='sr-only'></span></a></li>";

            for ($dpag = $pg + 1; $dpag <= $pg + $maxlink; $dpag++) {
                if ($dpag < $qnt_pag) {
                    echo "<li><a href='" . pg . "/list/list_niveis_acesso?pg=$dpag'>$dpag </a></li>";
                }
            }
            echo "<li><a href='" . pg . "/list/list_niveis_acesso?pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
            ?>
        </div>
    </div>
