//Calcula o preco total de um produto de acordo com o valor unitario e a quantidade
function calcPrecoTotalProduto(index,id){
    if(typeof id == 'undefined')
    {
        var id = '';
    }

    var id_preco_unitario = "DecorTipologiaHasProduto_"+index+"_preco_enviado_fornecedor";
    var id_quantidade = "DecorTipologiaHasProduto_"+index+"_quantidade";

    var preco_unitario = $("#"+id_preco_unitario).val();
    preco_unitario = string2float(preco_unitario);

    var quantidade = $("#"+id_quantidade).val();
    quantidade = string2float(quantidade);

    if(isFinite(quantidade) && isFinite(preco_unitario)){
        var preco_total = quantidade*preco_unitario;
        if(isFinite(preco_total) && preco_total != 0){
            $("#DecorTipologiaHasProduto_"+index+"_preco_total").val(float2string(preco_total.toFixed(2)));
        } else {
            $("#DecorTipologiaHasProduto_"+index+"_preco_total").val('');
        }
    } else {
        $("#DecorTipologiaHasProduto_"+index+"_preco_total").val('');
    }
}

function precoTotalProduto(index,id){
    calcPrecoTotalProduto(index,id);
    calcRTValor(index);
    calcPrecoTotalCotacao();
}

function calcPrecoTotalCotacao(){
    var preco_total = parseFloat('0');
    $(".preco_total_produto").each(function(){
        var preco_produto = $(this).val();
        preco_produto = string2float(preco_produto);
        if(isFinite(preco_produto)){
            preco_total = parseFloat(preco_total) + parseFloat(preco_produto);
        }
    });
    if(isFinite(preco_total)){
        $("#DecorCotacao_preco_total").html('<h4>Total da cotação: R$ '+float2string(preco_total.toFixed(2))+'</h4>');
    } else {
        $("#DecorCotacao_preco_total").html('<h4>Total da cotação: R$ 0,00</h4>');
    }
}

function calcPrecoTotalAnaliseCotacao(){
    var preco_total = parseFloat('0');
    $(".preco_total_produto").each(function(){
        var preco_produto = $(this).val();
        preco_produto = string2float(preco_produto);
        if(isFinite(preco_produto)){
            preco_total = parseFloat(preco_total) + parseFloat(preco_produto);
        }
    });
    if(isFinite(preco_total)){
        $("#DecorCotacao_preco_total").html('<h4>Total: R$ '+float2string(preco_total.toFixed(2))+'</h4>');
    } else {
        $("#DecorCotacao_preco_total").html('<h4>Total: R$ 0,00</h4>');
    }
}

function calcRTPercentagem(index,id){
    var rt_valor = $("#DecorTipologiaHasProduto_"+index+"_rt_fechada").val();
    rt_valor = string2float(rt_valor);

    var preco_produto = $("#DecorTipologiaHasProduto_"+index+"_preco_total").val();
    preco_produto = string2float(preco_produto);
    
    if(isFinite(rt_valor) && isFinite(preco_produto)){
        var rt_percentagem = 100*rt_valor/preco_produto;
        if(isFinite(rt_percentagem) && rt_percentagem != 0){
            $("#DecorTipologiaHasProduto_"+index+"_rt_fechada_percentagem").val(rt_percentagem.toFixed(0));
        } else {
            $("#DecorTipologiaHasProduto_"+index+"_rt_fechada_percentagem").val('');
        }
    } else {
        $("#DecorTipologiaHasProduto_"+index+"_rt_fechada_percentagem").val('');
    }
}

function RTPercentagem(index,id){
    calcRTPercentagem(index,id);
    calcRTAcumuladaCotacao(id);
}

function calcRTValor(index){
    var rt_percentagem = $("#DecorTipologiaHasProduto_"+index+"_rt_fechada_percentagem").val();

    var preco_produto = $("#DecorTipologiaHasProduto_"+index+"_preco_total").val();
    preco_produto = string2float(preco_produto);
    
    if(isFinite(rt_percentagem) && isFinite(preco_produto)){
        var rt_valor = rt_percentagem*preco_produto/100;
        if(isFinite(rt_valor) && rt_valor != 0){
            $("#DecorTipologiaHasProduto_"+index+"_rt_fechada").val(float2string(rt_valor.toFixed(2)));
        } else {
            $("#DecorTipologiaHasProduto_"+index+"_rt_fechada").val('');
        }
    } else {
        $("#DecorTipologiaHasProduto_"+index+"_rt_fechada").val('');
    }
}

function RTValor(index){
    calcRTValor(index)
    calcRTAcumuladaCotacao();
}

function calcRTAcumuladaCotacao(id){
    if(typeof id == 'undefined'){
        var id = '';
    }
    var rt_acumulada = parseFloat('0');
    $(".rt_produto").each(function(){
        var rt_produto = $(this).val();
        rt_produto = string2float(rt_produto);
        if(isFinite(rt_produto)){
            rt_acumulada = parseFloat(rt_acumulada) + parseFloat(rt_produto);
        }
    });
    if(isFinite(rt_acumulada)){
        $("#DecorCotacao_rt_acumulada").html('<h4>Pontuação: '+float2string(rt_acumulada)+'</h4>');
    } else {
        $("#DecorCotacao_rt_acumulada").html('<h4>Pontuação: 0,00</h4>');
    }
}

function calcRTAcumuladaAnaliseCotacao(id){
    if(typeof id == 'undefined'){
        var id = '';
    }
    var rt_acumulada = parseFloat('0');
    $(".rt_produto").each(function(){
        var rt_produto = $(this).val();
        rt_produto = string2float(rt_produto);
        if(isFinite(rt_produto)){
            rt_acumulada = rt_acumulada + parseFloat(rt_produto);
        }
    });
    if(isFinite(rt_acumulada)){
        $("#DecorCotacao_rt_acumulada").html('<h4>Pontuação: '+float2string(rt_acumulada)+'</h4>');
    } else {
        $("#DecorCotacao_rt_acumulada").html('<h4>Pontuação: 0,00</h4>');
    }
}

//Converte 10.000,50 para 10000.50
function string2float(string) {
    string = string.replace(/\./g,"");
    string = string.replace(",",".");
    string = parseFloat(string);

    return string;
}
