/*Validações dos campos obrigatórios de limites*/
function validaForm() {
    var valido;
    var current_value = $('input:radio:checked').val();
    tempo = '00:' + $("#Metrica_tempo").val();
    meta = parseInt($("#Metrica_meta").val());
    valido = true;

    if ($("#Metrica_meta_tempo").is(':checked')) {
        if ($("#Metrica_min_t").val() == '' || $("#Metrica_max_t").val() == '') {
            document.getElementById('message').innerHTML = "Valores de limite mínimo e máximo de tempo não podem ser vazios";
            $('#btn_modal_open').click();
            valido = false;
            exit;
        }
        if (timeToSeconds('00:' + $("#Metrica_min_t").val()) == timeToSeconds('00:' + $("#Metrica_max_t").val())) {
            document.getElementById('message').innerHTML = "Valor de tempo mínimo não pode ser igual ao valor de tempo máximo.";
            $('#btn_modal_open').click();
            valido = false;
        }
        if (timeToSeconds('00:' + $("#Metrica_min_t").val()) > timeToSeconds('00:' + $("#Metrica_max_t").val())) {
            document.getElementById('message').innerHTML = "Valor de tempo mínimo não pode ser maior do que o valor de tempo máximo.";
            $('#btn_modal_open').click();
            valido = false;
        }
    }
    if ($("#Metrica_meta_entrada").is(':checked')) {
        if ($("#Metrica_min_e").val() == '' || $("#Metrica_max_e").val() == '' || meta == '') {
            document.getElementById('message').innerHTML = "Valores de limite de entradas não podem ser vazios.";
            $('#btn_modal_open').click();
            valido = false;
            exit;
        }
        if (parseInt($("#Metrica_min_e").val()) == parseInt($("#Metrica_max_e").val())) {
            document.getElementById('message').innerHTML = "Valor de quantidade mínima de entradas não pode ser igual ao valor de quantidade máxima.";
            $('#btn_modal_open').click();
            valido = false;
            exit;
        }
        if (parseInt($("#Metrica_min_e").val()) > parseInt($("#Metrica_max_e").val())) {
            document.getElementById('message').innerHTML = "Valor de quantidade mínima de entradas não pode ser maior do que o valor de quantidade máxima.";
            $('#btn_modal_open').click();
            valido = false;
            exit;
        }
        if (meta > parseInt($("#Metrica_max_e").val())) {
            document.getElementById('message').innerHTML = "Valor da meta de produtividade não pode ser maior do que o valor de quantidade máxima de entradas";
            $('#btn_modal_open').click();
            valido = false;
            exit;
        }
        if (meta < parseInt($("#Metrica_min_e").val())) {
            document.getElementById('message').innerHTML = "Valor da meta de produtividade não pode ser menor do que o valor de quantidade mínima de entradas";
            $('#btn_modal_open').click();
            valido = false;
            exit;
        }

    }

    if (valido) {
        $('#metrica-form').submit();
    }

}

/*Carregar colaboradores da métrica no update */
$(document).ready(function () {

    $("#Metrica_meta_tempo").is(':checked') ? ControleCheckLimiteTempo(false) : ControleCheckLimiteTempo(true);
    $("#Metrica_meta_entrada").is(':checked') ? ControleCheckLimiteMeta(false) : ControleCheckLimiteMeta(true);

    $('#reuniao-participantes-grid').niceScroll({cursorwidth: '7px', cursorcolor: 'rgb(5, 141, 199)'});

    if ($('#Metrica_id').val() != null) {
        $.ajax({
            type: 'POST',
            data: {'metrica': $('#Metrica_id').val()},
            url: baseUrl + "/Metrica/GetColaboradoresPorEquipe/",
            success: function (data) {
                var colaboradores = data.split('|');
                var dados = [];

                $('#equipe').after(colaboradores[1]);

                var equipes = $("#fk_equipe").val().split(',');
                $.each(equipes, function () {
                    $('#equipe option[value="' + this + '"]').attr('selected', 'selected');
                });

                $("#equipe").trigger("change");

                $('#colaboradores_selecionados').html("");
                $("#colaboradores_selecionados").trigger("change");
                $('#colaboradores_selecionados').append(colaboradores[0]);
                $("#colaboradores_selecionados option").each(function (index) {
                    dados.push($(this).val());
                });
                $("#colaboradores_selecionados").val(dados).trigger("change");
            }
        });
    }

});
/*Alterar valor do input conforme evento do slider*/
$(function () {
    $("#slider-range-min").on("slide", function (event, ui) {
        $("#Metrica_meta").val(ui.value)
    });
    $("#slider-range-min_e").on("slide", function (event, ui) {
        $("#Metrica_min_e").val(ui.value)
    });
    $("#slider-range-max_e").on("slide", function (event, ui) {
        $("#Metrica_max_e").val(ui.value)
    });

});

