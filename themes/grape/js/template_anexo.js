function add_anexo(){
    var index = parseInt($("#next_index_anexo").val());
    var template = '\
        <div id="div_anexo_'+index+'" class="anexo">\n\
            <div class="_100">\n\
                <p>\n\
                    <label for="Anexo_'+index+'_arquivo">Arquivo</label>\n\
                    <!--<div class="uploader" id="uniform-Anexo_arquivo" style="">-->\n\
                        <input name="Anexo['+index+'][arquivo]" id="Anexo_'+index+'_arquivo" type="file"><!-- size="30.8" style="opacity: 0; "-->\n\
                        <!--<span class="filename">Nenhum arquivo selecionado</span>\n\
                        <span class="action">Selecione o arquivo</span>\n\
                    </div>-->\n\
                </p>\n\
            </div>\n\
            <div class="_100">\n\
                <p>\n\
                    <label for="Anexo_'+index+'_descricao">Descrição</label>\n\
                    <textarea name="Anexo['+index+'][descricao]" id="Anexo_'+index+'_descricao" rows="5"></textarea>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                <p>\n\
                    <a name="remover_anexo_'+index+'" id="remover_anexo_'+index+'" class="remover_anexo button" onclick="remover_anexo('+index+');" style="float: right;">Remover este anexo</a>\n\
                </p>\n\
            </div>\n\
        </div>';
    $("#div_add_anexos").before(template);
    $("#next_index_anexo").val(index+1);
}

function remover_anexo(index){
    $("#div_anexo_"+index).remove();
}

function excluir_anexo(index, id){
    remover_anexo(index);
    $("#next_index_anexo").before('<input type="hidden" value="'+id+'" name="excluir_anexos_ids[]">');
}