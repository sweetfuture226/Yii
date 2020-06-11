
<!--/**
* Created by PhpStorm.
 * User: joao
 * Date: 18/10/2017
 * Time: 11:20
 */-->
<!-- MODAL DE NOVA JUSTIFICATIVA TECNICA -->
<div class="modal " id="modal-nova-justificativa-tecnica" abindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Cadastrar nova Justificativa Técnica') ?></h4>
            </div>
            <div class="modal-body">

                <input type="hidden" id="row_questao_tecnica">
                <input type="hidden" id="column_questao_tecnica">

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
        saveTecnica();
    });

    function saveTecnica() {
        var row = $("#row_questao_tecnica").val();
        var column = $("#column_questao_tecnica").val();
        var obj = $('#nova-justificativa').val();
        $.ajax({
            method: "POST",
            url: baseUrl + '/QuestaoTecnica/CreateQuestaoTecnica',
            data: {
                tipo: obj,

            }
        }).done(function (retorno) {
            if (retorno == "success") {
                var nRow = $("#questao-tecnica tbody tr:eq(" + row + ")")[0];
                $('#questao-tecnica').append(nRow);
                $('#modal-nova-justificativa-tecnica').modal('hide');
                $('#modal-questao-tecnica').modal('toggle');
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Nova justificativa técnica inserida.') ?>", "success");

                location.reload();

            }
            else
                swal("Error!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar inserir a nova justificativa') ?>", "error");
        });
    }


</script>


