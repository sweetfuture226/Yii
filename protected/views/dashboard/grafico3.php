<div class="panel minimal minimal-gray">
    <div class="panel-heading">
        <div class="panel-title">
            <?php echo CHtml::link(Yii::t("smith", 'Produtividade por programas e sites de'), array('programasSites/RelatorioGeral'), array('target' => '_blank')); ?><?php echo '    ' . $dateInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dateFim; ?>
        </div>
        <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
            <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <div class="buttons">
            <div style="float: left; ">
                <?php echo CHtml::button(Yii::t("smith", 'Voltar'), array('class' => 'btn btn-info submitForm', 'id' => 'voltar', 'style' => 'display: none')); ?>
            </div>
        </div>
        <div class="widget-eqp">
        <?php
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
                    'text' => Yii::t('smith', 'Clique na fatia para ver detalhado')
                ),
                'chart' => array(
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
                        array('name' => Yii::t('smith', 'Programas'), 'y' => $grafico3['programa']),
                        array('name' => Yii::t('smith', 'Sites'), 'y' => $grafico3['site']),
                        array('name' => Yii::t('smith', 'Não identificado'), 'y' => $grafico3['nao_identificado']),
                        array('name' => Yii::t('smith', 'Atividade externa'), 'y' => $grafico3['externa']),
                        array('name' => Yii::t('smith', 'Ausente do computador'), 'y' => $grafico3['ocioso']),
                    )),

                ),
            )
        ));
        ?>
    </div>
    <?php
    foreach ($extras as $key => $extra) {
        $class = "extras";
        echo "<div id='" . array_search($key, array_keys($extras)) . "' class='$class'>";
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
                    'text' => Yii::t('smith', 'Detalhado')
                ),
                'chart' => array(
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
                            return '<b>'+ this.point.name +'</b>: '+ formatTime(this.y) +' " . Yii::t('smith', "horas") . "';
                        }",
                    'crosshairs' => true,
                    'shared' => true,
                ),
                'series' => array(
                    array('name' => Yii::t('smith', 'Produtivo'), 'data' => $extra),
                ),
            )
        ));
        echo "</div>";
    }
    ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".extras").hide();
    });

    $("#voltar").click(function () {
        $(".extras").hide();
        $(".widget-eqp").show();
        $(this).hide();
    });

    function exibeGrafico(id) {
        $(".widget-eqp").hide();
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
    <!-- <style>
        tspan {
            text-shadow: none;
        }
    </style> -->