/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function m_hover(id) {
    // alert(id);
    var produto = $('#produto_busca_' + id);
    produto.css({'background-color': '#bce8f1'});
    produto.css({'font-weight': 'bold'});
    produto.css({'cursor': 'pointer'});
    produto.css({'border': 'solid 1px #3a87ad'});
    produto.css({'border-left': 'none'});
    produto.css({'border-right': 'none'});
}
function m_hout(id) {
    // alert(id);
    var produto = $('#produto_busca_' + id);
    produto.css({'font-weight': ''});
    produto.css({'border': 'none'});
    produto.css({'text-style': ''});    
    produto.css({'background-color': '#fff'});
}
function renderizaDivProduto(id) {
    //modificar busca para o método get do jQuery
    var dados;

    $.post("/decorProjeto/getDados/" + id,
            {query: "" + id + ""
            }
    , function(data) { // Do an AJAX call        
        dados = data;
        dados = dados.split(';')
        var div = $("#produtoEdit");
        var nome = '';
        var foto = '';
        var qtd = 0;
        var fornecedor = '';
        for (var i = 0; i < dados.length; i++) {
            var dado = dados[i].split(':');
            if (dado[0] == id) {
                nome = dado[1];
                qtd = dado[2];
                foto = dado[3];
                fornecedor = dado[4];
            }
        }
        div.hide();
        div.html(
                '<fieldset>\n\
        <legend>Atualizar Produto</legend>\n\
                    <div class="form-group col-lg-2">\n\
                            <img src="/public/fotos_produtos/' + foto + '" width="200px" heigth="200px">\n\
                    </div>\n\
                    <div style="clear: both"></div>\n\
                    <div class="form-group col-lg-8">\n\
                        <label for="DecorTipologiaHasProduto[produto_id]">Produto</label>\n\
                        <input class="form-control validate[required]" disabled="disabled" type="text" value="' + nome + '" name="produto[nome]" id="produto_nome">\n\
                    </div>\n\
                    <div style="clear: both"></div>\n\
                    <div class="form-group col-lg-8">\n\
                        <label for="DecorTipologiaHasProduto[fornecedor]">Fornecedor</label>\n\
                        <input class="form-control validate[required]" disabled="disabled" type="text" value="' + fornecedor + '" name="produto[fornecedor]" id="produto_fornecedor">\n\
                    </div>\n\
                    <div style="clear: both"></div>\n\
                    <div class="form-group col-lg-3">\n\
                        <label>Quantidade</label>\n\
                        <input class="numInteiro validate[required] form-control" style="width:100%;" type="text" value="' + qtd + '" name="produto[quantidade]" id="produto_quantidade">\n\
                    </div>\n\
                    <div style="clear: both"></div>\n\
                    <div class="button form-group col-lg-10">\n\
                        <div style="clear: both height:10px;"></div>\n\
                        <button class="btn btn-info" id="bt_atualizar" onclick="atualizarProduto(' + id + ')" name="yt0" type="button">Atualizar produto</button>\n\
                    </div>\n\
                    <div style="clear: both"></div>\n\
                    <div id="info"></div>\n\
            </fieldset>'
                ).fadeIn('slow');
    });

}

function add_produto(id_tipologia, id_ambiente, id_tipo_tipologia) {
    var div = $("#produtoEdit");
    div.hide();
    div.html(
            '<fieldset>\n\
        <legend>Adicionar Produto</legend>\n\
                <div class="form-group col-lg-8">\n\
                    <label>Produto</label>\n\
                    <input onkeyup="buscarProduto(this.value,' + id_tipologia + ',' + '0' + ',' + id_tipo_tipologia + ');"class="form-control validate[required]" type="text" value="" name="produto[nome]" id="produto_nome">\n\
                </div>\n\
                <div style="clear: both"></div>\n\
                <div id="suggestions_' + id_tipologia + '_0"></div>\n\
                <div id="foto_produto"></div>\n\
                <div style="clear: both"></div>\n\
                <div class="form-group col-lg-3">\n\
                    <label>Quantidade</label>\n\
                    <input class="numInteiro validate[required] form-control" style="width:100%;" type="text" value="" name="produto[quantidade]" id="produto_quantidade">\n\
                </div>\n\
                <div style="clear: both"></div>\n\
                <div class="form-group button col-lg-10">\n\
                    <div style="clear: both; height:10px;"></div>\n\
                    <button class="button btn btn-info" id="bt_criar" onclick="criarProduto()" name="yt0" type="button" value="Adicionar produto">Adicionar produto</button>\n\
                </div>\n\
            </fieldset>\n\
            <input name="create_produto_id" id="create_produto_id" type="hidden" value="" />\n\
            <input name="create_tipologia_id" id="create_tipologia_id" type="hidden" value="" />'

            ).fadeIn('slow');


}



