/**
 * Created by Lucas on 07/01/2016.
 */
$('#doc-grid a.Finalizar').live('click', function () {
    $('#modal_finalizar_doc').modal();
    return false;
});

function closeDoc(item) {
    console.log($(item).closest('tr').attr('data-index'));
    indiceAtual = $(item).closest('tr').attr('data-index');
    document.getElementById('Documento[' + indiceAtual + '][finalizado]').remove()
    $(item).closest('tr').append('<input type="hidden" value="Finalizado" name="Documento[' + indiceAtual + '][finalizado]" id="Documento[' + indiceAtual + '][finalizado]">');
    $(item).closest('tr').find('td')[3].innerHTML = 'Finalizado';
    document.getElementById('message').innerHTML = "Documento finalizado com sucesso.";
    $('#btn_modal_open').click();
    return false;
}

function salvarDisciplina() {
    var disciplina = $("#disciplina_nome").val();
    var arrayDisciplina = '';
    $.ajax({
        url: baseUrl + "/disciplina/createAjaxDisciplina",
        type: 'POST',
        data: {disciplina: disciplina},
        success: function (data) {
            arrayDisciplina = data;
            $("#disciplina_nome").val('');
            $('#novaDisciplina').modal('hide');
            $.each($('.disciplinaDinamica'), function (data) {
                $(this).empty();
                $(this).append(arrayDisciplina);
                $("select.chzn-select").trigger("liszt:updated");
            });
        }
    });
}

$(function () {
    $('.fire-toggle').change(function () {
        var current_value = $('input:radio:checked').val();
        if (current_value == "ise") {
            $('.importCSV').show();
            $('.importManual').hide();
            $('.tempoPrevisto').hide();
        }
        if (current_value == "ism") {
            $('.importCSV').hide();
            $('.importManual').show();
            $('.tempoPrevisto').hide();
        }
        if (current_value == "nao") {
            getPrevisto();

            $('.importCSV').hide();
            $('.importManual').hide();
            $('.tempoPrevisto').show();
        }
    });
    /*$('.codigo').change(function () {
        var codigo = $('.codigo').val();
        $.ajax({
            type: 'POST',
            data: {'codigo': codigo},
            url: baseUrl + "/Contrato/codigoExiste",
            success: function () {
                document.getElementById('message').innerHTML = "Código de contrato já cadastrado.";
                $('#btn_modal_open').click();
                $('.codigo').val('');
            },
            error: function () {
            }
        });
     });*/
});

$(document).on('change', '#Contrato_data_final', function () {
    getPrevisto();
});

$(document).on('change', '#Contrato_data_inicio', function () {
    getPrevisto();
});

function getPrevisto() {
    var dataFinal = $('#Contrato_data_final').val().split('/');
    var dataInicio = $('#Contrato_data_inicio').val().split('/');
    dataFinal = new Date(dataFinal[2] + '-' + dataFinal[1] + '-' + dataFinal[0]);
    dataInicio = new Date(dataInicio[2] + '-' + dataInicio[1] + '-' + dataInicio[0]);
    var previsto = dataFinal - dataInicio;
    if (dataInicio > dataFinal && dataFinal != 0 && dataInicio != 0) {
        $('#Contrato_tempo_previsto').val('00:00:00');
    } else if (previsto == 0 || isNaN(previsto)) {
        $('#Contrato_tempo_previsto').val('00:00:00');
    } else {
        previsto = ((previsto / 1000) / 60) / 60 + ':00:00';
        $('#Contrato_tempo_previsto').val(previsto);
    }
}


