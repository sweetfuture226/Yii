jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});

$( document ).ready(function() {
  var qtd = $("#count_contato").val();
  for (i=0;i<qtd;i++){
      checkMask(i);
  }
});
function adicionarContato(){
var indice = $("#count_contato").val();
indice++;
var campo = 'contato';
var template='<div class="div_'+campo+'_'+indice+'">';    
template+='<fieldset>\n';
template+='<legend>Contato</legend>\n';    
template+='    <div class="form-group  col-lg-4">\n';
template+='        <label for="FornecedorHasContato_'+indice+'_nome">Nome</label>\n';
template+='            <input class ="form-control" size="45" maxlength="45" name="FornecedorHasContato['+indice+'][nome]" id="FornecedorHasContato_'+indice+'_nome" type="text" />\n';
template+='    </div>\n';
template+='    <div class="form-group  col-lg-4">\n';
template+='        <label for="FornecedorHasContato_'+indice+'_email">Email</label>\n';
template+='            <input class ="form-control" size="30" maxlength="45" name="FornecedorHasContato['+indice+'][email]" id="FornecedorHasContato_'+indice+'_email" type="email" />\n';
template+='    </div>\n';
template+='    <div class="form-group  col-lg-4">\n';
template+='        <label for="FornecedorHasContato_'+indice+'_telefone">Telefone</label>\n';
template+='            <input onChange="checkMask('+indice+');" class ="form-control telefone" size="10" maxlength="13" name="FornecedorHasContato['+indice+'][telefone]" id="FornecedorHasContato_'+indice+'_telefone" type="text" />                    \n';
template+='    </div>\n';
template+='<input class="btn btn-danger" id="bt_contato_'+indice+'_remove" onclick="removerContato('+indice+');" name="yt0" type="button" value="- Contato">';
template+='</fieldset>';
template+='</div>';

    
    $("#bt_contato").before(template);
    $("#count_contato").val(indice).trigger('change');    
   $('#FornecedorHasContato_'+indice+'_telefone').unmask();
    $('#FornecedorHasContato_'+indice+'_telefone').mask("(99)9999-9999?9",{placeholder:" "});
    
}
function checkMask(indice){
    telefone = $('#FornecedorHasContato_'+indice+'_telefone');
    if(telefone.val().length==14){
        telefone.unmask();
        telefone.mask("(99)99999-999?9",{placeholder:" "});
    }
    else{
        telefone.unmask();
        telefone.mask("(99)9999-9999?9",{placeholder:" "});
    }    
}
function removerContato(index){
    
    $('.div_contato_'+index).remove();
    var indice = $("#count_contato").val();
    indice--;
    $("#count_contato").val(indice).trigger('change');
}

