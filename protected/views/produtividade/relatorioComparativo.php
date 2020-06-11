<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Relatório comparativo'),
);
?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-atividade-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid', 'target' => '_blank'),
));
?>

<?php $date = MetodosGerais::setStartAndEndDate(); ?>
    <div class="col-lg-8">
        <div class="form-group  col-lg-6">
        <?php ?>
        <p>
            <?php echo CHtml::label(Yii::t("smith", 'Colaborador'), "colaborador_id"); ?>
            <?php echo CHtml::dropdownlist('colaborador[1]', '',
                array(),
                array("class" => "chzn-select", 'empty' => Yii::t('smith', 'Selecione'), 'onchange' => "checkChangeColaborador()", "style" => "width:100%;")); ?>
        </p>
    </div>
        <div class="form-group  col-lg-6">
        <?php ?>
        <p>
            <?php echo CHtml::label(Yii::t("smith", 'Colaborador'), "colaborador_id"); ?>
            <?php echo CHtml::dropdownlist('colaborador[2]', '',
                array(),
                array("class" => "chzn-select", 'empty' => Yii::t('smith', 'Selecione'), 'onchange' => "checkChangeColaborador()", "style" => "width:100%;")); ?>
        </p>
    </div>
        <div id="colMais"></div>
    </div>
    <div class="col-lg-4">
<?php echo CHtml::button(Yii::t("smith", '+ colaborador'), array('class' => 'btn btn-success submitForm', 'style' => 'margin-top: 20px', 'id' => 'novo_colaborador')); ?>
<?php echo CHtml::button(Yii::t("smith", '- colaborador'), array('class' => 'btn btn-danger submitForm', 'style' => ' margin-left: 3px; margin-top: 20px', 'id' => 'rm_colaborador')); ?>
    </div>
    <!--<div style="clear: both"></div>-->
    <input id="index" type="hidden" value="3">

    <div style="clear: both"></div>
    <div class="col-lg-8">
        <div class="form-group  col-lg-6">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
        <?php echo CHtml::textField('date_from', $date['start'], array('class' => 'date form-control validate[required]')); ?>
    </div>

        <div class="form-group  col-lg-6">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
        <?php echo CHtml::textField('date_to', $date['end'], array('class' => 'date form-control validate[required]')); ?>
    </div>
    </div>
<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
    <div style="clear: both"></div>
    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
            <?php echo CHtml::hiddenField('button'); ?>
            <!-- --><?php /*echo CHtml::button(Yii::t("smith", 'Planilha'),
                array('class' => 'btn btn-info submitForm', 'name' => 'button_excel',
                    'id' => 'btn_excel', 'onclick' => 'valida()')); */ ?>
            <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'),
                array('class' => 'btn btn-info submitForm', 'name' => 'button_grafico',
                    'id' => 'btn_enviar', 'onclick' => 'validaForm()')); ?>
        </div>
    </div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/system/relComparativo.js', CClientScript::POS_END);
?>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        verifyUserBlock("colaborador_1");
        verifyUserBlock("colaborador_2");
    });
</script>