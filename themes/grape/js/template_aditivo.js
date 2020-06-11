function add_aditivo(){
    var index = parseInt($("#next_index_aditivo").val());
    var template = '\
        <div id="div_aditivo_'+index+'" class="aditivo">\n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Aditivo_'+index+'_tipo">Tipo</label>\n\
                    <select class="chzn-select tipo_aditivo" style="width: 100%; " name="Aditivo['+index+'][tipo]" id="Aditivo_'+index+'_tipo">\n\
                        <option value="">Selecione</option>\n\
                        <option value="tempo">Tempo</option>\n\
                        <option value="valor">Valor</option>\n\
                        <option value="tempo_valor">Tempo / Valor</option>\n\
                    </select>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_25 tempo_aditivo" style="display: none;">\n\
                <p>\n\
                    <label for="Aditivo_'+index+'_tempo">Tempo</label>\n\
                    <input size="60" maxlength="255" name="Aditivo['+index+'][tempo]" id="Aditivo_'+index+'_tempo" type="text" class="text">\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_25 valor_aditivo" style="display: none;">\n\
                <p>\n\
                    <label for="Aditivo_'+index+'_valor">Valor</label>\n\
                    <input size="60" maxlength="255" name="Aditivo['+index+'][valor]" id="Aditivo_'+index+'_valor" type="text" class="text valor">\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100">\n\
                <p>\n\
                    <label for="Aditivo_'+index+'_descricao">Descrição</label>\n\
                    <textarea name="Aditivo['+index+'][descricao]" id="Aditivo_'+index+'_descricao" rows="5"></textarea>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                <p>\n\
                    <a name="remover_aditivo_'+index+'" id="remover_aditivo_'+index+'" class="remover_aditivo button" onclick="remover_aditivo('+index+');" style="float: right;">Remover este aditivo</a>\n\
                </p>\n\
            </div>\n\
        </div>';
    $("#div_add_aditivos").before(template);
    $(".chzn-select").chosen({no_results_text: "Não encontrado"}); 
    tipo_aditivo();
    input_valor();
    $("#next_index_aditivo").val(index+1);
}

function remover_aditivo(index){
    $("#div_aditivo_"+index).remove();
}

function excluir_aditivo(index, id){
    remover_aditivo(index);
    $("#next_index_aditivo").before('<input type="hidden" value="'+id+'" name="excluir_aditivos_ids[]">');
}