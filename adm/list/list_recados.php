<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: ../../index.php");
}
?>
<div class="well content">
    <div class="pull-right">
       <a href="<?php echo pg . "/register/reg_recados"; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
    </div>
    <div class="page-header">
        <?php
        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
        /* Verificar botoes */
        $button_register = load("register/reg_recados", $conn);
        $button_edit = load("process/edit/proc_edit_recados", $conn);
        // Início da paginação, recebe o valor do número da página atual 
        $pg_rec = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;

        //Fim da paginação.
        ?>
    </div>
    <div class="row">
        <div>
            <div class="col-sm-4 search-group">
                <form action="<?php echo pg . '/list/list_recados'; ?>" method="get">
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
            <?php
                $busca = filter_input(INPUT_GET, "q", FILTER_SANITIZE_STRING);

                if(!empty($busca)){
                    $sql ="SELECT p.id, p.sender_id, p.message, p.created, p.verify, u.name AS sender FROM posts AS p JOIN users AS u ON p.sender_id = u.id WHERE u.name LIKE :busca AND p.recipient_id =:id";
                    $res = $conn ->prepare($sql);
                    $res ->bindValue(":busca", "%".$busca."%");
                    $res ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
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
                        <th class="hidden-xs">Data</th>
                        <th>Remetente</th>
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
                              <strong>Aviso!</strong> Nem um registro encontrado na base de dados para este remetente.
                              </div>";
                    }
                        foreach ($row as $post):
                        ?>
                        <tr>
                            <td class="hidden-xs"><?php echo date("d/m/Y", strtotime($post["created"])); ?></td>
                            <td><?= $post["sender"]; ?></td>
                            <td class="text-center">
                                <?php
                                if(!$post["verify"]){
                                    echo "<a href='" . pg . "/process/edit/proc_edit_recados?id=" . $post["id"] . "' onclick=\"return confirm('{$post["message"]}');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Confirmar leitura.'><span class='fas fas fa-check-square'></span></button></a> ";
                                }else {
                                    echo "<a href='#'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Leitura confirmada.'><span class='fas fa-check-square'></span></button></a> ";
                                }
                                if (!$post["verify"]) {
                                    echo "<a href='" . pg . "/register/reg_recados?post_id=".$post["id"]."&sender_id=" . $post["sender_id"] . "'><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Responder mensagem.'><span class='fas fa-reply'></span></button></a> ";
                                }
                                if (!$post["verify"]) {
                                    echo "<a href='" . pg . "/viewer/view_recado?post_id=".$post["id"]."&sender_id=" . $post["sender_id"] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Ler mensagem.'><span class='fas fa-eye'></span></button></a> ";
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
                    $sql ="SELECT p.id, p.sender_id, p.message, p.created, p.verify, u.name AS sender FROM posts AS p JOIN users AS u ON p.sender_id = u.id WHERE p.recipient_id =:id";
                    $res = $conn ->prepare($sql);
                    $res ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
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
                        <th class="hidden-xs">Data</th>
                        <th>Remetente</th>
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
                              <strong>Aviso!</strong> Você não possui novos recados.
                              </div>";
                    }
                    foreach ($row as $post):
                        ?>
                        <tr>
                            <td class="hidden-xs"><?php echo date("d/m/Y H:i:s", strtotime($post["created"])); ?></td>
                            <td class="text-capitalize"><?= $post["sender"]; ?></td>
                            <td class="text-center">
                                <?php
                                    if(!$post["verify"]){
                                        echo "<a href='" . pg . "/process/edit/proc_edit_recados?id=" . $post["id"] . "' onclick=\"return confirm('{$post["message"]}');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Confirmar leitura.'><span class='fas fas fa-check-square'></span></button></a> ";
                                    }else {
                                        echo "<a href='#' onclick=\"return alert('{$post["message"]}');\"><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Leitura confirmada.'><span class='fas fa-check-square'></span></button></a> ";
                                    }
                                if (!$post["verify"]) {
                                    echo "<a href='" . pg . "/register/reg_recados?post_id=".$post["id"]."&sender_id=" . $post["sender_id"] . "'><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Responder mensagem.'><span class='fas fa-reply'></span></button></a> ";
                                }
                                if (!$post["verify"]) {
                                    echo "<a href='" . pg . "/viewer/view_recado?post_id=".$post["id"]."&sender_id=" . $post["sender_id"] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Ler mensagem.'><span class='fas fa-eye'></span></button></a> ";
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
            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM posts WHERE recipient_id =:id";
            $result_pag = $conn ->prepare($sql_pag);
            $result_pag ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
            $result_pag ->execute();
            $row_pag = $result_pag ->fetch(PDO::FETCH_ASSOC);
            $qnt_pag = ceil($row_pag["qnt_id"] / $result_pg);

            $maxlink = 5;
            echo "<nav class='text-right'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/list/list_recados?pg=1 aria label='Previous'><span aria-hidden='true'>&laquo</span></a> ";
            for ($ipag = $pg - $maxlink; $ipag <= $pg - 1; $ipag++) {
                if ($ipag >= 1) {
                    echo "<li><a href='" . pg . "/list/list_recados?pg=$ipag'>$ipag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pg <span class='sr-only'></span></a></li>";

            for ($dpag = $pg + 1; $dpag <= $pg + $maxlink; $dpag++) {
                if ($dpag < $qnt_pag) {
                    echo "<li><a href='" . pg . "/list/list_recados?pg=$dpag'>$dpag </a></li>";
                }
            }
            echo "<li><a href='" . pg . "/list/list_recados?pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
            ?>
        </div>
                <?php
                }
            ?>
        </div>
    </div>
