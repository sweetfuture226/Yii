<?php
$this->breadcrumbs=array(

	Yii::t("smith",'Produtividade Geral'),
);
?>
<div class="buttons">
    <div style="float: left; ">
        <?php echo CHtml::button(Yii::t("smith", 'Voltar'), array('class' => 'btn btn-info submitForm', 'id' => 'voltar', 'style' => 'display: none')); ?>
    </div>
</div>
<div class="widget">
    <?php
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'options' => array(
            'exporting' => array(
                'sourceHeight' => 800,
                'sourceWidth' => 1280,
            ),
            'colors' => array('#89A54E', '#4572A7', '#80699B', '#3D96AE', '#DB843D', '#AA4643', '#92A8CD', '#A47D7C', '#B5CA92'),
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
                'text' => Yii::t('smith', 'Produtividade geral de') . ' ' . $title . $periodoDatas
            ),
            'chart' => array(
                'margin' => array(50, 50, 100, 120),
                'height' => '600',
                'type' => 'pie'
            ),
            'plotOptions' => array(
                'pie' => array(
                    'dataLabels' => array(
                        'enabled' => true,
                        'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                    )
                ),
                'series' => array(
                    'cursor' => 'pointer',
                    'point' => array(
                        'events' => array(
                            'click' => 'js:function () {
                            exibeGrafico(this.x);
                        }'
                        ),
                    ),
                ),
            ),
            'tooltip' => array(
                'formatter' => "js:function(){
                    return '<b>'+ this.point.name +'</b>: '+ formatTime(this.y)  +' " . Yii::t('smith', "horas") . "';
                }",
                'crosshairs' => true,
                'shared' => true,
            ),
            'series' => array(
                array('name' => Yii::t('smith', 'Produtivo'), 'data' => array(
                    array('name' => Yii::t('smith', 'Programas'), 'y' => $somaProduzido),
                    array('name' => Yii::t('smith', 'Sites'), 'y' => $somaSites),
                    array('name' => Yii::t('smith', 'Atividade Externa'), 'y' => $somaAtividadeExterna),
                    array('name' => Yii::t('smith', 'Programas não identificados'), 'y' => $somaNaoIdentificado),
                    array('name' => Yii::t('smith', 'Sites não identificados'), 'y' => $somaSiteNaoIdentificado),
                    array('name' => Yii::t('smith', 'Ocioso'), 'y' => $somaOcioso),
                )),

            ),
        )
    ));
    ?>
</div>
<?php
for ($i = 0; $i < count($seriesArray); $i++) {
    $class = "extras";
    echo "<div id='$i' class='$class'>";
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'options' => array(
            'exporting' => array(
                'sourceHeight' => 800,
                'sourceWidth' => 1280,
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
                'text' => Yii::t('smith', 'Produtividade geral de') . ' ' . $title . $periodoDatas
            ),
            'chart' => array(
                'margin' => array(50, 50, 100, 120),
                'height' => '600',
                'type' => 'pie'
            ),
            'plotOptions' => array(
                'pie' => array(
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => array(
                        'enabled' => true,
                        'format' => '<b>{point.name}</b><br> {point.percentage:.1f} %',
                    )
                ),
            ),
            'tooltip' => array(
                'formatter' => "js:function(){
                    return '<b>'+ this.point.name +'</b>: '+ formatTime(this.y)  +' " . Yii::t('smith', "horas") . "';
                }",
                'crosshairs' => true,
                'shared' => true,
            ),
            'series' => array(
                array('name' => Yii::t('smith', 'Produtivo'), 'data' => $seriesArray[$i]),

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

    $("#voltar").click(function () {
        $(".extras").hide();
        $(".widget").show();
        $(this).hide();
    });

    function exibeGrafico(id) {
        $(".widget").hide();
        $(".extras").hide();
        $("#voltar").show();
        $("#" + id).show();
        resizeWindow();
    }
    function formatTime(secs) {
        var times = new Array(3600, 60, 1);
        var time = '';
        var tmp;
        for (var i = 0; i < times.length; i++) {
            tmp = Math.floor(secs / times[i]);
            if (tmp < 1) {
                tmp = '00';
            }
            else if (tmp < 10) {
                tmp = '0' + tmp;
            }
            time += tmp;
            if (i < 2) {
                time += ':';
            }
            secs = secs % times[i];
        }
        return time;
    }

</script>