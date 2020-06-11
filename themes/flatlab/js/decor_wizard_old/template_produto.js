jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});

function adicionarProduto(tipologia_id,tipo_tipologia_id){
    var campo = 'produto';
    var indice = $("#cont_produto_"+tipologia_id).val();
    indice++;
    var template = '\
            <div id="div_'+campo+'_'+tipologia_id+'_'+indice+'"  class="rowElem one_column" style="padding: 0; margin: 0 15px;">\n\
                <div class="form-group  col-lg-4">\n\
                    <label for="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][produto_id]">Produto <span class="required">*</span></label>\n\\n\
                        <div>\n\
                            <input name="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][produto_nome]" id="DecorTipologiaHasProduto_'+tipologia_id+'_'+indice+'_produto_nome" onkeyup="buscarProduto(this.value,'+tipologia_id+','+indice+','+tipo_tipologia_id+')" class="validate[required]" type="text" />\n\
                        </div>\n\
                        <input name="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][produto_id]" id="DecorTipologiaHasProduto_'+tipologia_id+'_'+indice+'_produto_id" type="hidden" class="validate[required]" />\n\
                        <div id="suggestions_'+tipologia_id+'_'+indice+'"></div>\n\
                    </div>\n\
                    <div class="fix"></div>\n\
                </div>\n\
                <div class="rowElem noborder produto_quantidade descricao_'+tipologia_id+'_'+indice+' invisible">\n\
                    <label for="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][quantidade]">Quantidade <span class="required">*</span></label>\n\\n\
                        <input class="numInteiro validate[required]" style="width:100%;" type="text" value="" name="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][quantidade]" id="DecorTipologiaHasProduto_'+tipologia_id+'_'+indice+'_quantidade">\n\
                    </div>\n\
                    <div class="fix"></div>\n\
                </div>\n\
                <div class="form-group  col-lg-4 descricao_'+tipologia_id+'_'+indice+' invisible">\n\
                    <label for="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][fornecedor_id]">Fornecedor</label>\n\\n\
                        <select class="chzn-select" style="width: 100%;" name="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][fornecedor_id]" id="DecorTipologiaHasProduto_'+tipologia_id+'_'+indice+'_fornecedor_id" disabled="disabled">\n\
                            <option value=""></option>\n\
                        </select>\n\
                    </div>\n\
                    <div class="fix"></div>\n\
                </div>\n\
                <div class="rowElem noborder one_column descricao_'+tipologia_id+'_'+indice+' invisible" style="margin-bottom: 10px;">\n\
                    <label for="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][informacoes_adicionais]">Informações adicionais</label>\n\\n\
                        <textarea cols="185" rows="6" style="width:100%;" type="text" value="" name="DecorTipologiaHasProduto['+tipologia_id+']['+indice+'][informacoes_adicionais]" id="DecorTipologiaHasProduto_'+tipologia_id+'_'+indice+'_informacoes_adicionais"></textarea>\n\
                    </div>\n\
                    <div class="fix"></div>\n\
                </div>\n\
                <div class="rowElem noborder one_column" style="padding: 0;  margin: 0;">\n\
                    <div class="rowElem noborder four_columns buttons" style="margin: 0; padding: 0 15px;">\n\
                        <div style="float: left; ">\n\
                           <input class="button" style="height: 26px vertical-align:middle;" id="bt_'+tipologia_id+'_'+indice+'" onclick="removerProduto('+tipologia_id+','+indice+')" name="yt0" type="button" value="- Remover produto">\n\
                        </div>\n\
                        <div class="fix"></div>\n\
                    </div>\n\
                </div>\n\
            </div>';
    $("#produto_bt_"+tipologia_id).before(template);
    $("#cont_produto_"+tipologia_id).val(indice);
    getNomesFornecedores(tipologia_id,indice);
    $(".chzn-select").chosen({no_results_text: "Não encontrado"});
    $('.numInteiro').unmaskMoney();
    $('.numInteiro').maskMoney({
        symbol:'',
        showSymbol:true,
        thousands:'',
        decimal:',',
        symbolStay: false,
        precision: 0
    });
    $("#decor-produto-form").validationEngine('detach');
    $("#decor-produto-form").validationEngine('attach');
    // Safely inject CSS3 and give the search results a shadow
    var cssObj = { 'box-shadow' : '#888 5px 10px 10px', // Added when CSS3 is standard
            '-webkit-box-shadow' : '#888 5px 10px 10px', // Safari
            '-moz-box-shadow' : '#888 5px 10px 10px'}; // Firefox 3.5+
    $('div[id^="suggestions_"]').css(cssObj);

    // Fade out the suggestions box when not active
    $('html').click(function() {
        $('div[id^="suggestions_"]').fadeOut();
    });

    $('div[id^="suggestions_"]').click(function(event){
        event.stopPropagation();
    });
}

