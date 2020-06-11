<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-central-notificacao-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
)); ?>

<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>

<?php echo $form->errorSummary($model); ?>
<div class="form-group  col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'fk_acao'); ?>
        <?php echo $form->textField($model, 'fk_acao', array('class' => 'form-control')); ?>
    </p>
</div>

<div class="form-group  col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'fk_documento_sem_contrato'); ?>
        <?php echo $form->textField($model, 'fk_documento_sem_contrato', array('class' => 'form-control')); ?>
    </p>
</div>

<div class="form-group  col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'descricao'); ?>
        <?php echo $form->textField($model, 'descricao', array('class' => 'form-control', 'size' => 60, 'maxlength' => 255)); ?>
    </p>
</div>

<div class="form-group  col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'tipo'); ?>
        <?php echo $form->textField($model, 'tipo', array('class' => 'form-control')); ?>
    </p>
</div>

<div class="form-group  col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'fk_empresa'); ?>
        <?php echo $form->textField($model, 'fk_empresa', array('class' => 'form-control')); ?>
    </p>
</div>

<div class="buttons">
    <div style="float: right; ">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', array('class' => 'btn btn-info submitForm')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>


