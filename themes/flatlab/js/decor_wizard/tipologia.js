jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});

function adicionarCampoTipologia(index){
    var campo = 'tipologia';
    var indice = $("#cont_tipologia_"+index).val();
    indice++;
    var template = '\
            <div id="div_'+campo+'_'+index+'_'+indice+'">\n\
                <input name="DecorTipologia['+index+'][0][id]" id="DecorTipologia_'+index+'_0_id" type="hidden" value="">\n\
                <div class="form-group  col-lg-4">\n\
                    <label for="DecorTipologia['+index+']['+indice+'][tipologia_codigo]">Código</label>\n\\n\
                        <input type="text" class="form-control" style="width: 100%;" name="DecorTipologia['+index+']['+indice+'][tipologia_codigo]" id="DecorTipologia_'+index+'_'+indice+'_tipologia_codigo">\n\
                </div>\n\
                <div class="form-group  col-lg-4">\n\
                    <label for="DecorTipologia['+index+']['+indice+'][tipologia_nome]">Nome</label>\n\\n\
                        <input type="text" class="validate[required] form-control" style="width: 100%;" name="DecorTipologia['+index+']['+indice+'][tipologia_nome]" id="DecorTipologia_'+index+'_'+indice+'_tipologia_nome">\n\
                </div>\n\
                <div class="form-group col-lg-3 buttons" style="margin: 0">\n\
                    <div style="float: left; ">\n\
                       <input class="btn button" style="height: 26px;" id="bt_'+index+'_'+indice+'" onclick="removerTipologia('+index+','+indice+','+'\''+campo+'\''+')" type="button" value="- Remover tipologia">\n\
                    </div>\n\
                </div>\n\
            </div>';
        $('#tipologia_'+index).append(template);
        $("#cont_tipologia_"+index).val(indice).trigger('change');
        $("#decor-tipologia-form").validationEngine('detach');
        $("#decor-tipologia-form").validationEngine('attach');
}

function removerTipologia(indexa, indext, campo){
    $('#div_'+campo+'_'+indexa+'_'+indext).remove();
    var cont = $("#cont_tipologia_"+indexa).val();
    $("#cont_tipologia_"+indexa).val(cont).trigger('change');
}
