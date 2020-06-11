<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-atividade-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

	<?php echo $form->errorSummary($model); ?>
        <div class="form-group  col-lg-4">
            <p>
            <?php
            $listData = Contrato::model()->findAll(array('order' => 'nome', 'condition' => $condicao));
            $data = array();
            foreach ($listData as $obras)
                $data[$obras->codigo] = $obras->nome . ' - ' . $obras->codigo;
            echo $form->labelEx($model, 'obra');
            echo $form->dropDownList($model, 'obra', $data, array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Sem contrato vinculado'), "style" => "width:100%;"));
            ?>
            </p>
        </div>
        <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model,'usuario'); ?>
                    <?php
                    $fk_empresa = MetodosGerais::getEmpresaId();
                    echo $form->dropdownlist($model, "usuario", CHtml::listData(Colaborador::model()->findAll(array("condition" => $condicaoCol, 'order' => 'nome ASC')), 'ad', 'nomeCompleto'), array('class' => 'chzn-select', 'multiple' => 'multiple')) ?>
                </p>
            </div>


        <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'descricao'); ?>
                <?php echo $form->textField($model,'descricao',array('class'=>'form-control ')); ?>
            </p>
        </div>
<div style="clear: both"></div>
        <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'hora_saida'); ?>
                <?php echo $form->textField($model,'hora_saida',array('size'=>60,'maxlength'=>255,'class'=>'form-control previstoHM')); ?>
            </p>
        </div>
        <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'hora_host'); ?>
                <?php echo $form->textField($model,'hora_host',array('size'=>60,'maxlength'=>255,'class'=>'form-control previstoHM')); ?>
            </p>
        </div>



        <div class="form-group  col-lg-4">
            <p>
                <?php echo $form->labelEx($model,'data'); ?>
                <?php echo $form->textField($model,'data',array('class'=>'date form-control')); ?>
            </p>
        </div>

        <div class="clear"></div>
        <div class="buttons">
            <div style="float: right; margin-bottom: 15px ">
               <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('smith','Salvar') : Yii::t('smith','Atualizar'), array('class'=>'btn btn-info submitForm')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
<!-- form -->
