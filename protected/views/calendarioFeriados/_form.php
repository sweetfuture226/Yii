<?php
/* @var $this CalendarioFeriadosController */
/* @var $model CalendarioFeriados */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'calendario-feriados-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'form valid'),
)); ?>

    <div id="dateDias" class="form-group  col-lg-4" style="display: block">
        <label for="data"><?php echo Yii::t("smith", 'Data') ?></label>
        <?php echo CHtml::textField('CalendarioFeriados[data]', $datas, array('class' => 'feriados form-control ')); ?>

    </div>
    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
            <?php echo CHtml::submitButton( Yii::t("smith",'Atualizar'), array('class'=>'btn btn-info submitForm')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<script>
    $("#CalendarioFeriados_data").keydown(false);
</script>