function removerProduto(tipologia_id, index, campo){
    $('#div_produto_'+tipologia_id+'_'+index).remove();
}

function getNomesProdutos(tipologia_id, index){
    //Passar indice por parâmetro
    $.get(baseUrl + '/decorProduto/getProdutos/',
    function(data){
        $("select#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_produto_id").empty();
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_produto_id").each(function(){
            $(this).html(data);
        });
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_produto_id").prepend('<option value="">Selecione</option>'); 
        $("select.chzn-select").trigger("liszt:updated");
    });
}

function getNomesFornecedores(tipologia_id, index){
    //Passar indice por parâmetro
    $.get(baseUrl + '/fornecedor/getFornecedor/',
    function(data){
        $("select#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_fornecedor_id").empty();
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_fornecedor_id").each(function(){
                $(this).html(data);
            });
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_fornecedor_id").prepend('<option value="">Selecione</option>'); 
        $("select.chzn-select").trigger("liszt:updated");
    });
}

function changeFornecedor(tipologia_id, index){
    //Passar indice por parâmetro
    var id = $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_produto_id").val();
    if(id != ''){
    $.get(baseUrl + '/decorProduto/getFornecedor/'+ id,
        function(data){
            $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_fornecedor_id").val(data);
            $("select.chzn-select").trigger("liszt:updated");
        });
    } else {
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_fornecedor_id").val($("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_fornecedor_id option:first").val());
        $("select.chzn-select").trigger("liszt:updated");
    }
}

function calcValorTotal(tipologia_id, index, id){
    var campo = 'produto';
    if(typeof id == 'undefined')
    {
        var id = '';
    }

    var cont = $("#cont_"+campo+"_"+tipologia_id).val();
    var valorTotal = parseFloat(0);
    
    var id_qnt = 'DecorTipologiaHasProduto_'+tipologia_id+'_'+index+'_quantidade';
    var id_pu = 'DecorTipologiaHasProduto_'+tipologia_id+'_'+index+'_preco_unitario_previsto';
    var qnt = $('#'+id_qnt).val();
    var element_pu = $('#'+id_pu);

    var valor_parcial = parseFloat(0);

    var preco_unitario = element_pu.val();
    if(id_pu == id) {
        preco_unitario = preco_unitario.substring(3);
    }
    preco_unitario = string2float(preco_unitario);
    if(isFinite(preco_unitario) && qnt != ''){
        valor_parcial = parseFloat(valor_parcial) + (parseFloat(preco_unitario) * parseFloat(qnt));
    }

    valorTotal = parseFloat(valorTotal) + parseFloat(valor_parcial);

    if(isFinite(valorTotal) && valorTotal != 0){
        valorTotal = valorTotal.toFixed(2);
        valorTotal = float2string(valorTotal);
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_preco_total_previsto").val(valorTotal);
    } else {
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_preco_total_previsto").val('');
    }
}

function carregarProduto(id,tipologia_id,indice,nome){
    $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+indice+"_produto_id").val(id);
    $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+indice+"_produto_nome").val(nome);
    changeFornecedor(tipologia_id, indice);
    $('.descricao_'+tipologia_id+'_'+indice).each(function(){
        $(this).removeClass('invisible');
    });
    $('div[id^="suggestions_"]').fadeOut();
}

