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
                'text' => Yii::t('smith', 'Produtividade de') . "  $colaborador " . Yii::t('smith', 'no dia') . " $data",
            ),
            'credits' => array('enabled' => false),

            'xAxis' => array(
                'categories' => $categorias,

                'title' => array(
                    'text' => Yii::t('smith', 'Período (h)'),
                ),
                'labels' => array(
                    'formatter' => "js:function(){
                                    return (this.value) +':00';
                                }",

                ),
            ),
            'yAxis' => array(
                'title' => array(
                    'text' => Yii::t('smith', 'Tempo Produzido'),
                ),
                'labels' => array(
                    'formatter' => "js:function(){
                                    return ((this.value/60).toFixed(0)) +'min';
                                }",

                ),
                'min' => 0,
            ),

            'tooltip' => array(
                'crosshairs' => true,
                'shared' => true,
                'formatter' => "js:function(){
                        var s = '". Yii::t('smith', 'Período de'). ' ' ." ' + (parseFloat(this.x)-1) + ':00' + ' às ' + this.x+ ':00';
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
                $produzido, $produtivoMeta, $produtivoMedia

            ),

        )
    ));
    ?>
</div>
        
  
