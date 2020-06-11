<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Contratos') => array('index'),
    Yii::t("smith", 'Relatório de contratos em atraso'),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'pro-obra-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid', 'target' => '_blank'),
));
?>


<div class="form-group  col-lg-4">
    <p>
        <?php
        $listData = Contrato::model()->with(array('documento' => array('joinType' => 'inner join')))->findAll(array('order' => 't.nome', 'condition' => $condicao));
        $data = array();
        foreach ($listData as $model)
            $data[$model->id] = $model->nome . ' - ' . $model->codigo;
        echo CHtml::label(Yii::t("smith", 'Contrato'), 'Obra');
        echo CHtml::dropDownList('idContrato', null, $data, array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione um projeto'), "style" => "width:100%;"));
        ?>
    </p>
</div>

<div style="clear: both"></div>
<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
<div style="clear: both"></div>
<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::hiddenField('button'); ?>
        <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'onclick' => "validaForm()")); ?>
    </div>
</div>

<?php $this->endWidget(); ?>


<script>
    function validaForm() {
        var valido = true;
        $('#button').val('');

        if ($('#Obra').val() == "") {
            document.getElementById('message').innerHTML = "É necessário selecionar um contrato para pesquisa.";
            $('#btn_modal_open').click();
            valido = false;
        }
        if (valido) {
            $('#pro-obra-form').submit();
        }
    }

</script>
