<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Documentos finalizados'),
);

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
            $.fn.yiiGridView.update('documento-grid', {
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
                'mGridId' => 'documento-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
            ));
            ?>
        </label>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'documento-grid',
    'dataProvider' => $model->searchDocumentoFinalizado(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'fk_empresa',
            'value' => '$data->fkEmpresa->nome',
            'filter' => CHtml::listData(Empresa::model()->findAll(array('order' => 'nome ASC')), 'id', 'nome'),
        ),
        array(
            'name' => 'fk_obra',
            'value' => '$data->fkObra->nome',
            'filter' => false,
        ),
        array(
            'name' => 'fk_colaborador',
            'value' => '$data->fkColaborador->nome',
            'filter' => false,
        ),
        'documento',
        array(
            'name' => 'duracao',
            'value' => 'MetodosGerais::formataTempo($data->duracao)',
            'filter' => false,
        ),
        array(
            'name' => 'data',
            'value' => 'MetodosGerais::dataBrasileira($data->data)',
            'filter' => false,
        ),


    ),
));

