<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-atividade-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

<?php echo $form->errorSummary($model); ?>
    <div class="form-group col-lg-4"><p>
        <?php
            echo CHtml::label(Yii::t('smith', 'Empresa'), 'empresa');
            echo CHtml::dropdownlist('empresa', '', CHtml::listData(Empresa::model()->findAll(array("condition" => 'ativo = 1 AND id != 41', 'order' => 'nome')), 'nome', 'nome'), array('class' => 'chzn-select'));
        ?>
    </p></div>

    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
           <?php echo CHtml::submitButton(Yii::t('smith','Confirmar'), array('class'=>'btn btn-info submitForm')); ?>
        </div>
    </div>
<?php $this->endWidget(); ?>