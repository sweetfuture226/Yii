<div class="form">
    <p class="note"><?php echo Yii::t('smith', 'Os campos disciplina, nome e previsto são obrigatórios.'); ?> <?= CHtml::link(Yii::t('smith', 'Modelo de arquivo'), '../public/csv/modelo/modelo.csv'); ?></p>
    <div class="form-group col-lg-4">
        <?php echo CHtml::label(Yii::t('smith', 'Importar .csv'), 'Documento_file'); ?>
        <input type="file" name="Documento[file]" id="Documento_file">
    </div>
    <div style="clear: both"></div>
    <p class="note"><?php echo Yii::t('smith', 'Deseja gerar uma <span id="csvLDP" style="cursor: pointer; color: #667fa0;">lista de documentos baseada em um novo código</span>?') ?></p>
</div>

<div class="modal fade" id="modalCsvLdp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo Yii::t('smith', 'Nova Lista de Documentos'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <p>
                        <label for="codigoLDP" class="required">
                            <?php echo Yii::t('smith', 'Código'); ?> <span class="required">*</span>
                        </label>
                        <input size="60" maxlength="256" class="form-control" id="codigoLDP" type="text"/>
                    <div style="clear: both"></div>
                    <div id='sugestoes_documentos_codigoLDP'
                         style='display: none; background-color: #DDD; max-height: 200px; overflow-y: scroll'></div>
                    </p>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Gerar LDP', array('class' => 'btn btn-info submitForm', 'onclick' => 'sendCSV();')); ?>
            </div>
        </div>
    </div>
</div>