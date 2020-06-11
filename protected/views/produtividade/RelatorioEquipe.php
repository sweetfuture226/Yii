<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Produtividade por equipe'),
);
?>

<p><?=Yii::t("smith", 'Resumo da produtividade em um gráfico de barras! Visualize a produtividade da equipe no período de datas escolhido.')?></p><br>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-atividade-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
));

$date = MetodosGerais::setStartAndEndDate();
?>


<?php
$htmlOptions = array("style"=> "width:100%;",'class'=>'chzn-select');
if (Yii::app()->user->groupName != 'coordenador')
    $htmlOptions['empty'] = Yii::t("smith",'TODAS');?>
<div class="form-group  col-lg-4">
    <p>
        <?php
        echo CHtml::label(Yii::t("smith", 'Equipe'), 'fk_equipe');
        echo CHtml::dropdownlist('equipe', 'TODAS', CHtml::listData(Equipe::model()->findAll(array('order' => 'nome', 'condition' => $condicao)), 'id', 'nome'), $htmlOptions);
        ?>
    </p>
</div>
<div class="form-group  col-lg-2">
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Considerar hora extra?'), 'sufixo'); ?> <br>
        <?php echo CHtml::checkBox('hora_extra', true, array('checked' => 'checked')); ?>
    </p>
</div>
<div style="clear: both"></div>
<div class="form-group  col-lg-4">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
    <?php echo CHtml::textField('date_from', $date['start'], array('class' => 'date form-control validate[required]')); ?>
</div>

<div class="form-group  col-lg-4">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
    <?php echo CHtml::textField('date_to', $date['end'], array('class' => 'date form-control validate[required]')); ?>
</div>

<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
<div style="clear: both"></div>
    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::hiddenField('button'); ?>
            <?php echo CHtml::hiddenField('tipo'); ?>

            <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                    data-placement="top" data-content=''>
                <?php echo Yii::t('smith', 'Gerar Planilha'); ?>

            </button>
            <?php echo CHtml::button(Yii::t("smith", 'Gerar Gráfico'),
            array('class' => 'btn btn-info submitForm', 'name' => 'button_grafico',
                'id' => 'btn_enviar', 'onclick' => 'validaForm()')); ?>

    </div>
</div>

    <script type="text/javascript">
        $(document).ready(function () {
            var csv = 'csv';
            var xlsx = 'xlsx';
            $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
            $('.pop').popover({html: true});
        });
    </script>

<?php
   Yii::app()->clientScript->registerScript('composicao_custos','baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_BEGIN);
   $cs=Yii::app()->clientScript;
   $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/validaDatasForm.js', CClientScript::POS_END);

$this->endWidget();


