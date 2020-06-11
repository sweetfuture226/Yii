<?php
$this->breadcrumbs=array(
    Yii::t("smith",'Acompanhamento de colaboradores'),
);
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'log-atividade-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'form valid','target'=>'_blank'),
));

$date = MetodosGerais::setStartAndEndDate();
?>

<p><?=Yii::t("smith", 'A que hora o colaborador iniciou as atividades no computador? Visualize aqui.')?></p><br>

<div class="form-group  col-lg-4">
    <?php $fk_equipe = null;
    $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
    $condicao = " 1 "; //recupera todos
    if (isset($user->fk_empresa))
        $condicao = "fk_empresa = " . $user->fk_empresa; ?>
    <p>
        <?php echo CHtml::label(Yii::t("smith",'Colaborador'),"colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('colaborador_id', '', array(),
            array("class"=>"chzn-select", "prompt"=>Yii::t("smith",'Todos'), "style"=> "width:100%;")); ?>
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
        <button type="button" class="btn btn-info pop" id="btn_popover" data-container="body" data-toggle="popover"
                data-placement="top" data-content=''>
            <?php echo Yii::t('smith', 'Gerar Planilha'); ?>

        </button>
        <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'),
            array('class' => 'btn btn-info submitForm', 'name' => 'button_grafico',
                'id' => 'btn_enviar', 'onclick' => 'validaForm()')); ?>
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
    function validaForm(){
        var valido = true;
        var inicio = $('#date_from').val();
        var fim = $('#date_to').val();
        $('#button').val('');
        if (!checkDateRange(inicio, fim)) {
            valido = false;
        }
        if(valido){
            $('#log-atividade-form').submit();
            //Loading.show();
        }
    }

    function checkDateRange(start, end) {
        var start2 = start.split("/");
        var end2 = end.split("/");
        var data = new Date();
        var hoje = new Date(data.getFullYear(),data.getMonth(),data.getDate());
        start2 = new Date(start2[2],start2[1]-1,start2[0]);
        end2 = new Date(end2[2],end2[1]-1,end2[0]);

        if (isNaN(start2)) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'Favor inserir uma data válida') ?>";
            $('#btn_modal_open').click();
            return false;
        }
        if (isNaN(end2)) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'Favor inserir uma data válida') ?>";
            $('#btn_modal_open').click();
            return false;
        }
        if (end2 > hoje) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'A data final não pode ser maior que a data de hoje') ?>";
            $('#btn_modal_open').click();
            return false;
        }

        if (end2 < start2) {
            document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'A data de início precisa ser antes da data de fim.') ?>";
            $('#btn_modal_open').click();
            return false;
        }

        return true;
    }


    function hideLoading() {
        Loading.hide();
    }

    function valida(tipo) {
        var inicio = $('#date_from').val();
        var fim = $('#date_to').val();
        if (!checkDateRange(inicio, fim)) {
            return false;
        }

        $('#button').val(tipo);
        setTimeout(hideLoading, 5000);
        $('#log-atividade-form').submit();
        Loading.show();
        return true;
    }
</script>
