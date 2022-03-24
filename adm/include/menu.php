<?php
$sql = "SELECT p.id, p.path AS endereco, p.name AS nome FROM page_access_level as p2 JOIN pages AS p ON p2.page_id = p.id WHERE p2.access = 1 AND p2.menu = 1 AND p2.al_id=:nva_user_id ORDER BY p.name";
$res = $conn->prepare($sql);
$res ->bindParam(":nva_user_id", $_SESSION["credentials"]["access_level"], PDO::PARAM_INT);
$res ->execute();
$row = $res ->fetchAll(PDO::FETCH_ASSOC);

?>
<nav class="navbar navbar-inverse visible-xs">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
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
                    echo "<li><a href='" . pg . "/" . $item["endereco"] . "' class='text-uppercase'>" . $item['nome'] . "</a></li>";
                }
                ?>
                <li><a href="#" onclick="realizarBackup()">SAIR</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="container-fluid" style="margin-top: 10px;">
    <div class="row content">
        <div class="sidenav col-sm-2 hidden-xs">
            <ul class="nav nav-pills nav-stacked">
                <?php
                foreach ($row as $item){
                    echo "<li><a href='" . pg . "/" . $item['endereco'] . "' class='text-uppercase'>" . $item['nome'] . "</a></li>";
                }
                ?>
                <li><a href="#" onclick="realizarBackup()">SAIR</a></li>
            </ul>
        </div>

