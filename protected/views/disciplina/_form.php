<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pro-disciplina-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

	<?php echo $form->errorSummary($model); ?>

        <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'codigo'); ?>
                <?php echo $form->textField($model,'codigo',array('size'=>60,'maxlength'=>64,'class'=>'form-control')); ?>
            </p>
        </div>
        <div style="clear: both"></div>
        <div class="buttons">
            <div style="float: right; margin-bottom: 15px ">
               <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t("smith",'Salvar') : Yii::t("smith",'Atualizar'), array('class'=>'btn btn-info submitForm')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
<!-- form -->