jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});

function adicionarColaborador(){
    var campo = 'colaborador';
    var indice = $("#cont_colaborador").val();
    indice++;
    var template = '\
            <div id="div_'+campo+'_'+indice+'">\n\
                <div class="form-group col-lg-6">\n\
                    <label for="DecorProjetoHasColaborador['+indice+'][colaborador_id]">Colaborador</label>\n\\n\
                        <select class="chzn-select" style="width: 100%;" name="DecorProjetoHasColaborador['+indice+'][colaborador_id]" id="DecorProjetoHasColaborador_'+indice+'_colaborador_id">\n\
                            <option value=""></option>\n\
                        </select>\n\
                </div>\n\
                <div class="form-group col-lg-3 buttons" style="margin: 0">\n\
                    <div style="float: left; ">\n\
                       <input class="btn button" style="height: 26px;" id="bt_b'+indice+'" onclick="removerColaborador('+indice+','+'\''+campo+'\''+')" name="yt0" type="button" value="- Remover colaborador">\n\
                    </div>\n\
                </div>\n\
            </div>';
        $('#colaborador').append(template);
        $("#cont_colaborador").val(indice).trigger('change');
        getNomesColaboradores(indice);
        $(".chzn-select").chosen({no_results_text: "Não encontrado"});
}

function removerColaborador(index, campo){
    $('#div_'+campo+'_'+index).remove();
    var cont = $("#cont_colaborador").val();
    $("#cont_colaborador").val(cont).trigger('change');
}

function getNomesColaboradores(i){
    //Passar indice por parâmetro
    $.get(baseUrl+'/funcionario/getFuncionarios/',
    function(data){
        $("select#DecorProjetoHasColaborador_"+i+'_colaborador_id').empty();
        $("#DecorProjetoHasColaborador_"+i+'_colaborador_id').each(function(){
                $(this).html(data);
            });
        $("#DecorProjetoHasColaborador_"+i+'_colaborador_id').prepend(""); 
        $("select.chzn-select").trigger("liszt:updated");
    });
}
