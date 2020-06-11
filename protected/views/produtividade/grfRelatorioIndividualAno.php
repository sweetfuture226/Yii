<?php
$this->breadcrumbs = array(

    Yii::t("smith", 'Produtividade individual'),
);
?>
<div class="widget">
    <?php


    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'scripts' => array(
            'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'modules/exporting', // adds Exporting button/menu to chart

        ),
        'options' => array(
            'exporting' => array(
                'sourceHeight' => 400,
                'sourceWidth' => 1280,
                'filename' => '[VivaSmith] - Produtividade individual diário'

            ),
            'colors' => array('#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'),
            'lang' => array(
                'decimalPoint' => ',',
                'downloadPNG' => Yii::t('smith', 'Baixar imagem em PNG'),
                'downloadJPEG' => Yii::t('smith', 'Baixar imagem em JPEG'),
                'downloadPDF' => Yii::t('smith', 'Baixar documento em PDF'),
                'downloadSVG' => Yii::t('smith', 'Baixar imagem vetorizada SVG'),
                'printChart' => Yii::t('smith', 'Imprimir gráfico'),
                'exportButtonTitle' => Yii::t('smith', 'Exportar'),
                'loading' => Yii::t('smith', 'Carregando...'),
                'months' => array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'),
                'shortMonths' => array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'),
                'printButtonTitle' => Yii::t('smith', 'Imprimir'),
                'resetZoom' => Yii::t('smith', 'Redefinir zoom'),
                'resetZoomTitle' => Yii::t('smith', 'Redefinir nível de zoom 1:1'),
                'thousandsSep' => '.',
                'weekdays' => array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado')
            ),
            'title' => array(
                'text' => Yii::t('smith', 'Produtividade de') . "  $colaborador " . Yii::t('smith', 'no ano') . " $data",
            ),
            'credits' => array('enabled' => false),

            'xAxis' => array(
                'categories' => $categorias,

                'title' => array(
                    'text' => Yii::t('smith', 'Período (meses)'),
                ),
                'labels' => array(
                    'formatter' => "js:function(){
                                    return (this.value) ;
                                }",

                ),
            ),
            'yAxis' => array(
                'title' => array(
                    'text' => Yii::t('smith', 'Tempo Produzido'),
                ),
                'labels' => array(
                    'formatter' => "js:function(){
                                    return ((this.value/3600).toFixed(0)) +'h';
                                }",

                ),
                'min' => 0,
            ),

            'tooltip' => array(
                'crosshairs' => true,
                'shared' => true,
                'formatter' => "js:function(){
                        var s = '".Yii::t('smith', 'No mês de') . ' ' ."' + this.x;
                        $.each(this.points, function () {
                            s +=  '<br/><b>'+this.series.name+'</b>' + ': ' + sec_to_time(this.y);
                        });
                        return s;
                    }"
            ),

            'plotOptions' => array(
                'line' => array(
                    'dataLabels' => array(
                        'enabled' => TRUE,
                        'formatter' => "js:function(){
                               return sec_to_time(this.y);
                           }",
                    ),
                    'enableMouseTracking' => TRUE,


                )
            ),

            'series' => array(
                array(
                    'name' => Yii::t('smith', 'Produtividade'),
                    'data' => array_values($produzido['data']),
                ),
                array(
                    'name' => Yii::t('smith', 'Meta estabelecida'),
                    'data' => array_values($produtivoMeta['data']),
                ),
                array(
                    'name' => Yii::t('smith', 'Média da equipe'),
                    'data' => array_values($produtivoMedia['data']),
                ),

            ),

        )
    ));
    ?>
</div>


