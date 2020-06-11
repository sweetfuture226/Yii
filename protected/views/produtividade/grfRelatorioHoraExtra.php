<?php

$this->breadcrumbs = array(
    Yii::t("smith", 'Produtividade no horário não comercial'),
);
?>
<div class="form-group  col-lg-4">
    <p>
        <?php
        echo CHtml::label(Yii::t("smith", 'Selecione'), 'fk_equipe');
        echo CHtml::dropdownlist('equipe', '', $options, array("class" => "chzn-select",  "style" => "width:100%;"));
        ?>
    </p>
</div>
<div style="clear: both"></div>

<?php
for ($i = 1; $i <= count($produtividade); $i++) {
    $class = ($i > 1) ? "widget extras" : "widget";
    echo "<div id='$i' class='$class'>";
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'scripts' => array(
            'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'modules/exporting', // adds Exporting button/menu to chart
        ),
        'options' => array(
            'exporting' => array(
                'sourceHeight' => 800,
                'sourceWidth' => 1280,
                'filename' => '[VivaSmith] - Produtividade em horário não comercial'

            ),
            'colors' => array("#95CEFF", "#34A878"),
            //'gradient' => array('enabled' => true),
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
                'text' => Yii::t('smith', 'Hora extra dos colaboradores de') . " " . $dataInicio . " " . Yii::t('smith', 'até') . " " . $dataFim
            ),
            'chart' => array(
                'margin' => array(50, 50, 100, 120),
                'height' => '600',
                'type' => 'bar'
            ),
            'xAxis' => array(
                'categories' => $categorias[$i - 1],
                'plotLines' => array(array(
                    'value' => (date('w')),
                    'width' => 1,
                    'color' => '#808080'
                )),
            ),
            'yAxis' => array(
                'title' => array(
                    'text' => Yii::t('smith', 'Duração'),
                ),
                'allowDecimals' => false,
                'labels' => array(
                    'formatter' => "js:function(){
                                    return this.value;
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
                                        return this.y;
                                }",
                    ),
                ),
            ),
            'tooltip' => array(
                'formatter' => "js:function(){
                    var s = '<b>".Yii::t('smith', 'Colaborador').": </b>'+ this.x +'';
                    var flag = false;

                    var total = 0;
                    $.each(this.points, function(i, point) {
                        if(point.series.name != 'Produtividade'){
                            total += this.y;
                        }

                    });
                    $.each(this.points.reverse(), function(i, point) {
                        if(this.y >0){
                            flag = true;
                            var duracao = this.y;
                            var segundos = duracao*3600;
                            var minutos = Math.floor(segundos/60);
                            var horas = Math.floor(minutos/60);
                            minutos = minutos%60;
                            minutos = (minutos > 9) ? '' + minutos: '0' + minutos;
                            percentage = ((this.y/total)*100).toFixed(1);
                            s += '<br/>'+ point.series.name + ':  '+ horas + ':' + minutos + ' - (' + percentage + '%)';
                        }
                    });
                    if(flag)
                        return s;
                    return s+'<br/>". Yii::t('smith', 'Nenhum') ."';
                }",
                'crosshairs' => true,
                'shared' => true,
            ),
            'series' => array(
                array('name' => Yii::t('smith', 'Duração'), 'data' => $duracao[$i - 1]),
                array('name' => Yii::t('smith', 'Produtividade'), 'data' => $produtividade[$i - 1])
            ),
        )
    ));

    echo "</div>";
}
?>



<script>
    $(document).ready(function () {
        $(".extras").hide();
    });
    $("#equipe").change(function () {
        var equipe = $("#equipe").val();
        if (equipe != "") {
            $(".widget").hide();
            $(".extras").hide();
            $("#" + equipe).show();
            $(window).trigger('resize');
        }

    });
</script>
