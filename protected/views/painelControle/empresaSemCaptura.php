<?php
$this->breadcrumbs = array(
    Yii::t('smith', 'Empresas Sem Captura'),
);
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'empresaSemCaptura-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    //'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'fk_empresa',
            'value' => 'Empresa::model()->find(array("condition" => "id = " . $data->fk_empresa))->nome',
            'headerHtmlOptions' => array('style' => 'text-align: center; width: 50%'),
            'filter' => CHtml::listData(Empresa::model()->findAll(array('order' => 'nome ASC')), 'id', 'nome')
        ),
        array(
            'name' => 'ultima_captura',
            'value' => 'MetodosGerais::dataBrasileira($data->ultima_captura)',
            'htmlOptions' => array('style' => 'text-align: center'),
            'headerHtmlOptions' => array('style' => 'text-align: center'),
            'filter' => false
        )
    )
));
?>