<?php
$this->breadcrumbs=array(
    Yii::t("smith",'Calendário de feriados'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("CalendarioFeriados/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t("smith", 'Adicionar') . '</button>\');
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
            $.fn.yiiGridView.update('feriado-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>

    <p><?=Yii::t("smith", 'Aqui você poderá visualizar as datas que os colaboradores não tiveram produtividade contabilizada e definir se foi feriado.')?></p><br>
    <?php echo CHtml::beginForm(); ?>
        <div align="left" style="margin: 0 0 18px 10px; display: block" class="row">
            <div style="float: left; ">
                <?php echo CHtml::button(Yii::t("smith", 'Definir data(s) como feriado'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'onclick' => "validaForm();")); ?>
            </div>

        </div>

    <div align="right" style="float: right; margin: 10px;" class="row">
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'feriado-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions'=>Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ));
        ?>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'feriado-grid',
    'dataProvider'=>$model->searchInativos(),
    'filter'=>$model,
    'pager' => array('cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css'),
    'cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css',
    'htmlOptions' => array('class' => 'grid-view rounded table-responsive'),
    'afterAjaxUpdate' => 'afterAjax',
    'columns'=>array(
        array(
            'id' => 'selectedItens',
            'class' => 'CCheckBoxColumn',
            'value' => '$data->data',
            'selectableRows' => 2,
        ),
        array(
            'name'=>'data',
            'value'=>'MetodosGerais::dataBrasileira($data->data)',
        ),

        array(
            'header' => Yii::t("smith","Ações"),
            'class' => 'CButtonColumn',
            'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
            'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
            'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
            'htmlOptions'=>array('style' => 'width: 5%; text-align: left;'),
            'template'=>'{delete}'
        ),
    ),
)); ?>

<?php $this->renderPartial('modalAutorizaFeriado'); ?>
