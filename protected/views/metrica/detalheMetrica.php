<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Métrica')=>array('index'),
	    Yii::t("smith", 'Detalhamento da métrica')
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
            $.fn.yiiGridView.update('pro-metrica-grid', {
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
            'mGridId' => 'pro-metrica-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'pro-metrica-grid',
    'dataProvider' => $model->searchDetalheMetrica($fkMetrica),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'data',
            'header'=>'Data',
            'value' => 'date("d/m/Y ",strtotime($data->data))',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'data',
                'language' => 'en',
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
        array(
            'name'=>'colaborador',
            'filter' => CHtml::listData(Colaborador::model()->findAll(array("condition" => "fk_empresa =" . MetodosGerais::getEmpresaId(), "order" => "nome ASC", "distinct" => true)), 'id', 'nomeCompleto'),
            'value' => '(Colaborador::model()->findByPk($data->colaborador)!==NULL)? Colaborador::model()->findByPk($data->colaborador)->nomeCompleto : "Sem colaborador"'
        ),
        'atuacao',
        array(
            'name'=>'total',
            'filter' => array('0'=>'00 '.Yii::t('smith','a').' 10min', '1'=>'10 '.Yii::t('smith','a').' 30min', '2'=>'30min '.Yii::t('smith','a').' 01h', '3'=>'01h '.Yii::t('smith','a').' 02h', '4'=>'02h '.Yii::t('smith','a').' 03h', '5'=>'03h '.Yii::t('smith','a').' 04h', '6'=>''.Yii::t('smith','Mais de').' 04h'),
            'value' => '$data->total'
        ),
        array(
            'name'=>'entradas',
            'filter' => array('0'=>'00 '.Yii::t('smith','a').' 05', '1'=>'05 '.Yii::t('smith','a').' 10', '2'=>'10 '.Yii::t('smith','a').' 20', '3'=>'20 '.Yii::t('smith','a').' 30', '4'=>'30 '.Yii::t('smith','a').' 40', '5'=>'40 '.Yii::t('smith','a').' 50', '6'=>''.Yii::t('smith','Mais de').' 50'),
            'value' => '$data->entradas'
        ),
        array(
            'name'=>'media',
            'filter' => array('0'=>'00 '.Yii::t('smith','a').' 05min', '1'=>'05 '.Yii::t('smith','a').' 10min', '2'=>'10 '.Yii::t('smith','a').' 20min', '3'=>'20 '.Yii::t('smith','a').' 30min', '4'=>'30 '.Yii::t('smith','a').' 40min', '5'=>'40 '.Yii::t('smith','a').' 50min', '6'=>'50 '.Yii::t('smith','a').' 60min','7'=>''.Yii::t('smith','Mais de').' 01h'),
            'value' => '$data->media'
        )
    ),
)); ?>
