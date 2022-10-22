<?php
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!empty($id)) {

    switch ($_SESSION["credentials"]["privilege"]){
        case 0;
            $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.cell_phone, u.user_name, u.situation, u.created, u.modified, al.name AS al_name FROM users AS u 
             JOIN access_level AS al ON u.access_level = al.id
             WHERE u.id =:user_id";
            $res = $conn ->prepare($sql);
            $res ->bindParam(":user_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
            break;
        case 1;
            $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.cell_phone, u.user_name, u.situation, u.created, u.modified, al.name AS al_name FROM users AS u 
             JOIN access_level AS al ON u.access_level = al.id
             WHERE u.id =:user_id";
            $res = $conn ->prepare($sql);
            $res ->bindParam(":user_id", $id, PDO::PARAM_INT);
            break;
    }

    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_OBJ);
    var_dump(
        $row
    );
    if ($res ->rowCount()) {

        ?>
        <div class="well content">
            <div class="pull-right">
                <?php
                $button_edit = load('edit/user', $conn);
                $button_list = load('list/users', $conn);
                $button_delete = load('process/del/user', $conn);
                if ($button_list) {
                    echo "<a href= '" . pg . "/list/users'><button type='button' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-list'></span> Listar</button></a> ";
                }
                if ($button_edit) {
                    echo "<a href= '" . pg . "/edit/user?id=" . $row ->id . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span> Editar</button></a> ";
                }
                if ($button_delete) {
                    echo "<a href= '" . pg . "/process/del/user?id=" . $row ->id . "'onclick=\"return confirm('Apagar usuário?');\"><button type='button' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span> Remover</button></a> ";
                }
                ?>
            </div>
            <div class="page-header"></div>
            <div class="dl-horizontal">
                <dt>Nome</dt>
                <dd class="text-uppercase"><?php echo $row  ->first_name ." ". $row ->last_name;?></dd>
                <dt>E-Mail</dt>
                <dd><?php echo $row ->email;?></dd>
                <dt>Usuário</dt>
                <dd class="text-uppercase"><?php echo $row->user_name;?></dd>
                <?php
                if(!empty($row ->recuperar_senha)){
                    echo "<dt>Recuperar Senha</dt>";
                    echo "<dd>".$row ->recuperar_senha."</dd>";
                }
                ?>
                <dt>Nível de Acesso</dt>
                <dd class="text-uppercase"><?= $row ->al_name;?></dd>
                <dt>Situação</dt>
                <dd class="text-uppercase">
                    <?php
                    switch ($row ->situation){
                        case 1:
                            echo "Ativo";
                            break;
                        case 2:
                            echo "Inativo";
                            break;
                    }
                    ?>
                </dd>
                <dt>Data de Cadastro</dt>
                <dd><?php echo date ("d/m/Y H:i:s", strtotime ($row ->created));?></dd>
                <dt>Ultima Alteração</dt>
                <dd><?php
                    if (!empty($row ->modified)){
                        echo date('d/m/Y H:i:s', strtotime($row ->modified));
                    } else {
                        echo $row ->modified;
                    }
                    ?>
                </dd>
            </div>
        </div>
        <?php
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Registro de usuário não encontrado!</div>";
        $url_destino = pg . "/list/users";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Whoops!&nbsp;</stron>"
        . "Registro de usuário não encontrado!</div>";
    $url_destino = pg . "/list/users";
    header("Location: $url_destino");
}
