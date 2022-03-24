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
    <div class="pull-right">
        <a href="<?php echo pg . '/list/list_recados'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</button></a>
    </div>
    <div class="page-header"></div>
    <?php
        $post_id = filter_input(INPUT_GET, "post_id", FILTER_SANITIZE_NUMBER_INT);
        $sender_id = filter_input(INPUT_GET, "sender_id", FILTER_SANITIZE_NUMBER_INT);

        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
    ?>
    <form name="cadRecados" method="post" action="<?php echo pg; ?>/process/reg/proc_reg_recados" class="form-horizontal">
        <input type="hidden" name="post_id" value="<?=$post_id?>"/>
        <input type="hidden" name="recipient_id" value="<?=$sender_id?>">
        <?php
            if(isset($sender_id) && isset($post_id)):
                $sql = "SELECT UPPER(p.message) AS message FROM posts AS p WHERE p.id =:post_id";
                $res = $conn ->prepare($sql);
                $res ->bindValue(":post_id", $post_id, PDO::PARAM_INT);
                $res ->execute();
                $row = $res ->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="form-group">
            <label for="post" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <textarea class="form-control" id="post" name="post" rows="5" style="resize: none" disabled>
                    <?= $row["message"]?>
                </textarea>
            </div>
        </div>
        <?php
            endif;
        ?>
        <div class="form-group">
            <label for="recipient_id" class="col-sm-2 control-label">Destinatário</label>
            <div class="col-sm-10">
                <select id="recipient_id" name="recipient_id" class="form-control" <?= isset($sender_id)?"disabled":"required" ?>>
                    <?php
                        if(!empty($sender_id)){
                            $sql = "SELECT id, UPPER(name) AS name FROM users WHERE id =:id AND situation = 1";
                            $res = $conn ->prepare($sql);
                            $res ->bindValue(":id", $sender_id, PDO::PARAM_INT);
                            $res ->execute();
                            $row = $res ->fetch(PDO::FETCH_ASSOC);
                            echo "<option value='{$row["id"]}'>{$row["name"]}</option>";
                        } else {
                            echo "<option value='*'>[Todos]</option>";
                        }
                        $sql = "SELECT id, UPPER(name) AS name FROM users WHERE id !=:id AND situation = 1";
                        $res = $conn ->prepare($sql);
                        $res ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
                        $res ->execute();
                        $row = $res ->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($row as $user):
                    ?>
                    <option value="<?=$user["id"]?>"><?=$user["name"]?></option>
                    <?php
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="mensagem" class="col-sm-2 control-label">Mensagem</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="mensagem" name="mensagem" rows="5" autofocus placeholder="Deixe um recado para seus colegas." style="resize: none" required></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-xs btn-success pull-right">
                    <span class="glyphicon glyphicon-floppy-saved"></span> 
                    Cadastrar
                </button>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        /*Função que impede o envio do formulário pela tecla enter acidental*/        
        $(document).ready(function () {
            $('input').keypress(function (e) {
                var code = null;
                code = (e.keyCode ? e.keyCode : e.which);                
                return (code == 13) ? false : true;
            });
        });
    </script>
</div>
