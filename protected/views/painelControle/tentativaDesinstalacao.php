<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Medições altas'),
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
            $.fn.yiiGridView.update('log-atividade-grid', {
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
                'mGridId' => 'log-atividade-consolidado-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
            ));
            ?>
        </label>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'log-atividade-consolidado-grid',
    'dataProvider' => $model->searchTentativas(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'fk_empresa',
            'value' => '$data->empresa->nome',
            'filter' => CHtml::listData(Empresa::model()->findAll(array('order' => 'nome ASC')), 'id', 'nome'),
        ),
        array(
            'name' => 'usuario',
            'header' => Yii::t("smith", "Usuário"),
            'value' => '$data->usuario'
        ),
        array(
            'name' => 'descricao',
            'header' => Yii::t("smith", "Descrição"),
            'value' => 'substr($data->descricao,0,130)',
        ),
        array(
            'name' => 'data',
            'header' => Yii::t("smith", "Data"),
            'value' => 'date("d/m/Y ",strtotime($data->data))',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'data',
                'language' => 'en',
                //'i18nScriptFile' => 'jquery.ui.datepicker-pt-BR.js',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_data',
                    'size' => '10',
                    'class' => 'date'
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus',
                    'dateFormat' => 'yy-mm-dd',
                    'yearRange' => '1940:',
                    'autoSize' => false,
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => false,
                )
            ), true),
        ),
    ),
));

