<p><?=Yii::t('wizard','Defina os parâmetros para determinar a hora de inicio e fim de expediente e almoço da instituição. Estes dados vão interferir na contabilidade da produtividade')?></p>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'empresaHasParametro-form',
	'enableAjaxValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,

        ),
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>
	<fieldset>
    <legend><?=Yii::t('wizard','Horário de Expediente e Almoço')?></legend>
         <div class="form-group  col-lg-4">
            <p>
		<?php echo $form->labelEx($modelParametros,'horario_entrada'); ?>
		<?php echo $form->textField($modelParametros,'horario_entrada',array('size'=>60,'class' => 'form-control previstoHM','maxlength'=>255)); ?>
            </p>
	</div>
        <div class="form-group  col-lg-4">
            <p>
		<?php echo $form->labelEx($modelParametros,'horario_saida'); ?>
		<?php echo $form->textField($modelParametros,'horario_saida',array('size'=>60,'class' => 'form-control previstoHM','maxlength'=>255)); ?>
            </p>
	</div>

        <div style="clear: both"></div>

        <div class="form-group  col-lg-4">
            <p>
		<?php echo $form->labelEx($modelParametros,'almoco_inicio'); ?>
		<?php echo $form->textField($modelParametros,'almoco_inicio',array('size'=>60,'class' => 'form-control previstoHM','maxlength'=>255)); ?>
            </p>
	</div>

	<div class="form-group  col-lg-4">
            <p>
		<?php echo $form->labelEx($modelParametros,'almoco_fim'); ?>
		<?php echo $form->textField($modelParametros,'almoco_fim',array('size'=>60,'class' => 'form-control previstoHM','maxlength'=>255)); ?>
            </p>
	</div>

    </fieldset>
<?php $this->endWidget(); ?>
