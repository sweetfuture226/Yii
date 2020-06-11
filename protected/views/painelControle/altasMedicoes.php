<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Medições altas'),
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
            $.fn.yiiGridView.update('log-atividade-grid', {
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
                'mGridId' => 'log-atividade-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
            ));
            ?>
        </label>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'log-atividade-grid',
    'dataProvider' => $model->searchAltasMedicoes(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'empresa',
            'value' => '$data->empresa->nome',
        ),
        array(
            'name' => 'usuario',
            'header' => Yii::t("smith", "Usuário"),
            'value' => '$data->usuario'
        ),
        array(
            'name' => 'programa',
            'header' => Yii::t("smith", "Programa"),
        ),
        array(
            'name' => 'descricao',
            'header' => Yii::t("smith", "Descrição"),
            'value' => 'substr($data->descricao,0,130)',
        ),
        array(
            'name' => 'data',
            'header' => Yii::t("smith", "Data"),
            'value' => 'date("d/m/Y ",strtotime($data->data))',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'data',
                'language' => 'en',
                //'i18nScriptFile' => 'jquery.ui.datepicker-pt-BR.js',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_data',
                    'size' => '10',
                    'class' => 'date'
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus',
                    'dateFormat' => 'yy-mm-dd',
                    'yearRange' => '1940:',
                    'autoSize' => false,
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => false,
                )
            ), true),
        ),
        array(
            'name' => 'duracao',
            'header' => Yii::t("smith", "Duração"),
            'filter' => array('00:00:00 AND 00:05:00' => '0 a 5 minutos', '00:05:00 AND 00:10:00' => '5 a 10 minutos',
                '00:20:00 AND 00:30:00' => '20 a 30 minutos', '00:30:00 AND 00:40:00' => '30 a 40 minutos',
                '00:40:00 AND 00:50:00' => '40 a 50 minutos', '00:50:00 AND 00:60:00' => '50 a 60 minutos',
            ),
            'value' => '$data->duracao'
        ),
        array(
            'name' => 'hora_host',
            'header' => Yii::t("smith", "Horário"),
            'filter' => array('06:00:00 AND 07:00:00' => 'de 7 as 8 horas', '07:00:00 AND 08:00:00' => 'de 8 as 9 horas', '08:00:00 AND 09:00:00' => 'de 9 as 10 horas',
                '09:00:00 AND 10:00:00' => 'de 10 as 11 horas', '10:00:00 AND 11:00:00' => 'de 11 as 12 horas',
                '11:00:00 AND 12:00:00' => 'de 12 as 13 horas', '12:00:00 AND 13:00:00' => 'de 13 as 14 horas',
                '13:00:00 AND 14:00:00' => 'de 14 as 15 horas', '14:00:00 AND 15:00:00' => 'de 15 as 16 horas',
                '15:00:00 AND 16:00:00' => 'de 16 as 17 horas', '16:00:00 AND 17:00:00' => 'de 17 as 18 horas',
                '17:00:00 AND 18:00:00' => 'de 18 as 19 horas'
            ),
        )
    ),
));