function buscarProduto(inputString, tipologia_id, indice, tipo_tipologia_id) {
    var projeto_id = $("#DecorProjeto_id").val();    
    if (inputString.length == 0) {
        $('div[id^="suggestions_"]').fadeOut(); // Hide the suggestions box

    } else {
        $.post("/decorProduto/getProdutosDinamico4Arvore",
                {queryString: "" + inputString + "",
                    tipologia_id: "" + tipologia_id + "",
                    indice: "" + indice + "",
                    tipo_tipologia_id: "" + tipo_tipologia_id + "",
                    projeto_id: "" + projeto_id + ""
                }
        , function(data) { // Do an AJAX call
            //alert(produtosEscolhidos);
            $('#suggestions_' + tipologia_id + '_' + indice).fadeIn(); // Show the suggestions box
            $('#suggestions_' + tipologia_id + '_' + indice).html(data); // Fill the suggestions box
        });
    }
}

function criarProduto() {
    var nome = $("#produto_nome").val();
    var qtd = $("#produto_quantidade").val();
    var produto_id = $("#create_produto_id").val();
    var tipologia_id = $("#create_tipologia_id").val();
    var projeto_id = $("#projeto_id").val();
    
    if ($.isNumeric(produto_id)) {
        $.post("/decorTipologiaHasProduto/createJs/" + produto_id,
                {query: "" + '1' + "",
                    tipologia_id: "" + tipologia_id + "",
                    tipo_tipologia_id: "" + tipologia_id + "",
                    produto_id: "" + produto_id + "",
                    projeto_id: "" + projeto_id + "",
                    quantidade: "" + qtd + ""
                }
        , function(data) { // Do an AJAX call
            //alert(produtosEscolhidos);

            geraTree(projeto_id);
            $("#produtoEdit").html('');

        });
    }
    else {
        document.getElementById('message').innerHTML = "Pesquise um produto!";
        $('#btn_modal_open').click();
    }
}

function deletarProduto(id) {
    var produto_id = id;

    if (confirm('Tem certeza que deseja deletar este produto?')) {
        if ($.isNumeric(produto_id)) {
            $.post("/decorTipologiaHasProduto/deleteJs/" + produto_id,
                    {query: "" + '1' + "",
                        produto_id: "" + produto_id + ""
                    }
            , function(data) { // Do an AJAX call                                
                var projeto_id = $("#DecorProjeto_id").val();
                geraTree(projeto_id);
		var div = $("#produtoEdit").fadeOut('slow');
            });
        }
    }
}

function geraTree(id) {
    $.post("/decorProjeto/gerarArvore/" + id,
            {'query': "" + 'inputString' + ""
            }
    , function(data) { // Do an AJAX call  
        $('#tree').html('');
        $('#tree').append(data);
        $("#tree").treeview({
            collapsed: true,
            animated: "medium",
            control: "#sidetreecontrol",
            persist: "location"
        });
    });
}

function atualizarProduto(id) {
    var qtd = 0;

    qtd = $("#produto_quantidade").val();
    if (($.isNumeric(qtd)) && (qtd != 0)) {
        $.post("/decorTipologiaHasProduto/update/" + id,
                {DecorTipologiaHasProduto: "" + 'inputString' + "",
                    qtd: "" + qtd + ""
                }
        , function(data) { // Do an AJAX call                
            $("#info").html(data).fadeIn();
        });
    }
    else {
        document.getElementById('message').innerHTML = "Informe um valor válido!";
        $('#btn_modal_open').click();
    }

}
function carregarProduto(produto_id, tipologia_id, produto_nome) {

    $("#produto_nome").val(produto_nome);
    $("#create_tipologia_id").val(tipologia_id);
    $("#create_produto_id").val(produto_id);
    var foto = $("#foto_produto");
    $("#produto_quantidade").val(0);
    $("#produto_quantidade").focus();
    $('div[id^="suggestions_"]').fadeOut();

}
