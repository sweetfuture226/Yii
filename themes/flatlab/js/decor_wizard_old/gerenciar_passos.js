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

function criarProjeto(){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorProjeto/createAjax",
        type: 'POST',
        async: false,
        data: $("#decor-projeto-form").serialize(),
        dataType: 'json',
        success: function(data){
            if(data['id'] == null){
                document.getElementById('message').innerHTML = "Projeto não pôde ser inserido.";
                $('#btn_modal_open').click();
                valid = false;
            }
            else{
                var projeto_id = data['id'];
                $("#DecorProjeto_id").val(projeto_id);
                $("#projeto_id").val(projeto_id);
                $(".projeto_id").val(projeto_id);
                valid = true;
            }
        },
        error: function(){
            document.getElementById('message').innerHTML = "Projeto não pôde ser inserido.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function atualizarProjeto(){
    var valid = false;
    var projeto_id = $("#DecorProjeto_id").val();
    $.ajax({
        url: baseUrl + "/decorProjeto/atualizarAjax/"+projeto_id,
        type: 'POST',
        async: false,
        data: $("#decor-projeto-form").serialize(),
        success: function(){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Dados do projeto não puderam ser atualizados.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function carregarColaboradores(){
    $.ajax({
        url: baseUrl + "/decorProjeto/carregarColaboradores",
        type: 'POST',
        async: false,
        data: $("#decor-projeto-form").serialize(),
        success: function(data){
            $("#colaborador").html(data);
            $(".chzn-select").chosen();
        },
        error: function(){
            document.getElementById('message').innerHTML = "Erro ao carregar colaboradores. Verifique se você está logado.";
            $('#btn_modal_open').click();
        }
    });
}

function salvarAmbientes(){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorProjeto/salvarAmbientes",
        type: 'POST',
        async: false,
        data: $("#decor-ambiente-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Ambientes não puderam ser salvos.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function carregarAmbientes(){
    $.ajax({
        url: baseUrl + "/decorProjeto/carregarAmbientes",
        type: 'POST',
        async: false,
        data: $("#decor-projeto-form").serialize(),
        success: function(data){
            $("#ambiente").html(data);
            $(".chzn-select").chosen();
        },
        error: function(data){
            document.getElementById('message').innerHTML = "Erro ao carregar ambientes. Verifique se você está logado.";
            $('#btn_modal_open').click();
        }
    });
}

function salvarTipologias(){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorProjeto/salvarTipologias",
        type: 'POST',
        async: false,
        data: $("#decor-tipologia-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Tipologias não puderam ser salvas.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function carregarTipologias(){
    $.ajax({
        url: baseUrl + "/decorProjeto/carregarTipologias",
        type: 'POST',
        async: false,
        data: $("#decor-projeto-form").serialize(),
        success: function(data){
            $("#tipologia").html(data);
            $(".chzn-select").chosen();
        },
        error: function(data){
            document.getElementById('message').innerHTML = "Erro ao carregar tipologias. Verifique se você está logado.";
            $('#btn_modal_open').click();
        }
    });
}

function salvarProdutos(){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorProjeto/salvarProdutos",
        type: 'POST',
        async: false,
        data: $("#decor-produto-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Produtos não puderam ser salvos.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function carregarProdutos(){
    $.ajax({
        url: baseUrl + "/decorProjeto/carregarProdutos",
        type: 'POST',
        async: false,
        data: $("#decor-projeto-form").serialize(),
        success: function(data){
            $("#produto").html(data);
            $(".chzn-select").chosen();
        },
        error: function(data){
            document.getElementById('message').innerHTML = "Erro ao carregar produtos. Verifique se você está logado.";
            $('#btn_modal_open').click();
        }
    });
    resetDropdownSearchBehavior();
}
