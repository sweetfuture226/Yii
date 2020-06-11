// Formata um número em reais
function float2string(valor) {
    return accounting.formatMoney(valor, '', 2, '.', ',', "%s%v");
}

// ========  verifica se existe algum CEP descrito =======//
function checkCEP() {
    if ($('#Endereco_cep').val() == '') {
        document.getElementById('message').innerHTML = "Favor preencher o campo CEP corretamente.";
        $('#btn_modal_open').click();
    }
}

// ======= busca o logradouro, bairro, cidade e UF do site republicavirtual e joga nos campos respectivos
function getEndereco() {
    // Se o campo CEP não estiver vazio
    if ($.trim($("#Endereco_cep").val()) != "") {
        $.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep=" + $("#Endereco_cep").val(), function () {
            // o getScript dá um eval no script, então é só ler!
            //Se o resultado for igual a 1
            if (resultadoCEP["resultado"] == 1 || resultadoCEP["resultado"] == 2) {
                // troca o valor dos elementos
                if (resultadoCEP["tipo_logradouro"]) {
                    $("#Endereco_logradouro").val(unescape(resultadoCEP["tipo_logradouro"]) + " " + unescape(resultadoCEP["logradouro"]));
                } else {
                    $("#Endereco_logradouro").val(unescape(''));
                }
                $("#Endereco_bairro").val(unescape(resultadoCEP["bairro"]));
                $("#Endereco_cidade").val(unescape(resultadoCEP["cidade"]));
                $("#Endereco_estado").val(unescape(getEstado(resultadoCEP["uf"])));
            } else {
                if ($("#Endereco_cep").val() != ""){
                    document.getElementById('message').innerHTML = "Endereço não encontrado.";
                    $('#btn_modal_open').click();
                }
            }
        });
    }
}

$(document).ready(function () {
    setFilterClass();
    $(".cep").keypress(function (evt) {
        var keycode = evt.charCode || evt.keyCode;
        if (keycode == 13) { //Enter key's keycode
            return false;
        }
    });
});


// Adicionar class form-controll aos filtros da grid view
function setFilterClass() {
    $('.filters').find('input').addClass('form-control');
    $('.filters').find('select').addClass('form-control');
}

// ======== pega a UF e retorna o nome do estado respectivo
function getEstado(uf) {
    switch (uf) {
        case 'AC':
            return 'Acre';
        case 'AL':
            return 'Alagoas';
        case 'AP':
            return 'Amapá';
        case 'AM':
            return 'Amazonas';
        case 'BA':
            return 'Bahia';
        case 'CE':
            return 'Ceará';
        case 'DF':
            return 'Distrito Federal ';
        case 'ES':
            return 'Espírito Santo';
        case 'GO':
            return 'Goiás';
        case 'MA':
            return 'Maranhão';
        case 'MT':
            return 'Mato Grosso';
        case 'MS':
            return 'Mato Grosso do Sul';
        case 'MG':
            return 'Minas Gerais';
        case 'PA':
            return 'Pará';
        case 'PB':
            return 'Paraíba';
        case 'PR':
            return 'Paraná';
        case 'PE':
            return 'Pernambuco';
        case 'PI':
            return 'Piauí';
        case 'RJ':
            return 'Rio de Janeiro';
        case 'RN':
            return 'Rio Grande do Norte';
        case 'RS':
            return 'Rio Grande do Sul';
        case 'RO':
            return 'Rondônia';
        case 'RR':
            return 'Roraima';
        case 'SC':
            return 'Santa Catarina';
        case 'SP':
            return 'São Paulo';
        case 'SE':
            return 'Sergipe';
        case 'TO':
            return 'Tocantins';
        default:
            return '';
    }
}

function verificaDataRelatorioIndDia(data_relatorio, id_colaborador)
{
    var url = $('input.submitForm').data('url');
    var ret = true;

    $.ajax({
        url: url,
        type: 'POST',
        data: {data_relatorio: data_relatorio, id_colaborador: id_colaborador},
        async: false
    }).done(function (res) {
        if (!res)
            ret = false;
    });
    return ret;
}

