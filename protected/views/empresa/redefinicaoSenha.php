<?php
$this->breadcrumbs=array(
    'Empresas'=>array('index'),
    'Redefinir Senha Cliente',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'empresa-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'form valid', 'enctype' => 'multipart/form-data'),
)); ?>

<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Cliente'),'nome'); ?>
        <?php echo CHtml::dropdownlist('username','',CHtml::listData(UserGroupsUser::model()->findAll(array('order'=>'username')), 'id', 'usuarioEmpresa'),array("class"=>"chzn-select" ,"style"=> "width:100%;")); ?>
    </p>
</div>
<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Email destinatário'),'nome'); ?>
        <?php echo CHtml::textField('email','',array('class'=>'form-control email')); ?>
    </p>
</div>
<div class="buttons">
    <div style="float: right; ">
        <?php echo CHtml::submitButton(Yii::t('smith','Redefinir senha'), array('class'=>'btn btn-info submitForm')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

