<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Documentos'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("Documento/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i> ' . Yii::t("smith", 'Adicionar') . ' </button>\');
');
Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){
        $(".odd td:not(.button-column), .even td:not(.button-column)").click(function(){
            url = $(this).parent().children().children(".view").attr("href");
            Boxy.load(url, {title:"Dados"});
        });
    }
    row_view();
');

Yii::app()->clientScript->registerScript('afterAjax', '
    function afterAjax(id, data) {
        row_view();

    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('pro-template-grid', {
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
	'id'=>'documento-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
                'nome',
                'previsto',
		array(
                    'name'=> 'fk_disciplina',
                    'value'=> array($this,'getNomeDisciplina'),
                    ),


		array(
                        'header' => Yii::t("smith",'Ações'),
                        'class' => 'CButtonColumn',
                        'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
                        'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
                        'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
                        'htmlOptions'=>array('style' => 'width:15%; text-align: right;'),
                        'buttons'=>array(
                            'delete'=>array(
                                'visible'=>'!$data->has_relations()',
                            ),
                        ),
		),
	),
)); ?>
