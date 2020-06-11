<?php
$this->breadcrumbs = array(
    Yii::t('smith', 'Empresas'),
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


<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'pro-pessoa-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
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
        'nome',
        array(
            'header' => Yii::t('smith', 'Total de contratos'),
            'name' => 'totalContratos',
            'value' => 'count(Contrato::model()->findAllByAttributes(array("fk_empresa"=>$data->id)))',
            'filter' => false,
        ),
        array(
            'header' => Yii::t('smith', 'Contratos com LDP'),
            'name' => 'contratosLDP',
            'value' => array($this, 'getContratoLDP'),
            'filter' => false,
        ),
        array(
            'header' => Yii::t('smith', 'Contratos sem LDP'),
            'name' => 'contratosSemLDP',
            'value' => array($this, 'getContratoSemLDP'),
            'filter' => false,
        ),
        array(
            'header' => Yii::t('smith', 'Contratos produtivos'),
            'name' => 'contratosProdutivos',
            'value' => array($this, 'getContratoProdutivos'),
            'filter' => false,
        ),


    ),
)); ?>
