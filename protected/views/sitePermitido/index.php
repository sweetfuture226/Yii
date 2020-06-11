<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Lista de sites permitidos'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("sitePermitido/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t("smith", 'Adicionar') . '</button>\');
');
Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){
        $(".view").click(function(){
            Boxy.load(url, {title:"Dados"});
        });
    }
    row_view();
');


Yii::app()->clientScript->registerScript('afterAjax', "
    function afterAjax(id, data) {
        row_view();

    }
");

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('cliente-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

    <p><?= Yii::t("smith", 'Aqui você poderá visualizar os sites permitidos para os usuários.') ?></p><br>

    <div class="dataTables_length">
        <label>
            <?php
            $this->widget('application.extensions.PageSize.PageSize', array(
                'mGridId' => 'programa-permitido-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
            ));
            ?>
        </label>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'programa-permitido-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'nome',
            'header' => 'Site',
        ),
        array(
            'header' => Yii::t("smith", 'Ações'),
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
            'template' => '{delete}',
        ),

    ),
)); ?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>