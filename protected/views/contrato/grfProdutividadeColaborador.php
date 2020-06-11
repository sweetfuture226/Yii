<?php
$this->breadcrumbs=array(
	
	Yii::t("smith",'Produtividade colaborador em contratos'),
); ?>

<?php
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'scripts' => array(
           'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
           'modules/exporting', // adds Exporting button/menu to chart
          
        ),
    'options'=>array(
        'exporting' => array(
            'sourceHeight' => 800,
            'sourceWidth' => 1280,
            'filename' => '[VivaSmith] - Produtividade em contratos'

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
            'text' => Yii::t('smith', 'Produtividade de').' '. $colaborador .' '. Yii::t('smith', 'nos contratos entre').' '. $data_inicio .Yii::t('smith', ' e '). $data_fim
        ),
        
        'credits' => array('enabled' => false),
        'chart' => array(
            'margin' => array(50, 50, 100, 120),
            'type' => 'bar'
           // 'height'=>$heightGrafico,
            
        ),
        'xAxis' => array(
            'categories' => $categorias,

            'plotLines'=> array(array(
                            'value' => (date('w')),
                            'width' => 1,
                            'color' => '#808080'
                        )),
        ),
        'yAxis' => array(
            'title' => array(
                'text' => Yii::t('smith', 'Horas trabalhadas'),
            ),
            'allowDecimals'=>false,
            'labels' => array(
                'formatter' => "js:function(){
                                    return this.value+' " . Yii::t('smith', 'hora(s)') . "';
                                }",

            ),
            'min' => 0,
          

        ),
        'plotOptions' => array(
        'column' => array(
                'stacking' => 'normal',
                'dataLabels' => array(
                'enabled' => true,
                'color' => 'black',
                'formatter' => "js:function(){
                                    if (this.y > 0)
                                        return this.y+' h';
                                }",
            ),

        ),
        ),
        'tooltip' => array(
            'formatter' => "js:function(){

                    var s = '<b>".Yii::t('smith', 'Contrato')." : </b>'+ this.x +'';
                    var flag = false;

                    $.each(this.points, function(i, point) {
                        if(this.y >0){
                            flag = true;
                            s += '<br/>". Yii::t('smith', 'Produtividade') .":  '+ this.y + ' h';
                        }
                    });
                    if(flag)
                        return s;
                    return s+'<br/>".Yii::t('smith', 'Nenhum')."';
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