<?php 

	include_once("config/conn.php");

	//$unidade = $_REQUEST['unidade'];
	echo $unidade;
	echo "TEste";
	$sql = "SELECT * FROM setores WHERE unidade_id=$unidade ORDER BY nome";
	$result = mysqli_query($conn, $sql);
	
	while ($row = mysqli_fetch_assoc($result) ) {
		$setor[] = array(
			'id'	=> $row['id'],
			'nome' => utf8_encode($row['nome']),
		);
	}
	
	echo(json_encode($setor));
