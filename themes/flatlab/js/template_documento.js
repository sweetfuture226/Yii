function carregarDocumentos(cargo_id,funcionario_id)
{
    if(typeof funcionario_id == 'undefined')
    {
        var _url =baseUrl+'/tipoDocumento/getDocumentos/?cargo_id='+cargo_id;
    }
    else
    {
        var _url = baseUrl+'/tipoDocumento/getDocumentos/?cargo_id='+cargo_id+'&funcionario_id='+funcionario_id;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: _url,
        success: function (data) {
            if(data != null){
                var length = data.length;
                var element = null;
                for (var i = 0; i < length; i++) {
                    element = data[i];
                    add_documento(element,i);
                }
            } else {
                $("#documentos").html('<h4 class="note">Não existem documentos associados.</h4>');
            }
        }
    });
}

function add_documento(element,index)
{
    if(element['id'] != '')
    {
        var checked = 'checked="checked"';
        var visible = '';
        var disabled = '';
    }
    else
    {
        var checked = '';
        var visible = 'style="display:none"';
        var disabled = 'disabled="disabled"';
    }
    var template = '\
        <input type="hidden" value="'+element["id"]+'" name="Documento['+index+'][id]" id="Documento_'+index+'_id" '+disabled+'>\n\
        <input type="hidden" value="'+element["tipo_documento_id"]+'" name="Documento['+index+'][tipo_documento_id]" id="Documento_'+index+'_tipo_documento_id" '+disabled+'>\n\
        \n\
        <div class="form-group  col-lg-6" style="min-height: 0px !important">\n\\n\
                <input type="checkbox" name="Documento_'+index+'_nome" value="'+element['nome']+'" '+checked+' onclick="check(this.id);" class="check_doc" id="Documento_'+index+'_nome">\n\
                <label for="Documento_'+index+'_nome">'+element['nome']+'</label>\n\
        </div>\n\
        \n\
        <div style="clear:both"></div>\n\
        <div class="form-group col-lg-12">\n\
            <div id="Documento_'+index+'_div_doc" '+visible+' class="class="form-group  col-lg-12"">';
            
    if(element['arquivo'] != '')
        template += '<label for="Documento_'+index+'_arquivo"><a href="'+baseUrl+'/public/documentos_funcionarios/'+element["arquivo"]+'" target="_blank">Clique aqui para abrir o arquivo enviado.</a></label>';
    else
        template += '<label for="Documento_'+index+'_arquivo">Arquivo</label>';

    template += '\
                <input type="file" name="Documento['+index+'][arquivo]" id="Documento_'+index+'_arquivo" '+disabled+'>\n\
            </div>\n\
        </div>';
    $("#documentos").append(template);
}

function check(id){
        
        var doc = id.replace("nome", "");
        if ($("#"+id).attr("checked")) {
            var id_change = "#"+doc+"id";
            $(id_change).attr("disabled", false);
            id_change = "#"+doc+"tipo_documento_id";
            $(id_change).attr("disabled", false);
            id_change = "#"+doc+"arquivo";
            $(id_change).attr("disabled", false);
            id_change = "#"+doc+"div_doc";
            $(id_change).css("display", "block");
        }else{
            var id_change = "#"+doc+"id";
            $(id_change).attr("disabled", true);
            id_change = "#"+doc+"tipo_documento_id";
            $(id_change).attr("disabled", true);
            id_change = "#"+doc+"arquivo";
            $(id_change).attr("disabled", true);
            $(id_change).val("");
            id_change = "#"+doc+"div_doc";
            $(id_change).css("display", "none");
        }
}