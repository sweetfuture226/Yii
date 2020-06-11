<!-- MODAL DE NOVO DOCUMENTO -->
<div class="modal fade" id="novoDocumento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('smith', 'Adicionar Documento'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-11">
                    <p>
                        <label for="Documento_nome" class="required">
                            <?php echo Yii::t('smith', 'Nome'); ?> <span class="required">*</span>
                        </label>
                        <input size="60" maxlength="256" class="form-control" name="Documento_nome" id="Documento_nome"
                               type="text"/>
                    <div style="clear: both"></div>
                    <div id='sugestoes_documentos_Documento_nome' style='background-color: #DDD'></div>
                    </p>
                </div>
                <i id="status_documento" class="entypo-block"
                   style="font-size: 1.5em; line-height: 80px; color: #ff6c60"></i>
                <div class="form-group col-lg-6">
                    <p>
                        <label for="Documento_previsto">
                            <?php echo Yii::t('smith', 'Tempo Previsto'); ?> <span class="required">*</span>
                        </label>
                        <input size="9" class="form-control previsto" id="Documento_previsto" name="Documento_previsto"
                               type="text"/>
                    </p>
                </div>
                <div class="form-group col-lg-6">
                    <p>
                        <label for="Documento_fk_disciplina" class="required">
                            <?php echo Yii::t('smith', 'Disciplina'); ?> <span class="required">*</span>
                        </label>
                        <?php $fk_empresa = MetodosGerais::getEmpresaId();
                        echo CHtml::dropdownlist('Documento_fk_disciplina', "fk_disciplina", CHtml::listData(Disciplina::model()->findAll(array("condition" => "fk_empresa=" . $fk_empresa)), 'id', 'codigo'), array('empty' => Yii::t("smith", 'Selecione'), 'class' => 'chzn-select disciplinaDinamica')); ?>
                    </p>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Salvar +', array('class' => 'btn btn-info submitForm docs_buttons', 'onclick' => 'add_documento("mais");')); ?>
                <?php echo CHtml::button('Salvar', array('class' => 'btn btn-info submitForm docs_buttons', 'onclick' => 'add_documento();')); ?>
            </div>
        </div>
    </div>
</div>