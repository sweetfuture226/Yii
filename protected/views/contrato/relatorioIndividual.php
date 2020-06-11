<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Contratos')=>array('index'),
    Yii::t("smith", 'Relatório Individual'),
);

 $form=$this->beginWidget('CActiveForm', array(
            'id'=>'pro-obra-form',
            'enableAjaxValidation'=>false,
     'htmlOptions' => array('class' => 'form valid', 'target' => '_blank'),
        ));
        ?>

<p><?=Yii::t("smith", 'Em uma tabela detalhada, visualize a produtividade do projeto ao longo de um período.')?></p><br>

        <div class="form-group  col-lg-4">
            <p>
            <?php
            $listData = Contrato::model()->findAll(array('order' => 'nome', 'condition' => $condicao));
            $data = array();
            foreach ($listData as $model)
                $data[$model->id] = $model->nome . ' - ' . $model->codigo;
                echo CHtml::label(Yii::t("smith", 'Contrato'),'Obra');
                echo CHtml::dropDownList('Obra',null,$data,array("class"=>"chzn-select",'empty'=>Yii::t("smith", 'Selecione um projeto'), "style"=> "width:100%;"));
            ?>
            </p>
        </div>

<?php if($hasDisciplina){ ?>
<div class="form-group col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t("smith", 'Disciplina'),'Obra'); ?> <span class="required">*</span>
        <?php $fk_empresa = MetodosGerais::getEmpresaId();
        $listData = Disciplina::model()->findAll(array("condition"=>'fk_empresa = ' . $fk_empresa));
        $disciplinas = CHtml::listData($listData, 'id', 'nome');
        echo CHtml::dropDownList('Disciplina', $disciplina, $disciplinas,
            array("class"=>"chzn-select",'empty'=>Yii::t("smith", 'TODAS'), "style"=> "width:100%;")); ?>
    </p>
</div>
<?php } ?>

<div style="clear: both"></div>
<div class="form-group  col-lg-8" id="selec" style="display: none ; padding-left: 0px;">
    <?php $date = MetodosGerais::setStartAndEndDate(); ?>
    <?php $dataEnd = date('d/m/Y'); ?>
    <div class="form-group  col-lg-4">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
        <?php echo CHtml::textField('date_from', '', array('class' => 'date form-control validate[required]')); ?>

    </div>

    <div class="form-group  col-lg-4">
        <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
        <?php echo CHtml::textField('date_to', $date['end'], array('class' => 'date form-control validate[required]')); ?>

    </div>

    <div id="checkDocs" class="form-group col-lg-4">
        <p>
            <?php echo CHtml::label(Yii::t('smith', 'Discriminar documentos não cadastrados'), 'docs_nao_cadastrados'); ?>
            <?php echo CHtml::checkBox('docs_nao_cadastrados'); ?>
        </p>
    </div>
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
        <?php echo CHtml::button(Yii::t("smith", 'Gerar PDF'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'onclick' => "validaForm()")); ?>
        </div>
    </div>

        <?php $this->endWidget(); ?>


 <script>

     $(document).ready(function () {
         var csv = 'csv';
         var xlsx = 'xlsx';
         $("#btn_popover").data('content', '<input class="btn btn-info submitForm" name="button_excel" id="btn_excel" onclick="valida(\'' + csv + '\')" type="button" value="CSV"> <input class="btn btn-info submitForm" name="btn_excel_new" id="btn_excel_new" onclick="valida(\'' + xlsx + '\')" type="button" value="XLSX">');
         $('.pop').popover({html: true});
     });

     function validaForm(){
        var valido = true;
         $('#button').val('');

        if($('#Obra').val() == ""){
            document.getElementById('message').innerHTML = "É necessário selecionar um contrato para pesquisa.";
            $('#btn_modal_open').click();
            valido = false;
        }
        if(valido){
            $('#pro-obra-form').submit();
        }
    }


     $(document).on('change', '#Obra', function () {
        $.ajax({
            type: 'POST',
            data: {'Obra': $('#Obra').val()},
            url: "GetDataInicioProjeto/",
            success: function(data){
                $("#selec").show();
                $("#date_from").val(data);
                $("#btn_enviar").show();

                $.ajax({
                    type: 'POST',
                    data: {
                        'Obra': $('#Obra').val(),
                        'date_from': $("#date_from").val(),
                        'date_to': $("#date_to").val()
                    },
                    url: "GetDocumentos/",
                    success: function () {
                        $("#checkDocs").show();
                    },
                    error: function () {
                        $("#checkDocs").hide();
                    }
                });
            },
            error:function(){
                document.getElementById('message').innerHTML = "Não há registros deste contrato";
                $('#btn_modal_open').click();
                $("#selec").hide();
                $("#btn_enviar").hide();
            }
        });
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

         $('#button').val(tipo);
         setTimeout(hideLoading, 5000);
         $('#pro-obra-form').submit();
         Loading.show();
         return true;
     }


     function checkDateRange(start, end) {
         var start2 = start.split("/");
         var end2 = end.split("/");
         var data = new Date();
         var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
         start2 = new Date(start2[2], start2[1] - 1, start2[0]);
         end2 = new Date(end2[2], end2[1] - 1, end2[0]);

         if (+end2 === +hoje) {
             document.getElementById('message').innerHTML = "A data final não pode ser de hoje, pois por questões de desempenho apresentamos a produtividade até dia anterior.";
             $('#btn_modal_open').click();
             return false;
         }

         if (end2 > hoje) {
             document.getElementById('message').innerHTML = "A data final não pode ser maior que hoje.";
             $('#btn_modal_open').click();
             return false;
         }

         if (isNaN(start2)) {
             document.getElementById('message').innerHTML = "A data de início não é válida, por favor insira uma data válida.";
             $('#btn_modal_open').click();
             return false;
         }

         if (isNaN(end2)) {
             document.getElementById('message').innerHTML = "A data de fim não é válida, por favor insira uma data válida.";
             $('#btn_modal_open').click();
             return false;
         }

         if (end2 < start2) {
             document.getElementById('message').innerHTML = "A data de início precisa ser antes da data de fim.";
             $('#btn_modal_open').click();
             return false;
         }

         return true;
     }
    </script>
