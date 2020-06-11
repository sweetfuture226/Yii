<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Contratos'),
);

if ($permissaoAcesso) {
    Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("Contrato/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i> ' . Yii::t("smith", 'Adicionar') . ' </button>\');
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


    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('pro-obra-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>
<p><?= Yii::t("smith", 'Visualize aqui a lista de contratos (projetos) cadastrados para gerenciamento.') ?></p><br>


<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'pro-obra-grid', //Gridview id
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
    'id' => 'pro-obra-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        'nome',
        'codigo',
        array(
            'name' => 'finalizada',
            'filter' => array('0' => Yii::t('smith', 'Em andamento'), '1' => Yii::t('smith', 'Finalizado')),
            'value' => '($data->finalizada==0)? Yii::t("smith","Em andamento") : Yii::t("smith","Finalizado") ',
        ),
        array(
            'name' => 'duracao',
            'filter' => false,
            'value' => array($this, 'getDuracao'),
        ),
        array(
            'header' => Yii::t("smith", 'Ações'),
            'class'=>'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 20%; text-align: left;'),
            'buttons' => array(
                'update' => array(
                    'label' => 'Editar',
                    'options' => array('class' => 'btn btn-orange btn-margin-grid'),
                    'visible' => 'MetodosGerais::checkPermissionAccessContract()'
                ),
                'delete' => array(
                    'label' => 'Excluir',
                    'click' => 'js:function(evt){
                                        evt.preventDefault();
                                        /*Your custom JS goes here :) */
                                        }',
                    'options' => array('class' => 'btn btn-danger btn-margin-grid deletar'),
                    'url' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
                    'visible' => 'MetodosGerais::checkPermissionAccessContract()'
                ),
                'andamento' => array(
                    'label' => Yii::t("smith", 'Visualizar andamento'),     // text label of the button
                    'url' => 'Yii::app()->controller->createUrl("contrato/andamentoObra?codigo=$data->codigo")',
                    'click' => 'function(){Loading.show(); return true;}',
                    'options' => array('class' => 'btn btn-info btn-margin-grid'),
                    'icon' => 'fa fa-info-circle'
                ),
                'finalizar' => array(
                    'label' => Yii::t("smith", 'Finalizar contrato'),     // text label of the button
                    'options' => array('class' => ' btn btn-success btn-margin-grid finalizar', 'data-id' => '$data->id'),
                    'icon' => 'fa fa-check',
                   // 'url' => '$data->id',
                    'visible' => '!$data->finalizada',
                ),
                'pdf' => array(
                    'label' => 'Gerar Relatorio',     // text label of the button
                    'url' => 'Yii::app()->controller->createUrl("contrato/relatorioIndividual?codigo=$data->codigo")',
                    'options'=>array('class'=>'btn btn-inverse btn-margin-grid'),
                    'icon' => 'fa fa-pdf',

                ),
            ),
            'template' => '{finalizar}{andamento}{update}{delete}',
        ),
    ),
));


?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>

<script>

    $(document).on('click', ".finalizar", function () {
        var id_contrato = $(this).data('id');
        swal({   
           title: "<?php echo Yii::t('smith', 'Deseja finalizar o contrato?'); ?>",
           text: "", 
           type: "warning", 
           showCancelButton: true, 
           showLoaderOnConfirm: true,
           confirmButtonColor: "#DD6B55",
           confirmButtonText: "<?= Yii::t("smith", 'Confirmar') ?>",
           closeOnConfirm: false
       }, function(){
        $.ajax({
            type: 'POST',
            data: {contrato: id_contrato},
            url: baseUrl + '/contrato/finalizar',
            success: function (data) {
               // $("#btn_fechar_modal_inativar_pessoa").click();
                $.fn.yiiGridView.update('pro-obra-grid');
                swal("Sucesso!", "Contrato finalizado com sucesso.", "success");
            }
        });        
      });
    });
</script>
