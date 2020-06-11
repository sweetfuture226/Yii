<?php
$this->breadcrumbs = array(
    Yii::t('smith', 'Colaborador'),
);

if (Yii::app()->user->group > "6") {
    Yii::app()->clientScript->registerScript('button_create', '
    $(".content-bod").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("Colaborador/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t('smith', 'Adicionar') . '</button>\');
');
}

Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){
        $(".odd td:not(.button-column), .even td:not(.button-column)").click(function(){
            url = $(this).parent().children().children(".view").attr("href");
            Boxy.load(url, {title:"Dados"});
        });
    }
');

Yii::app()->clientScript->registerScript('afterAjax', '
    function afterAjax(id, data) {
        $("#datepicker_for_nascimento").datepicker();
    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('pro-pessoa-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>

<?php //echo CHtml::button(Yii::t('smith', 'Enviar Planilha'), array('class' => 'btn btn-info submitForm', 'id' => 'planilha_colaboradores', 'style' => 'float: right')); ?>

<p><?= Yii::t("smith", 'Aqui você poderá visualizar os colaboradores que estão sendo monitorados.') ?></p><br>

<div class="dataTables_length">
    <label>
    <?php
    $this->widget('application.extensions.PageSize.PageSize', array(
        'mGridId' => 'pro-pessoa-grid', //Gridview id
        'mPageSize' => @$_GET['pageSize'],
        'mDefPageSize' => Yii::app()->params['defaultPageSize'],
        'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
    ));
    ?>
    </label>
</div>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'pro-pessoa-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'nome',
            'value' => '$data->nome . " " . $data->sobrenome',
        ),

        'email',
        'horas_semana',
        'ad',
        array(
            'name' => 'fk_equipe',
            'value' => 'isset($data->equipes->nome) ? $data->equipes->nome : ""',
            'filter' => '',
        ),
        array(
            'header' => Yii::t('smith', 'Ações'),
            'class'=>'booster.widgets.TbButtonColumn',

            'htmlOptions' => array('style' => 'width: 12%; text-align: left;'),
            'buttons' => array(
                'inativar' => array(
                    'label' => Yii::t('smith', 'Inativar colaborador'),     // text label of the button
                    'options' => array('class' => 'btn btn-success btn-margin-grid inativar', 'data-id' => '$data->id'),
                   // 'url' => '$data->id',
                    'icon' => 'fa fa-user',
                    'visible' => '$data->status'

                ),
                'reativar' => array(
                    'label' => Yii::t('smith', 'Reativar colaborador'),     // text label of the button
                    'options' => array('class' => 'btn btn-danger btn-margin-grid reativar', 'data-id' => '$data->id'),
                    'icon' => 'fa fa-user',
                   // 'url' => '$data->id',
                    'visible' => '!$data->status'

                ),
                'update' => array(
                    'label' => 'Editar',
                    'options' => array('class' => 'btn btn-orange btn-margin-grid'),
                ),
            ),
            'template' => '{reativar}{inativar}{update}',
        ),
    ),
)); ?>

<script>
    $(document).on('click', ".inativar", function () {
        $('#id_pessoa').val($(this).data('id'));
        swal({
           title: "<?= Yii::t("smith", 'Deseja realmente deixar este colaborador inativo?') ?>",
            text: "",
            type: "warning",
            showCancelButton: true,
           showLoaderOnConfirm: true,
            confirmButtonColor: "#3cb371",
           confirmButtonText: "<?= Yii::t("smith", 'Confirmar') ?>",
           closeOnConfirm: false
       }, function(){
        var id_pessoa = $("#id_pessoa").val();
        $.ajax({
            type: 'POST',
            data: {pessoa: id_pessoa},
            url: baseUrl + '/colaborador/inativar',
            success: function (data) {
                $.fn.yiiGridView.update('pro-pessoa-grid');
                swal("Sucesso!", "Colaborador inativado com sucesso.", "success");
            }
        });
      });
    });

    $(document).on('click', ".reativar", function () {
        $('#id_pessoa').val($(this).data('id'));
        swal({
           title: "<?= Yii::t("smith", 'Deseja realmente reativar este colaborador?') ?>",
            text: "",
            type: "warning",
            showCancelButton: true,
           showLoaderOnConfirm: true,
            confirmButtonColor: "#3cb371",
           confirmButtonText: "<?= Yii::t("smith", 'Confirmar') ?>",
           closeOnConfirm: false
       }, function(){
        var id_pessoa = $("#id_pessoa").val();
        $.ajax({
            type: 'POST',
            data: {pessoa: id_pessoa},
            url: baseUrl + '/colaborador/reativar',
            success: function (data) {
                $.fn.yiiGridView.update('pro-pessoa-grid');
                swal("Sucesso!", "Colaborador inativado com sucesso.", "success");
            }
        });
      });
    });
