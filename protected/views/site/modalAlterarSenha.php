<input type="hidden" id="visible_senha" value="1">
<p><?= Yii::t('smith', 'A troca de senha é um procedimento que preza pela segurança das informações geradas pelo Smith. Favor proceder') ?></p>
                        <div style="clear: both"></div>
<div class="form-group  col-lg-6" id="alterarsenha">
                            <p>
                                <?php echo CHtml::label(Yii::t('smith','Nova senha'), 'password'); ?>
                                <?php echo CHtml::passwordField('password', '',array('class' => 'form-control ')); ?>
                            </p>
                        </div>

                        <div class="form-group  col-lg-6">
                            <p>
                                <?php echo CHtml::label(Yii::t('smith','Repita a nova senha'), 'password_again'); ?>
                                <?php echo CHtml::passwordField('password_again', '', array('class'=>'form-control validate[equals[UserGroupsUser_password]]')); ?>
                            </p>
                        </div>
<div style="margin-left: 10px">
                             <span id="erro_senha" style="color: red;"><i class="fa fa-info-circle"
                                                                          aria-hidden="true"></i> <?php echo Yii::t('smith', 'Para atender os requisitos de segurança é necessário que a senha contenha pelo menos 6 caracteres, uma letra minúscula, uma letra maiúscula e um número.'); ?>
                            </span>
</div>