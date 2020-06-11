<?php $this->breadcrumbs = array(
    Yii::t("smith", 'Custo'),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-atividade-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
    ));
?>

<p><?=Yii::t("smith", 'Comparativo produtividade x custo em um gráfico de barras com o resumo de cada equipe.')?></p><br>
<?php $date = MetodosGerais::setStartAndEndDate(); ?>
<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Selecione uma opção'), 'opcao'); ?>
        <?php echo CHtml::dropDownList( 'opcao', 'opcao',
            array('equipe' => Yii::t("smith", 'Equipes'), 'colaborador' => Yii::t("smith", 'Colaboradores')),
            array('class' => 'chzn-select', 'prompt' => Yii::t("smith", 'Selecione')));
        ?>
    </p>
</div>

<div class="form-group  col-lg-4" id="selec" style="display: none;">
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Escolha um(a)'), "colaborador_id"); ?>
        <?php echo CHtml::dropDownList('selecionado', '', array(),
            array('class' => 'chzn-select', 'id' => 'selecionado')); ?>
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
    <div style="float: right;  margin-bottom: 15px">
        <?php echo CHtml::hiddenField('button'); ?>
        <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                data-placement="top" data-content=''>
            <?php echo Yii::t('smith', 'Gerar Planilha'); ?>
        </button>
        <?php echo CHtml::button(Yii::t("smith", 'Gerar Gráfico'),
            array('class' => 'btn btn-info submitForm', 'name'=>'button_grafico',
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
    $cs=Yii::app()->clientScript;
$cs->registerScript('composicao_custos',
        'baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_BEGIN);
    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/validaDataDropForm.js', CClientScript::POS_END);
?>
<?php $this->endWidget(); ?>

