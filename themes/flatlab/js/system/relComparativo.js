/**
 * Created by Lucas on 12/01/2016.
 */


$('#novo_colaborador').on('click', function () {
    var index = parseInt($('#index').val());
    var template = '<div class="form-group  col-lg-6">\n\
       <p> <label for="colaborador_id">Colaborador</label>\n\
       <select class="chzn-select" style="width:100%;" onchange="checkChangeColaborador()" name="colaborador[' + index + ']" id="colaborador_' + index + '"></select></p>\n\
       </div>';

    $('#colMais').append(template);
    var opcao = 'colaborador';
    var action = "/Colaborador/getColaboradores";

    $.ajax({
        type: 'POST',
        data: {'opcao': opcao},
        url: baseUrl + action,
        success: function (data) {
            $('#colaborador_' + index + '').append(data);
            $('#colaborador_' + index + ' option[value="todos_colaboradores"]').remove();
            $(".chzn-select").chosen({no_results_text: "N&atilde;o encontrado"});
            $("select.chzn-select").trigger("liszt:updated");
            index++;
            $("#index").val(index);
        }
    });

});

$('#rm_colaborador').on('click', function () {
        var index = parseInt($('#index').val());
        var ultimo = index - 1;
        if (ultimo == 2) {
            document.getElementById('message').innerHTML = "Não foi possível remover o colaborador, é necessário pelo menos 2 colaboradores para gerar o relatório.";
            $('#btn_modal_open').click();
        }
        else {
            $('#colaborador_' + ultimo + '').closest('div.form-group').remove();
            $("#index").val(ultimo);
        }
    }
);


function validaForm() {
    var valido = true;
    var inicio = $('#date_from').val();
    var fim = $('#date_to').val();
    $('#button').val('');
    if (!checkDateRange(inicio, fim)) {
        valido = false;
    }
    if (!checkColaboradores()) {
        valido = false;
    }

    if (valido) {
        $('#log-atividade-form').submit();
    }
}

function checkColaboradores() {
    $(".chzn-select").next().removeClass('chzn-container-error');
    var arr = $.map
    (
        $("select option:selected"), function (n) {

            return n.value;
        }
    );

    var values = [];
    for (i = 0; i < arr.length; i++) {
        var select = arr[i];
        if (values.indexOf(select) > -1) {
            $(".chzn-select").filter(function () {
                return this.value == select
            }).next().addClass("chzn-container-error");
            document.getElementById('message').innerHTML = "Não é permitido gerar o relatório comparativo com duas ocorrências do mesmo colaborador.";
            $('#btn_modal_open').click();
            return false;
        }
        else
            values.push(select);
    }
    return true;
}

function checkChangeColaborador() {
    $.each($(".chzn-select"), function () {
        value = $(this).attr('value');
        id = $(this).attr('id');
        selects = $(".chzn-select").filter(function () {
            return this.value == value
        }).length;
        if (selects == 1)
            $("#" + id).next().removeClass('chzn-container-error');

    })
}

function checkDateRange(start, end) {
    var start2 = start.split("/");
    var end2 = end.split("/");
    var data = new Date();
    var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
    start2 = new Date(start2[2], start2[1] - 1, start2[0]);
    end2 = new Date(end2[2], end2[1] - 1, end2[0]);

    if (+end2 === +hoje) {
        document.getElementById('message').innerHTML = "A data final não pode ser de hoje, pois por questões de desempenho apresentamos a produtividade até dia anterior.";
        $('#btn_modal_open').click();
        return false;
    }

    if (end2 > hoje) {
        document.getElementById('message').innerHTML = "A data final não pode ser maior que hoje.";
        $('#btn_modal_open').click();
        return false;
    }

    if (isNaN(start2)) {
        document.getElementById('message').innerHTML = "A data de início não é válida, por favor insira uma data válida.";
        $('#btn_modal_open').click();
        return false;
    }

    if (isNaN(end2)) {
        document.getElementById('message').innerHTML = "A data de fim não é válida, por favor insira uma data válida.";
        $('#btn_modal_open').click();
        return false;
    }

    if (end2 < start2) {
        document.getElementById('message').innerHTML = "A data de início precisa ser antes da data de fim.";
        $('#btn_modal_open').click();
        return false;
    }

    return true;
}