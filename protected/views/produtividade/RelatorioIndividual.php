<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Produtividade individual'),
);
?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-atividade-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
        ));
?>
<p><?= Yii::t("smith", 'Em um gráfico de linha, visualize a produtividade de um colaborador em um dia.') ?></p><br>

<div class="form-group  col-lg-4">
    <?php ?>
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Colaborador'), "colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('colaborador_id', '', array(),
                array("class" => "chzn-select", "style" => "width:100%;")); ?>
    </p> 
</div>
<div style="clear: both"></div>
<div class="form-group  col-lg-4">
    <?php ?>
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Exibir em'), "colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('tipo', '', array("dias"=>"Dia","mes"=>"Mês","ano"=>"Ano"),
            array("class" => "chzn-select", "style" => "width:100%;")); ?>
    </p>
</div>

<?php $dataIni = date('d/m/Y');
$dataEnd = date('t') . '/' . date('m/Y');
?>
<div id="dateDias" class="form-group  col-lg-4" style="display: block">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data') ?></label>
<?php echo CHtml::textField('dataDia', '', array('class' => 'date form-control ')); ?>

</div>

<div id="dateMes" class="form-group  col-lg-4" style="display: none">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Mês') ?></label>
<?php echo CHtml::textField('dataMes', '', array('class' => 'dateMonth form-control ')); ?>

</div>

<div id="dateAno" class="form-group  col-lg-4" style="display: none">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Ano') ?></label>
<?php echo CHtml::textField('dataAno', '', array('class' => 'dateYear form-control ')); ?>

</div>

<div class="clear"></div>
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
    </div>
</div>
<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
<?php $this->endWidget(); ?>

<script>
    $(document).ready(function(){
        var data = new Date();
        var dia = $.datepicker.formatDate('dd/mm/yy', data);
        var mes = $.datepicker.formatDate('mm/yy', data);
        var ano = $.datepicker.formatDate('yy', data);
        $("#dataDia").val(dia);
        $("#dataMes").val(mes);
        $("#dataAno").val(ano);
        verifyUserBlock("colaborador_id");
        var csv = 'csv';
        var xlsx = 'xlsx';
        $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
        $('.pop').popover({html: true});
    });

    $("#tipo").change(function(){
        var tipo = $("#tipo").val();
        switch (tipo){
            case 'dias':
                $("#dateMes").hide();
                $("#dateAno").hide();
                $("#dateDias").show();
                break;
            case 'mes':
                $("#dateDias").hide();
                $("#dateAno").hide();
                $("#dateMes").show();
                break;
            case 'ano':
                $("#dateMes").hide();
                $("#dateDias").hide();
                $("#dateAno").show();
                break;
        }
    });

    function hideLoading() {
        Loading.hide();
    }

    function valida(tipo) {
        $('#button').val(tipo);
        setTimeout(hideLoading, 5000);
        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }

    function valida2Grafico() {
        $('#button').val('');
        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }
</script>

