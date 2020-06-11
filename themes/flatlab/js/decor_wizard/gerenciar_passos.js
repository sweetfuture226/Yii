jQuery.fn.extend({
    exists: function() {
        return this.length > 0;
    }
});

function ifSelectNotEmpty(field, rules, i, options) {
    if ($(field).find("option").length > 0 &&
            $(field).find("option:selected").length == 0) {
        // this allows the use of i18 for the error msgs
        return "* Campo obrigatório";
    }
}


function concluir() {
    var valid = false;
    $.ajax({
        url: baseUrl + "/usuario/concluir",
        type: 'POST',
        async: false,
        data: $("#politica-form").serialize(),
        success: function(data) {
            $('#sucessoWizard').modal('show');
            valid = true;
            
        },
        error: function() {
            document.getElementById('message').innerHTML = "Wizard não pôde ser concluído.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}



function salvarEquipes() {
    var valid = false;
    
        $.ajax({
            url: baseUrl + "/equipe/createAjax",

            type: 'POST',
            async: false,
            data: $("#equipe-form").serialize(),
            success: function(data) {
                if(data == "Sucesso"){
                    valid = true;
                }
                else{
                    document.getElementById('message').innerHTML = "É necessário cadastrar equipes.";
                    $('#btn_modal_open').click();
                    valid = false;
                }

            },
            error: function() {
                document.getElementById('message').innerHTML = "Equipes não puderam ser salvos.";
                $('#btn_modal_open').click();
                valid = false;
            }
        });
    return valid;
}

function salvarColaboradores() {
    var valid = false;
    $.ajax({
        url: baseUrl + "/colaborador/createAjax",
        type: 'POST',
        async: false,
        data: $("#pro-pessoa-form").serialize(),
        success: function(data) {
            $('#sucessoWizard').modal('show');
            valid = true;
        },
        error: function() {
            document.getElementById('message').innerHTML = "Wizard não pôde ser concluído.";
            $('#btn_modal_open').click();
            valid = false;
        }
        
    });
    return valid;
}

function salvarPS() {
    var valid = false;
    $.ajax({
        url: baseUrl + "/programaPermitido/createAjax",
        type: 'POST',
        async: false,
        data: $("#p-s-form").serialize(),
        success: function(data) {
            
            if(data == "Sucesso"){
                valid = true;
                
            }
            else{
                document.getElementById('message').innerHTML = "É necessário cadastrar os programas ou sites permitidos!";
                $('#btn_modal_open').click();
                valid = false;
            }
            
        },
        error: function() {
            document.getElementById('message').innerHTML = "Programas e Sites não puderam ser salvos.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function salvarParametros() {
    var valid = false;
    $.ajax({
        url: baseUrl + "/parametros/createAjax",
        type: 'POST',
        async: false,
        data: $("#parametros-form").serialize(),
        success: function(data) {
            
            if(data == "Sucesso"){
                valid = true;
            }
            else{
                document.getElementById('message').innerHTML = "Parâmetros não puderam ser salvos, complete todos campos obrigatórios.";
                $('#btn_modal_open').click();
                valid = false;
            }
            
            
        },
        error: function() {
            document.getElementById('message').innerHTML = "Parâmetros não puderam ser salvos.";
            $('#btn_modal_open').click();
            valid = false;
        }
         
    });
    return valid;
}

function baixarInstalador() {
    var valid = false;
    $.ajax({
        url: baseUrl + "/parametros/baixarInstalador",
        //type: 'POST',
        async: false,
        //data: $("#parametros-form").serialize(),
        success: function(data) {
                if(data == "Sucesso"){
                    valid = true;
                }
            },
       
    });
    return valid;
}

function salvarPasso(passo) {
    //var valid = false;
    $.ajax({
        url: baseUrl + "/usuario/salvarPasso",
        type: 'POST',
        async: false,
        dataType : JSON,
        data: {passo: passo},
        success: function(data) {
//                if(data == "Sucesso"){
//                    valid = true;
//                }
            },
       
    });
    //return valid;
}

function salvarSenha() {
    var valid = false;
    var password = $('#password').val();
    var password_again = $('#password_again').val();
    if (password == password_again){
        
        $.ajax({
            url: baseUrl + "/usuario/alterarSenha",
            type: 'POST',
            async: false,
            dataType:"json",
            data: $("#profile-form").serialize(),
            success: function(data) {
                if(data.resposta == "sucesso"){
                    valid = true;
                }
                else{
                    valid = false;
                    $("#erro_senha").text(data.password);
                }

            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                //console.log(err.Message);
                //alert(err.Message);
            }
        });
    }
    else{
        document.getElementById('message').innerHTML = "Por favor, verifique se a senha foi digitada corretamente.";
        $('#btn_modal_open').click();
        valid = false;
    }
    return valid;
}

function carregarEquipes() {
   
    $.ajax({
        url: baseUrl + "/equipe/carregarEquipesAjax",
        type: 'POST',
        async: false,
        data: $("#pro-pessoa-form").serialize(),
        success: function(data) {
            $("#accordion").html(data);
           
        },
        error: function() {
            document.getElementById('message').innerHTML = "Erro ao carregar equipes. Verifique se você está logado.";
            $('#btn_modal_open').click();
        }
    });
}





