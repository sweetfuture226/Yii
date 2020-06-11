

<a id="btn_modal_ferias_pessoa" class="invisible" data-toggle="modal" href="#modal_ferias_pessoa">Dialog</a>
<div class="modal fade" id="modal_ferias_pessoa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=Yii::t("smith", 'Cadastrar férias/afastamentos do colaborador')?></h4>
            </div>
            <div class="modal-body">

                <div class="form-group  col-lg-6">
                    <label for="feriasInicio"><?php echo Yii::t("smith", 'Início das férias/afastamento') ?></label>
                    <?php echo CHtml::textField('feriasInicio', '', array('class' => 'date form-control ')); ?>

                </div>

                <div class="form-group  col-lg-6">
                    <label for="feriasFim"><?php echo Yii::t("smith", 'Retorno das férias/afastamento') ?></label>
                    <?php echo CHtml::textField('feriasFim', '', array('class' => 'date form-control ')); ?>

                </div>
                <div style="clear: both"></div>

            </div>
            <div class="modal-footer">
                <button id ="btn_fechar_modal_ferias_pessoa" data-dismiss="modal" class="btn btn-default" type="button"><?=Yii::t("smith", 'Cancelar')?></button>
                <button id = "btn_confirmar_modal_ferias_pessoa"class="btn btn-success" type="button"><?=Yii::t("smith", 'Salvar')?></button>
                <input type='hidden' id='id_pessoa' value="">
                <input type='hidden' id='date_ferias_inicio_pessoa' value="">
                <input type='hidden' id='date_ferias_fim_pessoa' value="">
            </div>
        </div>
    </div>
</div>

<script>

    $('#colaborador-has-falta-grid a.ferias').live('click',function() {
        $('#id_pessoa').val($(this).attr('href'));
        $('#btn_modal_ferias_pessoa').click();
        return false;
    });

    $("#btn_confirmar_modal_ferias_pessoa").click(function(){
        var id_pessoa = $("#id_pessoa").val();
        var feriasInicio = $("#feriasInicio").val();
        var feriasFim = $("#feriasFim").val();
        $.ajax({
            type: 'POST',
            data: {pessoa: id_pessoa,feriasInicio: feriasInicio,feriasFim: feriasFim },
            url: baseUrl +'/ColaboradorHasFerias/feriasAjax',
            success: function(data){
                $("#btn_fechar_modal_ferias_pessoa").click();
                $.fn.yiiGridView.update('colaborador-has-falta-grid');
            }

        });

    });
</script>