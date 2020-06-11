<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'programa-permitido-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
        ));
?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

<?php echo $form->errorSummary($model); ?>

<div class="form-group  col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'nome'); ?>
        <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
    </p>
</div>

<div class="form-group  col-lg-4">
    <p>
        <?php
        echo $form->labelEx($model, 'fk_equipe');
        echo $form->dropdownlist($model, 'fk_equipe', CHtml::listData(Equipe::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa =' . UserGroupsUser::model()->findByPk(Yii::app()->user->id)->fk_empresa)), 'id', 'nome'), array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Todas '), "style" => "width:100%;"));
        ?>
    </p>
</div>

<div class="buttons">
    <div style="float: right; ">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('smith', 'Salvar') : Yii::t('smith', 'Atualizar'), array('class' => 'btn btn-info submitForm')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
