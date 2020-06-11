<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Calendário de feriados'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<div style="float: right"><button style="margin-right: 3px"  onclick= location.href="' . CHtml::normalizeUrl(array("CalendarioFeriados/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t('smith', 'Adicionar') . '</button> <button id="planilha_calendario_feriados" class="btn btn-info" style="float: right;">' . Yii::t('smith', 'Importar planilha') . '</button></div>\');
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


    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('feriado-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>

<p><?= Yii::t("smith", 'Aqui você poderá visualizar as datas que os colaboradores não terão produtividade contabilizada.') ?></p>
<br>


<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'feriado-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'feriado-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'data',
            'value' => 'MetodosGerais::dataBrasileira($data->data)',
        ),

        array(
            'header' => Yii::t("smith", "Ações"),
            'class'=>'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 5%; text-align: left;'),
            'buttons' => array(
                'delete' => array(
                    'label' => 'Excluir',
                    'click' => 'js:function(evt){
                                        evt.preventDefault();
                                        /*Your custom JS goes here :) */
                                        }',
                    'options' => array('class' => 'btn btn-danger btn-margin-grid deletar'),
                    'url' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
                ),

            ),
            'template' => '{delete}'
        ),
    ),
)); ?>
<?php
$this->renderPartial('modalImportarPlanilha');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>
