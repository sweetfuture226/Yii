   
<?php
    $this->breadcrumbs = array(
       Yii::t('smith',  'Ranking'),
    );
?>

<?php
Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){
        $(".view").click(function(){
            Boxy.load(url, {title:"Dados"});
        });
    }
    row_view();
');


Yii::app()->clientScript->registerScript('afterAjax', "
    function afterAjax(id, data) {
        row_view();

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

<h4><?php echo Yii::t('smith', 'Ranking de produtividade no período de'); ?> <?= $dataInicio; ?> <?php echo Yii::t('smith', 'à'); ?> <?= $dataFim ?> </h4> <br>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'log-atividade-grid',
    'dataProvider' => $ranking->ranking(MetodosGerais::dataAmericana($dataInicio),
                                        MetodosGerais::dataAmericana($dataFim),
                                        $idEmpresa),
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'name' => 'equipe',
            'header' => Yii::t('smith', 'Equipe')
        ),
        array(
            'name' => 'nome',
            'header' => Yii::t('smith', 'Nome')
        ),
        array(
            'name' => 'produtividade',
            'value' => 'GrfProdutividadeConsolidado::formatarProdutividade($data["produtividade"])',
            'header' => Yii::t('smith', 'Produtividade')
        ),
        array(
            'name' => 'meta',
            'header' => Yii::t('smith', 'Meta'),
        ),
        array(
            'name' => 'coeficiente',
            'header' => Yii::t('smith', 'Coeficiente'),
            'value' => 'str_replace(".", ",", $data["coeficiente"])'
        ),
        array(
            'name' => 'ocioso',
            'value' => 'GrfProdutividadeConsolidado::formatarProdutividade($data["ocioso"])',
            'header' => Yii::t('smith', 'Ausência do computador')
        ),
    ),
));
?>

<script>
    $(document).on('click', '#pdf', function(){
        $('#formato').val('pdf');
    });

</script>

