jQuery.fn.extend({
    exists: function() {
        return this.length > 0;
    }
});


function atualizarCss(indice, tipologia_id) {

div = $('.cotacao_item');
div.removeClass('rowElem');
div.removeClass('noborder');
div.addClass('form-group col-lg-4');
    for (j=0;j<=indice;j++){
        i=j.toString();
        rt = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_rt_fechada');
        pz = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_prazo_previsto_fornecedor');
        pt = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_preco_total');
        pef = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_preco_enviado_fornecedor')
        qtd = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_quantidade');
        nome = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_nome')
        inf = $('#DecorTipologiaHasProduto_' + i + '_' + tipologia_id + '_informacoes_adicionais')
        rt.addClass('form-control');
        pz.addClass('form-control');
        pt.addClass('form-control');
        pef.addClass('form-control');
        qtd.addClass('form-control');
        nome.addClass('form-control');
        //inf.addClass('form-control')
    }
    
}



$(document).ready(function() {
    // Smart Wizard         
    $('#wizard').smartWizard({
        selected: passo_atual, // Selected Step, 0 = first step   
        keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
        enableAllSteps: false, // Enable/Disable all steps on first load
        transitionEffect: 'fade', // Effect on navigation, none/fade/slide/slideleft
        contentURL: null, // specifying content url enables ajax content loading
        contentCache: true, // cache step contents, if false content is fetched always from ajax url
        cycleSteps: false, // cycle step navigation
        enableFinishButton: (passo_atual == (total_passos - 1)), // makes finish button enabled always
        errorSteps: [], // array of step numbers to highlighting as error steps
        labelNext: 'Próximo item', // label for Next button
        labelPrevious: 'Item anterior', // label for Previous button
        labelFinish: 'Concluir cotação', // label for Finish button        
        // Events
        onLeaveStep: onLeave, // triggers when leaving a step
        onShowStep: onShow, // triggers when showing a step
        onFinish: onFinishSteps  // triggers when Finish button is clicked
    });

    $(".buttonPrevious").addClass("button");
    $(".buttonFinish").after("<a href='#' class='button buttonRascunho' id='buttonRascunho'>Salvar rascunho</a>");

    function salvarForm(step_num) {
        //var valid = $("#decor-tipologia-" + step_num + "-form").validationEngine('validate');
        var valid = true;
        if (valid) {
            valid = salvarTipologia(step_num);
        }
        return valid;
    }

    function salvarFormCotacao(step_num) {
        //var valid = $("#decor-tipologia-" + step_num + "-form").validationEngine('validate');
        var valid = true;
        if (valid) {
            $("#decor-tipologia-" + step_num + "-form").attr('action', baseUrl + '/decorCotacao/salvarCotacao');
            $("#decor-tipologia-" + step_num + "-form").attr('target', 'upload_target');
            $("#decor-tipologia-" + step_num + "-form").submit();
        }
        return valid;
    }

    function salvarFormCotacaoAndLeave(step_num) {
        //var valid = $("#decor-tipologia-" + step_num + "-form").validationEngine('validate');
        var valid = true;
        if (valid) {
            $("#decor-tipologia-" + step_num + "-form").attr('action', baseUrl + '/decorCotacao/salvarCotacao');
            $("#decor-tipologia-" + step_num + "-form").submit();
        }
        return valid;
    }

    function salvarFormCotacaoAndFinish(step_num) {
        //var valid = $("#decor-tipologia-" + step_num + "-form").validationEngine('validate');
        var valid = true;
        if (valid) {
            $("#decor-tipologia-" + step_num + "-form").attr('action', baseUrl + '/decorCotacao/salvarCotacao');
            $('#DecorCotacao_status').val('1');
            $("#decor-tipologia-" + step_num + "-form #DecorCotacao_wizard_passo").val('1');
            $("#decor-tipologia-" + step_num + "-form").submit();
        }
        return valid;
    }

    //Salvar Rascunho
    $(".buttonRascunho").click(function() {
        var step_num = $(".selected").attr('rel');
        if (step_num != total_passos) {
            var valid = salvarForm(step_num);
            if (!valid) {
                var confirmar = confirm('Não foi possível salvar o passo atual. Deseja continuar assim mesmo?');
            }
            if (confirmar || valid) {
                $("#decor-controle-form").submit();
            }
        } else {
            var valid = salvarFormCotacaoAndLeave(step_num);
        }
    });

    function onShow(obj) {
        var step_num = obj.attr('rel');
        var valid = salvarPassoAtual(step_num);
        return valid;
    }

    function onLeave(obj) {
        var step_num = $(".selected").attr('rel');
        if (step_num != total_passos) {
            var valid = salvarForm(step_num);
        } else {
            var valid = salvarFormCotacao(step_num);
        }
        return valid;
    }

    function onFinishSteps(obj) {
        var step_num = $(".selected").attr('rel');
        var valid = salvarFormCotacaoAndFinish(step_num);
        if (!valid) {
            document.getElementById('message').innerHTML = "Não foi possível salvar.";
            $('#btn_modal_open').click();
        }
        return valid;
    }

    function showWizardMessage(myMessage) {
        // You can call this line wherever to show message inside the wizard
        $('#wizard').smartWizard('showMessage', myMessage);
    }

    function setError(stepnumber, status) {
        $('#wizard').smartWizard('setError', {stepnum: stepnumber, iserror: status});
    }
});