function buscarProduto(inputString,tipologia_id,indice,tipo_tipologia_id){
    $('.descricao_'+tipologia_id+'_'+indice).each(function(){
        $(this).addClass('invisible');
    });
    
    
    /* Lógica para validar todos os campos (implementar)
     * var cont=0
    var campoProduto='0';
    while(campoProduto!='undefined'){
        campoProduto=$("#DecorTipologiaHasProduto_"+tipologia_id+"_"+cont+"_produto_nome").val();
        cont++;
    }*/
    
    //ler e armazena os nomes dos produtos escolhidos
    var produtosEscolhidos = '';
    
    for(var i=0;i<indice;i++){
        if(i<(indice-1))
            produtosEscolhidos+=$("#DecorTipologiaHasProduto_"+tipologia_id+"_"+i+"_produto_nome").val()+',';
        else
            produtosEscolhidos+=$("#DecorTipologiaHasProduto_"+tipologia_id+"_"+i+"_produto_nome").val();
    }
    
    
    if(inputString.length == 0) {
        $('div[id^="suggestions_"]').fadeOut(); // Hide the suggestions box
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+indice+"_produto_id").val('');
        $("#DecorTipologiaHasProduto_"+tipologia_id+"_"+indice+"_quantidade").val('');
        changeFornecedor(tipologia_id, indice);
    } else {
        var projeto_id = $("#DecorProduto_projeto_id").val();
        $.post(baseUrl + "/decorProduto/getProdutosDinamico",
        {queryString: ""+inputString+"",
            tipologia_id: ""+tipologia_id+"",
            indice: ""+indice+"",
            tipo_tipologia_id: ""+tipo_tipologia_id+"",
            projeto_id: ""+projeto_id+"",
            produtosEscolhidos:""+produtosEscolhidos+""}
        ,function(data) { // Do an AJAX call
                //alert(produtosEscolhidos);
            $('#suggestions_'+tipologia_id+'_'+indice).fadeIn(); // Show the suggestions box
            $('#suggestions_'+tipologia_id+'_'+indice).html(data); // Fill the suggestions box
        });
    }
}

$(document).ready(function()
{
    resetDropdownSearchBehavior();
});

function resetDropdownSearchBehavior(){
    // Safely inject CSS3 and give the search results a shadow
    var cssObj = { 'box-shadow' : '#888 5px 10px 10px', // Added when CSS3 is standard
            '-webkit-box-shadow' : '#888 5px 10px 10px', // Safari
            '-moz-box-shadow' : '#888 5px 10px 10px'}; // Firefox 3.5+
    $('div[id^="suggestions_"]').css(cssObj);

    // Fade out the suggestions box when not active
    $('html').click(function() {
        $('div[id^="suggestions_"]').fadeOut();
    });

    $('div[id^="suggestions_"]').click(function(event){
        event.stopPropagation();
    });
}

//centering popup : for jquery ui versions previous than 1.10
function centerPopup(){
    //request data for centering
    /*var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $(".ui-dialog").height();
    var popupWidth = $(".ui-dialog").width();
    //centering
    $(".ui-dialog").css({
            "position": "relative",
            "top": windowHeight/2-popupHeight/2,
            "left": windowWidth/2-popupWidth/2
    });*/
    //only need force for IE6

    /*$("#backgroundPopup").css({
            "height": windowHeight
    });*/
}

function initSalvarProduto(tipologia_id, index){
    $("#DecorProduto_nome").val($("#DecorTipologiaHasProduto_"+tipologia_id+"_"+index+"_produto_nome").val());
    $("#DecorProduto_descricao").val("");
    $("#DecorProduto_fornecedor_id").val("");
    $("#DecorProduto_tipologia_id").val(tipologia_id);
    $("#DecorProduto_index_produto").val(index);

    $.get(baseUrl + '/decorTipoTipologia/getTipoDeTipologia/' + tipologia_id,
    function(data){
        $("#DecorProduto_tipo_tipologia_id_visivel").val(data);
        $("#DecorProduto_tipo_tipologia_id").val(data);
        $("select.chzn-select").trigger("liszt:updated");
    });
}

//Converte 10.000,50 para 10000.50
function string2float(string) {
    string = string.replace(/\./g,"");
    string = string.replace(",",".");
    string = parseFloat(string);

    return string;
}

