/**
 * Created by VivaInovacao on 30/09/2015.
 */
var grid = $('.adv-table ').attr('id');
jQuery('.adv-table  a.deletar').live('click', function () {
    $('#btn_modal_deletar').click();
    $('#url_deletar').val($(this).attr('href'));
    return false;
});

jQuery("#btn_confirmar_modal_deletar").live('click', function () {
    $.ajax({
        type: 'POST',
        cache: false,
        url: $('#url_deletar').val(),
        success: function () {
            $("#btn_fechar_modal_deletar").click();
            $.fn.yiiGridView.update(grid);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.responseText);
        }
    });
});