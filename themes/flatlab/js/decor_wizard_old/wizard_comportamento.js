jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});
$(document).ready(function(){
    // Smart Wizard         
    $('#wizard').smartWizard({
            selected: passo_atual,  // Selected Step, 0 = first step   
            keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
            enableAllSteps: false,  // Enable/Disable all steps on first load
            transitionEffect: 'fade', // Effect on navigation, none/fade/slide/slideleft
            contentURL:null, // specifying content url enables ajax content loading
            contentCache:true, // cache step contents, if false content is fetched always from ajax url
            cycleSteps: false, // cycle step navigation
            enableFinishButton: (passo_atual == 3), // makes finish button enabled always
            errorSteps:[],    // array of step numbers to highlighting as error steps
            labelNext:'Próximo passo', // label for Next button
            labelPrevious:'Passo anterior', // label for Previous button
            labelFinish:'Concluir projeto',  // label for Finish button        
            // Events
            onLeaveStep: onLeave, // triggers when leaving a step
            onShowStep: onShow,  // triggers when showing a step
            onFinish: onFinishSteps  // triggers when Finish button is clicked
     });
     
     for(var i = 1 ; i < passo_atual+1; i++){
        var passo = $("#step"+i);
        passo.attr("isDone","1");
        passo.removeClass("disabled");
        passo.addClass("done");
    }
    $(".buttonPrevious").addClass("button");
    $(".buttonFinish").after("<a href='#' class='button buttonRascunho' id='buttonRascunho'>Salvar Rascunho</a>");

    function salvarForm(step_num){
        if(step_num == 1){
            //var valid = $("#decor-projeto-form").validationEngine('validate');
            var form = $( "#decor-projeto-form" );
            form.validate({
                rules: {
                    // tela de criacao de projeto
                    "DecorProjeto[nome]": "required",
                    "DecorProjeto[data_criacao]": "required",
                    "DecorProjeto[segmento_id]": "required",
                }
            });
            var valid = form.valid();
            if (valid){
                if($("#step2").attr("isdone")==0){
                    valid = criarProjeto();
                    if(!valid){
                        setError(1,true);
                    } else {
                        setError(1,false);
                    }
                }
                else{
                    valid = atualizarProjeto();
                    if(!valid){
                        setError(1,true);
                    } else {
                        setError(1,false);
                    }
                }
            } else {
                setError(1,true);
            }
        } else if(step_num == 2){
            //var valid = $("#decor-ambiente-form").validationEngine('validate');
            var form = $( "#decor-ambiente-form" );
            form.validate(/*{
                rules: {
                    "DecorProjeto[nome]": "required",
                    "DecorProjeto[data_criacao]": "required",
                    "DecorProjeto[segmento_id]": "required",
                }
            }*/);
            var valid = form.valid();
            if (valid){
                valid = salvarAmbientes();
                if(!valid){
                    setError(2,true);
                } else {
                    setError(2,false);
                }
            } else {
                setError(2,true);
            }
        } else if(step_num == 3){
//            var valid = $("#decor-tipologia-form").validationEngine('validate');
            var form = $( "#decor-tipologia-form" );
            form.validate(/*{
                rules: {
                    "DecorProjeto[nome]": "required",
                    "DecorProjeto[data_criacao]": "required",
                    "DecorProjeto[segmento_id]": "required",
                }
            }*/);
            var valid = form.valid();
            if (valid){
                valid = salvarTipologias();
                if(!valid){
                    setError(3,true);
                } else {
                    setError(3,false);
                }
            } else {
                setError(3,true);
            }
        } else if(step_num == 4){
            //var valid = $("#decor-produto-form").validationEngine('validate');
            var form = $( "#decor-produto-form" );
            form.validate({
                rules: {
                    "DecorProduto[nome]": "required",
                    "DecorProduto[tipo_tipologia_id]": "required",
                    "DecorProduto[fornecedor_id]": "required",
                }
            });
            var valid = form.valid();
            if (valid){
                valid = salvarProdutos();
                if(!valid){
                    setError(4,true);
                } else {
                    setError(4,false);
                }
            } else {
                setError(4,true);
            }
        }
        return valid;
    }
    //Salvar Rascunho
    $(".buttonRascunho").click(function(){
        var step_num = $(".selected").attr('rel');
        var valid = salvarForm(step_num);
        if(!valid){
            var confirmar = confirm('Não foi possível salvar o passo atual. Deseja continuar assim mesmo?');
        }
        if(confirmar || valid){
            $("#decor-controle-form").submit();
        }
    });

    function onLeave(obj){
        var step_num= obj.attr('rel');
        var valid = salvarForm(step_num);
        return valid;
    }

    function onShow(obj){
        var step_num= obj.attr('rel');
        if(step_num == 1){
            carregarColaboradores();
        } else if(step_num == 2){
            carregarAmbientes();
        } else if(step_num == 3){
            carregarTipologias();
        } else if(step_num == 4){
            carregarProdutos();
        }
        //$('#wizard').smartWizard('fixHeight');
    }

    function onFinishSteps(){
        var step_num = $(".selected").attr('rel');
        var valid = salvarForm(step_num);
        if(!valid){
            document.getElementById('message').innerHTML = "Não foi possível salvar o projeto. Verifique se há algum campo faltando.";
            $('#btn_modal_open').click();
        } else {
            $('#status').val('1');
            $("#decor-controle-form").submit();
        }
    }

    function showWizardMessage(myMessage){
        //var myMessage = 'Hello this is my message';
        // You can call this line wherever to show message inside the wizard
        $('#wizard').smartWizard('showMessage',myMessage);
    }

    function setError(stepnumber,status){
        $('#wizard').smartWizard('setError',{stepnum:stepnumber,iserror:status});
    }
});
