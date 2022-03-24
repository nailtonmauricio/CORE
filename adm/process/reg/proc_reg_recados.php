<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;
    var_dump(
        $data
    );

   if($data["recipient_id"] === "*"){
       echo "Mandar mensagem em massa";
       $sql_search = "SELECT id, situation FROM users WHERE situation = 1 AND id !=:user_id";
       $res_search = $conn ->prepare($sql_search);
       $res_search ->bindValue(":user_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
       $res_search ->execute();

       if($res_search ->rowCount()){
           $row_search = $res_search ->fetchAll(PDO::FETCH_ASSOC);
           foreach ($row_search as $find){
               try {
                   $sql = "INSERT INTO posts (sender_id, recipient_id, message) VALUES (:sender_id, :recipient_id, :message)";
                   $res = $conn->prepare($sql);
                   $res ->bindValue(":sender_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
                   $res ->bindValue(":recipient_id", $find["id"], PDO::PARAM_INT);
                   $res ->bindValue(":message", mb_strtolower($data["mensagem"]));
                   $res ->execute();

               } catch(PDOException $e){
                   setLog($e ->getFile());
                   setLog($e ->getMessage());
               }
           }

           var_dump(
               $row_search
           );

           $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
               . "<button type='button' class='close' data-dismiss='alert'>"
               . "<span aria-hidden='true'>&times;</span>"
               . "</button><strong>Aviso!&nbsp;</stron>"
               . "Mensagem enviada com sucesso!</div>";
           header("Location: ".pg."/list/list_recados");
       } else {
           $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
               . "<button type='button' class='close' data-dismiss='alert'>"
               . "<span aria-hidden='true'>&times;</span>"
               . "</button><strong>Whoops!&nbsp;</stron>"
               . "Não existem destinatários para sua postagem.</div>";
           header("Location: ".pg."/list/list_recados");
       }
   } else {
       try{
           $sql = "INSERT INTO posts (sender_id, recipient_id, message) VALUES (:sender_id, :recipient_id, :message)";
           $res = $conn->prepare($sql);
           $res ->bindValue(":sender_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
           $res ->bindValue(":recipient_id", $data["recipient_id"], PDO::PARAM_INT);
           $res ->bindValue(":message", mb_strtolower($data["mensagem"]));
           $res ->execute();

           if(isset($data["post_id"])){
               try {
                   $sql = "UPDATE posts SET verify = 1 WHERE id =:post_id";
                   $res = $conn ->prepare($sql);
                   $res ->bindValue(":post_id", $data["post_id"], PDO::PARAM_INT);
                   $res ->execute();
               } catch (PDOException $e){
                   setLog($e ->getFile());
                   setLog($e ->getMessage());

                   $error = true;
               }
           }

           $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
               . "<button type='button' class='close' data-dismiss='alert'>"
               . "<span aria-hidden='true'>&times;</span>"
               . "</button><strong>Aviso!&nbsp;</stron>"
               . "Mensagem enviada com sucesso!</div>";
           header("Location: ".pg."/list/list_recados");
       } catch (PDOException $e){
           setLog($e ->getFile());
           setLog($e ->getMessage());

           $error = true;
       }

   }

   if($error){
       $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
           . "<button type='button' class='close' data-dismiss='alert'>"
           . "<span aria-hidden='true'>&times;</span>"
           . "</button><strong>Whoops!&nbsp;</stron>"
           . "O recado não pode ser enviado, entrem em contato com o Administrador!</div>";
       header("Location: ".pg."/register/reg_recados");
   }
} else {
	$_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "O recado não pode ser enviado, entrem em contato com o Administrador!</div>";
    $url_return = pg . "/register/reg_recados";
    header("Location: $url_return");
}
