<form id="importarPlanilha" action="<?= Yii::app()->createUrl('AtividadeExterna/importarPlanilha') ?>" method="post">
    <div class="modal fade" id="modal_planilha_atividade_externa" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?= Yii::t("smith", 'Importação de atividades externas') ?></h4>
                </div>

                <div class="modal-body">

                    <p class="note"><?php echo CHtml::link(Yii::t("wizard", 'Clique aqui para baixar o modelo de planilha para importação de atividades externas.'), array('atividadeExterna/GetPlanilha'), array('id' => 'getPlanilha')); ?></p>

                    <fieldset>
                        <legend>Importar planilha preenchida</legend>
                        <div class="form-group col-lg-12">
                            <?php echo CHtml::hiddenField('nameFile', '', array('id' => 'file')); ?>
                            <?php $this->widget('ext.EAjaxUpload.EAjaxUpload', array('id' => 'planilhaPrametrizacao',
                                'config' => array(
                                    'action' => Yii::app()->createUrl('colaborador/upload'),
                                    'allowedExtensions' => array("xlsx"),
                                    'sizeLimit' => 10 * 1024 * 1024,
                                    'minSizeLimit' => 1,
                                    'onComplete' => "js:function(id, fileName, responseJSON){"
                                        . " $('#file').val(responseJSON.filename);"
                                        . "}",
                                )
                            )); ?>
                        </div>
                    </fieldset>
                    <div class="notification-import-planilha" style="float: left; display: none">
                        <div class="alert alert-block alert-warning fade in" style="margin-bottom: 0px !important">
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="icon-remove"></i>
                            </button>
                            <strong><i class="icon-info-sign"></i> Selecione um contrato para vincular a atividade
                                externa.
                        </div>
                    </div>
                    <?php echo CHtml::hiddenField('fk_empresa', MetodosGerais::getEmpresaId()); ?>
                    <div style="clear: both"></div>
                </div>

                <div class="modal-footer">
                    <div class="buttons">
                        <div style="float: right; ">
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
    $(document).on('click', '#planilha_atividade_externa', function () {
        $('#modal_planilha_atividade_externa').modal();
    });
    $(window).load(function () {
        document.getElementById("getPlanilha").setAttribute("href", baseUrl + '/atividadeExterna/GetPlanilha/');
    });
</script>
