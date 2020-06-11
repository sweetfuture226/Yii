<?php
$this->breadcrumbs = array(

    Yii::t("smith", 'Produtividade individual'),
);
?>
<div class="form-group  col-lg-4">
    <p>
        <?php
        echo CHtml::label(Yii::t("smith", 'Selecione'), 'option');
        echo CHtml::dropdownlist('option', '', $options, array("class" => "chzn-select", "style" => "width:100%;"));
        ?>
    </p>
</div>
<div style="clear: both"></div>

<?php
for ($i = 1; $i <= count($produzido); $i++) {
    $class = ($i > 1) ? "widget extras" : "widget";
    echo "<div id='$i' class='$class'>";
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'scripts' => array(
            'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)

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
            'chart' => array(
                'type' => 'line'
            ),
            'title' => array(
                'text' => Yii::t('smith', 'Produtividade de') . "  $colaborador " . Yii::t('smith', 'no mês') . " $data",
            ),
            'credits' => array('enabled' => false),

            'xAxis' => array(
                'categories' => $categorias[$i - 1],

                'title' => array(
                    'text' => Yii::t('smith', 'Período (dias)'),
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
                        var s = '". Yii::t('smith', 'No dia'). ' ' ."' + this.x;
                        $.each(this.points, function () {
                            s +=  '<br/><b>'+this.series.name+'</b>' + ': ' + sec_to_time(this.y);
                        });
                        return s  ;
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
                    'data' => $produzido[$i - 1],
                ),
                array(
                    'name' => Yii::t('smith', 'Meta estabelecida'),
                    'data' => $produzidoMeta[$i - 1],

                ), array(
                    'name' => Yii::t('smith', 'Média da equipe'),
                    'data' => $produzidoMedia[$i - 1],

                ),
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
    $("#option").change(function () {
        var opcao = $("#option").val();
        if (opcao != "") {
            $(".widget").hide();
            $(".extras").hide();
            $("#" + opcao).show();
            $(window).trigger('resize');
        }

    });
</script>
