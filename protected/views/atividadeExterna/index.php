<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Atividades externas'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<div style="float: right"><button style="margin-right: 3px"  onclick= location.href="' . CHtml::normalizeUrl(array("atividadeExterna/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i>  ' . Yii::t('smith', 'Adicionar') . '</button> <button id="planilha_atividade_externa" class="btn btn-info" style="float: right;">' . Yii::t('smith', 'Importar planilha') . '</button></div>\');
');

Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){

        $(".odd td:not(.button-column), .even td:not(.button-column)").click(function(){

            url = $(this).parent().children().children(".view").attr("href");
            Boxy.load(url, {title:"Dados"});
        });
    }

');

Yii::app()->clientScript->registerScript('afterAjax', "
    function afterAjax(id, data) {
        verifyUserBlockAd('AtividadeExterna[usuario]');
        verifyUserBlockAd('LogAtividade[usuario]');

    }
");

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
        //use the same parameters that you had set in your widget else the datepicker will be refreshed by default
    $('#datepicker_for_data').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['en'],{'dateFormat':'yy/mm/dd'}));
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

    <p><?php echo Yii::t("smith", 'Cadastre aqui reuniões e outras atividades produtivas realizadas fora da estação de trabalho.'); ?></p>
    <br>


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

        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'log-atividade-grid',
            'dataProvider' => $model->searchExtra(),
            'filter' => $model,
            'afterAjaxUpdate' => 'afterAjax',
            'columns' => array(
                array(
                    'header' => 'Colaborador',
                    'name' => 'usuario',
                    'filter' => CHtml::listData(Colaborador::model()->findAll(array("condition" => $condicao, "order" => "nome ASC", "distinct" => true)), 'ad', 'nomeCompleto'),
                    'value' => 'Colaborador::findColaboradorForGrid($data->usuario, $data->serial_empresa)'
                ),
                array(
                    'name' => 'descricao',
                    'value' => 'substr($data->descricao,0,130)',
                ),
                array(
                    'name' => 'data',
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
                    ),
                        true),
                ),
                array(
                    'name' => 'duracao',
                    'filter' => array('00:00:00 AND 01:00:00' => '0 a 1 hora', '01:00:00 AND 02:00:00' => '1 a 2 horas',
                        '02:00:00 AND 03:00:00' => '2 a 3 horas', '03:00:00 AND 04:00:00' => '3 a 4 horas',
                        '04:00:00 AND 05:00:00' => '4 a 5 horas', '05:00:00 AND 06:00:00' => '5 a 6 horas', '06:00:00 AND 07:00:00' => '6 a 7 horas', '06:00:00 AND 07:00:00' => '6 a 7 horas', '07:00:00 AND 08:00:00' => '7 a 8 horas', '08:00:00 AND 24:00:00' => '+ 8 horas',
                    ),
                    'value' => '$data->duracao'

                ),
                array(
                    'name' => 'data_hora_servidor',
                    //'header'=>'Horário',
                    'filter' => array('06:00:00 AND 07:00:00' => 'de 7 as 8 horas', '07:00:00 AND 08:00:00' => 'de 8 as 9 horas', '08:00:00 AND 09:00:00' => 'de 9 as 10 horas',
                        '09:00:00 AND 10:00:00' => 'de 10 as 11 horas', '10:00:00 AND 11:00:00' => 'de 11 as 12 horas',
                        '11:00:00 AND 12:00:00' => 'de 12 as 13 horas', '12:00:00 AND 13:00:00' => 'de 13 as 14 horas',
                        '13:00:00 AND 14:00:00' => 'de 14 as 15 horas', '14:00:00 AND 15:00:00' => 'de 15 as 16 horas',
                        '15:00:00 AND 16:00:00' => 'de 16 as 17 horas', '16:00:00 AND 17:00:00' => 'de 17 as 18 horas',
                        '17:00:00 AND 18:00:00' => 'de 18 as 19 horas'
                    ),
                    'value' => array($this, 'getHoraServidor')
                ),
                array(
                    'header' => Yii::t('smith', 'Ações'),
                    'class'=>'booster.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'width:5%; text-align: left;'),
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

<?php $this->renderPartial('modalImportarPlanilha') ?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>

<script type="text/javascript">
    $(document).ready(function () {
        verifyUserBlockAd('AtividadeExterna[usuario]');
    })
</script>
