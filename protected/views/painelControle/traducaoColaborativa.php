<?php
$this->breadcrumbs = array(
    'Traducao Literais',
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
            $.fn.yiiGridView.update('traducao-literal-grid', {
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
            'mGridId' => 'traducao-literal-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'traducao-literal-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        'message',
        array(
            'header' => Yii::t('smith', 'Britânico'),
            'name' => 'traducao.translation',
            'value' => 'isset($data->traducao[0])? $data->traducao[0]->translation : ""'
        ),
        array(
            'header' => Yii::t('smith', 'Espanhol'),
            'name' => 'traducao.translation',
            'value' => 'isset($data->traducao[1]) ? $data->traducao[1]->translation : ""',
        ),
        array(
            'header' => Yii::t('smith', 'Inglês'),
            'name' => 'traducao.translation',
            'value' => 'isset($data->traducao[2]) ? $data->traducao[2]->translation : ""',
        ),

        array(
            'header' => 'Ações',
            'class' => 'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 5%'),
            'template' => '{traduzir}',
            'buttons' => array(
                'traduzir' => array(
                    'label' => Yii::t('smith', 'Traduzir literal'),
                    'options' => array('class' => 'btn btn-orange btn-margin-grid traduzir'),
                    'url' => '$data->id',
                ),
            )
        ),
    ),
)); ?>

<?php $this->renderPartial('modalUpdateTraducao', array('model' => $model)); ?>


<script>
    $('#traducao-literal-grid a.traduzir').live('click', function () {
        var obj = $(this).parent().parent();
        var id = $(this).attr('href');
        var literal = obj.children(':first-child').text();
        var trd_ingles = obj.children(':nth-child(2)').text();
        var trd_espanhol = obj.children(':nth-child(3)').text();
        var trd_britanico = obj.children(':nth-child(4)').text();
        var row = obj[0].rowIndex;
        $('#id_literal').val(id);
        $('#row_grid').val(row);
        $('#literal').html(literal);
        $('#traducao_en').val(trd_ingles);
        $('#traducao_es').val(trd_espanhol);
        $('#traducao_uk').val(trd_britanico);
        $('#modal_update_traducao').modal()
        return false;
    });
</script>