jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});

function ifSelectNotEmpty(field, rules, i, options){
  if ($(field).find("option").length > 0 && 
      $(field).find("option:selected").length == 0) {
     // this allows the use of i18 for the error msgs
     return "* Campo obrigatório";
  }
}

function salvarTipologia(step_num){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorCotacao/salvarTipologiaAnalise",
        type: 'POST',
        async: false,
        data: $("#decor-tipologia-"+step_num+"-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Tipologia não pôde ser salva.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function salvarPassoAtual(step_num){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorCotacao/salvarPassoAtual",
        type: 'POST',
        async: false,
        data: $("#decor-tipologia-"+step_num+"-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Tipologia não pôde ser salva.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function selecionarProduto(index_produto,tipologia_id){
    $(".produto_"+tipologia_id).each(function(){
        $(this).removeClass('produto_selecionado');
    });
    $("input[name$='_"+tipologia_id+"][rt_fechada]']").each(function(){
        $(this).removeClass('rt_produto');
    });
    $("input[name$='_"+tipologia_id+"][preco_total]']").each(function(){
        $(this).removeClass('preco_total_produto');
    });
    $("#div_produto_"+index_produto).addClass('produto_selecionado');
    $("input[name='DecorTipologiaHasProduto["+index_produto+"][rt_fechada]']").addClass('rt_produto');
    $("input[name='DecorTipologiaHasProduto["+index_produto+"][preco_total]']").addClass('preco_total_produto');
    $('input[name="DecorCotacao['+tipologia_id+'][produto_escolhido]"][value="'+index_produto+'"]').attr('checked',true);
    calcRTAcumuladaAnaliseCotacao();
    calcPrecoTotalAnaliseCotacao();
}