<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Contratos'),
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
            $.fn.yiiGridView.update('pro-obra-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'pro-obra-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
        'nome',
        'codigo',
                array(
                'name' => 'finalizada',
                'filter' => array('0' => 'Em Andamento', '1' => 'Finalizada'),
                'type' => 'raw',
                'value'=>'$data->finalizada != 1 ? Yii::t("smith","Em Andamento") : Yii::t("smith","Finalizada")',
                ),
                array(
                    'name' => 'coordenador',
                    'value' => 'UserGroupsUser::model()->findByPk($data->coordenador)->username',
                ),

        array(
                        'header' => Yii::t("smith",'Ações'),
                        'class' => 'CButtonColumn',
                        'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
                        'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
                        'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
                        'htmlOptions'=>array('style' => 'width:15%; text-align: right;'),
                        'buttons'=>array(
                            'andamento' => array(
                            'label'=>'Visualizar Andamento',     // text label of the button

                                'url' => 'Yii::app()->controller->createUrl("contrato/andamentoObra?codigo=$data->codigo")',
                            'imageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/tree2.png', // image URL of the button. If not set or false, a text link is used

                            ),
                            'pdf' => array(
                            'label'=>'Gerar Relatorio',     // text label of the button

                                'url' => 'Yii::app()->controller->createUrl("contrato/relatorioIndividual?codigo=$data->codigo")',
                            'imageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/pdf1.png', // image URL of the button. If not set or false, a text link is used

                            ),
                        ),
                        'template'=>'{andamento}',
		),
	),
)); ?>
