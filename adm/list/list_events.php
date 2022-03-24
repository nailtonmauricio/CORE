<?php
ob_start();
if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: index.php");
}
?>
<div class="well content">
    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }

    $sql = "SELECT id, title, color, start, end, description FROM events WHERE user_id = :id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
    $res ->execute();
    ?>

    <div id="calendar"></div>

</div>

<script>
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            defaultDate: Date(),
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            eventClick: function (event) {

                $('#visualizar #id').text(event.id);
                $('#visualizar #id').val(event.id);
                $('#visualizar #title').text(event.title);
                $('#visualizar #title').val(event.title);
                $('#visualizar #color').val(event.color);
                $('#visualizar #start').text(event.start.format('DD/MM/YYYY HH:mm:ss'));
                $('#visualizar #start').val(event.start.format('DD/MM/YYYY HH:mm:ss'));
                $('#visualizar #end').text(event.end.format('DD/MM/YYYY HH:mm:ss'));
                $('#visualizar #end').val(event.end.format('DD/MM/YYYY HH:mm:ss'));
                $('#visualizar #description').text(event.description);
                $('#visualizar #description').val(event.description);
                $('#visualizar').modal('show');
                return false;

            },

            selectable: true,
            selectHelper: true,
            select: function (start, end) {
                $('#cadastrar #start').val(moment(start).format('DD/MM/YYYY HH:mm:ss'));
                $('#cadastrar #end').val(moment(end).format('DD/MM/YYYY HH:mm:ss'));
                $('#cadastrar').modal('show');
            },
            events: [
                <?php
                while ($row = $res ->fetch(PDO::FETCH_ASSOC)) {
                ?>
                {
                    id: '<?= $row["id"]; ?>',
                    title: '<?= $row["title"]; ?>',
                    start: '<?= $row["start"]; ?>',
                    end: '<?= $row["end"]; ?>',
                    color: '<?= $row["color"]; ?>',
                    description: '<?= $row["description"]; ?>'
                },<?php
                }
                ?>
            ]
        });
    });

    //Mascara para o campo data e hora
    function DataHora(evento, objeto) {
        var keypress = (window.event) ? event.keyCode : evento.which;
        campo = eval(objeto);
        if (campo.value === '00/00/0000 00:00:00') {
            campo.value === "";
        }

        caracteres = '0123456789';
        separacao1 = '/';
        separacao2 = ' ';
        separacao3 = ':';
        conjunto1 = 2;
        conjunto2 = 5;
        conjunto3 = 10;
        conjunto4 = 13;
        conjunto5 = 16;
        if ((caracteres.search(String.fromCharCode(keypress)) !== -1) && campo.value.length < (19)) {
            if (campo.value.length === conjunto1)
                campo.value = campo.value + separacao1;
            else if (campo.value.length === conjunto2)
                campo.value = campo.value + separacao1;
            else if (campo.value.length === conjunto3)
                campo.value = campo.value + separacao2;
            else if (campo.value.length === conjunto4)
                campo.value = campo.value + separacao3;
            else if (campo.value.length === conjunto5)
                campo.value = campo.value + separacao3;
        } else {
            event.returnValue = false;
        }
    }
</script>


<!-- Inicio Janela modal -->
<div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Dados do Evento</h4>
            </div>
            <div class="modal-body">
                <div class="visualizar">
                    <dl class="dl-horizontal">
                        <dt>ID</dt>
                        <dd id="id"></dd>
                        <dt>Titulo</dt>
                        <dd id="title"></dd>
                        <dt>Inicio</dt>
                        <dd id="start"></dd>
                        <dt>Fim</dt>
                        <dd id="end"></dd>
                        <dt>Descrição</dt>
                        <dd id="description"></dd>
                    </dl>
                    <hr/>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-xs btn-warning cancel_view ">
                            <span class="glyphicon glyphicon-edit"></span> Editar
                        </button>
                    </div>
                </div>

                <!-- Início do modal para realizar a edição do evento -->
                <div class="form">
                    <form name="formAgenda" class="form-horizontal" method="POST" action="<?php echo pg; ?>/process/edit/edit_events">
                        <input type="hidden" name="id" id="id"/>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Titulo</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Titulo do Evento"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="color" class="col-sm-2 control-label">Cor</label>
                            <div class="col-sm-10">
                                <input type="color" name="color" id="color" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start" class="col-sm-2 control-label">Inicio</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="start" id="start" onKeyPress="DataHora(event, this)"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end" class="col-sm-2 control-label">Fim</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="end" id="end" onKeyPress="DataHora(event, this)"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <!--Ao usar quebra de linha para a descrição a função que realiza a visualização apresenta erro -->
                            <label for="description" class="col-sm-2 control-label">Descrição</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" id="description" rows="10" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10 text-right">
                                <button type="button" class="btn btn-xs btn-danger cancel_edit">
                                    <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
                                </button>
                                <button class="btn btn-xs btn-success">
                                    <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Início do modal para cadastrar o novo evento -->
<div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Cadastrar Evento</h4>
            </div>
            <div class="modal-body">
                <form name="formAgenda" class="form-horizontal" method="POST" action="<?php echo pg; ?>/process/reg/reg_events">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Titulo</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Titulo do Evento"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="color" class="col-sm-2 control-label">Cor</label>
                        <div class="col-sm-10">
                            <input type="color" name="color" id="color" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start" class="col-sm-2 control-label">Inicio</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="start" id="start" onKeyPress="DataHora(event, this)"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end" class="col-sm-2 control-label">Fim</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="end" id="end" onKeyPress="DataHora(event, this)"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Descrição</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description" id="description" placeholder="Descreva o evento cadastrado em parágrafo único." rows="10" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <button class="btn btn-xs btn-success pull-right">
                                <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('.cancel_view').on("click", function () {
        $('.form').slideToggle();
        $('.visualizar').slideToggle();
    });
    $('.cancel_edit').on("click", function () {
        $('.visualizar').slideToggle();
        $('.form').slideToggle();
    });
</script>
<!-- Fim Janela modal -->