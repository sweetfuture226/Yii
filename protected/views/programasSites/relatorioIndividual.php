<?php
$this->breadcrumbs = array(

    Yii::t("smith", 'Relatório individual'),
); ?>


<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'log-atividade-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid', 'target' => '_blank'),
));
$permitido = (strpos(UserGroupsUser::model()->findByPk(Yii::app()->user->id)->username, 'admin'));

?>
<p><?= Yii::t("smith", 'Em uma tabela detalhada, visualize a produtividade de um colaborador em um dia.') ?></p><br>
<div class="form-group  col-lg-4">
    <p><?php echo CHtml::label(Yii::t("smith", 'Colaborador'), "colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('colaborador_id', '', array(),
            array("class" => "chzn-select", "style" => "width:100%;")); ?></p>
</div>
<?php $dataIni = date('d/m/Y', time() - (3600 * 27)); ?>
<?php $dataEnd = date('t') . '/' . date('m/Y'); ?>
<div class="form-group  col-lg-4">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data') ?></label>
    <?php echo CHtml::textField('data', $dataIni, array('class' => 'date form-control validate[required]')); ?>
</div>

<?php //$this->widget('ext.widgets.loading.LoadingWidget'); ?>

<div class="clear"></div>

<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::hiddenField('button'); ?>
        <?php
        if ($permitido !== FALSE)
            echo CHtml::button(Yii::t("smith", 'Baixar zip (todos relatórios)'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_zip', 'onclick' => "validaFormZip();")); ?>
        <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                data-placement="top" data-content=''>
            <?php echo Yii::t('smith', 'Gerar Planilha'); ?>

        </button>
        <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'data-url' => Yii::app()->createUrl('Colaborador/VerificaDataRelatorioIndDia'), 'onclick' => "validaForm();")); ?>

    </div>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        verifyUserBlock("colaborador_id");

        var csv = 'csv';
        var xlsx = 'xlsx';
        $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
        $('.pop').popover({html: true});
    });
    function validaForm() {
        var valido = true;

        var fim = $('#data').val();
        $('#button').val('');
        if (!checkDateRange(fim)) {
            valido = false;
        }

        var id = $("#colaborador_id").val();
        if (!verificaDataRelatorioIndDia(fim, id)) {
            mensagemAlerta('Não é possível gerar relátorio desse colaborador, colaborador inativo', 'warning');
            return false;
        }

        if (valido) {
            $('#log-atividade-form').submit();
            //Loading.show();
        }
    }


    function validaFormZip() {
        var valido = true;
        $('#button').val('zip');
        var fim = $('#data').val();

        if (!checkDateRange(fim)) {
            valido = false;
        }


        if (valido) {
            $('#log-atividade-form').submit();
            //Loading.show();
        }
    }
    function checkDateRange(end) {

        var end2 = end.split("/");
        var data = new Date();
        var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
        end2 = new Date(end2[2], end2[1] - 1, end2[0]);

        if (+end2 === +hoje) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'A data não pode ser de hoje, pois, por questões de desempenho, é apresentada a produtividade até o dia anterior') ?>";
            $('#btn_modal_open').click();
            return false;
        }

        if (end2 > hoje) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'A data final não pode ser maior que hoje') ?>";
            $('#btn_modal_open').click();
            return false;
        }


        if (isNaN(end2)) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'Favor inserir uma data válida') ?>";
            $('#btn_modal_open').click();
            return false;
        }


        return true;
    }

    function hideLoading() {
        Loading.hide();
    }

    function valida(tipo) {

        var fim = $('#data').val();
        if (!checkDateRange(fim)) {
            return false;
        }

        $('#button').val(tipo);
        setTimeout(hideLoading, 5000);
        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }

</script>