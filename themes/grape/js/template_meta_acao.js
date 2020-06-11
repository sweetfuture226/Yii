function add_meta(){
    var index = parseInt($("#next_index_meta").val());
    var template = '\
        <div id="div_meta_'+index+'" class="meta">\n\
            <fieldset>\n\
                <legend>Meta</legend>\n\
                \n\
                <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                    <p>\n\
                        <label for="Meta_'+index+'_descricao">Título</label>\n\
                        <input maxlength="255" name="Meta['+index+'][titulo]" id="Meta_'+index+'_titulo" type="text" class="text">\n\
                    </p>\n\
                </div>\n\
                \n\
                <div id="div_add_acoes_'+index+'" class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                    <input type="hidden" value="0" name="next_index_acao_'+index+'" id="next_index_acao_'+index+'">\n\
                    <p>\n\
                        <a id="adicionar_acao_'+index+'" class="button" onclick="add_acao('+index+');">Adicionar ação</a>\n\
                    </p>\n\
                </div>\n\
                \n\
                <div class="_100">\n\
                    <p>\n\
                        <a name="remover_meta_'+index+'" id="remover_meta_'+index+'" class="remover_meta button" onclick="remover_meta('+index+');" style="float: right;">Remover esta meta</a>\n\
                    </p>\n\
                </div>\n\
            </fieldset>\n\
        </div>';
    $("#div_add_metas").before(template);
    $("#next_index_meta").val(index+1);
}

function remover_meta(index){
    $("#div_meta_"+index).remove();
}

function excluir_meta(index, id){
    remover_meta(index);
    $("#next_index_meta").before('<input type="hidden" value="'+id+'" name="excluir_metas_ids[]">');
}





function add_acao(meta_index){
    var index = parseInt($("#next_index_acao_"+meta_index).val());
    var template = '\
        <div id="div_acao_'+meta_index+'_'+index+'" class="acao">\n\
            <div class="_50">\n\
                <p>\n\
                    <label for="Meta_'+meta_index+'_Acao_'+index+'_descricao">Descrição</label>\n\
                    <input size="60" maxlength="255" name="Meta['+meta_index+'][Acao]['+index+'][descricao]" id="Meta_'+meta_index+'_Acao_'+index+'_descricao" type="text" class="text">\n\
                </p>\n\
            </div>\n\
        \n\
        \n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Meta_'+meta_index+'_Acao_'+index+'_data_prevista">Data prevista</label>\n\
                    <input size="60" maxlength="255" name="Meta['+meta_index+'][Acao]['+index+'][data_prevista]" id="Meta_'+meta_index+'_Acao_'+index+'_data_prevista" type="text" class="date">\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Meta_'+meta_index+'_Acao_'+index+'_status">Status</label>\n\
                    <select class="chzn-select" style="width: 100%; " name="Meta['+meta_index+'][Acao]['+index+'][status]" id="Meta_'+meta_index+'_Acao_'+index+'_status">\n\
                        <option value="">Selecione</option>\n\
                        <option value="Pendente">Pendente</option>\n\
                        <option value="Em andamento">Em andamento</option>\n\
                        <option value="Concluído">Concluído</option>\n\
                    </select>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                <p>\n\
                    <a name="remover_acao_'+meta_index+'_'+index+'" id="remover_acao_'+meta_index+'_'+index+'" class="remover_acao button" onclick="remover_acao('+meta_index+','+index+');" style="float: right;">Remover esta ação</a>\n\
                </p>\n\
            </div>\n\
        </div>';
    $("#div_add_acoes_"+meta_index).before(template);
    $(".chzn-select").chosen({no_results_text: "Não encontrado"}); 
    $(".date").datepicker({ 
            autoSize: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1940:',
            dateFormat: 'dd/mm/yy'
    });	
    $("#next_index_acao_"+meta_index).val(index+1);
}

function remover_acao(meta_index,index){
    $("#div_acao_"+meta_index+"_"+index).remove();
}

function excluir_acao(meta_index,index, id){
    remover_acao(meta_index,index);
    $("#next_index_acao_"+meta_index).before('<input type="hidden" value="'+id+'" name="Meta['+meta_index+'][excluir_acoes_ids][]">');
}