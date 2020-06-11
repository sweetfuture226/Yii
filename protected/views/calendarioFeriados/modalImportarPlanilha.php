<form id="importarPlanilha" action="<?= Yii::app()->createUrl('CalendarioFeriados/ImportCSV') ?>" method="post">
    <div class="modal fade" id="modal_planilha_calendario_feriados" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?= Yii::t("smith", 'Importação de calendário de feriados') ?></h4>
                </div>

                <div class="modal-body">
                    <p class="note"><?php echo CHtml::link(Yii::t("wizard", 'Clique aqui para baixar o modelo de planilha para importação de calendário de feriados.'), Yii::app()->baseUrl . '/public/csv/modelo/modelo_feriado.csv'); ?></p>

                    <fieldset>
                        <legend>Importar planilha preenchida</legend>
                        <div class="form-group col-lg-12">
                            <?php echo CHtml::hiddenField('nameFile', '', array('id' => 'file')); ?>
                            <?php $this->widget('ext.EAjaxUpload.EAjaxUpload', array('id' => 'planilhaPrametrizacao',
                                'config' => array(
                                    'action' => Yii::app()->createUrl('CalendarioFeriados/upload'),
                                    'allowedExtensions' => array("csv"),
                                    'sizeLimit' => 10 * 1024 * 1024,
                                    'minSizeLimit' => 1,
                                    'onComplete' => "js:function(id, fileName, responseJSON){"
                                        . " $('#file').val(responseJSON.filename);"
                                        . "}",
                                )
                            )); ?>
                        </div>
                    </fieldset>
                    <div style="clear: both"></div>
                </div>

                <div class="modal-footer">
                    <div class="buttons">
                        <div style="float: right; margin-bottom: 15px ">
                            <?php echo CHtml::button(Yii::t('smith', 'Salvar'), array('class' => 'btn btn-info submitForm', 'id' => 'submitPlanilha')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).on('click', '#submitPlanilha', function () {
        $("#importarPlanilha").submit();
    });

    $(document).on('click', '#planilha_calendario_feriados', function () {
        $('#modal_planilha_calendario_feriados').modal();
    });
</script>
