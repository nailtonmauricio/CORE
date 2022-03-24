<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
        <h1>Listar Usuários</h1>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }

        $button_edit = laod('edit/edit_usuarios', $conn);
        $button_view = laod('viewer/view_usuarios', $conn);
        $button_delete = laod('process/del/del_usuario', $conn);

        // Início da paginação 
        $pg_rec = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;
        // Fim da paginação.
        if ($_SESSION['nva_user_id'] == 1) {
            $sql = "SELECT usuarios.id, usuarios.stu_id, usuarios.nome, usuarios.usuario, usuarios.nva_id, 
                    nv_acessos.nome AS nv_nome, st_usuarios.nome AS situacao FROM usuarios
                    JOIN st_usuarios ON usuarios.stu_id =  st_usuarios.id
                    JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
                    ORDER BY usuarios.id LIMIT $ini_pag, $result_pg";
        } else {
            $sql = "SELECT usuarios.id, usuarios.stu_id, usuarios.nome, usuarios.usuario, usuarios.nva_id, 
                    nv_acessos.nome AS nv_nome, st_usuarios.nome AS situacao FROM usuarios
                    JOIN st_usuarios ON usuarios.stu_id =  st_usuarios.id
                    JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
                    WHERE ordem > (SELECT ordem FROM nv_acessos WHERE id ='" . $_SESSION['nva_user_id'] . "')
                    ORDER BY usuarios.id LIMIT $ini_pag, $result_pg";
        }
        $result = mysqli_query($conn, $sql);
        ?>
    </div>
    <div class="row">
        <div>
            <div class="col-sm-4 search-group">
                <form action="<?php echo pg . '/list/list_usuarios'; ?>" method="get">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="sr-only"><label for="buscar">Buscar no site</label></span>
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
        <?php
                $busca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
                if(!empty($busca)){
                    $sql = "SELECT usuarios.id, usuarios.stu_id, usuarios.nome, usuarios.usuario, usuarios.nva_id, 
                    nv_acessos.nome AS nv_nome, st_usuarios.nome AS situacao FROM usuarios
                    JOIN st_usuarios ON usuarios.stu_id =  st_usuarios.id
                    JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
                    WHERE usuarios.nome LIKE '%$busca%' OR st_usuarios.nome LIKE '$busca'";
                    $result = mysqli_query($conn, $sql);
                    ?>
                        <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Usuário</th>
                        <th>Nivel de Acesso</th>   
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($result) ==0){
                        echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <span aria-hidden='true'>&times;</span>
                              </button>
                              <strong>Aviso!</strong> Nem um registro encontrado na base de dados.
                              </div>";
                    }
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>                               
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $row['nv_nome']; ?></td>
                            <td class="text-center">
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
                                #inicio da alteração 
                                if ($row['stu_id'] == 1) {
                                                        
                                    echo "<a href='" . pg . "/process/edit/edit_situacao?id=" . $row['id'] . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-unlock'></span></button>
                                          </a>";
                                } else {
                                                        
                                    echo "<a href='" . pg . "/process/edit/edit_situacao?id=" . $row['id'] . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-lock'></span></button>
                                          </a>";
                                                        
                                }#fim da alteração
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
                    <?php

                } else {
                    ?>
                    <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Usuário</th>
                        <th>Nivel de Acesso</th>
                        
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>                               
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $row['nv_nome']; ?></td>
                            
                            <td class="text-center">
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
                                #inicio da alteração 
                                if ($row['stu_id'] == 1) {
                                                        
                                    echo "<a href='" . pg . "/process/edit/edit_situacao?id=" . $row['id'] . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-unlock'></span></button>
                                          </a>";
                                } else {
                                                        
                                    echo "<a href='" . pg . "/process/edit/edit_situacao?id=" . $row['id'] . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-lock'></span></button>
                                          </a>";
                                                        
                                }#fim da alteração
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
            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM usuarios";
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
                    echo "<li><a href='" . pg . "/list/list_usuarios?pg=$dpag'>$dpag </a></li>";
                }
            }
            echo "<li><a href='" . pg . "/list/list_usuarios?pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
            ?>
        </div>
                    <?php
                }
        ?>
    </div>
