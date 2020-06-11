<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'pro-pessoa-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
));

Yii::app()->clientScript->registerScript('afterAjax', '
    function afterAjax(id, data) {

    }
');

$htmlOptions = array('size' => 60, 'maxlength' => 255, 'class' => 'form-control');
if (Yii::app()->controller->action->id == "update") {
    $htmlOptions['disabled'] = "disabled";
} ?>


<header class="panel-heading tab-bg-dark-navy-blue ">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#perfil"><?= Yii::t("smith", "Perfil") ?></a></li>
        <li class=""><a data-toggle="tab" href="#ferias"><?= Yii::t("smith", "Férias/Afastamentos") ?></a></li>
        <li class=""><a data-toggle="tab" href="#salario"><?= Yii::t("smith", "Históricos do colaborador") ?></a></li>
    </ul>
</header>
<div class="panel-body">
    <div class="tab-content">
        <?php echo $form->errorSummary($model); ?>

        <div id="perfil" class="tab-pane active">
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'nome'); ?>
                    <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control firstname', 'onkeyup' => 'maiuscula(".firstname")')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'sobrenome'); ?>
                    <?php echo $form->textField($model, 'sobrenome', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control lastname', 'onkeyup' => 'maiuscula(".lastname")')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                </p>
            </div>

            <div style="clear: both"></div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'salario'); ?>
                    <?php echo $form->textField($model, 'salario', array('class' => 'form-control valor')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'horas_semana'); ?>
                    <?php echo $form->textField($model, 'horas_semana', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control previstoHHM')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'valor_hora'); ?>
                    <?php echo $form->textField($model, 'valor_hora', array('class' => 'form-control valor')); ?>
                </p>
            </div>

            <div style="clear: both"></div>
            <div class="form-group col-lg-4">
                <p>
                    <?php echo CHtml::label(Yii::t('smith', 'Horário de entrada'), 'horario_entrada'); ?>
                    <?php echo CHtml::textField('horario_entrada', $horario_entrada, array('class' => 'form-control previstoHHM')); ?>
                </p>
            </div>
            <div class="form-group col-lg-4">
                <p>
                    <?php echo CHtml::label(Yii::t('smith', 'Horário de saída'), 'horario_saida'); ?>
                    <?php echo CHtml::textField('horario_saida', $horario_saida, array('class' => 'form-control previstoHHM')); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'ad'); ?>
                    <?php echo $form->textField($model, 'ad', $htmlOptions); ?>
                </p>
            </div>

            <div style="clear: both"></div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'fk_equipe');
                    echo $form->dropdownlist($model, 'fk_equipe', CHtml::listData(Equipe::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa =' . UserGroupsUser::model()->findByPk(Yii::app()->user->id)->fk_empresa)), 'id', 'nome'), array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione '), "style" => "width:100%;"));
                    ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <a style="margin-top: 20px" class="btn btn-success" data-toggle="modal" href="#novaEquipe"><i
                        class="icon-plus-sign"></i> <?= Yii::t("smith", "Nova") ?></a>
            </div>
        </div>
        <!-- ABA DE FERIAS -->
        <div id="ferias" class="tab-pane">
            <fieldset>
                <legend><?= Yii::t("smith", "Adicionar período de férias") ?></legend>
                <div class="form-group  col-lg-4">
                    <label for="feriasInicio"><?php echo Yii::t("smith", 'Início das férias') ?></label>
                    <?php echo CHtml::textField('feriasInicio', '', array('class' => 'date form-control ')); ?>
                </div>

                <div class="form-group  col-lg-4">
                    <label for="feriasFim"><?php echo Yii::t("smith", 'Retorno das férias') ?></label>
                    <?php echo CHtml::textField('feriasFim', '', array('class' => 'date form-control ')); ?>
                </div>

                <div class="form-group  col-lg-4">
                    <label for="feriasDesc"><?php echo Yii::t("smith", 'Descrição') ?></label>
                    <?php echo CHtml::textArea('feriasDesc', '', array('class' => 'form-control')); ?>
                </div>
                <div style="float: right;">
                    <?= CHtml::button('+ Adicionar', array('id' => 'add_ferias', 'class' => 'btn btn-success')); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t("smith", "Período de férias registradas") ?></legend>
                <div class="form-group  col-lg-12">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'pro-pessoa-ferias-grid',
                    'dataProvider'=>$modelFerias->search($model->id),
                    'filter'=>$modelFerias,
                    'afterAjaxUpdate' => 'afterAjax',
                    'columns'=>array(
                        array(
                            'name'=>'data_inicio',
                            'header'=> Yii::t("smith", "Início das férias"),
                            'value'=>'MetodosGerais::dataBrasileira($data->data_inicio)',
                        ),
                        array(
                            'name'=>'data_fim',
                            'header'=> Yii::t("smith", "Retorno das férias"),
                            'value'=>'MetodosGerais::dataBrasileira($data->data_fim)'
                        ),
                        array(
                            'name'=>'descricao',
                            'header'=> Yii::t("smith", "Descrição"),
                            'value'=>'$data->descricao',
                        ),
                        array(
                            'header' => Yii::t('smith','Ações'),
                            'class' => 'booster.widgets.TbButtonColumn',
                            'htmlOptions'=>array('style' => 'width: 8%; text-align: left;'),
                            'buttons' => array(
                                'delete' => array(
                                    'label' => 'Excluir',
                                    'click' => 'js:function(evt){
                                        evt.preventDefault();
                                        /*Your custom JS goes here :) */
                                        }',
                                    'options' => array('class' => 'btn btn-danger btn-margin-grid deletar'),
                                    'url' => 'Yii::app()->createUrl("ColaboradorHasFerias/delete", array("id"=>$data->id))',
                                ),
                            ),
                            'template'=>'{delete}',
                        ),
                    ),
                )); ?>
                    </div>
            </fieldset>
        </div>
        <!-- ABA DE SALARIO -->
        <div id="salario" class="tab-pane">
            <fieldset>
                <legend><?= Yii::t("smith", "Histórico de equipes") ?></legend>
                <div class="form-group  col-lg-12">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'pro-pessoa-salario-grid',
                        'dataProvider' => $modelEquipes->search(MetodosGerais::getEmpresaId(), $model->id),
                        'filter' => $modelEquipes,
                        'afterAjaxUpdate' => 'afterAjax',
                        'columns' => array(
                            array(
                                'name' => 'data_inicio',
                                'header' => Yii::t("smith", "Ano de inicio"),
                                'value' => 'MetodosGerais::dataBrasileira($data->data_inicio)',
                            ),
                            array(
                                'name' => 'fk_equipe',
                                'header' => Yii::t("smith", "Equipe"),
                                'value' => '$data->equipes->nome',
                            ),
                        ),
                    )); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t("smith", "Histórico de salários") ?></legend>
                <div class="form-group  col-lg-12">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'pro-pessoa-salario-grid',
                        'dataProvider' => $modelSalario->search(MetodosGerais::getEmpresaId(), $model->id),
                        'filter' => $modelSalario,
                        'afterAjaxUpdate' => 'afterAjax',
                        'columns' => array(
                            array(
                                'name' => 'data_inicio',
                                'header' => Yii::t("smith", "Data de inicio"),
                                'value' => 'MetodosGerais::dataBrasileira($data->data_inicio)',
                            ),
                            array(
                                'name' => 'valor',
                                'header' => Yii::t("smith", "Valor"),
                                'value' => 'MetodosGerais::float2real($data->valor)',
                            ),
                        ),
                    )); ?>
                </div>
            </fieldset>
        </div>
        <div class="buttons">
            <div style="float: right; margin-bottom: 15px ">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('smith', 'Salvar') : Yii::t('smith', 'Atualizar'), array('class' => 'btn btn-info submitForm')); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>