/*Alterar valor do slider conforme mudança do input*/
$("#Metrica_meta").on('change', function () {
    $('#slider-range-min').slider("option", "value", this.value);
});
$("#Metrica_min_e").on('change', function () {
    $('#slider-range-min_e').slider("option", "value", this.value);
});
$("#Metrica_max_e").on('change', function () {
    $('#slider-range-max_e').slider("option", "value", this.value);
});

/*Desativar ou reativar campos de limites no evento de check*/
$("#Metrica_meta_entrada").on('change', function () {
    if ($(this).is(':checked')) {
        ControleCheckLimiteMeta(false);
    }
    else {
        ControleCheckLimiteMeta(true);
    }

});
$("#Metrica_meta_tempo").on('change', function () {
    if ($(this).is(':checked')) {
        ControleCheckLimiteTempo(false);
    }
    else {
        ControleCheckLimiteTempo(true);
    }
});

function ControleCheckLimiteTempo(status) {
    $('#Metrica_min_t').attr('disabled', status);
    $('#Metrica_max_t').attr('disabled', status);
}

function ControleCheckLimiteMeta(status) {
    $('#slider-range-min').slider({disabled: status});
    $('#slider-range-mini').slider({disabled: status});
    $('#slider-range-maxi').slider({disabled: status});
    $('#Metrica_meta').attr('disabled', status);
    $('#Metrica_min_e').attr('disabled', status);
    $('#Metrica_max_e').attr('disabled', status);
}

/*Procedimentos para busca de registros do critério desejado*/
var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$("#Metrica_sufixo").click(function () {
    buscarCriterio($("#Metrica_criterio").val());
});

$('#Metrica_criterio').on('keyup', function () {
    var criterio = this.value;
    delay(function () {
        buscarCriterio(criterio)
    }, 2000);
});

$("#Metrica_programa").on('change', function () {
    if ($(this).val() != '' && $("#Metrica_criterio").val() != '') {
        $('#Metrica_criterio').val('');
        $("#sugestoes_criterio").fadeOut();
    }
});

function buscarCriterio(string) {
    var sugestoes = $("#sugestoes_criterio");
    var programa = $("#Metrica_programa").val();
    var sufixo;
    if (programa == '') {
        document.getElementById('message').innerHTML = "Selecione um programa para a pesquisa de critério.";
        $('#btn_modal_open').click();
    }
    else {
        if (string == '' || string.length < 3) {
            sugestoes.fadeOut();
        }
        else {
            $("#loader_image").hide();
            $("#Metrica_criterio").addClass('spinner');
            $("#Metrica_criterio").attr('disabled', true);
            sufixo = $('#Metrica_sufixo').is(":checked");
            sufixo = (sufixo == true) ? 1 : 0;
            $.post(baseUrl + "/Metrica/getCriterioDinamico",
                {
                    criterio: "" + string + "", programa: $("#Metrica_programa").val(), sufixo: sufixo,
                }
                , function (data) { // Do an AJAX call

                    sugestoes.html(data).fadeIn();
                    $("#Metrica_criterio").removeClass('spinner');
                    $("#Metrica_criterio").attr('disabled', false);
                });
        }
    }

}

function carregarCriterio(string) {
    string = string.trim();
    $('#Metrica_criterio').val(string);
    var sugestoes = $("#sugestoes_criterio");
    sugestoes.fadeOut();
}
/*Procedimentos para busca de registros da métrica para a grid de pre-visualização*/
$('#inserirCriterios').click(function () {
    var string = $('#Metrica_criterio').val();
    var sugestoes = $("#sugestoes_pre_visualizacao");
    var programa = $("#Metrica_programa").val();
    var sufixo;
    sufixo = $('#Metrica_sufixo').is(":checked");
    sufixo = (sufixo == true) ? 1 : 0;
    if (string == '' && programa != '') {
        $('#btn_modal_confirm_metrica').click();
    }
    else {
        if (programa == '') {
            document.getElementById('message').innerHTML = "Selecione um programa para a pesquisa de critério.";
            $('#btn_modal_open').click();
        }
        else {
            buscaPreVisualizacao(sugestoes, sufixo, programa, string, 0, 1);
        }
    }
});

