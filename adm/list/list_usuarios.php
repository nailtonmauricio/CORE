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
    $button_cad = load("register/reg_usuarios", $conn);
    if ($button_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/register/reg_usuarios'; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
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

        $button_edit = load("edit/edit_usuarios", $conn);
        $button_view = load("viewer/view_usuarios", $conn);
        $button_delete = load("process/del/del_usuario", $conn);

        // Início da paginação 
        $pg_rec = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;
        // Fim da paginação.
        if ($_SESSION["credentials"]["access_level"] == 1) {
            $sql = "SELECT u.id, u.situation, UPPER(u.name) AS nome, UPPER(u.user_name) AS usuario, u.access_level, al.name AS nv_nome FROM users AS u JOIN access_level AS al ON u.access_level = al.id ORDER BY u.id LIMIT :ini_pag, :result_pg";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
            $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
        } else {
            $sql = "SELECT u.id, u.situation, UPPER(u.nome) AS name, UPPER(u.user_name) AS usuario, u.access_level, al.name AS nv_nome FROM users AS u JOIN access_level AS al ON users.access_level = al.id WHERE position > (SELECT position FROM access_level WHERE id =:nva_user_id) ORDER BY u.id LIMIT :ini_pag, :result_pg";
            $res = $conn ->prepare($sql);
            $res ->bindValue(":nva_user_id", $_SESSION["nva_user_id"], PDO::PARAM_INT);
            $res ->bindValue(":ini_pag", $ini_pag, PDO::PARAM_INT);
            $res ->bindValue(":result_pg", $result_pg, PDO::PARAM_INT);
        }
        $res ->execute();
        $row = $res ->fetchAll(PDO::FETCH_ASSOC);

        var_dump(
                $row
        );
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
            	$busca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
            	if(!empty($busca)){
            		$sql = "SELECT u.id, u.situation, UPPER(u.name) AS nome, UPPER(u.user_name) AS usuario, u.access_level, 
                    al.name AS nv_nome, u.situation FROM users AS u
                    JOIN access_level AS al ON u.access_level = al.id
                 	WHERE u.name LIKE :busca";
            		$res = $conn ->prepare($sql);
            		$res ->bindValue(":busca", "%$busca%", PDO::PARAM_STR);
            		$res ->execute();
            		$row = $res ->fetchAll(PDO::FETCH_ASSOC);

                    var_dump(
                            $row
                    );
            		?>
            			<div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th class="hidden-xs">Nome</th>
                        <th>Usuário</th>
                        <th class="hidden-xs">Nivel de Acesso</th>   
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($res ->rowCount() == 0){
                        echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <span aria-hidden='true'>&times;</span>
                              </button>
                              <strong>Aviso!</strong> Nem um registro encontrado na base de dados.
                              </div>";
                    }
                    foreach ($row as $user):
                        ?>
                        <tr>
                            <td><?php echo $user["id"]; ?></td>
                            <td class="hidden-xs"><?php echo $user["nome"]; ?></td>
                            <td><?php echo $user["usuario"]; ?></td>
                            <td class="text-uppercase hidden-xs"><?php echo $user["nv_nome"]; ?></td>
                            <td class="text-center">
                                <?php
                                if ($button_view) {
                                    echo "<a href= '" . pg . "/viewer/view_usuarios?id=" . $user["id"] . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                                }
                                if ($button_edit) {
                                    echo "<a href= '" . pg . "/edit/edit_usuarios?id=" . $user["id"] . "'><button type='button' class='btn btn-xs btn-warning hidden-xs'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                                }
                                if ($button_delete) {
                                    echo "<a href= '" . pg . "/process/del/del_usuario?id=" . $user["id"] . "' onclick=\"return confirm('Apagar registro?');\"><button type='button' class='btn btn-xs btn-danger hidden-xs'><span class='glyphicon glyphicon-remove'></span></button></a> ";
                                }
                                #inicio da alteração 
                                if ($user["situation"] == 1) {
                                					  	
                                    echo "<a href='" . pg . "/process/edit/proc_edit_usuario?id=" . $user["id"] . "'>
                                          	<button type='button' class='btn btn-xs btn-default'><span class='fa fa-unlock'></span></button>
                                          </a>";
                                } else {
                                                    	
                                    echo "<a href='" . pg . "/process/edit/proc_edit_usuario?id=" . $user["id"] . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-lock'></span></button>
                                          </a>";
                                                        
                                }#fim da alteração
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
                        <th>ID</th>
                        <th class="hidden-xs">Nome</th>
                        <th>Usuário</th>
                        <th class="hidden-xs">Nivel de Acesso</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($row as $user):
                        ?>
                        <tr>
                            <td><?php echo $user["id"]; ?></td>
                            <td class="hidden-xs"><?php echo $user["nome"]; ?></td>
                            <td><?php echo $user["usuario"]; ?></td>
                            <td class="text-uppercase hidden-xs"><?php echo $user["nv_nome"]; ?></td>
                            
                            <td class="text-center">
                                <?php
                                if ($button_view) {
                                    echo "<a href= '" . pg . "/viewer/view_usuarios?id=" . $user["id"] . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                                }
                                if ($button_edit) {
                                    echo "<a href= '" . pg . "/edit/edit_usuarios?id=" . $user["id"] . "'><button type='button' class='btn btn-xs btn-warning hidden-xs'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                                }
                                if ($button_delete) {
                                    echo "<a href= '" . pg . "/process/del/del_usuario?id=" . $user["id"] . "' onclick=\"return confirm('Apagar registro?');\"><button type='button' class='btn btn-xs btn-danger hidden-xs'><span class='glyphicon glyphicon-remove'></span></button></a> ";
                                }
                                #inicio da alteração 
                                if ($user["situation"] == 1) {
                                					  	
                                    echo "<a href='" . pg . "/process/edit/proc_edit_usuario?id=" . $user["id"] . "'>
                                          	<button type='button' class='btn btn-xs btn-default'><span class='fa fa-unlock'></span></button>
                                          </a>";
                                } else {
                                                    	
                                    echo "<a href='" . pg . "/process/edit/proc_edit_usuario?id=" . $user["id"] . "'>
                                            <button type='button' class='btn btn-xs btn-default'><span class='fa fa-lock'></span></button>
                                          </a>";
                                                        
                                }#fim da alteração
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
            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM users";
            $res_pag = $conn ->prepare($sql_pag);
            $res_pag ->execute();
            $row_pag = $res_pag ->fetch(PDO::FETCH_ASSOC);
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
