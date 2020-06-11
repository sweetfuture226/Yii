<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'empresa-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
)); ?>

<?php echo $form->errorSummary($model); ?>

<fieldset>
    <legend>Cliente</legend>
    <div class="form-group  col-lg-4">
        <p>
            <?php echo CHtml::label(Yii::t('smith', 'Nome da empresa'), 'nome'); ?>
            <?php echo CHtml::textField('nome_empresa', $model->nome, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
        </p>

    </div>
    <div class="form-group col-lg-4">
        <?php echo $form->labelEx($model, 'responsavel'); ?>
        <?php echo $form->textField($model, 'responsavel', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
    </div>

    <div class="form-group col-lg-4">
        <?php echo $form->labelEx($model, 'telefone'); ?>
        <?php echo $form->textField($model, 'telefone', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control telefone')); ?>
    </div>
    <div style="clear: both"></div>
    <div id="email" class="form-group  col-lg-4">

        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'data-original-title' => 'Email já cadastrado', 'onchange' => 'validaEmpresa(this,"email");')); ?>

    </div>

    <div id="username" class="form-group  col-lg-4">
        <?php echo CHtml::label(Yii::t('smith', 'Login'), 'nome'); ?>
        <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'data-original-title' => 'Usuário já cadastrado', 'onchange' => 'validaEmpresa(this,"username");')); ?>

    </div>


    <div class="form-group  col-lg-4">
        <p>
            <?php echo $form->labelEx($model, 'colaboradores_previstos'); ?>
            <?php echo $form->textField($model, 'colaboradores_previstos', array('size' => 60, 'class' => 'form-control')); ?>
        </p>
    </div>
</fieldset>

<fieldset>
    <legend>Revenda</legend>
    <div class="form-group  col-lg-6">
        <div class="col-lg-8">
            <?php echo CHtml::label('Revenda', 'revenda'); ?>
            <?php echo CHtml::dropDownList('revenda', $modelRevenda->id, CHtml::listData(Revenda::model()->findAll(), 'id', 'nome'), array('class' => 'chzn-select revendaDrop', 'empty' => 'Selecione')); ?>

        </div>
        <div class="col-lg-1">
            <a style="margin-top: 20px" class="btn btn-xs btn-success" data-toggle="modal"
               href="#createRevenda"> <?= Yii::t("smith", "Nova revenda") ?></a>
        </div>
    </div>

    <div class="form-group  col-lg-6">
        <div class="col-lg-8">
            <?php echo CHtml::label('Responsável', 'revenda'); ?>
            <?php echo CHtml::dropDownList('responsavel', $modelContato->id, array(), array('class' => 'chzn-select', 'empty' => 'Selecione')); ?>
            <?php echo CHtml::hiddenField('contato_id', $modelContato->id); ?>
        </div>
        <div class="col-lg-1">
            <a style="margin-top: 20px" class="btn btn-xs btn-success" data-toggle="modal"
               href="#createContato"> <?= Yii::t("smith", "Adicionar contato") ?></a>
        </div>
    </div>
    <div class="form-group  col-lg-4">
        <div class="col-lg-6">
            <?php echo CHtml::label('Duração POC', 'duracao_poc'); ?>
            <?php echo CHtml::dropDownList('duracao', $modelRevendaHasPoc->duracao, (array('15' => '15 dias', '30' => '30 dias')), array('class' => 'chzn-select', 'empty' => 'Selecione')); ?>
        </div>
    </div>
</fieldset>

<div class="col-lg-4">
    <?= CHtml::checkBox("Empresa[envia_email]", false, array('style' => 'margin-right: 2px', 'checked' => 'true')); ?>
    <?= CHtml::label(Yii::t("smith", 'Enviar email para o cliente'), 'meta_entrada') ?>
</div>


<div class="buttons">
    <div style="float: right; margin-bottom: 15px">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('smith', 'Salvar') : Yii::t('smith', 'Atualizar'), array('class' => 'btn btn-info submitForm')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
<!-- form -->
<?php
$this->renderPartial('modalCreateRevenda');
$this->renderPartial('modalCreateContato');
?>

<script>
    $(document).ready(function () {
        var id = $("#revenda").val();
        var contato = $("#contato_id").val();
        if (id != "") {
            $('#revenda_contato option[value="' + id + '"]').attr('selected', 'selected');
            $('#revenda_contato').trigger('change');
            loadContato(id, contato);
        }
    });

    $("#revenda").on('change', function () {
        $('#revenda_contato option[value="' + $(this).val() + '"]').attr('selected', 'selected');
        $('#revenda_contato').trigger('change');
        loadContato($(this).val());

    });

    function loadContato(id, contato = '') {
        $.ajax({
            type: 'POST',
            data: {'id': id, 'contato': contato},
            url: baseUrl + '/empresa/loadContato',
            success: function (data) {
                $("#responsavel").empty();
                $("#responsavel").append(data);
                $("#responsavel").trigger("change");
            }
        })
    }
    function validaEmpresa(campo, tipo) {
        $.ajax({
            type: 'POST',
            data: {'campo': campo.value, 'tipo': tipo},
            url: baseUrl + '/empresa/validaNovaEmpresa',
            success: function (data) {
                $("#" + tipo).removeClass('has-error');
                $("#" + tipo).addClass('has-success');
                $('input[type="submit"]').prop('disabled', false);
            },
            error: function () {
                $("#" + tipo).removeClass('has-success');
                $("#" + tipo).addClass('has-error');
                $('input[type="submit"]').prop('disabled', true);
            }
        });
    }
</script>
