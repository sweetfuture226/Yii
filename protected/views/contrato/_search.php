<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
        'htmlOptions'=>array('class'=>'form'),
)); ?>

    
        <div class="_25">
            <p>
                <?php echo $form->label($model, 'nome'); ?>
                <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255)); ?>
            </p>
        </div>

    
        <div class="_25">
            <p>
                <?php echo $form->label($model, 'codigo'); ?>
                <?php echo $form->textField($model, 'codigo', array('size' => 60, 'maxlength' => 255)); ?>
            </p>
        </div>

    
        <div class="buttons">
            <div style="float: left;">
		<?php echo CHtml::submitButton('Buscar', array('class'=>'button')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->