
<!--/**
* Created by PhpStorm.
 * User: joao
 * Date: 18/10/2017
 * Time: 11:20
 */-->
<!-- MODAL DE NOVA JUSTIFICATIVA TECNICA -->
<div class="modal " id="modal-nova-justificativa-ausencia" abindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Cadastrar nova Justificativa') ?></h4>
            </div>
            <div class="modal-body">

                <input type="hidden" id="row_justificativa_ausencia">
                <input type="hidden" id="column_justificativa_ausencia">

                <p class="titulo-info"></p>
                <p>
                    <?php
                       echo CHtml::label(Yii::t("smith", 'Justificativa'), 'Selecione o tipo');
                    ?>
                </p>
                <p align="right">

                    <?= CHtml::textField('nova-justificativa', '', array('class' => 'form-control')); ?>


                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel"
                        data-dismiss="modal"><?= Yii::t('smith', 'Fechar') ?></button>
                <button type="button"
                        class="btn btn-primary saveJustificativa"><?= Yii::t('smith', 'Salvar') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.saveJustificativa').on('click', function () {
        saveJustificativaAusencia();
    });

    function saveJustificativaAusencia() {
        var row = $("#row_justificativa_ausencia_tecnica").val();
        var column = $("#column_justificativa_ausencia").val();
        var obj = $('#nova-justificativa').val();
        $.ajax({
            method: "POST",
            url: baseUrl + '/JustificativaAusencia/CreateJustificativaAusencia',
            data: {
                tipo: obj,

            }
        }).done(function (retorno) {
            if (retorno == "success") {
                var nRow = $("#justificativa-ausencia tbody tr:eq(" + row + ")")[0];
                $('#justificativa-ausencia').append(nRow);
                $('#modal-nova-justificativa-ausencia').modal('hide');
                $('#modal-justificativa-ausencia').modal('toggle');
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Nova justificativa inserida.') ?>", "success");

                location.reload();

            }
            else
                swal("Error!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar inserir a nova justificativa') ?>", "error");
        });
    }


</script>


