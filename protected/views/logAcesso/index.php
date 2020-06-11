<?php
$this->breadcrumbs = array(
    'Log Acessos',
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
            $.fn.yiiGridView.update('log-acesso-grid', {
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
        'mGridId' => 'log-acesso-grid', //Gridview id
        'mPageSize' => @$_GET['pageSize'],
        'mDefPageSize' => 20,
        'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
    ));
    ?>
    </label>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'log-acesso-grid',
    'dataProvider' => $model->searchIndex(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'data_horario',
            'value' => 'date("d/m/Y",strtotime($data->data_horario))',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'data_horario',
                'language' => 'en',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_data',
                    'size' => '10',
                    'class' => 'dateSearch'
                ),
            ),
                true),
        ),
        array(
            'name' => 'fk_empresa',
            'filter' => CHtml::dropdownlist('LogAcesso[fk_empresa]', $model->fk_empresa, CHtml::listData(Empresa::model()->findAll(array('order' => 'nome ASC')), 'id', 'nome'), array('empty' => 'Todos')),
            'value' => 'Empresa::model()->findByPk($data->fk_empresa)->nome'
        ),
        array(
            'name' => 'fk_usuario',
            'filter' => CHtml::dropdownlist(
                'LogAcesso[fk_usuario]',
                $model->fk_usuario,
                CHtml::listData(
                    !empty($model->fk_empresa) ? UserGroupsUser::model()->findAll(array('condition' => 'nome IS NOT NULL AND fk_empresa = ' . $model->fk_empresa, 'order' => 'nome ASC')) : UserGroupsUser::model()->findAll(array('condition' => 'nome IS NOT NULL', 'order' => 'nome ASC')),
                    'id',
                    'nome'
                ),
                array(
                    'empty' => 'Todos'
                )
            ),
            'value' => 'UserGroupsUser::model()->findByPk($data->fk_usuario)->nome',
        ),
        array(
            'name' => 'modulo',
            'filter' => CHtml::listData(LogAcesso::model()->findAll(array('order' => 'modulo ASC', 'group' => 'modulo')), 'modulo', 'modulo'),
            'value' => 'LogAcesso::model()->findByPk($data->id)->modulo'
        ),
        array(
            'name' => 'acao',
            'filter' => CHtml::listData(!empty($model->modulo) ? LogAcesso::model()->findAll(array('condition' => 'modulo = "' . $model->modulo . '"', 'group' => 'acao', 'order' => 'acao ASC')) : LogAcesso::model()->findAll(array('group' => 'acao', 'order' => 'acao ASC')), 'acao', 'acao'),
            'value' => 'LogAcesso::model()->findByPk($data->id)->acao'
        ),
        'titulo',
        array(
            'name' => 'tempo_resposta',
            'filter' => array(
                '0' => 'Abaixo de 0,001',
                '1' => 'Entre 0,001 e 0,01',
                '2' => 'Entre 0,01 e 0,1',
                '3' => 'Entre 0,1 e 1',
                '4' => 'Entre 1 e 10',
                '5' => 'Acima de 10'
            ),
            'value' => 'str_replace(".",",",$data->tempo_resposta)'
        )
    ),
)); ?>
