<?php

	if (!isset($_SESSION["check"])) {
	    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
	            . "<button type='button' class='close' data-dismiss='alert'>"
	            . "<span aria-hidden='true'>&times;</span>"
	            . "</button><strong>Aviso!&nbsp;</stron>"
	            . "Área restrita, faça login para acessar.</div>";
	    header("Location: ../index.php");
	}


$result = "";

/*Verifica as tabelas contidas na base de dados*/
$sql_table = "SHOW TABLES";
$res_table = $conn ->prepare($sql_table);
$res_table ->execute();
$row_table = $res_table ->fetchAll(PDO::FETCH_ASSOC);

foreach ($row_table as $table) {
    foreach ($table as $item_table){
        $sql_column = "SELECT * FROM {$item_table}";
        $res_column = $conn ->prepare($sql_column);
        $res_column ->execute();
        $column_count = $res_column ->columnCount();

        /* Apagando as tabelas caso existam*/
        $result .= "DROP TABLE IF EXISTS {$item_table};";

        /* Verificar DML das tabelas*/
        $sql_create_table = "SHOW CREATE TABLE {$item_table}";
        $res_create_table = $conn->prepare($sql_create_table);
        $res_create_table ->execute();
        $row_create_table = $res_create_table ->fetch(PDO::FETCH_NUM);
        $result .= "\n\n" . $row_create_table[1] . ";\n\n";

        for($i = 0; $i < $column_count; $i++){
            while($row_column = $res_column ->fetch(PDO::FETCH_NUM)){

                //Criar a intrução para inserir os dados
                $result .="INSERT INTO {$item_table} VALUES (";
                for($j = 0; $j < $column_count; $j++){
                    //addslashes — Adiciona barras invertidas a uma string
                    $row_column[$j] = addslashes($row_column[$j]);
                    //str_replace — Substitui todas as ocorrências da string \n pela \\n
                    $row_column[$j] = str_replace("\n", "\\n", $row_column[$j]);

                    if(isset($row_column[$j])){
                        if(!empty($row_column[$j])){
                            $result .= '"' . $row_column[$j].'"';
                        }else{
                            $result .= "NULL";
                        }
                    }else{
                        $result .= "NULL";
                    }

                    if($j < ($column_count - 1)){
                        $result .=',';
                    }
                }
                $result .= ");\n";
            }
        }
    }
}
//Criar o diretório de backup
$diretorio = __DIR__ . "/../backups/";
if(!is_dir($diretorio)){
    mkdir($diretorio, 0777, true);
    chmod($diretorio, 0777);
}

//Nome do arquivo de backup
$data = date("Y-m-d_H-i-s");
$nome_arquivo = $diretorio . "db_backup_".$data;
//echo $nome_arquivo;

$handle = fopen($nome_arquivo . ".sql", "w+");
fwrite($handle, $result);
fclose($handle);

//Montagem do link do arquivo
$download = $nome_arquivo . ".sql";


//echo "Realziar a rotina de backup";
if($handle){
    $url_return = pg . "/sair.php";
    header("Location: $url_return");
}