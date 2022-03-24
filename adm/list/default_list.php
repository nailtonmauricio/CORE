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
        //Buttons path
        $list_path = "list/list_paginas";
        $register_path = "register/reg_paginas";
        $edit_path = "edit/edit_paginas";
        $viewer_path = "viewer/view/paginas";

        //Button verification
        $btn_list = load("$list_path", $conn);
        $btn_register = load("$register_path", $conn);
        $btn_edit = load("$edit_path", $conn);
        $btn_view = load("$viewer_path", $conn);

        $sql = "SELECT * FROM documento_falta";
        $res = $conn ->prepare($sql);
        $res ->execute();
        $row = $res ->fetchAll(PDO::FETCH_ASSOC);

        var_dump($row);

//      /* $json = '{
//      "id": null,
//      "title": "Product Var Example",
//      "description": "Product Var Example",
//      "nbm": {
//        "id": "0"
//      },
//      "brand": {
//        "id": "2712",
//        "name": "10"
//      },
//      "origin": {
//        "id": "0"
//      },
//      "category": {
//        "id": "38762"
//      },
//      "model": "SM-804",
//      "warrantyText": "90 dias",
//      "warrantyTime": "3",
//      "weight": 0.3,
//      "height": 8,
//      "width": 12,
//      "length": 17,
//      "images": [{
//        "main": true,
//        "url": "https://ww.example.com.br/1.jpg"
//      }, {
//        "main": true,
//        "url": "https://ww.example.com.br/2.jpg"
//      }, {
//        "main": true,
//        "url": "https://ww.example.com.br/3.jpg"
//      }, {
//        "main": true,
//        "url": "https://ww.example.com.br/4.jpg"
//      }],
//      "priceFactor": 1,
//      "calculatedPrice": false,
//      "characteristics": [{
//        "index": 0,
//        "name": "Cor",
//        "value": "Prata"
//      }, {
//        "index": 1,
//        "name": "Marca",
//        "value": "Besi"
//      }, {
//        "index": 2,
//        "name": "Voltagem",
//        "value": "-"
//      }, {
//        "index": 3,
//        "name": "Compatibilidade",
//        "value": "Ideal para c\u00e2meras de pequeno porte com peso at\u00e9 900 gramas"
//      }, {
//        "index": 4,
//        "name": "Indica\u00e7\u00e3o de Uso",
//        "value": "C\u00e2meras fotogr\u00e1ficas digitais, anal\u00f3gicas, e web cam"
//      }, {
//        "index": 5,
//        "name": "Material\/Composi\u00e7\u00e3o",
//        "value": "Metal"
//      }, {
//        "index": 6,
//        "name": "Medida Aproximada",
//        "value": "Max 20 cent\u00edmetros de altura;  Dimens\u00e3o fechado: 150 mil\u00edmetros de altura; Extens\u00e3o aberto: 210 mil\u00edmetros de altura"
//      }, {
//        "index": 7,
//        "name": "Peso Aproximado",
//        "value": "33 gramas"
//      }, {
//        "index": 8,
//        "name": "Itens Inclusos",
//        "value": "1x  Mini trip\u00e9"
//      }],
//      "skus": [{
//        "price": 19.99,
//        "amount": "2.0000",
//        "additionalTime": 5,
//        "ean": null,
//        "partnerId": "113003600",
//        "title": "Mini Trip\u00e9 Universal Retr\u00e1til SM-804 Para C\u00e2mera Digital ",
//        "idProduct": null,
//        "internalIdProduct": "2573"
//      }]
//    }';
//
//
//        $j_decode = json_decode($json);
//    foreach ($j_decode ->characteristics as $characteristic){
//            var_dump(
//                    $characteristic
//                    );
//        }
//    foreach ($j_decode ->skus as $skus) {
//        var_dump(
//                $skus ->price
//        );
//    }*/
        if($btn_register):
    ?>
            <div class="pull-right">
                <a href="<?php echo pg . '/register/reg_paginas'; ?>"><button type="button" class="btn btn-xs btn-success"><span class='glyphicon glyphicon-floppy-saved'></span> Cadastrar</button></a>
            </div>
        <?php
        endif;
        ?>
    <div class="page-header">
        <?php
            if (isset($_SESSION["msg"])) {
                echo $_SESSION["msg"];
                unset($_SESSION["msg"]);
            }
        ?>
    </div>
    <div class="row">
        <div class="col-sm-4 search-group">
            <form action="<?php echo pg . '/list/default_list'; ?>" method="get">
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
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>HEAD</th>
                    <th>HEAD</th>
                    <th>HEAD</th>
                    <th class="text-center">ACTIONS</th>
                </tr>
                </thead>

                <tbody>
                <?php
                    $search = filter_input(INPUT_GET, "q", FILTER_SANITIZE_SPECIAL_CHARS);
                    if(isset($search)){
                        $sql= "SELECT p.id, p.path, p.name, p.description FROM pages AS p
                           WHERE p.name LIKE :search OR p.path LIKE :search
                           ORDER BY created";
                        $res = $conn ->prepare($sql);
                        $res ->bindValue(":search", "%".$search."%");
                    } else {
                        $current_page = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
                        $page = (!empty($current_page)) ? $current_page : 1;
                        $result_per_pagination = 5;
                        $initial_page = ($result_per_pagination * $page) - $result_per_pagination;
                        $sql = "SELECT p.id, p.path, p.name, p.description FROM pages AS p LIMIT :initial_page, :result_per_pagination";
                        $res= $conn->prepare($sql);
                        $res ->bindValue(":initial_page", $initial_page, PDO::PARAM_INT);
                        $res ->bindValue(":result_per_pagination", $result_per_pagination, PDO::PARAM_INT);
                    }
                    $res ->execute();
                    $row = $res->fetchAll(PDO::FETCH_ASSOC);

                    /*var_dump(
                            $row
                    );*/

                    if(empty($row)):
                ?>
                        <div class="alert alert-warning alert-dismissible text-center" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
                        </div>
                <?php
                    endif;
                    foreach ($row as $page):
                ?>
                        <tr>
                            <td><?=$page["path"]; ?></td>
                            <td><?=$page["name"]; ?></td>
                            <td><?=$page["description"];?></td>
                            <td class="text-center">
                                <?php
                                if ($btn_view) {
                                    echo "<a href= '" . pg . "/viewer/view_paginas?id=" . $page['id'] . "'><button type='button' class='btn btn-xs btn-info'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                                }
                                if ($btn_edit) {
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
    </div>
</div>