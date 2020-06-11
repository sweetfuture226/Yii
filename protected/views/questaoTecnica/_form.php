
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'questao-tecnica-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>

	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>

	<?php echo $form->errorSummary($model); ?>
        <div class="form-group  col-lg-4">
	    <p>
            <?php echo $form->labelEx($model,'id'); ?>
            <?php echo $form->textField($model,'id',array('class'=>'form-control')); ?>
	    </p>
        </div>

        <div class="form-group  col-lg-4">
	    <p>
            <?php echo $form->labelEx($model,'tipo'); ?>
            <?php echo $form->textField($model,'tipo',array('class'=>'form-control', 'size'=>60,'maxlength'=>150)); ?>
	    </p>
        </div>

        <div class="buttons">    
            <div style="float: right; ">
               <?php echo CHtml::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', array('class'=>'btn btn-info submitForm')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>


