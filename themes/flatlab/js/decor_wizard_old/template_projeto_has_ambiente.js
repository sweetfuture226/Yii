jQuery.fn.extend({
    exists: function() {
        return this.length > 0;
    }
});

function adicionarAmbiente() {
    var indice = $("#indice_proximo_ambiente").val();
    var ambiente_selecionado = $("#selecionar_ambiente option:selected");

    

    var ambiente_nome = ambiente_selecionado.text();
    var tipo_ambiente_id = ambiente_selecionado.val();
    if (tipo_ambiente_id != '') {
        var template = '\
            <div class="ambiente_selecionado_projeto">\n\
            <input type="hidden" value="" name="DecorAmbiente[selecionados][' + indice + '][id]" id="DecorAmbiente_selecionados_' + indice + '_id" />\n\
            <input type="hidden" value="' + tipo_ambiente_id + '" name="DecorAmbiente[selecionados][' + indice + '][tipo_ambiente_id]" id="DecorAmbiente_selecionados_' + indice + '_tipo_ambiente_id" />\n\
            <input type="text" value="' + ambiente_nome + '" name="DecorAmbiente[selecionados][' + indice + '][nome_personalizado]" id="DecorAmbiente_selecionados_' + indice + '_nome_personalizado" maxlength="255" class="form-control nome_personalizado"/>\n\
            <input class="remover_item btn" onclick="removerAmbiente(this)" type="button" value="" />\n\
            </div>';
        $('#ambientes_selecionados').append(template);
        if(indice%3==0){
            if(indice!=0)
                $("#ambientes_selecionados").height("+=45");
            else
                $("#ambientes_selecionados").height("+=25");
        }
        indice++;
        
        $("#indice_proximo_ambiente").val(indice);
    } else {
        document.getElementById('message').innerHTML = "Você deve selecionar um ambiente!";
        $('#btn_modal_open').click();
    }
}

function removerAmbiente(element) {
    var indice = $("#indice_proximo_ambiente").val();
    element.parentNode.remove();
    indice--;
    $("#indice_proximo_ambiente").val(indice);
    if((indice)%3==0){
           if(indice!=0)
                $("#ambientes_selecionados").height("-=45");
            else
                $("#ambientes_selecionados").height("-=25");
        }
}

function adicionarFieldAmbiente(index) {
    var template = '<fieldset id="fieldset_amb_' + index + '">\n\
                        <legend id="legend_amb_' + index + '"><b>Ambiente: </b></legend>\n\
                        <div id="tipologia_' + index + '">\n\
                            <input name="DecorAmbiente[' + index + '][id]" id="DecorAmbiente_' + index + '_id" type="hidden" value="">\n\
                            <div id="div_tipologia_' + index + '_0">\n\
                                <input name="DecorTipologia[' + index + '][0][id]" id="DecorTipologia_' + index + '_0_id" type="hidden" value="">\n\
                                <div class="form-group  col-lg-4">\n\
                                    <label for="DecorTipologia[' + index + '][0][tipologia_codigo]">Código</label>\n\\n\
                                        <input disabled="disabled" class="form-control" type="text" name="DecorTipologia[' + index + '][0][tipologia_codigo]" id="DecorTipologia_' + index + '_0_tipologia_codigo">\n\
                                </div>\n\
                                <div class="form-group  col-lg-4">\n\
                                    <label for="DecorTipologia[' + index + '][0][tipologia_nome]">Nome <span class="required">*</span></label>\n\\n\
                                        <input class="validate[required] form-control" type="text" name="DecorTipologia[' + index + '][0][tipologia_nome]" id="DecorTipologia_' + index + '_0_tipologia_nome">\n\
                                </div>\n\
                                <div class="fix"></div>\n\
                                <div class="form-group col-lg-3 button">\n\
                                    <input class="button btn" style="height: 26px;" id="bt_' + index + '_0" onclick="adicionarTipologia(' + index + ');" name="yt2" type="button" value="+ Adicionar outra tipologia">\n\
                                </div>\n\
                            </div>\n\
                            <input id="cont_tipologia_' + index + '" type="hidden" value="0" name="cont_tipologia_' + index + '">\n\
                        </div>\n\
                    </fieldset>';
    $('#ambiente_has_tipologia_fieldset').append(template);
    $("#decor-tipologia-form").validationEngine('detach');
    $("#decor-tipologia-form").validationEngine('attach');
}

function removerFieldAmbiente(index) {
    var cont_tipologia = $("#cont_tipologia_" + index).val();
    cont_tipologia = parseInt(cont_tipologia);
    for (var i = cont_tipologia; i >= 0; i--) {
        var tipologia_div = $("#div_tipologia_" + index + "_" + i);
        if (tipologia_div.exists()) {
            tipologia_div.remove();
        }
    }
    $("#fieldset_amb_" + index).remove();
}

