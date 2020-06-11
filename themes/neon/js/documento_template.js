//window.onload = function init(){
//    add_documento();
//    
//}

function rm_documento(index){
    
    $(".painel"+index).remove();
    
    
}

function associarDocumento() {
        var indice = $("#indice").val();
        
        var documento_selecionado = $("#documentos option:selected");

        var existe = false;

        var documento_nome = documento_selecionado.text();
        
        var tipo_documento_id = documento_selecionado.val();
        for (i=0 ; i<=indice;i++){
            if(documento_nome == $('#Documento_selecionados_'+i+'_nome_documento').val())
                existe = true;
        }
        
        if (tipo_documento_id != '') {
            if(!existe){
            var template = '\n\
 <li class="search-choice">\n\
 \
 <input type="hidden" value="' + tipo_documento_id + '" name="Documento[selecionados][' + indice + '][id_documento]" id="Documento_selecionados_' + indice + '_nome_documento" />\n\
 <span type="text" value="' + documento_nome + '" name="Documento[selecionados][' + indice + '][nome_personalizado]" id="Documento_selecionados_' + indice + '_nome_personalizado" maxlength="255" readonly/>' + documento_nome + '</span>\n\
 <input class="remover_item btn" onclick="removerDocumento(this)" type="button" value="" /></li>\n\
 ';
            $('#documentos_selecionados').append(template);
            if (indice % 3 == 0) {
                if (indice != 0)
                    $("#documentos_selecionados").height("+=45");
                else
                    $("#documentos_selecionados").height("+=25");
            }
            indice++;

            $("#indice").val(indice);
        }
        else{
            document.getElementById('message').innerHTML = "Documento já inserido na lista!";
            $('#btn_modal_open').click();
        } 
    }
        else {
            document.getElementById('message').innerHTML = "Você deve selecionar um documento!";
            $('#btn_modal_open').click();
        }
  }
    function removerDocumento(element) {
        var indice = $("#indice_proximo_documento").val();
        element.parentNode.remove();
        indice--;
        $("#indice_proximo_documento").val(indice);
        if ((indice) % 3 == 0) {
            if (indice != 0)
                $("#documentos_selecionados").height("-=45");
            else
                $("#documentos_selecionados").height("-=25");
        }
    }
    
    $("#bt_assoc_doc").click(function(){
        $("#associarDocumento").show();
        
    });