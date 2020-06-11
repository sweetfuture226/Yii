function add_producao(){
    var index = parseInt($("#next_index_producao").val());
    var template = '\
        <div id="div_producao_'+index+'" class="producao">\n\
            <div class="_50">\n\
                <p>\n\
                    <label for="Producao_'+index+'_tipo">Tipo</label>\n\
                    <select class="chzn-select tipo_producao" style="width: 100%; " name="Producao['+index+'][tipo]" id="Producao_'+index+'_tipo">\n\
                        <option value="">Selecione</option>\n\
                        <option value="Artigo publicado em periódico">Artigo publicado em periódico</option>\n\
                        <option value="Artigo publicado em jornal ou revista">Artigo publicado em jornal ou revista</option>\n\
                        <option value="Livros e capítulos">Livros e capítulos</option>\n\
                        <option value="Projetos de pesquisa">Projetos de pesquisa</option>\n\
                        <option value="Participação/organização de eventos">Participação/organização de eventos</option>\n\
                        <option value="Participação de banca/comissão julgadora">Participação de banca/comissão julgadora</option>\n\
                        <option value="Software">Software</option>\n\
                        <option value="Trabalhos publicados em anais de eventos">Trabalhos publicados em anais de eventos</option>\n\
                    </select>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100">\n\
                <p>\n\
                    <label for="Producao_'+index+'_descricao">Descrição</label>\n\
                    <textarea name="Producao['+index+'][descricao]" id="Producao_'+index+'_descricao" rows="5"></textarea>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                <p>\n\
                    <a name="remover_producao_'+index+'" id="remover_producao_'+index+'" class="remover_producao button" onclick="remover_producao('+index+');" style="float: right;">Remover esta produção</a>\n\
                </p>\n\
            </div>\n\
        </div>';
    $("#div_add_producoes").before(template);
    $(".chzn-select").chosen({no_results_text: "Não encontrado"}); 
    $("#next_index_producao").val(index+1);
}

function remover_producao(index){
    $("#div_producao_"+index).remove();
}

function excluir_producao(index, id){
    remover_producao(index);
    $("#next_index_producao").before('<input type="hidden" value="'+id+'" name="excluir_producoes_ids[]">');
}