<?php
$this->breadcrumbs=array(
	Yii::t('smith','Coordenadores')=>array('index'),
    Yii::t('smith', 'Adicionar'),
); ?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user_groups_user-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'mainForm valid'),
)); ?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p> -->

	<?php echo $form->errorSummary($model); ?>

        <div class="form-group  col-lg-4">
            <p>
                <?php echo CHtml::label(Yii::t('smith', 'Nome'), 'UserGroupsUser_nome'); ?>
                <?php echo $form->textField($model,'nome',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
            </p>
        </div>

       <div class="form-group  col-lg-4">
            <p>
                <?php echo CHtml::label(Yii::t('smith', 'Login'), 'UserGroupsUser_nome'); ?>
                <?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
            </p>
        </div>



        <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
            </p>
        </div>

          <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'Senha'); ?>
                <?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
            </p>
        </div>

        <div class="form-group  col-lg-4">
            <p>
            <?php
                $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            $condicao = ' fk_empresa = ' . $user->fk_empresa;
            echo CHtml::label(Yii::t("smith", 'Contratos'), 'Colaborador[nome]');
            echo CHtml::dropDownList('contratos[]', '', CHtml::listData(Contrato::model()->findAll(array('order' => 'nome', 'condition' => $condicao)), 'id', 'nome'), array("class" => "chzn-select", "style" => "height:28px;", "multiple" => "multiple"));
            ?>
            </p>
        </div>
        <div class="form-group  col-lg-4">
            <p>
                <?php   echo $form->labelEx($model,'fk_equipe');
                echo $form->dropdownlist($model, 'fk_equipe', CHtml::listData(Equipe::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa =' . UserGroupsUser::model()->findByPk(Yii::app()->user->id)->fk_empresa)), 'id', 'nome'), array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione '), "style" => "width:100%;"));
                ?>
            </p>
        </div>


<div class="clear"></div>


<div class="buttons">
            <div style="float: right; ">
               <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('smith','Salvar') : Yii::t('smith','Atualizar'), array('class'=>'btn btn-info submitForm')); ?>
            </div>
        </div>


<?php $this->endWidget(); ?>
