/**
 * Created by lucas on 12/12/2015.
 */

function salvar() {
    var form = [];
    var action = 'saveManual'
    $('.helpSmith').each(function () {
        form.push({
            "html": $(this).html(),
            "id": $(this).attr('id')
        });
    });

    $.ajax({
        type: 'POST',
        data: {'form': form},
        url: baseUrl + '/help/' + action,
        success: function () {
            document.getElementById('message').innerHTML = "Edição realizada com sucesso.";
            $('#btn_modal_open').click();
        }
    });
}
