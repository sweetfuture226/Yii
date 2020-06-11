<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'metrica-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
));
?>
<?php $this->renderPartial('modalPreVisualizar'); ?>
<?php
$programas = CHtml::listData(ProgramaPermitido::model()->findAll(array('order' => 'TRIM(nome) ASC', 'condition' => 'fk_empresa = :empresa',
    'params' => array(':empresa' => MetodosGerais::getEmpresaId()))), 'nome', 'nome');
$sites = CHtml::listData(SitePermitido::model()->findAll(array('order' => 'TRIM(nome) ASC', 'condition' => 'fk_empresa = :empresa',
    'params' => array(':empresa' => MetodosGerais::getEmpresaId()))), 'nome', 'nome');
$arrayAplicacao = array_merge($programas, $sites);
asort($arrayAplicacao, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
?>
<?php echo $form->errorSummary($model); ?>

<?php
if (isset($model->id))
    echo $form->hiddenfield($model, 'id', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control'));
?>
    <div class="form-group  col-lg-4">
        <p>
            <?php echo $form->labelEx($model, 'titulo'); ?>
            <?php echo $form->textField($model, 'titulo', array('size' => 60, 'maxlength' => 40, 'class' => 'form-control')); ?>
        </p>
    </div>

    <div class="form-group  col-lg-4">
        <p>
            <?php echo $form->labelEx($model, 'atuacao'); ?>
            <?php echo $form->textField($model, 'atuacao', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
        </p>
    </div>

    <div class="form-group  col-lg-4">
        <p>
            <?php echo $form->labelEx($model, 'descricao'); ?>
            <?php echo $form->textArea($model, 'descricao', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'style' => 'resize: none')); ?>
        </p>
    </div>
    <div class="form-group  col-lg-4">
        <p>
            <?php
            echo CHtml::label(Yii::t('smith', 'Equipe'), 'equipe');
            echo CHtml::dropdownlist('equipe', 'equipe', CHtml::listData(Equipe::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa = :empresa', 'params' => array(':empresa' => MetodosGerais::getEmpresaId()))), 'id', 'nome'), array("class" => "chzn-select", "style" => "width:100%;", 'multiple' => true));
            ?>
        </p>
    </div>
    <div class="form-group  col-lg-8">
        <?php echo CHtml::label(Yii::t('smith', 'Colaboradores'), 'nome'); ?>
        <div class="chzn-container chzn-container-multi" style="width: 100%;">
            <select class="default" id="colaboradores_selecionados" name="Membros[]" multiple="multiple"
                    style="width: 100%;">
                
            </select>
        </div>
    </div>

    <div style="clear: both"></div>
    <fieldset>
        <legend><?= Yii::t('smith', 'Parte 1 - Aplicação e critério') ?></legend>
        <p></p>

        <div class="form-group  col-lg-4">
            <p>
                <?php
                echo $form->labelEx($model, 'programa');
                echo $form->dropdownlist($model, 'programa', $arrayAplicacao, array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione '), "style" => "width:100%;"));
                ?>
            </p>
        </div>


        <div class="form-group  col-lg-3">
            <p>
                <?php echo $form->labelEx($model, 'sufixo'); ?> <br>
                <?php echo $form->checkBox($model, 'sufixo', array('checked' => 'checked')); ?>
            </p>
        </div>


        <div style="clear: both"></div>


        <div class="form-group  col-lg-9">
            <p>
                <?php echo $form->labelEx($model, 'criterio'); ?>
                <?php echo $form->textField($model, 'criterio', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control firstname')); ?>
            </p>

            <div id='sugestoes_criterio' style='background-color: #dddddd'></div>
        </div>
        <div style="float: left; margin: 24px 5px; ">
            <?php echo CHtml::Button(Yii::t('smith', 'Pré-visualizar'), array('class' => 'btn btn-info submitForm', 'id' => 'inserirCriterios')); ?>
        </div>

        <div style="clear: both"></div>
        <div id="resultados" style="display: none; float: right;margin-right: 15px;">
            <span>Exibindo </span><span id="qtdResultados"></span> de <span id="totalCriterio"></span> resultados
        </div>
        <div style="clear: both"></div>
        <div id='sugestoes_pre_visualizacao'></div>
        <p><br></p>


        <div id="grid-pre-visualizar" style="margin: 0 10px 0 10px">

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'reuniao-participantes-grid',
                'summaryText' => '',
                'dataProvider' => $modelLogs->getGridUpdateForm($model->programa, $model->criterio, $model->sufixo, 0),
                'htmlOptions' => array('style' => 'max-height: 300px'),
                'columns' => array(
                    'descricao',
                    'usuario',
                    'duracao',
                    'data',
                ),
            ));
            ?>
        </div>
    </fieldset>

<div style="clear:both;"></div>
    <fieldset>
        <legend><?= Yii::t('smith', 'Parte 2 - Limites') ?></legend>
        <div class="form-group  col-lg-8">
            <?php
            echo CHtml::label(Yii::t("smith", 'Como será configurada a métrica por colaborador por dia?'), 'label', array('style' => 'margin-right : 3px'));
            ?>
        </div>
        <div style="clear: both"></div>

        <div class="form-group  col-lg-10" style="margin-bottom: 25px;">
            <div class="col-lg-4">
                <?= CHtml::checkBox("Metrica[meta_tempo]", !isset($model->id) ? true : $model->meta_tempo == 1 ? true : false, array('style' => 'margin-right: 2px')); ?>
                <?= CHtml::label(Yii::t("smith", 'Por tempo despendido na métrica'), 'meta_tempo') ?>
            </div>

            <div class="byTime col-lg-8">
                <div class="form-group  col-lg-4">
                    <p>
                        <label for="Metrica_previsto">
                            <?php echo Yii::t('smith', 'Tempo mínimo esperado'); ?> <span class="required">*</span>
                        </label>
                        <?php echo $form->textField($model, 'min_t', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control previstoHM', 'placeholder' => 'HH:MM')); ?>
                    </p>
                </div>
                <div class="form-group  col-lg-4">
                    <p>
                        <label for="Metrica_previsto">
                            <?php echo Yii::t('smith', 'Tempo máximo esperado'); ?> <span class="required">*</span>
                        </label>
                        <?php echo $form->textField($model, 'max_t', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control previstoHM', 'placeholder' => 'HH:MM')); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="form-group  col-lg-12">
            <div class="col-lg-4">
                <?= CHtml::checkBox("Metrica[meta_entrada]", !isset($model->id) ? true : $model->meta_entrada == 1 ? true : false, array('style' => 'margin-right: 2px', 'checked' => 'true')); ?>
                <?= CHtml::label(Yii::t("smith", 'Por quantidade de entradas de métricas'), 'meta_entrada') ?>
            </div>
            <div class="byQuantidade col-lg-8">
                <div class="form-group  col-lg-4">
                    <?php echo CHtml::label(Yii::t('smith', 'Meta'), 'nome'); ?> <span
                        class="required">*</span>
                    <div class="slider" data-min="1" data-max="100" data-value="<?php echo $model->meta ?>"
                         id="slider-range-min"></div>
                    <div class="slider-info">
                        <?php echo $form->textField($model, 'meta', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control quantidade', 'placeholder' => 'entradas')); ?>
                    </div>
                </div>
                <div class="form-group  col-lg-4">
                    <?php echo CHtml::label('Mínimo de entradas', 'nome'); ?> <span class="required">*</span>
                    <div class="slider" data-min="1" data-max="100" data-value="<?php echo $model->min_e ?>"
                         id="slider-range-min_e"></div>
                    <div class="slider-info">
                        <?php echo $form->textField($model, 'min_e', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control min quantidade', 'placeholder' => 'entradas')); ?>
                    </div>
                </div>
                <div class="form-group  col-lg-4">
                    <?php echo CHtml::label('Máximo de entradas', 'nome'); ?> <span class="required">*</span>
                    <div class="slider" data-min="1" data-max="100" data-value="<?php echo $model->max_e ?>"
                         id="slider-range-max_e"></div>
                    <div class="slider-info">
                        <?php echo $form->textField($model, 'max_e', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control max quantidade', 'placeholder' => 'entradas')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="form-group  col-lg-10">
            <?= CHtml::checkBox('Metrica[alerta]', !isset($model->id) ? true : $model->alerta == 1 ? true : false, array('class' => 'fire-toggle')); ?>
            <?= CHtml::label(Yii::t("smith", 'Quero ser notificado por email quando o limite mínimo ou máximo for alcançado.'), 'alerta_email') ?>
        </div>
    </fieldset>

 <?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
    <div class="buttons">
        <div style="float: right; margin-bottom: 15px ">
            <?php echo CHtml::button($model->isNewRecord ? Yii::t('smith', 'Salvar') : Yii::t('smith', 'Atualizar'), array('class' => 'btn btn-info submitForm', 'onclick' => 'validaForm();')); ?>
        </div>
    </div>

<?php
Yii::app()->clientScript->registerScript('composicao_custos', 'baseUrl = ' . CJSON::encode(Yii::app()->baseUrl) . ';', CClientScript::POS_BEGIN);
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/metricaJS.js', CClientScript::POS_END);
?>
<?php $this->endWidget(); ?>
