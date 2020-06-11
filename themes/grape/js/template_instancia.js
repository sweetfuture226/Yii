function add_instancia(tipo){
    switch (tipo) {
        case 'executora':
            var indice = '0';
            break;
        case 'fonte':
            var indice = '1';
            break;
        case 'interventora':
            var indice = '2';
            break;
    }

    var index = parseInt($("#next_index_instancia_"+tipo).val());
    var template = '\
        <div id="div_instancia_'+tipo+'_'+index+'" class="instancia" style="border-bottom: 1px solid #E7E7E7; padding-bottom: 15px;">\n\
            <div class="_25">\n\
                <p>\n\
                    <label for="Instituicao_'+indice+'_Instancia_'+index+'_id">Instância</label>\n\
                    <select class="chzn-select instancia_'+tipo+'" style="width: 100%; " name="Instituicao['+indice+'][Instancia]['+index+'][id]" id="Instituicao_'+indice+'_Instancia_'+index+'_id">\n\
                        <option value="">Selecione</option>\n\
                    </select>\n\
                </p>\n\
            </div>\n\
        \n\
            <div class="_25" style="padding-top: 18px;">\n\
                <p>\n\
                    <a name="remover_instancia_'+indice+'_'+index+'" id="remover_instancia_'+indice+'_'+index+'" class="remover_instancia button" onclick="remover_instancia(\''+tipo+'\','+index+');" style="float: right;">Remover esta instância</a>\n\
                </p>\n\
            </div>\n\
            <div class="clear"></div>\n\
        </div>';
    $("#div_add_instancias_"+tipo+"s").before(template);
    $(".chzn-select").chosen({no_results_text: "Não encontrado"});
    if (window.location.host=="localhost") {
        var url='/ctai/contrato/getinstancias'+tipo+'s';
    } else {
        var url='/contrato/getinstancias'+tipo+'s';
    }
    $.ajax({
        type:'POST',
        data: $('form').serialize(),
        url: url,
        success: function(data){
            $(".instancia_"+tipo).each(function(){
                if($(this).val()=="")
                    $(this).html(data);
            });
            $("select.chzn-select").trigger("liszt:updated");
        }
    });
    $("#next_index_instancia_"+tipo).val(index+1);
}

function remover_instancia(tipo,index){
    $("#div_instancia_"+tipo+"_"+index).remove();
}

function excluir_instancia(tipo, index, id){
    remover_instancia(tipo, index);
    switch (tipo) {
        case 'executora':
            var indice = '0';
            break;
        case 'fonte':
            var indice = '1';
            break;
        case 'interventora':
            var indice = '2';
            break;
    }
    $("#next_index_instancia_"+tipo).before('<input type="hidden" value="'+id+'" name="Instituicao['+indice+'][excluir_instancias_ids][]">');
}