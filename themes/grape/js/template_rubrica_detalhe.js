function add_rubrica(){
    var index = parseInt($("#next_index_rubrica").val());
    var template = '\
        <div id="div_rubrica_'+index+'" class="rubrica">\n\
            <fieldset>\n\
                <legend>Rubrica</legend>\n\
                \n\
                <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                    <p>\n\
                        <label for="Rubrica_'+index+'_descricao">Descrição</label>\n\
                        <input maxlength="255" name="Rubrica['+index+'][descricao]" id="Rubrica_'+index+'_descricao" type="text" class="text">\n\
                    </p>\n\
                </div>\n\
                \n\
                <div id="div_add_detalhes_'+index+'" class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                    <input type="hidden" value="0" name="next_index_detalhe_'+index+'" id="next_index_detalhe_'+index+'">\n\
                    <p>\n\
                        <a id="adicionar_detalhe_'+index+'" class="button" onclick="add_detalhe('+index+');">Adicionar detalhe</a>\n\
                    </p>\n\
                </div>\n\
                \n\
                <div class="_100">\n\
                    <p>\n\
                        <a name="remover_rubrica_'+index+'" id="remover_rubrica_'+index+'" class="remover_rubrica button" onclick="remover_rubrica('+index+');" style="float: right;">Remover esta rubrica</a>\n\
                    </p>\n\
                </div>\n\
            </fieldset>\n\
        </div>';
    $("#div_add_rubricas").before(template);
    $("#next_index_rubrica").val(index+1);
}

function remover_rubrica(index){
    $("#div_rubrica_"+index).remove();
}

function excluir_rubrica(index, id){
    remover_rubrica(index);
    $("#next_index_rubrica").before('<input type="hidden" value="'+id+'" name="excluir_rubricas_ids[]">');
}





function add_detalhe(rubrica_index){
    var index = parseInt($("#next_index_detalhe_"+rubrica_index).val());
    var template = '\
        <div id="div_detalhe_'+rubrica_index+'_'+index+'" class="detalhe">\n\
            <div class="_50">\n\
                <p>\n\
                    <label for="Rubrica_'+rubrica_index+'_Detalhe_'+index+'_descricao">Descrição</label>\n\
                    <input size="60" maxlength="255" name="Rubrica['+rubrica_index+'][Detalhe]['+index+'][descricao]" id="Rubrica_'+rubrica_index+'_Detalhe_'+index+'_descricao" type="text" class="text">\n\
                </p>\n\
            </div>\n\
        \n\
        \n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Rubrica_'+rubrica_index+'_Detalhe_'+index+'_quantidade">Quantidade</label>\n\
                    <input size="60" maxlength="255" name="Rubrica['+rubrica_index+'][Detalhe]['+index+'][quantidade]" id="Rubrica_'+rubrica_index+'_Detalhe_'+index+'_quantidade" type="text" class="text">\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Rubrica_'+rubrica_index+'_Detalhe_'+index+'_valor_previsto">Valor previsto</label>\n\
                    <input size="60" maxlength="255" name="Rubrica['+rubrica_index+'][Detalhe]['+index+'][valor_previsto]" id="Rubrica_'+rubrica_index+'_Detalhe_'+index+'_valor_previsto" type="text" class="text valor">\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                <p>\n\
                    <a name="remover_detalhe_'+rubrica_index+'_'+index+'" id="remover_detalhe_'+rubrica_index+'_'+index+'" class="remover_detalhe button" onclick="remover_detalhe('+rubrica_index+','+index+');" style="float: right;">Remover este detalhe</a>\n\
                </p>\n\
            </div>\n\
        </div>';
    $("#div_add_detalhes_"+rubrica_index).before(template);
    input_valor();
    $("#next_index_detalhe_"+rubrica_index).val(index+1);
}

function remover_detalhe(rubrica_index,index){
    $("#div_detalhe_"+rubrica_index+"_"+index).remove();
}

function excluir_detalhe(rubrica_index,index, id){
    remover_detalhe(rubrica_index,index);
    $("#next_index_detalhe_"+rubrica_index).before('<input type="hidden" value="'+id+'" name="Rubrica['+rubrica_index+'][excluir_detalhes_ids][]">');
}