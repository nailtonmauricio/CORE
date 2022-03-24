<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;

    $data["start"] = convertDbDateTime($data["start"]);
    $data["end"]   = convertDbDateTime($data["end"]);


    try {
        $sql = "INSERT INTO events (title, color, start, end, user_id, description) VALUES(:title, :color, :start, :end, :user_id, :description)";
        $res = $conn  ->prepare($sql);
        $res ->bindValue(":title", $data["title"]);
        $res ->bindValue(":color", $data["color"]);
        $res ->bindValue(":start", $data["start"]);
        $res ->bindValue(":end", $data["end"]);
        $res ->bindValue(":user_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
        $res ->bindValue(":description", empty($data["description"])?NULL:$data["description"]);
        $res ->execute();

        header("Location:".pg."/list/list_events");
    } catch (PDOException $e){
        setLog($e ->getFile());
        $error = true;
    }

    if($error){

        header("Location:".pg."/list/list_events");
    }
}

/*if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    $erro = false;
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (empty($dados['title'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha o campo TÍTULO (msg 1)!</div>";
    } elseif (empty($dados['color'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Escolha a cor do EVENTO (msg 2)!</div>";
    } elseif (empty($dados['start'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha a data de início do evento (msg 3)!</div>";
    } elseif (empty($dados['end'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha a data de encerramento do evento (msg 4)!</div>";
    } elseif (empty($dados['descricao'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha a descrição do evento (msg 5)!</div>";
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/home";
        header("Location: $url_destino");
    } else {
        //Converter a data de iníco para o padrão do 
        $data = explode(" ", $dados['start']);
        list($date, $hora) = $data;
        $data_sem_barra = array_reverse(explode("/", $date));
        $data_sem_barra = implode("-", $data_sem_barra);
        $start_sem_barra = $data_sem_barra . " " . $hora;

        $data = explode(" ", $dados['end']);
        list($date, $hora) = $data;
        $data_sem_barra = array_reverse(explode("/", $date));
        $data_sem_barra = implode("-", $data_sem_barra);
        $end_sem_barra = $data_sem_barra . " " . $hora;

        //Removendo as quebras de linhas para salvar no banco de dados.
        $descricao = preg_replace('/[\n|\r|\n\r|\r\n]{2,}/',' ', $dados['descricao']);
        
        $sql = "INSERT INTO events VALUES (DEFAULT, '" . $dados['title'] . "', '" . $dados['color'] . "','" . $start_sem_barra . "',
                                        '" . $end_sem_barra . "', '" . $_SESSION['id'] . "', '$descricao', NOW(),NULL)";
        $result = mysqli_query($conn, $sql);
        if (mysqli_insert_id($conn)) {
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Evento cadastrado com sucesso!</div>";
            $url_destino = pg . "/viewer/view_agenda";
            header("Location: $url_destino");
        } else {
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Erro ao cadastrar evento!</div>";
            $url_destino = pg . "/viewer/view_agenda";
            header("Location: $url_destino");
        }
    }
} else {
    // Este esle é chamado caso o botão cadastrar evendo não seja clicado para acionar a página.
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Seu nível de acesso não permite executar este procedimento!</div>";
    $url_destino = pg . "/home";
    header("Location: $url_destino");
}*/