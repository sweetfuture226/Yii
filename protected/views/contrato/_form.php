<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'pro-obra-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid', 'enctype' => 'multipart/form-data',),
));

?>
<?php echo $form->errorSummary($model); ?>

<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'nome'); ?>
        <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control ')); ?>
    </p>
</div>

<div class="form-group col-lg-4" title="Digite e pressione enter para inserir mais de um código">
    <p>
        <?php echo $form->labelEx($model, 'codigo'); ?>
        <?php echo $form->textField($model, 'codigo',
            array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'title' => Yii::t('smith', ''))); ?>
    </p>
</div>

<div class="form-group col-lg-4">
    <p>
        <label for="Documento_disciplina_id" class="required">
            <?php echo Yii::t("smith", 'Coordenador') ?><span class="required">*</span>
        </label>
        <?php
        $fk_empresa = MetodosGerais::getEmpresaId();
        echo $form->dropdownlist($model, "coordenador",
            CHtml::listData(UserGroupsUser::model()->findAll(array("condition" => $condicao)), 'id', 'username'),
            array('class' => 'chzn-select')) ?>
    </p>
</div>
<div style="clear: both"></div>
<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'data_inicio'); ?>
        <?php echo $form->textField($model, 'data_inicio', array('class' => 'date form-control')); ?>
    </p>
</div>
<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'data_final'); ?>
        <?php echo $form->textField($model, 'data_final', array('class' => 'date form-control')); ?>
    </p>
</div>
<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'valor'); ?>
        <?php echo $form->textField($model, 'valor', array('class' => 'form-control valor')); ?>
    </p>
</div>
<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'moeda'); ?>
        <?php echo $form->dropDownList($model, 'moeda', array(
            'BRL' => 'Real',
            'EUR' => 'Euro',
            'USD' => 'Dólar'
        ), array("class" => "chzn-select", "style" => "width:100%;")); ?>
    </p>
</div>
<div class="form-group col-lg-4">
    <p><?php echo CHtml::hiddenField('Contrato[finalizada]', 0); ?></p>
</div>
<?php if (($this->action->id == 'create') || ($this->action->id == 'update' && empty($documentos))) { ?>
    <div style="clear: both"></div>
    <div class="form-group  col-lg-4">
        <?php
        echo CHtml::label(Yii::t("smith", 'Como serão cadastrados os documentos do projeto?'), 'label', array('style' => 'margin-right : 3px'));
        ?>
    </div>
    <div class="form-group col-lg-4">
        <?php echo CHtml::radioButtonList("documento", ($this->action->id == 'update' && empty($documentos)) ? 'nao' : 'selecionado',
            array('ism' => Yii::t("smith", 'Inserção manual'), 'ise' => Yii::t("smith", 'Importação planilha'),
                'nao' => Yii::t("smith", 'Não haverá lista de documentos')), array('class' => 'fire-toggle')); ?>
    </div>
<?php } ?>
<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'receber_email'); ?>
        <?php echo $form->checkBox($model, 'receber_email', array('checked' => 'checked')); ?>
    </p>
