function colocarMascara() {
    $('.previstoHM').timepicker({
        showMeridian: false
    });
}

$(document).ready(function () {
    $('.chzn-select').select2();
    $(".default").select2();
    $(".previsto").mask("99:99:99", {placeholder: " "});
    setFilterClass();
    setTranslateTable();
    $("html").niceScroll();
    $('.sidebar-menu-inner').niceScroll();


});

function setTranslateTable() {
    if ($('html').attr('lang') == "en") {
        $('.table').find('span.empty').each(function () {
            $(this).html('No matching records found');
        });

        var portugues = $('.row').find('div.dataTables_info').html().split(' ');
        var ingles = 'Showing ' + portugues[2] + ' to ' + portugues[4] + ' of ' + portugues[6] + ' entries';
        $('.row').find('div.dataTables_info').html(ingles);
    }
}
function setFilterClass() {
    $('.replace-inputs').find('select').select2();
}
function verificaDataRelatorioIndDia(data_relatorio, id_colaborador) {
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

 $(function () {
//     //===== loading =====//
     $(document).ajaxStart(function () {
         $('body').addClass("loading");
    });
     $(document).ajaxStop(function () {
         $('body').removeClass("loading");
        setFilterClass();
        loadDateWidget();
    });
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
//
     $(".cpf").mask("999.999.999-99", {placeholder: " "});
     $(".cep").mask("99999-999", {placeholder: " "});
     $(".cnpj").mask("99.999.999/9999-99", {placeholder: " "});
     $(".data").mask("99/99/9999", {placeholder: " "});
     $(".hora").mask("99:99", {placeholder: " "});
     $(".previsto").mask("99:99:99", {placeholder: " "});
     $(".previstoHHM").mask("99:99", {placeholder: "HH:MM"});
     $('.previstoHM').mask("99:99", {clearIfNotMatch: true});
     $(".pis").mask("999.999999.99-9", {placeholder: " "});

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
//
    $(".date").datepicker({
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2028',
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    });

    $(".dateMonth").datepicker({
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        minViewMode: "months",
        viewMode: "months",
        yearRange: '1940:2028',
        format: 'mm/yyyy'
    });

    $(".dateYear").datepicker({
        autoclose: true,
        changeYear: true,
        minViewMode: "years",
        viewMode: "years",
        yearRange: '1940:2028',
        format: 'yyyy'
    });
//
    $('.feriados').datepicker({
         format: "dd/mm/yyyy",
         multidate: true,
         daysOfWeekDisabled: "0,6",
         todayHighlight: true
     });
//
     $(".dateSearch").datepicker({
         autoSize: true,
         changeMonth: true,
         changeYear: true,
         yearRange: '1940:2028',
         format: 'yyyy-mm-dd',
         todayHighlight: true,
     });

 });

function time_to_minute(time) {
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

function verifyUserBlock(option) {
    $.ajax({
        method: "POST",
        url: baseUrl + '/colaborador/IsBlock',
        async: false,
    }).done(function (retorno) {
        $("#" + option).html("");
        $("#" + option).html(retorno);
    })
}


function verifyUserBlockAd(option) {
    $.ajax({
        method: "POST",
        url: baseUrl + '/colaborador/IsBlockAd',
        async: false,
    }).done(function (retorno) {
        $("select[name='" + option + "']").html("");
        $("select[name='" + option + "']").html("<option value> </option>");
        $("select[name='" + option + "']").append(retorno);
    })
}