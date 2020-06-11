<?php
$this->breadcrumbs=array(
	'Justificativa Ausencias',
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".panel-heading").prepend(\'<button class="btn btn-success" style="float: right;" onclick= location.href="'.CHtml::normalizeUrl(array("JustificativaAusencia/create")).'"><i class="icon-plus-sign"></i> Novo</button>  \');
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

        boxy_view();
    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('justificativa-ausencia-grid', {
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
                'mGridId' => 'justificativa-ausencia-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions'=>Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        )); 
    ?>
    </label>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'justificativa-ausencia-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
		'id',
		'tipo',
		array(
                        'header' => 'Ações',
                        'class' => 'CButtonColumn',
                        'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
                        'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
                        'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
		),
	),
)); ?>
