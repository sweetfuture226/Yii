<?php
$this->breadcrumbs = array(
    
    Yii::t("smith", 'Custo de energia por contrato'),
);
?>

<div class="widget">
    <?php
    $title = Yii::t("smith","Consumo de Energia ");
    $totalLabel = Yii::t("smith","Total de R$");
    $title2 = Yii::t("smith","Custo (R$)");
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'scripts' => array(
            'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'modules/exporting', // adds Exporting button/menu to chart

        ),
        'options'=>array(
            'exporting' => array(
                'sourceHeight' => 400,
                'sourceWidth' => 1330,
                'filename' => '[VivaSmith] - Custo de energia'

            ),
            'credits' => array('enabled' => false),
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
                'text' => $title. $contrato  ."<br>".$totalLabel. $valor_total,
            ),
            'credits' => array('enabled' => false),

            'xAxis' => array(
                'categories' => $categorias,

            ),
            'yAxis' => array(
                'title' => array(
                    'text' => Yii::t('smith', 'Consumo'),
                ),
                'labels' => array(
                    'formatter' => "js:function(){
                                    var s = this.value.toFixed(2);
                                    s  = s.replace('.',',');
                                    return 'R$'+ s;
                                }",

                ),

                'min' => 0,

            ),
            'tooltip' => array(
                'formatter' => "js:function(){

                    var s = '<b>'+ this.x +'</b>';
                    var flag = false;
                    var value;
                    var total = 0;

                    $.each(this.points, function(i, point) {
                        if(this.y >0){
                            flag = true;
                            total += this.y;
                            value = this.y.toFixed(2);
                            value = value.replace('.',',');
                            s += '<br/>'+ point.series.name +
                                ': R$'+ value;
                        }
                    });
                    total = total.toFixed(2);
                    total = total.replace('.',',');

                    if(flag)
                        return s;
                    return s+'<br/>".Yii::t('smith', 'Sem custo')."';
                }",
                'crosshairs'=>true,
                'shared' =>true,
            ),



            'series' => array(
                $produzido

            ),

        )
    ));
    ?>
</div>
        
  
