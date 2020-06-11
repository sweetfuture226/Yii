<?php
$this->breadcrumbs=array(
    Yii::t("smith", 'Programas permitidos'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("ProgramaPermitido/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t("smith", 'Adicionar') . '</button>\');
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
            $.fn.yiiGridView.update('programa-permitido-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>

<p><?=Yii::t("smith", 'Aqui você poderá visualizar os programas permitidos para os usuários.')?></p><br>


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
	'id'=>'programa-permitido-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
		'nome',
		array(
            'header' => Yii::t("smith",'Ações'),
            'class'=>'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 12%; '),
            'buttons'=>array(
                'delete' => array(
                    'label' => 'Excluir',
                    'click' => 'js:function(evt){
                                        evt.preventDefault();
                                        /*Your custom JS goes here :) */
                                        }',
                    'options' => array('class' => 'btn btn-danger btn-margin-grid deletar'),
                    'url' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
                ),
                'update' => array(
                    'label' => 'Editar',
                    'options' => array('class' => 'btn btn-orange btn-margin-grid'),
                ),

            ),
        'template'=>'{update}{delete}',
		),
	),
)); ?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>