function boxy_view() {
    $('.view').unbind("click");
    $('.view').boxy({title: 'Dados'});
}

$(function () {
    //===== loading =====//
    $("body").ajaxStart(function () {
        $(this).addClass("loading");
    });
    $("body").ajaxStop(function () {
        $(this).removeClass("loading");
        setFilterClass();
        loadDateWidget();
    });

    //===== Light box - boxy =====//
    $('.view').boxy({title: 'Dados'});
    $(document).keyup(function (event) {
        if ((Boxy.isModalVisible()) && (boxy != undefined) && (event.keyCode == 27)) {
            boxy.hide();
        }
    });

    //===== Information boxes =====//
    $(".hideit").click(function () {
        $(this).fadeOut(400);
    });

    //===== Wizard =====//
    $('.wizard').smartWizard({
        selected: 0, // Selected Step, 0 = first step
        keyNavigation: true, // Enable/Disable key navigation(left and right keys are used if enabled)
        enableAllSteps: false, // Enable/Disable all steps on first load
        transitionEffect: 'slideleft', // Effect on navigation, none/fade/slide/slideleft
        contentURL: null, // specifying content url enables ajax content loading
        contentCache: true, // cache step contents, if false content is fetched always from ajax url
        cycleSteps: false, // cycle step navigation
        enableFinishButton: false, // makes finish button enabled always
        errorSteps: [], // array of step numbers to highlighting as error steps
        labelNext: 'Next', // label for Next button
        labelPrevious: 'Previous', // label for Previous button
        labelFinish: 'Finish', // label for Finish button
        // Events
        onLeaveStep: null, // triggers when leaving a step
        onShowStep: null, // triggers when showing a step
        onFinish: null  // triggers when Finish button is clicked
    });

    //============= Chosen Select =====================//
    $(".chzn-select").chosen({no_results_text: "Não encontrado"});
    $(".chzn-select-deselect").chosen({allow_single_deselect: true});

    //===== Form elements masks =====//

    $(".telefone").focusout(function () {
        var phone, element;
        element = $(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if (phone.length > 10) {
            element.mask("(99) 99999-9999", {placeholder: " "});
        } else {
            element.mask("(00) 0000-00009", {placeholder: " "});
        }
    }).trigger('focusout');

    $(".cpf").mask("999.999.999-99", {placeholder: " "});
    $(".cep").mask("99999-999", {placeholder: " "});
    $(".cnpj").mask("99.999.999/9999-99", {placeholder: " "});
    $(".data").mask("99/99/9999", {placeholder: " "});
    $(".hora").mask("99:99", {placeholder: " "});
    $(".previsto").mask("99:99:99", {placeholder: " "});
    $(".previstoHHMM").mask("99:99", {placeholder: " "});
    $('.previstoHM').mask("99:99", {clearIfNotMatch: true});
    $(".pis").mask("999.999999.99-9", {placeholder: " "});
    $(".real").autoNumeric('init', {mDec: 5, aSep: '.', aDec: ',', aPad: 2, vMax: 9999999999999999999999999999999999999999999.99999});
    $(".dinheiro").autoNumeric('init', {mDec: 5, aSep: '.', aDec: ',', aPad: 2, vMax: 9999999999999999999999999999999999999999999.99999});

    $(".quantidade").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Digits Only").show().fadeOut("slow");
            return false;
        }
    });

    $(".numInteiro").maskMoney({
        symbol: '',
        showSymbol: true,
        thousands: '',
        decimal: ',',
        symbolStay: false,
        precision: 0
    });

    $(".valor").maskMoney({
        symbol: 'R$ ',
        showSymbol: true,
        thousands: '.',
        decimal: ',',
        symbolStay: false
    });

    $(".valorNeg").maskMoney({
        symbol: 'R$ ',
        showSymbol: true,
        thousands: '.',
        decimal: ',',
        symbolStay: false,
        allowNegative: true,
    });

    $(".percentagem").maskMoney({
        symbol: '',
        showSymbol: true,
        thousands: '.',
        decimal: ',',
        symbolStay: false,
        allowNegative: true
    });

    $(".date").datepicker({
        autoSize: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2028',
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    });

    $(".dateMonth").datepicker({
        autoSize: true,
        changeMonth: true,
        changeYear: true,
        minViewMode: "months",
        viewMode: "months",
        yearRange: '1940:2028',
        format: 'mm/yyyy'
    });

    $(".dateYear").datepicker({
        autoSize: true,
        changeYear: true,
        minViewMode: "years",
        viewMode: "years",
        yearRange: '1940:2028',
        format: 'yyyy'
    });

    $('.feriados').datepicker({
        format: "dd/mm/yyyy",
        multidate: true,
        daysOfWeekDisabled: "0,6",
        todayHighlight: true
    });

    $(".dateSearch").datepicker({
        autoSize: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2028',
        format: 'yyyy-mm-dd',
        todayHighlight: true,
    });

    //===== Form validation engine =====//
    $("form").submit(function () {
        $(".validate_select").each(function () {
            var id = $(this).attr("id");
            if (($(this).hasClass("validate[required]")) && ($(this).val() == "")) {
                $("#" + id + "_chzn").validationEngine("showPrompt", "* Campo obrigatótio", "error", "", true);
            } else {
                $("#" + id + "_chzn").validationEngine("hide");
            }
        });
    });

    //===== Tabs =====//
    $.fn.simpleTabs = function () {

        //Default Action
        $(this).find(".tab_content").hide(); //Hide all content
        $(this).find("ul.tabs li:first").addClass("activeTab").show(); //Activate first tab
        $(this).find(".tab_content:first").show(); //Show first tab content

        //On Click Event
        $("ul.tabs li").click(function () {
            $(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
            $(this).addClass("activeTab"); //Add "active" class to selected tab
            $(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            $(activeTab).show(); //Fade in the active content
            return false;
        });
    };//end function

    $("div[class^='widget']").simpleTabs(); //Run function on any div with class name of "Simple Tabs"

});