function add_documento(salvar) {
    var index = parseInt($("#next_index_documento").val());

    nome = (salvar != 'edit') ? $('#Documento_nome').val() : $('#edit_doc_nome').val();
    previsto = (salvar != 'edit') ? $('#Documento_previsto').val() : $('#edit_doc_previsto').val();
    disciplina = (salvar != 'edit') ? $('#Documento_fk_disciplina').val() : $('#edit_doc_fk_disciplina').val();

    var status = 'Novo';
    if (salvar == 'edit') {
        $('.editDoc').closest('tr').remove();
        status = 'Modificado';
    }

    $.ajax({
        type: 'post',
        url: baseUrl + '/Documento/getDisciplinaGrid/',
        data: {'disciplina': disciplina},
        async: false,
        success: function (data) {
            disciplinaCod = data;
        },
        error: function () {
            alert('Não foi selecionada disciplina');
        }
    });

    if ($('#doc-grid tbody tr td').last().hasClass('empty') == true)
        $('#doc-grid tbody').empty();

    if ($('#doc-grid tbody tr').last().hasClass('even') == true)
        var itemClass = 'odd';
    else
        var itemClass = 'even';

    $('#doc-grid tbody').append('<tr class="' + itemClass + '">\n\
        <td class="nomes_docs">' + nome + '</td>\n\
        <td>' + previsto + '</td>\n\
        <td>' + disciplinaCod + '</td>\n\
        <td>' + status + '</td>\n\
        <td style="width: 11%; text-align: right;">\n\
            <a style="cursor: pointer" onclick="editDoc(this)" title="Edit">\n\
                <img src="' + baseUrl + '/themes/flatlab/images/icons/edit.png" alt="Edit">\n\
            </a>\n\
            <a class="delete" title="Excluir" style="cursor: pointer" onclick="removerDoc(this)">\n\
                <img src="' + baseUrl + '/themes/flatlab/images/icons/trash.png" alt="Excluir">\n\
            </a>\n\
        </td>\n\
        <input type="hidden" value="' + nome + '" name="Documento[' + index + '][nome]" id="Documento[' + index + '][nome]">\n\
        <input type="hidden" value="' + previsto + '" name="Documento[' + index + '][previsto]" id="Documento[' + index + '][previsto]">\n\
        <input type="hidden" value="Em andamento" name="Documento[' + index + '][finalizado]" id="Documento[' + index + '][finalizado]">\n\
        <input type="hidden" value="' + disciplina + '" name="Documento[' + index + '][fk_disciplina]" id="Documento[' + index + '][fk_disciplina]">\n\
    </tr>');

    index++;
    $("#next_index_documento").val(index);
    $('#Documento_nome').val('');
    $('#Documento_previsto').val('');
    if (salvar != 'mais') {
        $('#novoDocumento').modal('hide');
    }
    if (salvar == 'edit') {
        $('#editDocumento').modal('hide');
    }

    $('#status_documento').removeClass('icon-ok');
    $('#status_documento').addClass('icon-ban-circle');
    $('#status_documento').attr({style: 'font-size: 1.5em; line-height: 80px; color: #ff6c60'});
    $('.docs_buttons').attr({disabled: 'disabled'});
}

function removerDoc(item) {
    item.closest('tr').remove();
}

function editDoc(item) {
    $('.editDoc').removeClass('editDoc');
    $(item).addClass('editDoc');
    $('#edit_doc_nome').val($(item).closest('tr').children()[0].innerHTML);
    $('#edit_doc_previsto').val($(item).closest('tr').children()[1].innerHTML);

    $.ajax({
        type: 'post',
        url: baseUrl + '/Documento/getDisciplinaId/',
        data: {'disciplina': $(item).closest('tr').children()[2].innerHTML},
        async: false,
        success: function (data) {
            disciplinaId = data;
        }
    });

    $("#edit_doc_fk_disciplina option[value=" + disciplinaId + "]").attr('selected', 'selected');
    $("select.chzn-select").trigger("liszt:updated");

    $('#editDocumento').modal('show');
}

function validaFormObra() {
    var valido = true;
    var inicio = $('#Contrato_data_inicio').val();
    var fim = $('#Contrato_data_final').val();

    if (!checkDateRange(inicio, fim)) {
        valido = false;
    }

    if (valido) {
        $('#pro-obra-form').submit();
        Loading.show();
    }
}

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$('#disciplina_nome').on('keyup', function() {
    delay(function () {
        $.ajax({
            url: baseUrl + '/Disciplina/compareDisciplina/',
            type: 'POST',
            data: {disciplina: $('#disciplina_nome').val()},
        }).done(function() {
            $('#status_disciplina').removeClass('icon-ok');
            $('#status_disciplina').addClass('icon-ban-circle');
            $('#status_disciplina').attr({style: 'font-size: 1.5em; line-height: 40px; color: #ff6c60'});
            $('#submit_dis').attr({disabled: 'disabled'});
        }).fail(function() {
            $('#status_disciplina').removeClass('icon-ban-circle');
            $('#status_disciplina').addClass('icon-ok');
            $('#status_disciplina').attr({style: 'font-size: 1.5em; line-height: 40px; color: #a9d86e'});
            $('#submit_dis').removeAttr('disabled');
        });
    }, 1000);
});

$(document).ready(function () {
    var even = $('.even'),
        odd = $('.odd');
    $(even).each(function () {
        var first = $(this).find('td')[0];
        $(first).addClass('nomes_docs');
    });
    $(odd).each(function () {
        var first = $(this).find('td')[0];
        $(first).addClass('nomes_docs');
    });
});

function getValues() {
    var tempValues = [];
    var index = 0;
    $('.nomes_docs').each(function() {
        var th = $.trim(this.innerHTML);
        tempValues[index] = th;
        index++;
    });
    return tempValues;
}

