<?php
$this->breadcrumbs=array(

	Yii::t("smith",'Ranking por período'),
);
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-atividade-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form valid', 'target' => '_blank'),
));
?>
<p><?=Yii::t("smith", 'Para a meritocracia e avaliações períodicas, visualize o Ranking de produtividade.')?></p><br>
<?php $date = MetodosGerais::setStartAndEndDate(); ?>
    <div class="form-group  col-lg-4">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
        <?php echo CHtml::textField('date_from', $date['start'], array('class' => 'date form-control validate[required]')); ?>
    </div>

    <div class="form-group  col-lg-4">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
        <?php echo CHtml::textField('date_to', $date['end'], array('class' => 'date form-control validate[required]')); ?>
    </div>

<input type="hidden" name="flagPDF" id="flagPDF" value="0">

    <div style="clear: both"></div>
    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
            <?php echo CHtml::hiddenField('button'); ?>
            <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                    data-placement="top" data-content=''>
                <?php echo Yii::t('smith', 'Gerar Planilha'); ?>

            </button>
            <?php echo CHtml::button(Yii::t("smith", 'Gerar Gráfico'),
                array('class' => 'btn btn-info submitForm', 'name' => 'button_grafico',
                    'id' => 'btn_enviar', 'onclick' => 'valida2Grafico()')); ?>
            <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'),
                array('class' => 'btn btn-info submitForm', 'name' => 'button_pdf',
                    'id' => 'btn_enviar', 'onclick' => 'valida2PDF()')); ?>
        </div>
    </div>
<?php
   Yii::app()->clientScript->registerScript('composicao_custos','baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_BEGIN);
   $cs=Yii::app()->clientScript;
   $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/validaDatasForm.js', CClientScript::POS_END);

$this->endWidget();
?>

<script>

    $(document).ready(function () {
        var csv = 'csv';
        var xlsx = 'xlsx';
        $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
        $('.pop').popover({html: true});
    });
    function hideLoading() {
        Loading.hide();
    }

    function valida(tipo) {
        var inicio = $('#date_from').val();
        var fim = $('#date_to').val();
        if (!checkDateRange(inicio, fim)) {
            return false;
        }
        $("#flagPDF").val(0);
        $('#button').val();
        setTimeout(hideLoading, 5000);

        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }

    function valida2Grafico() {
        var inicio = $('#date_from').val();
        var fim = $('#date_to').val();
        if (!checkDateRange(inicio, fim)) {
            return false;
        }

        $('#button').val('');
        $("#flagPDF").val(0);
        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }

    function valida2PDF() {
        var inicio = $('#date_from').val();
        var fim = $('#date_to').val();
        if (!checkDateRange(inicio, fim)) {
            return false;
        }
        $("#flagPDF").val(1);
        $('#button').val('');
        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }
</script>
