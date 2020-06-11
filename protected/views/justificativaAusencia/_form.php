
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'justificativa-ausencia-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>

	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>

	<?php echo $form->errorSummary($model); ?>
        <div class="form-group  col-lg-4">
	    <p>
            <?php echo $form->labelEx($model,'tipo'); ?>
            <?php echo $form->textField($model,'tipo',array('class'=>'form-control', 'size'=>45,'maxlength'=>45)); ?>
	    </p>
        </div>

        <div class="buttons">    
            <div style="float: right; ">
               <?php echo CHtml::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', array('class'=>'btn btn-info submitForm')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>


