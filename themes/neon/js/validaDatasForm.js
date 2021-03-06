function validaForm() {
    var valido = true;
    var inicio = $('#date_from').val();
    var fim = $('#date_to').val();
    $('#button').val('');
    if (!checkDateRange(inicio, fim)) {
        valido = false;
    }

    if (valido) {
        $('#log-atividade-form').submit();
        Loading.show();
    }
}

function checkDateRange(start, end) {
    var start2 = start.split("/");
    var end2 = end.split("/");
    var data = new Date();
    var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
    start2 = new Date(start2[2], start2[1] - 1, start2[0]);
    end2 = new Date(end2[2], end2[1] - 1, end2[0]);

    if (+end2 === +hoje) {
        document.getElementById('message').innerHTML = "A data final não pode ser de hoje, pois por questões de desempenho apresentamos a produtividade até dia anterior.";
        $('#btn_modal_open').click();
        return false;
    }

    if (end2 > hoje) {
        document.getElementById('message').innerHTML = "A data final não pode ser maior que hoje.";
        $('#btn_modal_open').click();
        return false;
    }

    if (isNaN(start2)) {
        document.getElementById('message').innerHTML = "A data de início não é válida, por favor insira uma data válida.";
        $('#btn_modal_open').click();
        return false;
    }

    if (isNaN(end2)) {
        document.getElementById('message').innerHTML = "A data de fim não é válida, por favor insira uma data válida.";
        $('#btn_modal_open').click();
        return false;
    }

    if (end2 < start2) {
        document.getElementById('message').innerHTML = "A data de início precisa ser antes da data de fim.";
        $('#btn_modal_open').click();
        return false;
    }

    return true;
}

function hideLoading() {
    Loading.hide();
}

function valida(tipo) {
    var inicio = $('#date_from').val();
    var fim = $('#date_to').val();
    if (!checkDateRange(inicio, fim)) {
        return false;
    }
    $('#button').val(tipo);
    setTimeout(hideLoading, 5000);
    $('#log-atividade-form').submit();
    Loading.show();
    return true; 
}
