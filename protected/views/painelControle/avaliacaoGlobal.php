<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'avaliacao-global-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),


));
$date = MetodosGerais::setStartAndEndDate();
?>
    <div class="form-group col-lg-4"><p>
            <?php
            echo CHtml::label(Yii::t('smith', 'Empresa'), 'empresa');
            echo CHtml::dropdownlist('empresa', '', CHtml::listData(Empresa::model()->findAll(array("condition" => 'ativo = 1 AND id != 41', 'order' => 'nome')), 'id', 'nome'), array('class' => 'chzn-select'));
            ?>
        </p></div>
    <div style="clear: both"></div>

    <div class="form-group  col-lg-4">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
        <?php echo CHtml::textField('date_from', $date['start'], array('class' => 'date form-control validate[required]')); ?>
    </div>

    <div class="form-group  col-lg-4">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
        <?php echo CHtml::textField('date_to', $date['end'], array('class' => 'date form-control validate[required]')); ?>

    </div>

    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
            <?php echo CHtml::submitButton(Yii::t('smith', 'Pequisar'), array('class' => 'btn btn-info submitForm')); ?>
        </div>
    </div>
<?php $this->endWidget(); ?>