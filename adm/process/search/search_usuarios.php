<?php
//Verifica se a página foi acessada de forma direta pel aurl, faz o redirecionamento na linha 10 para a página de login.
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
?>
<div class="well conteudo">
    <?php
    $button_cad = laod('register/reg_usuarios', $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/reg_usuarios'; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
        </div>
        <?php
    }
    ?>
    <div class="page-header">
        <h1>Listar Arfácil</h1>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }

        $button_edit = laod('edit/edit_usuarios', $conn);
        $button_view = laod('viewer/view_usuarios', $conn);
        $button_delete = laod('process/del/del_usuario', $conn);

        // Início da filtragem da paginação 
        $pg_rec = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;
        // Fim da filtragem.
        $busca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
        $sql = "SELECT usuarios.*,st_usuarios.nome AS situacao, nv_acessos.nome AS nivel FROM usuarios 
                JOIN st_usuarios ON usuarios.stu_id = st_usuarios.id
                JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
                WHERE usuarios.stu_id = '$busca' OR usuarios.nome LIKE '%$busca%' 
                AND ordem > (SELECT ordem FROM nv_acessos WHERE id ='" . $_SESSION['nva_user_id'] . "')    
                ORDER BY created LIMIT $ini_pag, $result_pg";
        $result = mysqli_query($conn, $sql);
        if (mysqli_affected_rows($conn) > 0) {
            ?>
        </div>
        <div class="row">
            <div class="pull-right">
                <div class="col-sm-4 search-group">
                    <form action="<?php echo pg . '/process/search/search_usuarios'; ?>" method="get">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="sr-only"><label for="buscar">Buscar registro</label></span>
                                <input type="search" class="form-control" name="q" placeholder="Buscar registro" id="buscar">
                                <span class="input-group-addon">
                                    <button style="border:0;background:transparent;">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-12">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th class="hidden-xs">Usuário</th>
                            <th class="hidden-xs">Nível de Acesso</th>
                            <th>Situação</th>
                            <th class="text-center hidden-xs">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nome']; ?></td>                               
                                <td><?php echo $row['usuario']; ?></td>
                                <td><?php echo $row['nivel']; ?></td>
                                <td><?php echo $row['situacao']; ?></td>
                                <td class="text-right hidden-xs">
                                    <?php
                                    if ($button_view) {
                                        echo "<a href= '" . pg . "/viewer/view_usuarios?id=" . $row['id'] . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-file'></span> Visualizar</button></a> ";
                                    }
                                    if ($button_edit) {
                                        echo "<a href= '" . pg . "/edit/edit_usuarios?id=" . $row['id'] . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span> Editar</button></a> ";
                                    }
                                    if ($button_delete) {
                                        echo "<a href= '" . pg . "/process/del/del_usuario?id=" . $row['id'] . "' onclick=\"return confirm('Apagar registro?');\"><button type='button' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span> Apagar</button></a> ";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Início da paginação-->
                <?php
                $sql_pag = "SELECT COUNT(id) AS qnt_id FROM usuarios
                            WHERE usuarios.stu_id = '$busca' OR usuarios.nome LIKE '%$busca%'";
                $result_pag = mysqli_query($conn, $sql_pag);
                $row_pag = mysqli_fetch_assoc($result_pag);
                $qnt_pag = ceil($row_pag['qnt_id'] / $result_pg);

                $maxlink = 5;
                echo "<nav class='text-right'>";
                echo "<ul class='pagination'>";
                echo "<li><a href='" . pg . "/list/list_usuarios?pg=1 aria label='Previous'><span aria-hidden='true'>&laquo</span></a> ";
                for ($ipag = $pg - $maxlink; $ipag <= $pg - 1; $ipag++) {
                    if ($ipag >= 1) {
                        echo "<li><a href='" . pg . "/list/list_usuarios?pg=$ipag'>$ipag </a></li>";
                    }
                }

                echo "<li class='active'><a href='#'> $pg <span class='sr-only'></span></a></li>";

                for ($dpag = $pg + 1; $dpag <= $pg + $maxlink; $dpag++) {
                    if ($dpag < $qnt_pag) {
                        echo "<li><a href='" . pg . "/list/list_usuariosl?pg=$dpag'>$dpag </a></li>";
                    }
                }
                echo "<li><a href='" . pg . "/list/list_usuarios?pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
                ?>
            </div>
        </div>
        <?php
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Nem um registro encontrado!</div>";
        $url_destino = pg . "/list/list_usuarios";
        header("Location: $url_destino");
    }
        