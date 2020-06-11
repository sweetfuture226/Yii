function add_objetivo(){
    var index = parseInt($("#next_index_objetivo").val());
    var template = '\
        <div id="div_objetivo_'+index+'" class="objetivo">\n\
            <div class="_100">\n\
                <p>\n\
                    <label for="Objetivo_'+index+'_descricao">Descrição</label>\n\
                    <textarea name="Objetivo['+index+'][descricao]" id="Objetivo_'+index+'_descricao" rows="5"></textarea>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_100" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
                <p>\n\
                    <a name="remover_objetivo_'+index+'" id="remover_objetivo_'+index+'" class="remover_objetivo button" onclick="remover_objetivo('+index+');" style="float: right;">Remover este objetivo</a>\n\
                </p>\n\
            </div>\n\
        </div>';
    $("#div_add_objetivos").before(template);
    $("#next_index_objetivo").val(index+1);
}

function remover_objetivo(index){
    $("#div_objetivo_"+index).remove();
}

function excluir_objetivo(index, id){
    remover_objetivo(index);
    $("#next_index_objetivo").before('<input type="hidden" value="'+id+'" name="excluir_objetivos_ids[]">');
}