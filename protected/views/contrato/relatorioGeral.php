<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Relatório Geral'),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-atividade-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid', 'target' => '_blank'),
));

$date = MetodosGerais::setStartAndEndDate();
?>
<p><?=Yii::t("smith", 'Em uma tabela detalhada, visualize a produtividade dos projetos ao longo de um período separados por equipe ou colaborador.')?></p><br>

<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Selecione uma opção'), 'opcao'); ?>
        <?php echo CHtml::dropDownList('opcao', 'opcao',
            array(
                'contrato' => Yii::t("smith",'Contratos'),
                'equipe' => Yii::t("smith",'Equipes'),
                'colaborador' => Yii::t("smith",'Colaboradores')
            ),
            array('class' => 'chzn-select contrato', 'prompt' => Yii::t("smith",'Selecione'))); ?>
    </p>
</div>

<div class="form-group  col-lg-4" id="selec" style="display: none;">
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Escolha um(a)'),"colaborador_id"); ?>
        <?php echo CHtml::dropDownList('selecionado', '', array(), array('class' => 'chzn-select', 'id' => 'selecionado')); ?>
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

<div style="clear: both"></div>
<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::hiddenField('button'); ?>
        <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                data-placement="top" data-content=''>
            <?php echo Yii::t('smith', 'Gerar Planilha'); ?>

        </button>
        <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'),
            array('class' => 'btn btn-info submitForm', 'name' => 'button_grafico',
                'id' => 'btn_enviar', 'onclick' => 'validaForm2()')); ?>
    </div>
</div>
<?php
   Yii::app()->clientScript->registerScript('composicao_custos','baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_BEGIN);
   $cs=Yii::app()->clientScript;
   $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/validaDataDropForm.js', CClientScript::POS_END);
$this->widget('ext.widgets.loading.LoadingWidget'); 
$this->endWidget();
?>

<script>

    $(document).ready(function () {
        var csv = 'csv';
        var xlsx = 'xlsx';
        $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
        $('.pop').popover({html: true});
    });
    function validaForm2(){
        var valido = true;
        var inicio = $('#date_from').val();
        var fim = $('#date_to').val();
        $('#button').val('');
        if($('#opcao').val() == "" || !$('#selecionado').val()){
            document.getElementById('message').innerHTML = "Selecione equipe ou colaborador e a respectiva opção";
            $('#btn_modal_open').click();
            valido = false;
        }

        if(!checkDateRange(inicio,fim)){
            valido = false;
        }

        if(valido){
            $('#log-atividade-form').submit();
        }
    }
</script>
