<div class="header-admin hidden-xs">
    <div class="pull-left" style="margin-left: 20px; margin-top: 15px;">
        <strong>Tempo restante <span class="clock"></span></strong>
    </div>
    <div class="pull-right" style="margin-right: 20px;">
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
                    <?php echo $_SESSION["credentials"]["first_name"]; ?>
            </button>
            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="<?php echo pg; ?>/viewer/user?id=<?=$_SESSION["credentials"]["id"]; ?>">
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
