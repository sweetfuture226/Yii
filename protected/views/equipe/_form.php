<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipe-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid', 'onsubmit'=>"getValueOfOcioso();"),
)); ?>

<!--<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

<header class="panel-heading tab-bg-dark-navy-blue ">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#perfil"><?= Yii::t("smith", "Formulário") ?></a></li>
        <?php if (!$model->isNewRecord) { ?>
            <li class=""><a data-toggle="tab" href="#ferias"><?= Yii::t("smith", "Histórico") ?></a></li>
        <?php } ?>
    </ul>
</header>
<div class="panel-body">
    <div class="tab-content">
        <?php echo $form->errorSummary($model); ?>
        <div id="perfil" class="tab-pane active">
            <div class="form-group  col-lg-4">
        <p>
            <?php echo $form->labelEx($model,'nome'); ?>
            <?php echo $form->textField($model,'nome',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        </p>
    </div>
	<div class="form-group col-lg-4">
        <p>
	        <?php
	            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
	            $condicao = ' fk_empresa = '.$user->fk_empresa;
	        	echo CHtml::label(Yii::t("smith", 'Membros'), 'Colaborador[nome]');
            echo CHtml::dropDownList('Membros[]', '', array(), array("class" => "chzn-select", "style" => "width:100%;", "multiple" => "multiple"));
	        ?>
        </p>
    </div>
    <div style="clear: both"></div>
    <div class="form-group  col-lg-4">
        <?php echo CHtml::label(Yii::t('smith', 'Meta de produtividade'),'nome'); ?>
        <div class="slider" data-min="1" data-max="100" data-value="<?php echo (isset($model->meta)) ? $model->meta : 60; ?>" data-postfix=" %" id="slider-range-min" ></div>
        <div class="slider-info">
            <?php echo $form->hiddenField($model,'meta',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        </div>
    </div>
        </div>

        <?php if (!$model->isNewRecord) { ?>
            <!-- ABA DE FERIAS -->
            <div id="ferias" class="tab-pane">
                <fieldset>
                    <legend><?= Yii::t("smith", "Histórico de metas") ?></legend>
                    <div class="form-group  col-lg-12">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'grid-historico-metas',
                            'dataProvider' => $historico->search(MetodosGerais::getEmpresaId(), $model->id),
                            'afterAjaxUpdate' => 'afterAjax',
                            'columns' => array(
                                array(
                                    'name' => 'data',
                                    'header' => Yii::t("smith", "Data de início"),
                                    'value' => 'MetodosGerais::dateTimeBrasileiro($data->data)',
                                ),
                                array(
                                    'name' => 'meta',
                                    'header' => Yii::t("smith", "Meta"),
                                    'value' => 'MetodosGerais::concatPorcetagem($data->meta)',
                                ),
                            ),
                        )); ?>
                    </div>
                </fieldset>
            </div>
        <?php } ?>
    </div>
</div>

    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
           <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t("smith",'Salvar') : Yii::t("smith",'Atualizar'), array('class'=>'btn btn-info submitForm')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>
<!-- form -->
<?php
foreach ($model->proPessoas as $key => $value) {
    echo '<input type="hidden" class="idPessoas" value="' . $value->id . '">';
}

?>
<script>
    $(document).ready(function () {
        verifyUserBlock("Membros");
        var colaboradores = [];
        $(".idPessoas").each(function (index) {
            colaboradores.push($(this).val());
        });
        if (colaboradores.length > 0)
            $("#Membros").val(colaboradores).trigger("change");
        $("div.ui-slider-range.ui-widget-header").css('background', 'green');
        $(".slider .ui-slider-handle").css('background', 'green');
    });
    function getValueOfOcioso(argument) {
        $("#Equipe_meta").val($("#slider-range-min").slider("option", "value"));
        return true;
    }
</script>
