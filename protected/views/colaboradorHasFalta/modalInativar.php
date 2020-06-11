

<a id="btn_modal_inativar_pessoa" class="invisible" data-toggle="modal" href="#modal_inativar_pessoa">Dialog</a>
<div class="modal fade" id="modal_inativar_pessoa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=Yii::t("smith", 'Inativar colaborador')?></h4>
            </div>
            <div class="modal-body">

                <?=Yii::t("smith", 'Deseja realmente deixar este colaborador inativo?')?>

            </div>
            <div class="modal-footer">
                <button id ="btn_fechar_modal_inativar_pessoa" data-dismiss="modal" class="btn btn-default" type="button"><?=Yii::t("smith", 'Cancelar')?></button>
                <button id = "btn_confirmar_modal_inativar_pessoa"class="btn btn-success" type="button"><?=Yii::t("smith", 'Confirmar')?></button>
                <input type='hidden' id='id_pessoa' value="">
            </div>
        </div>
    </div>
</div>

<script>

    $('#colaborador-has-falta-grid a.inativar').live('click',function() {
        $('#id_pessoa').val($(this).attr('href'));
        $('#btn_modal_inativar_pessoa').click();
        return false;
    });

    $("#btn_confirmar_modal_inativar_pessoa").click(function(){
        var id_pessoa = $("#id_pessoa").val();
        $.ajax({
            type: 'POST',
            data: {pessoa: id_pessoa },
            url: baseUrl + '/colaborador/inativar',
            success: function(data){
                $("#btn_fechar_modal_inativar_pessoa").click();
                $.fn.yiiGridView.update('colaborador-has-falta-grid');
            },

        });

    });
</script>