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

// Início da paginação, recebe o valor do número da página atual
$pg_rec = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
$pg = (!empty($pg_rec)) ? $pg_rec : 1;
$result_pg = 5;
$ini_pag = ($result_pg * $pg) - $result_pg;

if (!empty($id)) {
    
    if ($_SESSION["credentials"]["id"] == 1) {
        $sql = "SELECT pal.id, pal.page_id, pal.access, pal.menu,al.name AS nva_acesso, al.position, p.path, p.name, p.description FROM page_access_level AS pal JOIN pages as p ON pal.page_id =  p.id JOIN access_level AS al ON pal.al_id = al.id WHERE pal.al_id =:id ORDER BY pal.page_id LIMIT :ini_pag, :result_pg";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":id", $id, PDO::PARAM_INT);
        $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
        $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
    } else {
        $sql = "SELECT pal.id, pal.page_id, pal.access, pal.menu,al.name AS nva_acesso, al.position, p.path, p.name, p.description FROM page_access_level AS pal JOIN pages as p ON pal.page_id =  p.id JOIN access_level AS al ON pal.al_id = al.id WHERE pal.al_id =:id AND al.position > :position ORDER BY pal.page_id LIMIT :ini_pag, :result_pg";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":id", $id, PDO::PARAM_INT);
        $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
        $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
        $res ->bindValue(":position", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
    }

    $res ->execute();
    $row = $res ->fetchAll(PDO::FETCH_ASSOC);
    var_dump(
            $row
    );
    if ($res->rowCount()) {
        $sql_nva = "SELECT al.name AS nva_acesso FROM page_access_level AS pal JOIN access_level AS al ON pal.al_id = al.id WHERE pal.al_id =:id ORDER BY pal.page_id";
        $res_nva = $conn ->prepare($sql_nva);
        $res_nva ->bindValue(":id", $id, PDO::PARAM_INT);
        $res_nva -> execute();
        $row_nva = $res_nva ->fetch(PDO::FETCH_ASSOC);

        var_dump(
            $row_nva
        );
        ?>
        <div class="well content">
            <?php
            $button_cad = load('list/list_niveis_acesso', $conn);
            if ($button_cad):
                ?>
                <div class="pull-right">
                    <a href="<?php echo pg . '/list/list_niveis_acesso'; ?>">
                        <button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button>
                    </a>
                </div>
                <?php
            endif;
            ?>
            <div class="page-header">
                <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
                <h1 class="text-uppercase"><strong><?php echo $row_nva["nva_acesso"]; ?></strong></h1>
            </div>
            <div class="row">
                <div>
                    <div class="col-sm-4 search-group"><!-- Formulário para realizar buscas dentro da página listar permissões, onde você pode buscar uma determinada página para liberar-->
                        <form action="<?php echo pg . '/list/list_permissoes'; ?>" method="get">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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
                    <?php
                    $busca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

                    if (!empty($busca)) {
                        $sql_pagina = "SELECT pal.id, pal.page_id, pal.access, pal.menu,al.name AS nva_acesso, al.position, p.path, p.name, p.description FROM page_access_level AS pal JOIN pages as p ON pal.page_id =  p.id JOIN access_level AS al ON pal.al_id = al.id WHERE pal.al_id =:id AND (p.name LIKE :busca OR p.path LIKE :busca)";
                        $res_paginas = $conn ->prepare($sql_pagina);
                        $res_paginas ->bindValue(":id", $id, PDO::PARAM_INT);
                        $res_paginas ->bindValue(":busca", "%".$busca."%");
                        $res_paginas ->execute();
                        $row_paginas = $res_paginas ->fetchAll(PDO::FETCH_ASSOC);

                        var_dump(
                                $row_paginas
                        );
                        ?>

                        <div class="col-md-12">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome Página</th>
                                        <th class="hidden-xs">Endereço</th>
                                        <th class="text-center" colspan="4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(empty($row_paginas)){
                                        echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                                              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                              <span aria-hidden='true'>&times;</span>
                                              </button>
                                              <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
                                              </div>";
                                    }
                                    $lin_executadas = 1;
                                        foreach ($row_paginas as $permissao):
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if (!empty($permissao["description"])) {
                                                        echo "<span class='glyphicon glyphicon-question-sign' data-toggle='tooltip' data-placement='top' title='" . $permissao["description"] . "'></span>";
                                                    } else {
                                                        echo "&nbsp;&nbsp;&nbsp;";
                                                    }
                                                    ?>
                                                    <?php echo $permissao["name"]; ?>
                                                </td>
                                                <td class="hidden-xs"><?php echo $permissao["path"]; ?></td>
                                                <td>
                                                    <?php
                                                    if ($permissao["access"] == 1) {
                                                        echo "<a href='" . pg . "/process/edit/edit_permissao?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-success'><span class='fa fa-unlock' data-toggle='tooltip' data-placement='top' title='Bloquear o acesso à página'></span></button>
                                                          </a>";
                                                    } else {
                                                        echo "<a href='" . pg . "/process/edit/edit_permissao?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-danger'><span class='fa fa-lock' data-toggle='tooltip' data-placement='top' title='Liberar o acesso à página'></span></button>
                                                          </a>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($permissao["menu"] == 1) {
                                                        echo "<a href='" . pg . "/process/edit/edit_menu?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-success'><span class='fa fa-unlock' data-toggle='tooltip' data-placement='top' title='Bloquear para o Menu'></span></button>
                                                          </a>";
                                                    } else {
                                                        echo "<a href='" . pg . "/process/edit/edit_menu?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-danger'><span class='fa fa-lock' data-toggle='tooltip' data-placement='top' title='Liberar para o Menu'></span></button>
                                                          </a>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php
                                                	echo "<span class='badge' data-toggle='tooltip' data-placement='top' title='Ordem da Linha'>".$permissao["position"]."</span>";
                                                ?>	
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($lin_executadas == 1) {
                                                        echo "<button type='button' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button> ";
                                                    } else {
                                                        echo "<a href = '" . pg . "/process/edit/edit_ordem_menu?ordem=" . $permissao["position"] . "'><button type='button' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='top' title='Alterar Ordem'>
                                          <span class='glyphicon glyphicon-arrow-up' ></span>
                                          </button></a> ";
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
                        </div>

                        <?php
                    } else {
                        ?>
                        <div class="col-md-12">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome Página</th>
                                        <th class="hidden-xs">Endereço</th>
                                        <th class="text-center" colspan="4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $lin_executadas = 1;
                                    foreach ($row as $permissao):
                                        $sql_qnt = "SELECT COUNT(id) AS qnt_per FROM page_access_level WHERE page_id = '" . $permissao['page_id'] . "' AND al_id = '" . $_SESSION['credentials']['access_level'] . "' LIMIT 1";
                                        $result_qnt = $conn->prepare($sql_qnt);
                                        $result_qnt ->execute();
                                        $row_qnt = $result_qnt ->fetch(PDO::FETCH_ASSOC);
                                        if ($row_qnt['qnt_per'] > 0) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if (!empty($permissao["description"])) {
                                                        echo "<span class='glyphicon glyphicon-question-sign' data-toggle='tooltip' data-placement='top' title='" . $permissao["description"] . "'></span>";
                                                    } else {
                                                        echo "&nbsp;&nbsp;&nbsp;";
                                                    }
                                                    ?>
                                                    <?php echo $permissao["name"]; ?>
                                                </td>
                                                <td class="hidden-xs"><?php echo $permissao["path"]; ?></td>
                                                <td>
                                                    <?php
                                                    if ($permissao["access"] == 1) {
                                                        echo "<a href='" . pg . "/process/edit/edit_permissao?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-success'><span class='fa fa-unlock' data-toggle='tooltip' data-placement='top' title='Bloquear acesso à página'></span></button>
                                                          </a>";
                                                    } else {
                                                        echo "<a href='" . pg . "/process/edit/edit_permissao?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-danger'><span class='fa fa-lock' data-toggle='tooltip' data-placement='top' title='Liberar acesso à página'></span></button>
                                                          </a>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($permissao["menu"] == 1) {
                                                        echo "<a href='" . pg . "/process/edit/edit_menu?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-success'><span class='fa fa-unlock' data-toggle='tooltip' data-placement='top' title='Bloquear para o Menu'></span></button>
                                                          </a>";
                                                    } else {
                                                        echo "<a href='" . pg . "/process/edit/edit_menu?id=" . $permissao["id"] . "'>
                                                            <button type='button' class='btn btn-xs btn-danger'><span class='fa fa-lock' data-toggle='tooltip' data-placement='top' title='Liberar para o Menu'></span></button>
                                                          </a>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        echo "<span class='badge' data-toggle='tooltip' data-placement='top' title='Ordem da Linha'>".$permissao["position"]."</span>";
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($lin_executadas == 1) {
                                                        echo "<button type='button' class='btn btn-default btn-xs'>
                                          <span class='glyphicon glyphicon-arrow-up' data-toggle='tooltip' data-placement='top' title='Editar Ordem'></span>
                                          </button> ";
                                                    } else {
                                                        echo "<a href = '" . pg . "/process/edit/edit_ordem_menu?ordem=" . $permissao["position"] . "'><button type='button' class='btn btn-default btn-xs'>
                                          <span class='glyphicon glyphicon-arrow-up' data-toggle='tooltip' data-placement='top' title='Editar Ordem'></span>
                                          </button></a> ";
                                                    }
                                                    $lin_executadas ++;
                                                    ?>
                                                </td>

                                            </tr>
                                            <?php
                                        }
                                    //}
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                            <!-- Início da paginação-->
                            <?php
                            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM page_access_level WHERE al_id =:id";
                            $result_pag = $conn ->prepare($sql_pag);
                            $result_pag ->bindValue(":id", $id, PDO::PARAM_INT);
                            $result_pag ->execute();
                            $row_pag = $result_pag ->fetch(PDO::FETCH_ASSOC);
                            $qnt_pag = ceil($row_pag['qnt_id'] / $result_pg);

                            $maxlink = 5;
                            echo "<nav class='text-right'>";
                            echo "<ul class='pagination'>";
                            echo "<li><a href='" . pg . "/list/list_permissoes?id=$id&pg=1 aria label='Previous'><span aria-hidden='true'>&laquo</span></a> ";
                            for ($ipag = $pg - $maxlink; $ipag <= $pg - 1; $ipag++) {
                                if ($ipag >= 1) {
                                    echo "<li><a href='" . pg . "/list/list_permissoes?pg=$ipag&id=$id'>$ipag </a></li>";
                                }
                            }

                            echo "<li class='active'><a href='#'> $pg <span class='sr-only'></span></a></li>";

                            for ($dpag = $pg + 1; $dpag <= $pg + $maxlink; $dpag++) {
                                if ($dpag < $qnt_pag) {
                                    echo "<li><a href='" . pg . "/list/list_permissoes?pg=$dpag&id=$id'>$dpag </a></li>";
                                }
                            }
                            echo "<li><a href='" . pg . "/list/list_permissoes?id=$id&pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>

        <?php
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Nem uma permissão encontrada para o nível de acesso selecionado!</div>";
        $url_destino = pg . "/list/list_niveis_acesso";
        header("Location: $url_destino");
    }
    ?>
        </div>
            <?php
        } else {
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Nem uma permissão encontrada para o nível de acesso selecionado!</div>";
            $url_destino = pg . "/list/list_niveis_acesso";
            header("Location: $url_destino");
        }