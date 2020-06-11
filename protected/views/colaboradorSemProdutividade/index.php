<?php
/* @var $this ColaboradorSemProdutividadeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t("smith",'Colaboradores sem produtividade'),
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

<div style="margin-bottom: 10px; overflow: auto;float: right;">
    <form action="<?= Yii::app()->baseUrl ?>/colaboradorSemProdutividade/gerarPDF" method="post" align="left"
          target="_blank" style="display: inline-block">
        <input type="hidden" id="inputData" name="data">
        <input type="hidden" id="inputEquipe" name="equipe">
        <input type="hidden" id="inputNome" name="nome">
        <input type="submit" id="pdf" align="left" class="btn btn-info" value="Gerar PDF">
    </form>

</div>
<div style="clear: both"></div>
<p style="margin:0px 0px 20px "><?= Yii::t("smith", 'Verifique quem e quando precisou estar ausente da estação de trabalho para outras atividades.') ?></p>

<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'ColaboradorSemProdutividade-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ColaboradorSemProdutividade-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
        array(
            'name' => 'data',
            'value'=> 'date("d/m/Y ",strtotime($data->data))',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model,
                'attribute'=>'data',
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
        'equipe',
        'nome',
	),
)); ?>

<script type="text/javascript">
    $(document).on('change', '#datepicker_for_data', function(){
        $('#inputData').val($('#datepicker_for_data').val());
        $('#inputEquipe').val($('[name="ColaboradorSemProdutividade[equipe]"]').val());
        $('#inputNome').val($('[name="ColaboradorSemProdutividade[nome]"]').val());
    });
    $(document).on('change', '[name="ColaboradorSemProdutividade[equipe]"]', function(){
        $('#inputData').val($('#datepicker_for_data').val());
        $('#inputEquipe').val($('[name="ColaboradorSemProdutividade[equipe]"]').val());
        $('#inputNome').val($('[name="ColaboradorSemProdutividade[nome]"]').val());
    });
    $(document).on('change', '[name="ColaboradorSemProdutividade[nome]"]', function(){
        $('#inputData').val($('#datepicker_for_data').val());
        $('#inputEquipe').val($('[name="ColaboradorSemProdutividade[equipe]"]').val());
        $('#inputNome').val($('[name="ColaboradorSemProdutividade[nome]"]').val());
    });
</script>
