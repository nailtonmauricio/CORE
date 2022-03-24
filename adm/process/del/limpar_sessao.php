<?php

	session_start();
	//Funcionando para iniciar os testes

	unset($_SESSION['dados'], $_SESSION['lastID'], $_SESSION['control'], $_SESSION['colaborador'], 
		$_SESSION['sexo'], $_SESSION['dt_nasc'],$_SESSION['rg'], $_SESSION['cpf'], $_SESSION['cnh'], 
		$_SESSION['ctps'], $_SESSION['email_c'], $_SESSION['cel'], $_SESSION['fone'], $_SESSION['cidade'], 
		$_SESSION['bairro'], $_SESSION['rua'], $_SESSION['complemento'], $_SESSION['unidade'], 
		$_SESSION['setor'], $_SESSION['funcao'], $_SESSION['dt_admissao'], $_SESSION['edit'], 
		$_SESSION['bairro_id'], $_SESSION['cliente_id'], $_SESSION['rota'], $_SESSION['controle']);
		$_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
	    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
	    . "<span aria-hidden='true'>&times;</span>"
	    . "</button><strong>Aviso!&nbsp;</stron>"
	    . "Variáveis de sessão apagadas com sucesso!</div>";
	$url_destino = pg . "/home";
	header("Location: $url_destino");

?>
<!--<div class="well conteudo">
  <?php
   
  	//Buscar as informações das coletas do dia
  	/*$sqlColeta = "SELECT coletas.id, coletas.agendamento AS agendamento,
	clientes.rua, bairros.nome AS bairro FROM coletas
	JOIN clientes ON coletas.cliente_id = clientes.id
	JOIN bairros ON coletas.bairro_id =  bairros.id
	WHERE coletas.agendamento like '2019-06-09 %'";
	$resColeta = mysqli_query($conn, $sqlColeta);
	$rowColeta = mysqli_fetch_array($resColeta);*/


    /*$origins	= "R. Eulália Resende Pereira, 299 - Serrotão";
	$destinations	= "R. Afonso Campos, 68 - Centro";
	$mode	= "CAR";
	$language	= "PT";
	$sensor	= "false";

    //$request_url = "https://maps.googleapis.com/maps/api/distancematrix/xml?origins=''".$origins."''|&destinations=''".$destinations."''|&mode=''".$mode."''|&language=''".$language."''|&sensor=false&departure_time=now&key=AIzaSyBO-CC33HZoDVaFCB4FzWO1n-ZF0D7pGYc";
    $request_url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=''".$origins."''|&destinations=''".$destinations."''|&mode=''".$mode."''|&language=''".$language."''|&sensor=false&departure_time=now&key=AIzaSyBO-CC33HZoDVaFCB4FzWO1n-ZF0D7pGYc";
    if(!empty($request_url)){
    	header("Location:$request_url");
    }*/
  ?>

</div>-->