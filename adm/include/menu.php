<?php
$sql = "SELECT p.id, p.path, p.name FROM page_access_level as p2 JOIN pages AS p ON p2.page_id = p.id WHERE p2.access = 1 AND p2.menu = 1 AND p2.al_id=:nva_user_id ORDER BY p.name";
$res = $conn->prepare($sql);
$res ->bindParam(":nva_user_id", $_SESSION["credentials"]["access_level"], PDO::PARAM_INT);
$res ->execute();
$row = $res ->fetchAll(PDO::FETCH_OBJ);

#var_dump($row);
?>
<nav class="navbar navbar-inverse visible-xs" style="margin-top: 5px">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="pull-left navbar-toggle">
                <span id="clock" style="color: #ffffff">asd</span>
            </div>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <script>
                function realizarBackup() {
                   if (confirm("Realizar backup da base de dados?")) {
                        location.href="<?php echo pg; ?>/backup";
                   } else {
                        location.href="<?php echo pg; ?>/sair.php";
                   }
                }
            </script>
            <ul class="nav navbar-nav">
                <?php
                foreach ($row as $item){
                    echo "<li><a href='" . pg . "/" . $item ->path . "' class='text-uppercase'>" . $item ->name . "</a></li>";
                }
                ?>
                <li><a href="#" onclick="realizarBackup()">SAIR</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="sidenav col-sm-2 hidden-xs" style="margin-top: 5px">
    <ul class="nav nav-pills nav-stacked">
        <?php
            foreach ($row as $item){
                echo "<li><a href='" . pg . "/" . $item ->path . "' class='text-uppercase'>" . $item ->name . "</a></li>";
                }
                ?>
        <li><a href="#" onclick="realizarBackup()">SAIR</a></li>
    </ul>
</div>

