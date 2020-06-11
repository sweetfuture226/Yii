<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'pro-obra-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
        ));
?>

<header class="panel-heading tab-bg-dark-navy-blue ">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#contrato">Contrato</a></li>
        <li class=""><a data-toggle="tab" href="#ldp">Lista de documentos</a></li>
    </ul>
</header>
<div class="panel-body">
    <div class="tab-content">

        <?php echo $form->errorSummary($model); ?>
        <div id="contrato" class="tab-pane active">
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'nome'); ?>
                    <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control ')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'codigo'); ?>
                    <?php echo $form->textField($model, 'codigo', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control ')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <label for="Documento_disciplina_id" class="required"><?php echo Yii::t("smith", 'Coordenador') ?> <span class="required">*</span></label>
                    <?php
                    $fk_empresa = MetodosGerais::getEmpresaId();
                    echo $form->dropdownlist($model, "coordenador", CHtml::listData(UserGroupsUser::model()->findAll(array("condition" => $condicao)), 'id', 'username'), array('class' => 'chzn-select'))
                    ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'data_inicio'); ?>
                    <?php echo $form->textField($model, 'data_inicio', array('class' => 'date form-control')); ?>
                </p>
            </div>
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model,'data_final'); ?>
                    <?php echo $form->textField($model,'data_final',array('class'=>'date form-control')); ?>
                </p>
            </div>
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model,'valor'); ?>
                    <?php echo $form->textField($model,'valor',array('class'=>'form-control valor')); ?>
                </p>
            </div>
            <div class="form-group col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'tempo_previsto'); ?>
                    <?php echo $form->textField($model, 'tempo_previsto', array('class'=>'form-control')); ?>
                </p>
            </div>
            <div class="form-group  col-lg-4">
                <p>
        		<?php echo $form->labelEx($model,'moeda'); ?>
        		<?php echo $form->dropDownList($model,'moeda',array(
                        'BRL'=>'Real',
                        'EUR'=>'Euro',
                        'USD'=>'Dólar'
        			),array("class"=>"chzn-select", "style"=> "width:100%;")); ?>
                </p>
        	</div>
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model,'receber_email'); ?>
                    <?php echo $form->checkBox($model,'receber_email'); ?>
                </p>
            </div>
            <div class="form-group  col-lg-4">
                <p>
                    <?php
                    echo CHtml::hiddenField('Contrato[finalizada]', 0);
                    ?>
                </p>
            </div>
        </div>
            <div id="ldp" class="tab-pane">
                <p>Abaixo estão listados todos os documentos referentes ao contrato. Caso deseje fazer alguma alteração, clique no nome do documento.</p>

                <div class="" style="margin-bottom: 10px">

                    <?php echo CHtml::Button('+ Adicionar documento', array('id' => 'bt_add_doc', 'onclick' => 'add_documento();', 'class' => 'btn btn-success')); ?>
                    <?php //echo CHtml::Button('Associar documento existente', array('id' => 'bt_assoc_doc', 'class' => 'btn btn-success')); ?>

                </div>
                <div class="importManual" style="display: block;">

                    <?php $index = count($documentos); ?>

                    <div id="ref"></div>
    <?php foreach ($documentos as $documento) { ?>
                        <div class="panel panel-default painel<?php echo $index; ?>">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $index; ?>">
        <?php echo $documento->nome ?>
                                    </a>
                                </h4>
                            </div>
                            <div style="height: 0px;" id="collapse<?php echo $index; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="form-group  col-lg-4">
                                        <p>
                                            <label for="Documento_nome" class="required">Nome <span class="required">*</span></label>
        <?php echo CHtml::hiddenField('Documento[' . $index . '][id]', $documento->id); ?>
        <?php echo CHtml::textField('Documento[' . $index . '][nome]', $documento->nome, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control ')) ?>


                                        </p>
                                    </div>
                                    <div class="form-group  col-lg-4">
                                        <p>
                                            <label for="Documento_previsto">Tempo Previsto <span class="required">*</span></label>
        <?php echo CHtml::textField('Documento[' . $index . '][previsto]', $documento->previsto, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control previsto')) ?>

                                        </p>
                                    </div>
                                    <div class="form-group  col-lg-4">
                                        <p>
                                            <label for="Documento_disciplina_id" class="required">Disciplina <span class="required">*</span></label>
        <?php
        $fk_empresa = MetodosGerais::getEmpresaId();
        echo CHtml::dropdownlist('Documento[' . $index . '][fk_disciplina]', $documento->fk_disciplina, CHtml::listData(Disciplina::model()->findAll(array("condition" => "fk_empresa=$fk_empresa")), 'id', 'codigo'), array('empty' => Yii::t("smith", 'Selecione '), 'class' => 'chzn-select'))
        ?>
                                        </p>
                                    </div>
                                    <div class="buttons">
                                           <div style="float: right; ">
                                        <?php echo CHtml::Button('- Remover', array('id'=>'bt_add_doc','onclick'=>'rm_documento('.$index.');', 'class'=>'btn btn-danger')); ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php $index--;
            } ?>
<?php echo CHtml::hiddenField('next_index_documento', count($documentos)+1); ?>

                </div>
                <div id="associarDocumento" style="display : none">
                    <div class="form-group  col-lg-4">
                        <p>
                            <?php echo CHtml::hiddenField("indice", 0) ?>
                            <?php echo CHtml::label(Yii::t('smith', 'Documentos'), "documentos"); ?>
                            <?php echo CHtml::dropdownlist('documentos', 0, CHtml::listData(Documento::model()->findAll(array("order" => "nome", "condition" => "fk_empresa = $fk_empresa AND fk_contrato != $model->id")), 'id', 'nome'), array("class" => "chzn-select form-control input-sm m-bot15", "prompt" =>Yii::t("smith","Selecione "), "style" => "width:100%;")); ?>
                        </p>
                    </div>
                    <div class="form-group  col-lg-2" style="margin-top: 20px">
                        <input class="btn btn-success"  id="bt_adicionar_ambiente" onclick="associarDocumento();" value="Associar" type="button">
                    </div>
                    <div class="form-group  col-lg-6" style="margin-top: 20px">
                        <div class="chzn-container chzn-container-multi" style="width: 100%;" >
                            <ul class="chzn-choices" id="documentos_selecionados">
                                <li class="search-field" style="width: 100%;"><input type="text" value="" class="default" autocomplete="off" style="width: 100%;"></li>
                            </ul>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
<?php  ?>

        <div class="buttons">
            <div style="float: right; ">
<?php echo CHtml::button($model->isNewRecord ? Yii::t("smith", 'Salvar') : Yii::t("smith", 'Atualizar'), array('class' => 'btn btn-info submitForm','onclick'=>'validaFormObra();')); ?>
            </div>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScript('composicao_custos', 'baseUrl = ' . CJSON::encode(Yii::app()->baseUrl) . ';', CClientScript::POS_BEGIN);
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/documento_template.js', CClientScript::POS_END);
?>
<?php $this->endWidget(); ?>

<!-- form -->

<script>
    // $(function(){
    //     $('#inserir_documento').click(function(){

    //         $.ajax({
    //                 url: 'novoDocumento',
    //                 type: 'POST',
    //                 data: $("#pro-obra-form").serialize(),
    //                 success: function(data){
    //                    $('#yw0').find('ul').append('<li>'+data+'</li>');

    //                 }

    //             });
    //     });
    // });

    $(document).on('change', '#Contrato_data_final', function () {
        getPrevisto();
    });

    $(document).on('change', '#Contrato_data_inicio', function () {
        getPrevisto();
    });

    function getPrevisto() {
        var dataFinal = $('#Contrato_data_final').val().split('/');
        var dataInicio = $('#Contrato_data_inicio').val().split('/');
        dataFinal = new Date(dataFinal[2] + '-' + dataFinal[1] + '-' + dataFinal[0]);
        dataInicio = new Date(dataInicio[2] + '-' + dataInicio[1] + '-' + dataInicio[0]);
        var previsto = dataFinal - dataInicio;
        if (dataInicio > dataFinal && dataFinal != 0 && dataInicio != 0) {
            $('#Contrato_tempo_previsto').val('00:00:00');
        } else if (previsto == 0 || isNaN(previsto)) {
            $('#Contrato_tempo_previsto').val('00:00:00');
        } else {
            previsto = ((previsto / 1000) / 60) / 60 + ':00:00';
            $('#Contrato_tempo_previsto').val(previsto);
        }
    }

    function validaFormObra() {
        var valido = true;
        var inicio = $('#Contrato_data_inicio').val();
        var fim = $('#Contrato_data_final').val();

        if (!checkDateRange(inicio, fim)) {
            valido = false;
        }

        if (valido) {
            $('#pro-obra-form').submit();
            Loading.show();
        }
    }

    function checkDateRange(start, end) {
        var start2 = start.split("/");
        var end2 = end.split("/");
        var data = new Date();
        var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
        start2 = new Date(start2[2], start2[1] - 1, start2[0]);
        end2 = new Date(end2[2], end2[1] - 1, end2[0]);

        if (isNaN(start2)) {
            document.getElementById('message').innerHTML = "<?= Yii::t("smith", "A data inicial não é válida, por favor insira uma data válida!"); ?>";
            $('#btn_modal_open').click();
            $('#Contrato_tempo_previsto').val('00:00:00');
            return false;
        }

        if (isNaN(end2)) {
            document.getElementById('message').innerHTML = "<?= Yii::t("smith", "A data final não é válida, por favor insira uma data válida!"); ?>";
            $('#btn_modal_open').click();
            $('#Contrato_tempo_previsto').val('00:00:00');
            return false;
        }

        if (end2 < start2) {
            document.getElementById('message').innerHTML = "<?= Yii::t("smith", "A data inicial precisa ser anterior à data final!"); ?>";
            $('#btn_modal_open').click();
            $('#Contrato_tempo_previsto').val('00:00:00');
            return false;
        }

        return true;
    }


    function add_documento() {

        var index = parseInt($("#next_index_documento").val());
        var template_documento = '<div style="margin: 0 5px;" class="panel panel-default painel'+index+'"> \n\
                              <div class="panel-heading"> \n\
                                  <h4 class="panel-title"> \n\
                                      <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse'+index+'"> \n\
                                          <?= Yii::t("smith", "Documento"); ?>  \n\
                                      </a>\n\
                                  </h4> \n\
                              </div> \n\
                              <div style="height: 0px;" id="collapse'+index+'" class="panel-collapse collapse in">\n\
                                  <div class="panel-body"> \n\
                                  <div class="form-group  col-lg-4">\n\
                                    <p>\n\
                                        <label for="Documento_nome" class="required"><?= Yii::t("smith", "Nome"); ?> <span class="required">*</span></label>                \n\
                                        <input size="60" maxlength="256" class="form-control" name="Documento['+index+'][nome]" id="Documento_nome_'+index+'" type="text" />            \n\
                                    </p>\n\
                                    </div>\n\
                                    <div class="form-group  col-lg-4">\n\
                                        <p>\n\
                                            <label for="Documento_previsto"><?= Yii::t("smith", "Tempo Previsto"); ?> <span class="required">*</span></label>    \n\
                                            <input size="9" class="form-control previsto" id="Documento_previsto_'+index+'" name="Documento['+index+'][previsto]" type="text" />                \n\
                                        </p>\n\
                                    </div>\n\
                                    <div class="form-group  col-lg-4">\n\
                                    <p><label for="disciplina"><?= Yii::t("smith", "Disciplinas"); ?></label><span class="required">*</span>\n\
                                    <select class="chzn-select disciplinaDinamica" name="Documento[' + index + '][fk_disciplina]"  id="disciplina' + index + '" style="width: 100%;">\n\
                                    <option value=""><?= Yii::t("smith", "Selecione"); ?></option></select></p></div>\n\
                                    <div class="buttons" style="margin-bottom: 25px"><div style="float: right; "><input id="bt_add_doc" onclick="rm_documento('+index+');" class="btn btn-danger" name="yt1" type="button" value="- <?= Yii::t("smith", "Remover"); ?>" /></div></div></div></div></div></div><div style="clear: both"></div>';

        $('#ref').after(template_documento);
        $(".chzn-select").chosen({no_results_text: "N&atilde;o encontrado"});
        $(".previsto").mask("99:99:99",{placeholder:" "});
        $('#collapse'+(index-1)).removeClass("in");
        $("#next_index_documento").val(index+1);
        $.ajax({
            type: 'POST',
            data: $('pro-obra-form').serialize(),
            url: baseUrl + '/Contrato/getDisciplina/',
            success: function(data) {

                $("#disciplina" + index).each(function() {
                    if ($(this).val() == "")
                        $(this).html(data);

                });
                $("#disciplina" + index).prepend("<option value>Selecione</option>");
                $("#disciplina" + index).trigger("change");

            }
        });
    }
</script>
