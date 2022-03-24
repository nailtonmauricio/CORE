<?php
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    $sql = "SELECT p.id, p.name, p.path, p.description, p.created, p.modified FROM pages AS p WHERE id =:id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":id", $id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);
    var_dump(
            $row
    );
    if ($res ->rowCount()) {
        //$row = mysqli_fetch_assoc($result);
        ?>
        <div class="well content">
            <div class="pull-right">
                <?php
                $button_edit = load('edit/edit_paginas', $conn);
                $button_list = load('list/list_paginas', $conn);
                
                if ($button_list) {
                    echo "<a href= '" . pg . "/list/list_paginas'><button type='button' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-list'></span> Listar</button></a> ";
                }
                if ($button_edit) {
                    echo "<a href= '" . pg . "/edit/edit_paginas?id=" . $row['id'] . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span> Editar</button></a> ";
                }

                ?>
            </div>
            <div class="page-header"></div>
            <div class="dl-horizontal">
                <dt>Id</dt>
                <dd><?php echo $row['id']; ?></dd>
                <dt>Nome</dt>
                <dd><?php echo $row['name']; ?></dd>
                <dt>Endereço</dt>
                <dd><?php echo $row['path']; ?></dd>
                <dt>Descrição</dt>
                <dd><?php echo $row['description']; ?></dd>
                <dt>Cadastrada</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row ["created"])); ?></dd>
                <dt>Ultima Alteração</dt>
                <dd><?php
                    if (!empty($row['modified'])) {
                        echo date('d/m/Y', strtotime($row['modified']));
                    } else {
                        echo $row['modified'];
                    }
                    ?>
                </dd>
            </div>
        </div>
        <?php
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Registro de usuário não encontrado!</div>";
        $url_destino = pg . "/list/list_paginas";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Registro não encontrado!</div>";
    $url_destino = pg . "/list/list_paginas";
    header("Location: $url_destino");
}