function mensagemAlerta(mensagem, tipo)
{
    if (tipo == 'warning')
    {
        $('.notifications #notificationGeral strong').html(mensagem);
        $('.notifications #notificationGeral').addClass('alert-warning in');
    }
}

function time_to_minute(time)
{
    var minutes = 0;
    var arr = time.split(":");
    minutes += parseInt(arr[0]) * 60;
    minutes += parseInt(arr[1]);
    return minutes;
}

function sec_to_time(value) {
    var times = new Array(3600, 60, 1);
    var time = '';
    var tmp;
    for (var i = 0; i < times.length; i++) {
        tmp = Math.floor(value / times[i]);
        if (tmp < 1) {
            tmp = '00';
        }
        else if (tmp < 10) {
            tmp = '0' + tmp;
        }
        time += tmp;
        if (i < 2) {
            time += ':';
        }
        value = value % times[i];
    }
    return time;
}

$(document).on('click', '.implantation', function (e) {
    e.preventDefault();
    $('#modal-implantation').modal('show');
    $('#modal-implantation .modal-footer').data('idnotificacao', $(this).data('id'));
});

$(document).on('click', '#modal-implantation .btn-default, #modal-implantation .viewer', function (e) {
    e.preventDefault();

    var url = $(this).closest('.modal-footer').data('url');
    var id = $(this).closest('.modal-footer').data('idnotificacao');

    $.ajax({
        url: url,
        type: 'POST',
        data: {id: id},
        async: false
    }).done(function (res) {
        if (res)
            $('#modal-implantation').modal('hide');
    });

    if ($(this).attr('href') != undefined)
        window.location = $(this).attr('href');
});

function resizeWindow() {
    $(window).resize(function () {
        $('#content').height($(window).height() - 46);
    });

    $(window).trigger('resize');
}

function loadDateWidget() {
    $(".date").datepicker({
        autoSize: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2028',
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    });
}
