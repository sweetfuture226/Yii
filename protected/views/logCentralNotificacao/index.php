<?php
$this->breadcrumbs = array(
    'Log Central Notificacaos',
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".panel-heading").prepend(\'<button class="btn btn-success" style="float: right;" onclick= location.href="' . CHtml::normalizeUrl(array("LogCentralNotificacao/create")) . '"><i class="icon-plus-sign"></i> Adicionar</button>  \');
');
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

        boxy_view();
    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('log-central-notificacao-grid', {
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
            'mGridId' => 'log-central-notificacao-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'log-central-notificacao-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'fk_documento_sem_contrato',
            'header' => Yii::t("smith", "Documentos"),
            'value' => 'DocumentoSemContrato::model()->FindByPK($data->fk_documento_sem_contrato)->documento',
        ),
        array(
            'name' => 'tipo',
            'header' => Yii::t("smith", "Tipo"),
            'value' => '($data->tipo == 2) ? Yii::t("smith","Novo documento") : Yii::t("smith","Documento existente")',
        ),
        array(
            'header' => Yii::t("smith", 'Ações'),
            'class' => 'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 20%; text-align: left;'),
            'buttons' => array(
                'desfazer' => array(
                    'label' => Yii::t("smith", 'Desfazer ação'),     // text label of the button
                    'options' => array('class' => ' btn btn-orange btn-margin-grid desfazer', 'data-id' => '$data->fk_documento_sem_contrato', 'data-tipo' => '$data->tipo'),
                    'icon' => 'fa fa-undo',
                ),
            ),
            'template' => '{desfazer}',
        ),
    ),
)); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".desfazer").on('click', function () {
            var id = $(this).data('id');
            var tipo = $(this).data('tipo');
            swal({
                title: "Atenção!",
                text: "Você tem certeza que deseja desfazer essa ação?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    method: "POST",
                    url: baseUrl + "/LogCentralNotificacao/Desfazer",
                    data: {fk_central: id, tipo: tipo}
                }).done(function (retorno) {
                    swal("Sucesso!", "Ação foi desfeita com sucesso", "success");
                    location.reload();
                });
            });
        });
    });
</script>
