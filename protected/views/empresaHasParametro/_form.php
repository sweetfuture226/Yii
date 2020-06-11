<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'empresaHasParametro-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid', 'onsubmit'=>"getValueOfOcioso();",),
)); ?>
<p><?= Yii::t("smith", 'Aqui você poderá cadastrar os parâmetros necessários para filtrar o tempo ausente do computador.') ?></p>
<br>

<?php echo $form->errorSummary($model); ?>

<header style="margin-top: 10px" class="panel-heading tab-bg-dark-navy-blue ">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#basico">Básico</a></li>
        <li class=""><a data-toggle="tab" href="#avancado">Avançado</a></li>
    </ul>
</header>
<div class="panel-body">
    <div class="tab-content">
        <div id="basico" class="tab-pane active">
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'horario_entrada'); ?>
                    <?php echo $form->textField($model, 'horario_entrada', array('size' => 60, 'class' => 'form-control previstoHM', 'maxlength' => 255)); ?>
                </p>
            </div>
            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'horario_saida'); ?>
                    <?php echo $form->textField($model, 'horario_saida', array('size' => 60, 'class' => 'form-control previstoHM', 'maxlength' => 255)); ?>
                </p>
            </div>

            <div style="clear: both"></div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'almoco_inicio'); ?>
                    <?php echo $form->textField($model, 'almoco_inicio', array('size' => 60, 'class' => 'form-control previstoHM', 'maxlength' => 255)); ?>
                </p>
            </div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'almoco_fim'); ?>
                    <?php echo $form->textField($model, 'almoco_fim', array('size' => 60, 'class' => 'form-control previstoHM', 'maxlength' => 255)); ?>
                </p>
            </div>
            <div style="clear: both"></div>

            <div class="form-group  col-lg-4">
                <p>
                    <?php echo $form->labelEx($model, 'moeda'); ?>
                    <?php echo $form->dropDownList($model, 'moeda', array(
                        'BRL' => 'Real',
                        'EUR' => 'Euro',
                        'USD' => 'Dólar'
                    ), array("class" => "chzn-select", "style" => "width:100%;")); ?>
                </p>
            </div>
            <div class="form-group  col-lg-4">
                <?php echo CHtml::label(Yii::t('smith', 'Tempo ausente do computador permitido'), 'ocioso'); ?>
                <div class="slider" data-min="1" data-max="30" data-value="<?= $model->tempo_ocio ?>" data-postfix=" minutos" id="slider-range-min" ></div>
                <div class="slider-info">
                    <?php echo $form->hiddenField($model, 'tempo_ocio', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                </div>
            </div>
        </div>
        <div id="avancado" class="tab-pane">
            <span>Permissão de acesso a gerência de contratos</span>
            <div class="checkboxes">
                <label class="has-js label_check" for="EmpresaHasParametro_permissao_contrato">
                    <?php echo $form->checkBox($model, 'permissao_contrato'); ?> Coordernador
                </label>
            </div>

        </div>
    </div>
</div>

<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::button($model->isNewRecord ? Yii::t("smith", 'Salvar') : Yii::t("smith", 'Atualizar'), array('class' => 'btn btn-info submitForm', 'onclick' => 'atualizarHorario()')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
<!-- form -->

<script>
    function getValueOfOcioso(argument) {
        $("#EmpresaHasParametro_tempo_ocio").val($("#slider-range-min").slider("option", "value"));
        return true;
    }

    function atualizarHorario() {
        var valido = true;
        $(this).css('border-color', '#e2e2e4');

        $('.previstoHM').each(function () {
            if (!validaHorario(this)) {
                valido = false;
                $(this).css('border-color', 'red');
            }
        });

        if (valido) {
            $('#empresaHasParametro-form').submit();
        } else {
            document.getElementById('message').innerHTML = "<?= Yii::t("smith", "Horário inválido. Favor inserir um horário entre 00:00 e 23:59"); ?>";
            $('#btn_modal_open').click();
        }
    }

    function validaHorario(element) {
        if ($(element).val() == "") return false;
        var splittime = $(element).val().split(":");
        if (splittime[0] > 23 || splittime[1] > 59) {
            return false;
        }
        return true;
    }

    $('.previstoHM').change(function () {
        if (!validaHorario(this)) {
            document.getElementById('message').innerHTML = "<?= Yii::t("smith", "Horário inválido. Favor inserir um horário entre 00:00 e 23:59"); ?>";
            $('#btn_modal_open').click();
            $(this).css('border-color', 'red');
        } else {
            $(this).css('border-color', '#e2e2e4');
        }
    });
</script>
