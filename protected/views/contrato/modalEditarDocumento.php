<!-- MODAL DE EDITAR DOCUMENTO -->
<div class="modal fade" id="editDocumento" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close">×</button>
                <h4 class="modal-title"><?= Yii::t('smith', 'Editar Documento'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <p>
                        <label for="edit_doc_nome" class="required">
                            <?php echo Yii::t('smith', 'Nome'); ?> <span class="required">*</span>
                        </label>
                        <input size="60" maxlength="256" class="form-control" name="edit_doc_nome" id="edit_doc_nome"
                               type="text"/>
                    </p>
                </div>
                <div class="form-group col-lg-6">
                    <p>
                        <label for="edit_doc_previsto">
                            <?php echo Yii::t('smith', 'Tempo Previsto'); ?> <span class="required">*</span>
                        </label>
                        <input size="9" class="form-control previsto" id="edit_doc_previsto" name="edit_doc_previsto"
                               type="text"/>
                    </p>
                </div>
                <div class="form-group col-lg-6">
                    <p>
                        <label for="edit_doc_fk_disciplina" class="required">
                            <?php echo Yii::t('smith', 'Disciplina'); ?> <span class="required">*</span>
                        </label>
                        <?php $fk_empresa = MetodosGerais::getEmpresaId();
                        echo CHtml::dropdownlist('edit_doc_fk_disciplina', "fk_disciplina", CHtml::listData(Disciplina::model()->findAll(array("condition" => "fk_empresa=" . $fk_empresa)), 'id', 'codigo'), array('empty' => Yii::t("smith", 'Selecione'), 'class' => 'chzn-select disciplinaDinamica')); ?>
                    </p>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Salvar', array('class' => 'btn btn-info', 'onclick' => 'add_documento("edit");')); ?>
            </div>
        </div>
    </div>
</div>