var pathname = window.location.pathname;
var pathsize = pathname.split('/');
var contrato = pathsize[pathsize.length - 1];

function validName() {
    if (contrato == 'create') {
        delay(function () {
            if ($.inArray($.trim($('#Documento_nome').val()), getValues()) > -1 || $('#Documento_nome').val() == '') {
                $('#status_documento').removeClass('icon-ok');
                $('#status_documento').addClass('icon-ban-circle');
                $('#status_documento').attr({style: 'font-size: 1.5em; line-height: 80px; color: #ff6c60'});
                $('.docs_buttons').attr({disabled: 'disabled'});
            } else {
                $('#status_documento').removeClass('icon-ban-circle');
                $('#status_documento').addClass('icon-ok');
                $('#status_documento').attr({style: 'font-size: 1.5em; line-height: 80px; color: #a9d86e'});
                $('.docs_buttons').removeAttr('disabled');
            }
        }, 1000);
    } else {
        delay(function () {
            if ($.inArray($.trim($('#Documento_nome').val()), getValues()) > -1 || $('#Documento_nome').val() == '') {
                $('#status_documento').removeClass('icon-ok');
                $('#status_documento').addClass('icon-ban-circle');
                $('#status_documento').attr({style: 'font-size: 1.5em; line-height: 80px; color: #ff6c60'});
                $('.docs_buttons').attr({disabled: 'disabled'});
            } else {
                $.ajax({
                    url: baseUrl + '/Documento/compareDocumento/',
                    type: 'POST',
                    data: {documento: $('#Documento_nome').val(), contrato: contrato},
                }).done(function() {
                    $('#status_documento').removeClass('icon-ok');
                    $('#status_documento').addClass('icon-ban-circle');
                    $('#status_documento').attr({style: 'font-size: 1.5em; line-height: 80px; color: #ff6c60'});
                    $('.docs_buttons').attr({disabled: 'disabled'});
                }).fail(function() {
                    $('#status_documento').removeClass('icon-ban-circle');
                    $('#status_documento').addClass('icon-ok');
                    $('#status_documento').attr({style: 'font-size: 1.5em; line-height: 80px; color: #a9d86e'});
                    $('.docs_buttons').removeAttr('disabled');
                });
            }
        }, 0);
    }
};

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).on('keyup', '#Documento_nome', function () {
    buscarDocumento($.trim(this.value), this.id);
});

$(document).on('keyup', '#codigoLDP', function () {
    buscarDocumento($.trim(this.value), this.id);
});

function buscarDocumento(string, field) {
    var sugestoes = $("#sugestoes_documentos_" + field);
    var nomesGrid = getValues();
    delay(function () {
        validName();
        if (string == '' || string.length < 3) {
            sugestoes.fadeOut();
        } else {
            $("#loader_image").hide();
            $("#" + field).addClass('spinner');
            $("#" + field).attr('disabled', true);
            $.post(baseUrl + "/Contrato/getDocumentoDinamico",
                {
                    documento: "" + string + "",
                    action: contrato,
                    nomesGrid: nomesGrid,
                    elemento: field
                }, function (data) {
                    sugestoes.html(data).fadeIn();
                    $("#" + field).removeClass('spinner');
                    $("#" + field).attr('disabled', false);
                }
            );
        }
    }, 1000);
}

function carregarDocumento(string) {
    string = string.trim();
    $('#Documento_nome').val(string);
    var sugestoes = $("#sugestoes_documentos_Documento_nome");
    sugestoes.fadeOut();
    validName();
}

$(document).on('click', '#newDoc', function () {
    $('#sugestoes_documentos_Documento_nome').hide();
});

$(document).on('click', '#csvLDP', function () {
    $('#modalCsvLdp').modal('show');
});

function sendCSV() {
    var docs = {};

    $('.csvDocs').each(function () {
        docs[this.id] = $(this).text();
    });
    Loading.show();
    $.ajax({
        url: baseUrl + '/Documento/createCSV',
        type: 'POST',
        data: {docs: docs},
    }).done(function (data) {
        window.open(baseUrl + '/public/' + data, '_blank');
        $.ajax({
            url: baseUrl + '/Documento/deleteCSV',
            type: 'POST',
            data: {doc: '/public/' + data},
        }).done(function (data) {
            Loading.hide();
        });
    }).fail(function () {
        alert("Não foi possível gerar a planilha. Contate o suporte técnico.");
    });
    $('#modalCsvLdp').modal('hide');
    $('#codigoLDP').val('');
    $('#sugestoes_documentos_codigoLDP').empty();
}