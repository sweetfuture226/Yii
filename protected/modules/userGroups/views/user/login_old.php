<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',        
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
        'htmlOptions'=>array('class'=>'block-content form valid')
)); ?>

    <p class="inline-small-label">
        <label for="username">Login</label>
        <?php echo $form->textField($model,'username', array('class'=>'text validate[required]')); ?>
    </p>
    <p class="inline-small-label">
        <label for="password">Senha</label>
        <?php echo $form->passwordField($model,'password', array('class'=>'password validate[required]')); ?>
    </p>
    <!--<p>
        <label>
            <div class="checker" id="uniform-undefined">
                <span><input type="checkbox" name="keep_logged" style="opacity: 0; "></span>
            </div>
            Lembrar-me
        </label>
    </p>-->
    <div class="clear"></div>
    <div class="block-actions">
        <!--<ul class="actions-left">
            <li><a class="button" name="recover_password" href="javascript:void(0);">Recover Password</a></li>
            <li class="divider-vertical"></li>
            <li><a class="button red" id="reset-login" href="javascript:void(0);">Cancel</a></li>
        </ul>-->
        <ul class="actions-right">
            <li>
                <?php echo CHtml::submitButton('Entrar', array('class'=>'button')); ?>
            </li>
        </ul>
    </div>
<?php $this->endWidget(); ?>

<?php if(Yii::app()->user->hasFlash('login')): ?>
    
    <div class="alert error">
        <strong>ERRO: </strong><?php echo Yii::app()->user->getFlash('login'); ?>
    </div>

<?php endif; ?>