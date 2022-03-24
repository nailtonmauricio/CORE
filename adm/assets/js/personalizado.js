/*Buscar bairros*/
$("#cidade_id").on("change", function(){
    var cidade_id = $("#cidade_id").val();
    //alert(cidade_id);
    $.ajax({
        url: '../process/search/search_bairros.php',
        type: 'POST',
        data:{id:cidade_id},
        success: function(data){
            $("#bairro_id").html(data);
        },
        error: function(data){
            $("#bairro_id").html("Erro ao carregar");
        }
    });
});

/*Buscar horários para cadastro*/
$("#dt_coleta").on("change", function(){
    var dt_coleta = $("#dt_coleta").val();
    //alert(dt_coleta);
    $.ajax({
        url: '../process/search/buscar_horario.php',
        type: 'POST',
        data:{dt_coleta:dt_coleta},
        success: function(data){
            $("#horario").html(data);
        },
        error: function(data){
            $("#horario").html("Erro ao carregar");
        }
    });
});

//Mascara de inputs
jQuery(function ($) {
    $(".h_geral").mask("99:99");
    $(".dt_geral").mask("99/99/9999");
    $(".dth_geral").mask("99/99/99 99:99");
    $(".cpf").mask("999.999.999-99");
    $(".celular").mask("(99) 99999-9999");
    $(".fixo").mask("(99) 9999-9999");
    $(".moeda").mask("###.##0,00", {reverse: true});
    $("#h_encaixe").mask("99:99");
});