<?php
$this->breadcrumbs=array(
    Yii::t('smith','Colaboradores em falta'),
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
            $.fn.yiiGridView.update('colaborador-has-falta-grid', {
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
                'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
            ));
            ?>
        </label>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'colaborador-has-falta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
        array(
            'name'=>'fk_colaborador',
            'value' => 'Colaborador::model()->findByPk($data->fk_colaborador)->nomeCompleto',
        ),
        array(
            'name'=>'data',
            'value'=>array($this,'getDatas'),
        ),
		array(
            'header' => 'Ações',
            'class' => 'CButtonColumn',
            'htmlOptions'=>array('style' => 'width: 7%; text-align: left;'),
            'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
            'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
            'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
            'buttons'=>array(
                'inativar' => array(
                    'label'=>Yii::t('smith','Inativar colaborador'),     // text label of the button
                    'options'=>array('class'=>'inativar'),
                    'url'=> '$data->fk_colaborador',
                    'imageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/online.png', // image URL of the button. If not set or false, a text link is used
                ),
                'ferias' => array(
                    'label'=>Yii::t('smith','Férias do colaborador'),     // text label of the button
                    'options'=>array('class'=>'ferias'),
                    'url'=> '$data->fk_colaborador',
                    'imageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/ferias.png', // image URL of the button. If not set or false, a text link is used

                ),
            ),
            'template'=>'{ferias}{inativar}',
		),
	),
));

$this->renderPartial('modalInativar');
$this->renderPartial('modalFerias');
