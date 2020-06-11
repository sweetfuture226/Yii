<?php $this->breadcrumbs=array(
    Yii::t("smith",'Produtividade em dias'),
); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'log-atividade-form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
    )); ?>

<p><?=Yii::t("smith", 'Em um gráfico de barras, visualize a produtividade de um colaborador em determinadas datas.')?></p><br>
<?php $date = MetodosGerais::setStartAndEndDate(); ?>
<div class="form-group  col-lg-4">
    <?php $fk_equipe = null;
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $condicao = " 1 "; //recupera todos
        if(isset($user->fk_empresa))
            $condicao = "fk_empresa = ".$user->fk_empresa;
    ?>
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Colaborador'), "colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('colaborador_id', '', array(),
            array("class" => "chzn-select", "style" => "width:100%;")); ?>
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
<div class="clear"></div>
<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::hiddenField('button'); ?>
        <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                data-placement="top" data-content=''>
            <?php echo Yii::t('smith', 'Gerar Planilha'); ?>

        </button>
        <?php echo CHtml::button(Yii::t("smith", 'Gerar Gráfico'),
            array('name' => 'grafico',  'id' => 'btn_grafico',
            'class' => 'btn btn-info submitForm', 'onclick' => "valida2Grafico();")); ?>
    </div>
</div>

<?php
    $cs=Yii::app()->clientScript;
    $cs->registerScript('composicao_custos','baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_BEGIN);
    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/validaDatasForm.js', CClientScript::POS_END);
?>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        verifyUserBlock("colaborador_id");
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
    if(!checkDateRange(inicio,fim)){
        return false;
    }

        $('#button').val(tipo);
    setTimeout(hideLoading, 5000);
    $('#log-atividade-form').submit();
    Loading.show();
    return true;
}

function valida2Grafico(){
    var inicio = $('#date_from').val();
    var fim = $('#date_to').val();
    if(!checkDateRange(inicio,fim)){
        return false;
    }

    $('#button').val('');
    $('#log-atividade-form').submit();
    Loading.show();
    return true;
}
</script>
