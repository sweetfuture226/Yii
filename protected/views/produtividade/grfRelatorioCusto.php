<?php
$this->breadcrumbs = array(

    Yii::t("smith", "Custo"),
); ?>
<?php $l = (!isset($larg)) ? 12 : $larg; ?>

<div class="form-group  col-lg-4">
    <p>
        <?php
        echo CHtml::label(Yii::t("smith", 'Selecione'), 'fk_equipe');
        echo CHtml::dropdownlist('equipe', '', $options, array("class" => "chzn-select", "style" => "width:100%;"));
        ?>
    </p>
</div>
<div style="clear: both"></div>

<div class="grid_<?php echo $l ?>">
    <div class="block-border">

        <div class="block-content">

            <?php //aqui novo grafico
            for ($i = 1; $i <= count($produzido); $i++) {
                $class = ($i > 1) ? "widget extras" : "widget";
                echo "<div id='$i' class='$class'>";
                $this->Widget('ext.highcharts.HighchartsWidget', array(
                    'scripts' => array(
                        'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                        'modules/exporting', // adds Exporting button/menu to chart

                    ),
                    'options' => array(
                        'exporting' => array(
                            'sourceHeight' => 800,
                            'sourceWidth' => 1280,
                            'filename' => '[VivaSmith] - Produtividade em custo'

                        ),
                        'colors' => array("#A61414", "#34A878"),
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
                            'text' => Yii::t('smith', 'Custo aproximado de') . " " . $title . " " . Yii::t('smith', 'entre') . " " . $data_inicio . " " . Yii::t('smith', 'e') . " " . $data_fim
                        ),
                        'chart' => array(
                            'type' => 'bar',
                            'height' => $heightGrafico,
                            'margin' => array(50, 50, 140, 120),
                            /*
                            'marginRight'=> 130,
                            'marginBottom' => 25,*/
                        ),
                        'xAxis' => array(
                            'categories' => $categorias[$i - 1],
//            'categories' => array('Grupo 1','Grupo 2','Grupo 3','Grupo 4'),
                            'plotLines' => array(array(
                                'value' => (date('w')),
                                'width' => 1,
                                'color' => '#808080'
                            )),
                        ),
                        'yAxis' => array(
                            'title' => array(
                                'text' => Yii::t('smith', 'Salário por coeficiente de produtividade'),
                            ),
                            'allowDecimals' => false,
                            'labels' => array(
                                'formatter' => "js:function(){
                                    value = parseInt(this.value.toFixed(2).toString().replace(/[^\d]+/g, ''));
                                    var tmp = value+'';
                                    tmp = tmp.replace(/([0-9]{2})$/g, ',$1');
                                    if( tmp.length > 6 )
                                         tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, '.$1,$2');
                                    return 'R$'+(tmp);
                                }",

                            ),
                            'min' => 0,
                            'stackLabels' => array(
                                'enabled' => true,
                                'formatter' => "js:function(){
                                    if (this.total > 0)
                                        return 'R$'+this.total.toFixed(2);
                                }",
                                'style' => array(
                                    'fontWeight' => 'bold',
                                    'color' => 'gray'
                                ),
                            ),

                        ),
                        'plotOptions' => array(
                            'column' => array(
                                'stacking' => 'normal',
                                'dataLabels' => array(
                                    'enabled' => true,
                                    'color' => 'black',
                                    'formatter' => "js:function(){
                                    if (this.y > 0)
                                        return 'R$'+this.y.toFixed(2) + ' [' + ((this.y*100)/this.total).toFixed(2) + '%' + ']';
                                }",
                                ),

                            ),
                        ),
                        'tooltip' => array(
                            'formatter' => "js:function(){
                                var s = '<b>'+ this.x +'</b>';
                                var flag = false;
                                var value;
                                var total = 0;

                                $.each(this.points, function(i, point) {
                                    if(this.y >0){
                                        total += this.y;
                                    }
                                });

                                $.each(this.points.reverse(), function(i, point) {
                                    if(this.y >0){
                                        flag = true;
                                        value = parseInt(this.y.toFixed(2).toString().replace(/[^\d]+/g, ''));
                                        var tmp = value+'';
                                        tmp = tmp.replace(/([0-9]{2})$/g, ',$1');
                                        if( tmp.length > 6 )
                                                tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, '.$1,$2');
                                        percentage = ((this.y/total)*100).toFixed(1);
                                        s += '<br/>'+ point.series.name +
                                            ': R$'+ tmp + ' - (' + percentage + '%)';
                                    }
                                });
                                valueTotal = parseInt(total.toFixed(2).toString().replace(/[^\d]+/g, ''));
                                var tmpTotal = valueTotal+'';
                                tmpTotal = tmpTotal.replace(/([0-9]{2})$/g, ',$1');
                                if( tmpTotal.length > 6 )
                                        tmpTotal = tmpTotal.replace(/([0-9]{3}),([0-9]{2}$)/g, '.$1,$2');
                                s += '<br/>" . Yii::t('smith', 'Custo total') . ": R$'+ tmpTotal;
                                if(flag)
                                    return s;
                                return s+'<br/>". Yii::t('smith', 'Nenhum') ."';
                            }",
                            'crosshairs' => true,
                            'shared' => true,
                        ),
                        'series' => array(
                            array(
                                'name' => Yii::t('smith', 'Ausente do computador'),
                                'data' => $ocioso[$i - 1]
                            ),
                            array(
                                'name' => Yii::t('smith', 'Produtivo'),
                                'data' => $produzido[$i - 1]
                            ),
                        )
                    )
                ));
                echo "</div>";
            }

            ?>


        </div>
    </div>
</div>


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