$('#btn_modal_confirm_visualiza_metrica').click(function () {
    var programa = $("#Metrica_programa").val();
    var sugestoes = $("#sugestoes_pre_visualizacao");
    buscaPreVisualizacao(sugestoes, '', programa, '', 0, 1);
    $('#modal_confirm_metrica').modal('hide');

});

function buscaPreVisualizacao(sugestoes, sufixo, programa, string, qtd, total) {
    sugestoes.fadeOut();
    $("#reuniao-participantes-grid").addClass("loading");
    $.ajax({
        type: 'POST',
        data: {criterio: "" + string + "", programa: programa, sufixo: sufixo, qtd: qtd, total: total},
        url: baseUrl + "/Metrica/getPreVisualizacao",
        global: false,
        success: function (data) {
            $("#reuniao-participantes-grid").removeClass("loading");
            sugestoes.html(data);
            $("#sugestoes_criterio").fadeOut();
            $('#reuniao-participantes-grid table tbody').find('tr').remove();
            var stringTr = "";
            var classCount = 1;
            var i = 0;
            var contador = $("#qtdCriterio").val();
            var total = $('#totalResultados').val();
            for (i = 0; i < contador; i++) {
                stringTr += "<tr >\n\
                <td>" + $("#p_criterio_" + i).html() + "</td>\n\
                    <td>" + $("#p_usuario_" + i).html() + "</td>\n\
                    <td>" + $("#p_duracao_" + i).html() + "</td>\n\
                    <td>" + $("#p_data_" + i).html() + "</td>\n\
                </tr>";
            }
            $('#reuniao-participantes-grid table tbody').append(stringTr);
            $('#qtdResultados').html(contador);
            if (total != '')
                $('#totalCriterio').html(total);
            $('#resultados').show();
        }
    });
}

/*Busca de colaboradores com base na equipe selecionada*/
$("#equipe").on('change', function () {
    Loading.show();
    $.ajax({
        type: 'POST',
        data: {'equipe': $('#equipe').val()},
        url: baseUrl + "/Metrica/GetColaboradoresPorEquipe/",
        async: false,
        success: function (data) {
            if (data != "not") {
                var dados = [];

                $('#colaboradores_selecionados').html("");
                $("#colaboradores_selecionados").trigger("change"); 
                $('#colaboradores_selecionados').append(data);
                $("#colaboradores_selecionados option").each(function( index ) {
                  dados.push($(this).val());
                });   
                $("#colaboradores_selecionados").val(dados).trigger("change");
                Loading.hide();
            } else {
                $('#colaboradores_selecionados').html("");
                $("#colaboradores_selecionados").trigger("change");
                Loading.hide();
                $('#colaboradores_selecionados').select2({
                    placeholder: 'Selecione uma equipe',
                    allowClear: true
                });
            }
    
        }
    });
});

function removerPrograma(element) {
    element.parentNode.remove();

}

/*Funções auxiliares*/
function timeToSeconds(hms) {
    var a = hms.split(':');
    return seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
}


function limparMetrica() {
    $('#Metrica_min').val('');
    $('#Metrica_max').val('');
    $('#slider-range-min-amount2').html('');
    $('#slider-range-min-amount3').html('');
}

/* Scroll ajax grid pre-visualizar*/
$('#reuniao-participantes-grid').scroll(function () {
    var resultados = parseInt($('#qtdCriterio').val());
    var total = parseInt($('#totalCriterio').html());
    var elem = $(this);
    var string = $('#Metrica_criterio').val();
    var sugestoes = $("#sugestoes_pre_visualizacao");
    var programa = $("#Metrica_programa").val();
    var sufixo;
    sufixo = $('#Metrica_sufixo').is(":checked");
    sufixo = (sufixo == true) ? 1 : 0;
    if (((elem.scrollTop() + elem.height()) > (elem.prop('scrollHeight') - 15)) && resultados < total) {
        buscaPreVisualizacao(sugestoes, sufixo, programa, string, resultados, 0)
    }
});

