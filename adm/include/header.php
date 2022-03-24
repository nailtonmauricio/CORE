<div class="header-admin">
    <div class="pull-left" style="margin-left: 20px; margin-top: 15px;">
        <script type="text/javascript">

			var tempo = new Number();
			// Tempo em segundos
			tempo = 1800;

			function startCountdown(){

				// Se o tempo não for zerado
				if((tempo - 1) >= 0){

					// Pega a parte inteira dos minutos
					var min = parseInt(tempo/60);
					// Calcula os segundos restantes
					var seg = tempo%60;

					// Formata o número menor que dez, ex: 08, 07, ...
					if(min < 10){
						min = "0"+min;
						min = min.substr(0, 2);
					}
					if(seg <=9){
						seg = "0"+seg;
					}

					// Cria a variável para formatar no estilo hora/cronômetro
					horaImprimivel = '00:' + min + ':' + seg;
					//JQuery pra setar o valor
					$("#clock").html(horaImprimivel);

					// Define que a função será executada novamente em 1000ms = 1 segundo
					setTimeout('startCountdown()',1000);

					// diminui o tempo
					tempo--;

				// Quando o contador chegar a zero faz logoff e encerra a sessão
				} else {
					location.href="<?php echo pg; ?>/sair.php";
				}
			}
			// Chama a função ao carregar a tela
			startCountdown();
		</script>
        <strong>
            <?php
                //echo $date = date('d/m/Y');
            	echo "Tempo restante";
            ?>
        </strong>
        <strong><span id="clock"></span></strong>
    </div>
    <div class="pull-right" style="margin-right: 20px;">
        <?php

        /*$sql = "SELECT id, UPPER(nome) AS nome FROM usuarios WHERE id='" . $_SESSION['id'] . "' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];*/
        ?>

        <div class="btn-group hidden-xs" style="margin-top: 10px;">
            <script>
                function realizarBackup() {
                   if (confirm("Realizar backup da base de dados?")) {
                        location.href="<?php echo pg; ?>/backup";
                   } else {
                        location.href="<?php echo pg; ?>/sair.php";
                   }
                }
            </script>
            <button class="btn btn-default btn-xs">
                <?php
                    switch ($_SESSION["credentials"]["id"]) {
                        case '1':
                            echo "<span class='fas fa-user-cog'></span>";
                            break;
                        
                        case '2':
                            echo "<span class='fas fa-user-tie'></span>";
                            break;
                        case '10':
                            echo "<span class='fas fa-headset'></span>";
                            break;
                        default:
                            echo "<span class='fas fa-user'></span>";
                            break;
                    }
                ?>
                
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <button type="button" class="btn btn-default btn-xs text-uppercase">
                    <?php echo $_SESSION["credentials"]["name"]; ?>
            </button>
            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="<?php echo pg; ?>/viewer/view_usuarios?id=<?=$_SESSION["credentials"]["id"]; ?>">
                        <span class="glyphicon glyphicon-cog"></span> Meu Perfil
                    </a>
                </li>
                <li>
                    <a href="#" onclick="realizarBackup()">
                        <span class="glyphicon glyphicon-log-out"></span> Sair
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
