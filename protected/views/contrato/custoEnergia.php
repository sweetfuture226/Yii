<?php
$this->breadcrumbs=array(

	Yii::t("smith",'Custo de energia por contrato'),
); ?>
<?php $l = (!isset($larg)) ? 12 : $larg; ?>

<div class="grid_<?php echo $l ?>">
    <div class="block-border">

        <div class="block-content">
            <div class="widget">

<?php if(isset($obra)){ ?>

    <?php

                $tam  =  count($documentos );
                $potenciaPC = 200;
                $feitoT = '00:00:00';
                for ($i = 0; $i< $tam ; $i++){

                    $duracao_filhos = "00:00:00";
                    foreach ($documentos[$i]['logs'][0]  as $doc) {
                        $duracao_filhos = sum_the_time($duracao_filhos, $doc['duracao']);
                    }
                    $feitoT = sum_the_time($feitoT, $duracao_filhos);
                }
                $custo = round(((($potenciaPC * time_to_seconds($feitoT))/3600)/1000)*$tarifa,2);
                echo '<li>'.Yii::t('smith', 'Total de horas').': ' . $feitoT .' - '.Yii::t('smith', 'tarifa').': '.$tarifa.' - '.Yii::t('smith' ,'Custo de Energia').' = R$ '.$custo;

            ?>

             <div class="clear"></div>
        <div class="block-actions">
            <ul class="actions-left">
            </ul>
            <ul class="actions-right">
                <li>
                   <?php echo CHtml::link('Voltar',Yii::app()->getBaseUrl(true).'/logAtividade/TempoPessoaObraMensal' ,array('class'=>'button')); ?>
                </li>
            </ul>
        </div>
    <?php
    }else{
         $form=$this->beginWidget('CActiveForm', array(
            'id'=>'log-atividade-form',
            'enableAjaxValidation'=>false,
            'htmlOptions'=>array('class'=>'form valid'),
         ));
        ?>
        <?php
        $date = date ("Y-m-d");
        $dateInicio = date (01 . "/m/Y");
        $today = date('d/m/Y');
        if(strtotime($today) == strtotime($dateInicio))
            $dateInicio = date('d/m/Y', strtotime('-1 months'));
        $dateFim = date ("d/m/Y", strtotime("-1 day", strtotime($date)));
        ?>
             <?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
             <p><?php echo Yii::t('smith', 'Insira o custo por kwh para saber quanto da conta de energia está sendo gasta por atividade.'); ?></p><br>
        <div class="form-group  col-lg-4">
            <p>
            <?php
                $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            $condicao = ' fk_empresa = ' . $user->fk_empresa;
            $listData = Contrato::model()->findAll(array('order' => 'nome', 'condition' => $condicao));
                $data = array();
                foreach ($listData as $model)
                    $data[$model->id] = $model->nome . ' - ' . $model->codigo;
            echo CHtml::label(Yii::t("smith", 'Contrato'), 'Obra');
                echo CHtml::dropDownList('Obra',null,$data,array("class"=>"chzn-select", "style"=> "width:100%;"));
            ?>
            </p>
        </div>
        <div class="form-group  col-lg-4">
            <p>
            <?php
            echo CHtml::label(Yii::t("smith", 'Tarifa'), 'tarifa');
            echo CHtml::textField('tarifa', $tarifa, array('class' => 'form-control valor', "style" => "width:100%;"));
            ?>
            </p>
        </div>
        <div style="clear: both"></div>
        <div class="form-group  col-lg-4">
            <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
            <?php echo CHtml::textField('date_from', '', array('class' => 'date form-control validate[required]')); ?>
        </div>

        <div class="form-group  col-lg-4">
            <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
            <?php echo CHtml::textField('date_to', $dateFim, array('class' => 'date form-control validate[required]')); ?>
        </div>
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
                    'id' => 'btn_enviar', 'onclick' => 'validaForm()')); ?>
        </div>
    </div>

    <?php
    Yii::app()->clientScript->registerScript('composicao_custos', 'baseUrl = ' . CJSON::encode(Yii::app()->baseUrl) . ';', CClientScript::POS_BEGIN);
    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/validaDatasForm.js', CClientScript::POS_END);

    $this->endWidget();
    }

function sum_the_time($time1, $time2) {
  $times = array($time1, $time2);
  $seconds = 0;
  foreach ($times as $time)
  {
    list($hour,$minute,$second) = explode(':', $time);
    $seconds += $hour*3600;
    $seconds += $minute*60;
    $seconds += $second;
  }
  $hours = floor($seconds/3600);
  $seconds -= $hours*3600;
  $minutes  = floor($seconds/60);
  $seconds -= $minutes*60;
  // return "{$hours}:{$minutes}:{$seconds}";
  return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
}
 function time_to_seconds($time) {
  $seconds = 0;
    list($hour,$minute,$second) = explode(':', $time);
    $seconds += $hour*3600;
    $seconds += $minute*60;
    $seconds += $second;
	return $seconds;
}
?>

            </div>
        </div>
    </div>
</div>

<script>
    function getData() {
        $.ajax({
            type: 'POST',
            data: {'Obra': $('#Obra').val()},
            url: "GetDataInicioProjeto/",
            success: function(data){
                $("#date_from").val(data);
            },
            error:function(){
                document.getElementById('message').innerHTML = "Não há registros deste contrato";
                $('#btn_modal_open').click();
            }
        });
    }

    $(document).on('change', '#Obra', function() {
        getData();
    });

    $(document).ready(function() {
        getData();

    });
    $(document).ready(function () {
        var csv = 'csv';
        var xlsx = 'xlsx';
        $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
        $('.pop').popover({html: true});
    });

</script>
