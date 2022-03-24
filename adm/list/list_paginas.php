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
    $button_cad = load("register/reg_paginas", $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/reg_paginas'; ?>"><button type="button" class="btn btn-xs btn-success"><span class='glyphicon glyphicon-floppy-saved'></span> Cadastrar</button></a>
            <?php
        }
        ?>
    </div>
    <div class="page-header">
        <?php
        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
        /* Verificar botoes */

        $button_edit = load("edit/edit_paginas", $conn);
        $button_view = load("viewer/view_paginas", $conn);

        // Início da paginação, recebe o valor do número da página atual 
        $pg_rec = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;
        $sql = "SELECT p.id, p.path, p.name, p.description FROM pages AS p LIMIT :ini_pag, :result_pg";
        $res= $conn->prepare($sql);
        $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
        $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
        $res ->execute();
        $row = $res->fetchAll(PDO::FETCH_ASSOC);

        var_dump(
            $row
        );
        // Fim da paginação.
        ?>
    </div>
    <div class="row">
        <div>
            <div class="col-sm-4 search-group">
                <form action="<?php echo pg . '/list/list_paginas'; ?>" method="get">
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
            <?php
                $busca = filter_input(INPUT_GET, "q", FILTER_SANITIZE_STRING);

                if(!empty($busca)){
                    
                    $sql= "SELECT p.id, p.path, p.name, p.description FROM pages AS p
                           WHERE p.name LIKE :busca OR p.path LIKE :busca
                           ORDER BY created";
                    $res = $conn ->prepare($sql);
                    $res ->bindValue(":busca", "%".$busca."%", PDO::PARAM_STR);
                    $res ->execute();
                    $row = $res->fetchAll(PDO::FETCH_ASSOC);

                    var_dump(
                            $row
                    );
            ?>
                <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Endereço</th>
                        <th>Menu</th>
                        <th>Descrição</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(empty($row)){
                        echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                                              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                              <span aria-hidden='true'>&times;</span>
                                              </button>
                                              <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
                                              </div>";
                    }

                    foreach ($row as $page):
                        ?>
                        <tr>
                            <td><?=$page["path"]; ?></td>
                            <td><?=$page["name"]; ?></td>
                            <td><?=$page["description"];?></td>
                            <td class="text-center">
                                <?php
                                if ($button_view) {
                                    echo "<a href= '" . pg . "/viewer/view_paginas?id=" . $page['id'] . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                                }
                                if ($button_edit) {
                                    echo "<a href= '" . pg . "/edit/edit_paginas?id=" . $page['id'] . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                                }
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
                        <th>Endereço</th>
                        <th>Menu</th>
                        <th>Descrição</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($row as $page):
                        ?>
                        <tr>
                            <td><?=$page["path"]; ?></td>
                            <td><?=$page["name"]; ?></td>
                            <td><?=$page["description"];?></td>
                            <td class="text-center">
                                <?php
                                if ($button_view) {
                                    echo "<a href= '" . pg . "/viewer/view_paginas?id=" . $page['id'] . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                                }
                                if ($button_edit) {
                                    echo "<a href= '" . pg . "/edit/edit_paginas?id=" . $page['id'] . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span></button></a> ";
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
            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM pages";
            $result_pag = $conn->prepare($sql_pag);
            $result_pag ->execute();
            $row_pag = $result_pag->fetch(PDO::FETCH_ASSOC);
            $qnt_pag = ceil($row_pag["qnt_id"] / $result_pg);

            $maxlink = 5;
            echo "<nav class='text-right'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/list/list_paginas?pg=1 aria label='Previous'><span aria-hidden='true'>&laquo</span></a> ";
            for ($ipag = $pg - $maxlink; $ipag <= $pg - 1; $ipag++) {
                if ($ipag >= 1) {
                    echo "<li><a href='" . pg . "/list/list_paginas?pg=$ipag'>$ipag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pg <span class='sr-only'></span></a></li>";

            for ($dpag = $pg + 1; $dpag <= $pg + $maxlink; $dpag++) {
                if ($dpag < $qnt_pag) {
                    echo "<li><a href='" . pg . "/list/list_paginas?pg=$dpag'>$dpag </a></li>";
                }
            }
            echo "<li><a href='" . pg . "/list/list_paginas?pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
            ?>
        </div>
                <?php
                }
            ?>
        </div>
    </div>
