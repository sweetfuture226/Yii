<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'mainForm valid'),
)); ?>
<p><?=Yii::t('smith','Por favor, por medidas de segurança a senha enviada por email deve ser alterada na primeira utilização do sistema.')?></p>

        <div class="form-group  col-lg-4">
            <p>
                <?php echo CHtml::label(Yii::t('smith','Nova senha'), 'password'); ?>
                <?php echo CHtml::passwordField('password', '',array('class' => 'form-control ')); ?>
            </p>
        </div>
        
        <div class="form-group  col-lg-4">
            <p>
                <?php echo CHtml::label(Yii::t('smith','Repita a nova senha'), 'password_again'); ?>
                <?php echo CHtml::passwordField('password_again', '', array('class'=>'form-control validate[equals[UserGroupsUser_password]]')); ?>
            </p>
        </div>
<div style="clear: both"></div>
<span id="erro_senha" style="color: red; margin-left: 10px"></span>


<?php $this->endWidget(); ?>