</script>


<a id="btn_modal_inativar_pessoa" class="invisible" data-toggle="modal" href="#modal_inativar_pessoa">Dialog</a>
<div class="modal fade" id="modal_inativar_pessoa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Yii::t("smith", 'Inativar colaborador') ?></h4>
            </div>
            <div class="modal-body">

                <?= Yii::t("smith", 'Deseja realmente deixar este colaborador inativo?') ?>

            </div>
            <div class="modal-footer">
                <button id="btn_fechar_modal_inativar_pessoa" data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t("smith", 'Cancelar') ?></button>
                <button id="btn_confirmar_modal_inativar_pessoa" class="btn btn-success"
                        type="button"><?= Yii::t("smith", 'Confirmar') ?></button>
                <input type='hidden' id='id_pessoa' value="">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_planilha_colaboradores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Yii::t("smith", 'Planilha colaboradores') ?></h4>
            </div>

            <div class="modal-body">
                <?php
                $colaboradorEmpresa = Yii::app()->createController('empresa')[0];
                $form = $colaboradorEmpresa->beginWidget('CActiveForm', array(
                    'id' => 'colaborador-form',
                    'enableAjaxValidation' => false,
                    'action' => 'empresa/parametrizar',
                    'htmlOptions' => array('class' => 'form valid', 'enctype' => 'multipart/form-data'),
                ));
                ?>

                <p class="note"><?= Yii::t('wizard', 'Importe o arquivo csv com o nome dos colaboradores e preencha todos os campos.') ?></p>
                <p class="note"><?php echo CHtml::link(Yii::t("wizard", 'Clique aqui para baixar a planilha de parametrização dos colaboradores'), array('empresa/GetPlanilha'), array('id' => 'getPlanilha')); ?></p>

                <div class="form-group col-lg-4">
                    <?php echo CHtml::label(Yii::t('smith', 'Arquivo CSV'), 'Documento_file'); ?>
                    <?php echo CHtml::hiddenField('nameFile', '', array('id' => 'file')); ?>
                    <?php $colaboradorEmpresa->widget('ext.EAjaxUpload.EAjaxUpload', array('id' => 'planilhaPrametrizacao',
                        'config' => array(
                            'action' => Yii::app()->createUrl('colaborador/upload'),
                            'allowedExtensions' => array("xls"),//array("jpg","jpeg","gif","exe","mov" and etc...
                            'sizeLimit' => 10 * 1024 * 1024,// maximum file size in bytes
                            'minSizeLimit' => 1,// minimum file size in bytes
                            'onComplete' => "js:function(id, fileName, responseJSON){"
                                . " $('#file').val(responseJSON.filename);"
                                . "}",
                        )
                    )); ?>
                </div>
                <?php echo CHtml::hiddenField('fk_empresa', MetodosGerais::getEmpresaId()); ?>
                <div style="clear: both"></div>
            </div>

            <div class="modal-footer">
                <div class="buttons">
                    <div style="float: right; ">
                        <?php echo CHtml::submitButton(Yii::t('smith', 'Salvar'), array('class' => 'btn btn-info submitForm')); ?>
                    </div>
                </div>
                <?php $colaboradorEmpresa->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#planilha_colaboradores', function () {
        $('#modal_planilha_colaboradores').modal();
    });

    $(window).load(function () {
        document.getElementById("getPlanilha").setAttribute("href", baseUrl + '/empresa/GetPlanilha/' + <?php echo MetodosGerais::getEmpresaID(); ?>);
    });
</script>