<!-- form -->


<!-- MODAL DE NOVA EQUIPE -->
<div class="modal fade" id="novaEquipe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Nova Equipe</h4>
            </div>
            <div class="modal-body">
                <div class="form-group  col-lg-12">
                    <p>
                        <?php echo CHtml::label(Yii::t('smith', 'Equipe'), 'nome'); ?>
                        <?php echo CHtml::textField('equipe_nome', '', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'id' => 'equipe_nome')); ?>
                    </p>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Fechar</button>
                <?php echo CHtml::button('Salvar', array('class' => 'btn btn-info submitForm', 'onclick' => 'salvarEquipe();')); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('#add_ferias').on('click', function () {
        $.ajax({
            url: baseUrl + "/ColaboradorHasFerias/FeriasAjax",
            type: 'POST',
            data: {
                feriasInicio: $("#feriasInicio").val(),
                feriasFim: $("#feriasFim").val(),
                pessoa: <?= $model->id ?> ,
                descricao: $("#feriasDesc").val()
            },
            success: function (data) {
                swal("", "Cadastro de férias inserida com sucesso.", "success");
                $("#feriasInicio").val('');
                $("#feriasFim").val('');
                $("#feriasDesc").val('');
                $.fn.yiiGridView.update("pro-pessoa-ferias-grid");
            }
        }).fail(function () {
            swal("", "O cadastro de férias necessita uma data de início e fim", "error");
        });
    });

    function salvarEquipe() {
        var equipe = $("#equipe_nome").val();
        $.ajax({
            url: baseUrl + "/equipe/createAjaxEquipe",
            type: 'POST',
            data: {equipe: equipe},
            success: function (data) {
                $("#equipe_nome").val('');
                $('#novaEquipe').modal('hide');
                $("#ProPessoa_fk_equipe").empty();
                $("#ProPessoa_fk_equipe").append(data);
                $("#ProPessoa_fk_equipe").trigger("change");
            },
        });
    }

    function maiuscula(id) {
        //palavras para ser ignoradas
        var wordsToIgnore = ["DOS", "DAS", "de", "do", "Dos", "Das"],
            minLength = 2;
        var str = $(id).val();
        var getWords = function (str) {
            return str.match(/\S+\s*/g);
        }
        $(id).each(function () {
            var words = getWords(this.value);
            $.each(words, function (i, word) {
                // somente continua se a palavra nao estiver na lista de ignorados
                if (wordsToIgnore.indexOf($.trim(word)) == -1 && $.trim(word).length > minLength) {
                    words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
                } else {
                    words[i] = words[i].toLowerCase();
                }
            });
            this.value = words.join("");
        });
    }
    ;


    $('#Colaborador_salario').blur(function () {
        var horaSemana = convertHora($('#Colaborador_horas_semana').val());
        var salario = string2Float($('#Colaborador_salario').val());
        console.log(horaSemana + '-' + salario);
        if (!isNaN(horaSemana)) {
            var horaMensal = horaSemana * 4;
            var total = salario / horaMensal;
            $('#Colaborador_valor_hora').val(total.toFixed(2).replace('.', ','));
        }
    });

    $('#Colaborador_horas_semana').blur(function () {
        var horaSemana = convertHora($('#Colaborador_horas_semana').val());
        var salario = string2Float($('#Colaborador_salario').val());
        var valorHora = string2Float($('#Colaborador_valor_hora').val());
        console.log(horaSemana + '-' + salario + '-' + valorHora);
        if (!isNaN(valorHora)) {
            var total = valorHora * horaSemana * 4;
            $('#Colaborador_salario').val(float2string(total));
        } else if (!isNaN(salario)) {
            var horaMensal = horaSemana * 4;
            var total = salario / horaMensal;
            $('#Colaborador_valor_hora').val(total.toFixed(2).replace('.', ','));
        }
    });

    $('#Colaborador_valor_hora').blur(function () {
        var horaSemana = convertHora($('#Colaborador_horas_semana').val());
        var valorHora = string2Float($('#Colaborador_valor_hora').val());
        console.log(horaSemana + '-' + valorHora);
        if (!isNaN(horaSemana) && !isNaN(valorHora)) {
            var total = valorHora * horaSemana * 4;
            $('#Colaborador_salario').val(total.toFixed(2).replace('.', ','));
        }
    });


    function convertHora(valor) {
        var a = valor.split(':');
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60;
        return parseFloat(seconds / 3600);
    }

    function string2Float(number) {
        return parseFloat(number.replace('R$ ', '').replace(".", "").replace(",", "."));
    }
</script>