</div>
<div style="clear: both"></div>
<?php if (($this->action->id == 'create') || ($this->action->id == 'update' && empty($documentos))) { ?>
<div class="importManual" style="display: none;">
    <?php } else { ?>
    <div class="importManual">
        <?php } ?>
        <?php echo CHtml::hiddenField('next_index_documento', 0); ?>
        <div>
            <?php if ($this->action->id == 'create') { ?>
                <legend><?= Yii::t("smith", 'Inserção manual'); ?></legend>
            <?php } else { ?>
                <legend><?= Yii::t("smith", 'Lista de documentos'); ?></legend>
            <?php } ?>
            <div class="form-group col-lg-4">
                <a style="" class="btn btn-success" data-toggle="modal" href="#novoDocumento"><i
                        class="icon-plus-sign"></i> <?= Yii::t('smith', 'Adicionar Documento'); ?></a>
                <a style="" class="btn btn-success" data-toggle="modal" href="#novaDisciplina"><i
                        class="icon-plus-sign"></i> <?= Yii::t('smith', 'Adicionar Disciplina'); ?></a>
            </div>
            <div style="clear: both"></div>
            <div id="ref"></div>
            <div class="panel panel-default painel1" style="margin: 0 5px;">
                <?php
                if ($this->action->id == 'update')
                    $docDataProvider = new CActiveDataProvider('Documento', array('pagination' => array('pageSize' => 100), 'criteria' => array('condition' => 'fk_contrato =' . $model->id)));
                else
                    $docDataProvider = new CActiveDataProvider('Documento', array('pagination' => array('pageSize' => 100), 'criteria' => array('condition' => 'id = 0')));
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'doc-grid',
                    'dataProvider' => $docDataProvider,
                    'columns' => array(
                        'nome',
                        'previsto',
                        array(
                            'name' => 'fk_disciplina',
                            'value' => 'Disciplina::model()->findByPk($data->fk_disciplina)->codigo',
                        ),
                        array(
                            'name' => 'finalizado',
                            'value' => '(!$data->finalizado)?"Em andamento":"Finalizado"'
                        ),
                        array(
                            'header' => Yii::t("smith", 'Ações'),
                            'class' => 'CButtonColumn',
                            'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
                            'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
                            'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
                            'htmlOptions' => array('style' => 'width:11%; text-align: right;'),
                            'template' => '{Edit}{Erase}{Finalizar}',
                            'buttons' => array(
                                'Finalizar' => array(
                                    'url' => '$data->id',
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/images/icons/close.png',
                                    'visible' => '!$data->finalizado',
                                    'options' => array('style' => 'cursor: pointer', 'onclick' => 'return closeDoc(this);')
                                ),
                                'Edit' => array(
                                    'url' => '',
                                    'visible' => '!$data->finalizado',
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
                                    'options' => array('style' => 'cursor: pointer', 'onclick' => 'editDoc(this)')
                                ),
                                'Erase' => array(
                                    'url' => '',
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
                                    'options' => array('style' => 'cursor: pointer', 'onclick' => 'removerDoc(this)')
                                )
                            )
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>
    <div class="importCSV" style="display: none;">
        <fieldset>
            <legend><?php echo Yii::t('smith', 'Importar Lista de Documentos'); ?></legend>
            <?php echo $this->renderPartial("importCSV", array('condicao' => $condicao)); ?>
        </fieldset>
    </div>
    <div style="clear: both"></div>
    <?php if (($this->action->id == 'create') || ($this->action->id == 'update' && !empty($documentos))) { ?>
    <div class="tempoPrevisto" style="display: none;">
        <?php } else { ?>
        <div class="tempoPrevisto">
            <?php } ?>
            <fieldset>
                <legend><?php echo Yii::t('smith', 'Tempo Previsto'); ?></legend>
                <div class="form-group col-lg-4">
                    <?php echo $form->labelEx($model, 'tempo_previsto'); ?>
                    <?php echo $form->textField($model, 'tempo_previsto', array('class' => 'form-control')); ?>
                </div>
            </fieldset>
        </div>
        <div class="buttons">
            <div style="float: right; margin-top: 10px;">
                <?php echo CHtml::button($model->isNewRecord ? Yii::t("smith", 'Salvar') : Yii::t("smith", 'Atualizar'), array('class' => 'btn btn-info submitForm', 'onclick' => 'validaFormObra();')); ?>
            </div>
        </div>
        <?php
        Yii::app()->clientScript->registerScript('composicao_custos', 'baseUrl = ' . CJSON::encode(Yii::app()->baseUrl) . ';', CClientScript::POS_BEGIN);
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/documento_template.js', CClientScript::POS_END);
        ?>
        <?php
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/system/formContrato.js', CClientScript::POS_END);
        ?>
        <?php $this->endWidget(); ?>
        <?php $this->renderPartial('modalNovoDocumento'); ?>
        <?php $this->renderPartial('modalNovaDisciplina'); ?>
        <?php $this->renderPartial('modalEditarDocumento'); ?>
        <?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
        <!-- form -->
        <script>
            jQuery(document).ready(function ($) {

                $('.form-group').tooltip();
                $('#Contrato_codigo').tokenfield({createTokensOnBlur: true});
                $('#Contrato_codigo').on('tokenfield:createtoken', function (event) {
                    var existingTokens = $(this).tokenfield('getTokens');
                    $.each(existingTokens, function (index, token) {
                        if (token.value === event.attrs.value)
                            event.preventDefault();
                    });
                });
            });


            function checkDateRange(start, end) {
                var start2 = start.split("/");
                var end2 = end.split("/");
                var data = new Date();
                var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
                start2 = new Date(start2[2], start2[1] - 1, start2[0]);
                end2 = new Date(end2[2], end2[1] - 1, end2[0]);
                if (isNaN(start2)) {
                    document.getElementById('message').innerHTML = "<?= Yii::t("smith", "A data inicial não é válida, por favor insira uma data válida!"); ?>";
                    $('#Contrato_tempo_previsto').val('00:00:00');
                    $('#btn_modal_open').click();
                    return false;
                }
                if (isNaN(end2)) {
                    document.getElementById('message').innerHTML = "<?= Yii::t("smith", "A data final não é válida, por favor insira uma data válida!"); ?>";
                    $('#Contrato_tempo_previsto').val('00:00:00');
                    $('#btn_modal_open').click();
                    return false;
                }
                if (end2 < start2) {
                    document.getElementById('message').innerHTML = "<?= Yii::t("smith", "A data inicial precisa ser anterior à data final!"); ?>";
                    $('#Contrato_tempo_previsto').val('00:00:00');
                    $('#btn_modal_open').click();
                    return false;
                }
                return true;
            }
        </script>
        <style>
            [data-tip] {
                position: relative;

            }

            [data-tip]:before {
                content: '';
                /* hides the tooltip when not hovered */
                display: none;
                content: '';
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-bottom: 5px solid #1a1a1a;
                position: absolute;
                top: 30px;
                left: 35px;
                z-index: 8;
                font-size: 0;
                line-height: 0;
                width: 0;
                height: 0;
            }

            [data-tip]:after {
                display: none;
                content: attr(data-tip);
                position: absolute;
                top: 35px;
                left: 0px;
                padding: 5px 8px;
                background: #1a1a1a;
                color: #fff;
                z-index: 9;
                font-size: 0.75em;
                height: 18px;
                line-height: 18px;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
                white-space: nowrap;
                word-wrap: normal;
            }

            [data-tip]:hover:before,
            [data-tip]:hover:after {
                display: block;
            }
        </style>