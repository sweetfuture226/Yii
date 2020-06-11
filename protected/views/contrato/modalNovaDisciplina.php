<!-- MODAL DE NOVA DISCIPLINA -->
<div class="modal fade" id="novaDisciplina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('smith', 'Nova Disciplina'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-11">
                    <p>
                        <?php echo CHtml::label(Yii::t('smith', 'Código'), 'nome'); ?>
                        <?php echo CHtml::textField('disciplina_nome', '', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'id' => 'disciplina_nome')); ?>
                    </p>
                </div>
            </div>
            <i id="status_disciplina" class="icon-ban-circle" style="font-size: 1.5em; line-height: 40px; color: #ff6c60"></i>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Salvar', array('id' => 'submit_dis', 'class' => 'btn btn-info submitForm', 'onclick' => 'salvarDisciplina();', 'disabled' => true)); ?>
            </div>
        </div>
    </div>
</div>