<?php
$this->breadcrumbs = array(
    Yii::t('smith', 'Empresas'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("Empresa/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t('smith', 'Adicionar') . '</button>\');
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
            $.fn.yiiGridView.update('empresa-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>


<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'empresa-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>

<?php
$this->widget('ext.widgets.loading.LoadingWidget');

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'empresa-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        'nome',
        'serial',
        'email',
        array(
            'header' => Yii::t('smith', 'Ações'),
            'class' => 'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width:18%; text-align: right;'),
            'buttons' => array(
                'update' => array(
                    'label' => 'Editar',
                    'options' => array('class' => 'btn btn-orange btn-margin-grid'),
                ),
                'delete' => array(
                    'label' => 'Excluir',
                    'click' => 'js:function(evt){
                                        evt.preventDefault();
                                        /*Your custom JS goes here :) */
                                        }',
                    'options' => array('class' => 'btn btn-danger btn-margin-grid deletar'),
                    'url' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
                ),
                'viewInfo' => array(
                    'label' => Yii::t('smith', 'Visualizar info da POC'),
                    'options' => array('class' => 'btn btn-info btn-margin-grid viewInfo', 'data-id' => '$data->id'),
                    'icon' => 'fa fa-info-circle'
                ),
                'inativar' => array(
                    'label' => Yii::t('smith', 'Desativar empresa'),
                    'options' => array('class' => 'btn btn-success btn-margin-grid inativar', 'data-id' => '$data->id'),
                    'icon' => 'fa fa-user',
                    'visible' => '$data->ativo'

                ),
                'reativar' => array(
                    'label' => Yii::t('smith', 'Reativar empresa'),
                    'options' => array('class' => 'btn btn-default btn-margin-grid reativar', 'data-id' => '$data->id'),
                    'icon' => 'fa fa-user',
                    'visible' => '!$data->ativo'

                ),
            ),
            'template' => '{reativar}{inativar}{viewInfo}{update}{delete}',
        ),
    ),
));


Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>


<script>
    $(document).on('click', ".inativar", function () {
        $('#id_empresa').val($(this).data('id'));
        $('#status').val(0);
        swal({
            title: "<?= Yii::t("smith", 'Deseja desativar esta empresa?') ?>",
            text: "",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?= Yii::t("smith", 'Confirmar') ?>",
            closeOnConfirm: false
        }, function () {
            var id_empresa = $("#id_empresa").val();
            var status = $("#status").val();
            $.ajax({
                type: 'POST',
                data: {id_empresa: id_empresa, 'status': status},
                url: baseUrl + '/empresa/desativar',
                success: function (data) {
                    swal("Sucesso!", "Empresa desatovada com sucesso.", "success");
                    $.fn.yiiGridView.update('empresa-grid');
                }

            });
        });
    });

    $(document).on('click', ".reativar", function () {
        $('#id_empresa').val($(this).data('id'));
        $('#status').val(1);
        swal({
            title: "<?= Yii::t("smith", 'Deseja reativar esta empresa?') ?>",
            text: "",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?= Yii::t("smith", 'Confirmar') ?>",
            closeOnConfirm: false
        }, function () {
            var id_empresa = $("#id_empresa").val();
            var status = $("#status").val();
            console.log(id_empresa, status);
            $.ajax({
                type: 'POST',
                data: {id_empresa: id_empresa, 'status': status},
                url: baseUrl + '/empresa/desativar',
                success: function (data) {
                    swal("Sucesso!", "Empresa reativada com sucesso.", "success");
                    $.fn.yiiGridView.update('empresa-grid');
                }

            });
        });
    });
</script>

<script>
    $('#empresa-grid a.viewInfo').live('click', function () {
        var obj = $(this).parent().parent();
        var empresa = obj.children(':first-child').text();
        var email = obj.children(':nth-child(3)').text();
        $.ajax({
            type: 'POST',
            data: {id: $(this).data('id')},
            dataType: 'JSON',
            url: baseUrl + '/empresa/viewInfoPoc',
            success: function (data) {
                $("#empresa_nome").html(empresa);
                $("#empresa_email").html(email);
                $("#empresa_responsavel").html(data.empresa_responsavel);
                $("#empresa_telefone").html(data.empresa_telefone);
                $("#empresa_logo").attr('src', data.empresa_logo);
                $("#quantidade_maquinas").html(data.quantidade_maquinas);
                $("#duracao").html(data.duracao);
                $("#revenda_nome").html(data.revenda_nome);
                $("#revenda_responsavel").html(data.revenda_responsavel);
                $("#revenda_email").html(data.revenda_email);
                $("#revenda_telefone").html(data.revenda_telefone);
                $('#infoPoc').modal();
            }

        });
        return false;
    });
</script>
<?php $this->renderPartial('modalViewInfoPoc'); ?>

<input type='hidden' id='id_empresa' value="">
<input type='hidden' id='status' value="">