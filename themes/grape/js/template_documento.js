function add_documento(){

    var index = parseInt($("#next_index_documento").val());
    var template = '\
        <div id="div_documento_'+index+'" class="documento" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Documento_'+index+'_fk_disciplina">Disciplina</label>\n\
                    <select class="chzn-select disciplina" style="width: 100%; " name="Documento['+index+'][fk_disciplina]" id="Documento_'+index+'_fk_disciplina">\n\
                        <option value="">Selecione</option>\n\
                    </select>\n\
                </p>\n\
            </div>\n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Documento_'+index+'_nome">Nome do Documento</label>\n\
                         <input size="60" maxlength="255" name="Documento['+index+'][nome]" id="Documento_'+index+'_nome" type="text" class="text">\n\
                    </p>\n\
            </div>\n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Documento_'+index+'_previsto">Tempo Previsto</label>\n\
                         <input size="60" maxlength="255" name="Documento['+index+'][previsto]" id="Documento_'+index+'_previsto" type="text" class="previsto">\n\
                    </p>\n\
            </div>\n\
                \n\
            <div class="_25" style="padding-top: 18px;">\n\
                <p>\n\
                    <a name="remover_documento_'+index+'" id="remover_documento_'+index+'" class="remover_documento button" onclick="remover_documento('+index+');" style="float: right;">Remover este Documento</a>\n\
                </p>\n\
            </div>\n\
            <div class="clear"></div>\n\
        </div>';
    $("#div_add_documentos").append(template);
    $(".chzn-select").chosen({no_results_text: "Não encontrado"});
    if (window.location.host=="localhost") {
        var url='/smith.vivainovacao.com/disciplina/getAll';
    } else {
        var url='/disciplina/getAll';
    }
     $.ajax({
        type:'POST',
        data: $('form').serialize(),
        url: url,
        success: function(data){

            $("#Documento_"+index+"_fk_disciplina").each(function(){
                if($(this).val()=="")
                    $(this).html(data);
            });
            $("select.chzn-select").trigger("liszt:updated");
        }
    });
    $("#next_index_documento").val(index+1);
    $(".previsto").mask("99:99:99",{placeholder:" "});
}


function remover_documento(index){
    $("#div_documento_"+index).remove();
}

function excluir_documento( index, id){
    if (window.location.host=="localhost") {
        var url='/smith.vivainovacao.com/documento/deleteDocumento';
    } else {
        var url='/documento/deleteDocumento';
    }
    $.ajax({
        type:'POST',
        data: { id: id},
        url: url,
        success: function(data){
            //alert(data+ " foi deletado!");
        }
    });

    remover_documento( index);
     //$(this).removeClass("loading");

    //$("#next_index_documento_"+tipo).before('<input type="hidden" value="'+id+'" name="Instituicao['+indice+'][excluir_instancias_ids][]">');
}
