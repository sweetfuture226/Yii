<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Produtividade'),
);
?>


<?php if ($equipe == 'Todas') { ?>
    <div class="buttons">
        <div style="float: left; ">
            <?php echo CHtml::button(Yii::t("smith", 'Voltar'), array('class' => 'btn btn-info submitForm', 'id' => 'voltar', 'style' => 'display: none')); ?>
        </div>
    </div>
<?php
} else {
    //$nomeEquipe = Equipe::model()->findByPk($equipe)->nome;
    ?>
    <div class="buttons">
        <div style="float: left; ">
            <?php echo CHtml::button(Yii::t("smith", 'Ver detalhado'), array('class' => 'btn btn-info submitForm', 'id' => 'detalhado')); ?>
    <?php echo CHtml::button(Yii::t("smith", 'Voltar'), array('class' => 'btn btn-info submitForm', 'id' => 'voltar', 'style' => 'display: none')); ?>
    <?php echo CHtml::hiddenField('equipeNome', $equipe); ?>
        </div>
    </div>
<?php } ?>
<div style="clear: both"></div>
<div class="widget">
    <div  id="grafico" >
        <?php
        $this->Widget('ext.highcharts.HighchartsWidget', array(
            'options' => array(
                'exporting' => array(
                    'sourceHeight' => 800,
                    'sourceWidth' => 1280,
                    'filename' => '[VivaSmith] - Produtividade por equipe'

                ),
                'colors'=> array('#4572A7','#AA4643','#89A54E','#80699B','#3D96AE','#DB843D','#92A8CD','#A47D7C','#B5CA92'),
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
                    'text' => Yii::t('smith', 'Produtividade entre') . " $data_inicio " . Yii::t('smith', 'e ') . $data_fim
                ),
                'subtitle' => array(
                    'text' => Yii::t('smith', 'Clique na barra para ver detalhado')
                ),
                'chart' => array(
                    'margin' => array(80, 50, 100, 120),
                    'type' => 'bar'
                ),
                'xAxis' => array(
                    'categories' => $categorias,
                    'plotLines' => array(array(
                            'value' => (date('w')),
                            'width' => 1,
                            'color' => '#808080'
                        )),
                ),
                'yAxis' => array(
                    array(
                        'title' => array(
                            'text' => Yii::t('smith', 'Coeficiente de Produtividade (%)' ),
                        ),
                        'allowDecimals' => false,
                        'labels' => array(
                            'formatter' => "js:function(){
                                    return this.value+'%';
                                }",
                        ),
                        'min' => 0,
                    ),array(
                        'title' => array(
                            'text' => Yii::t('smith', 'Meta (%)' ),
                        ),
                        'allowDecimals' => false,
                        'labels' => array(
                            'formatter' => "js:function(){
                                    return this.value+'%';
                                }",
                        ),
                        'min' => 0,
                        'opposite'=>true,
                    ),
                ),
                'plotOptions' => array(
                    'bar' => array(
                        'stacking' => 'normal',
                        'colorByPoint'=>true,
                        'point' => array(
                            'events' => array(
                                'click' => 'js:function() {
                                    changeGrafico(this.x);
                                }'
                            )
                        )
                    ),
                    'spline' => array(
                        'color'=>'#434348',
                    ),
                ),
                'tooltip' => array(
                    'formatter' => "js:function(){

                    var s = '<b>".Yii::t('smith', 'Equipe').": </b>'+ this.x +'';
                    var flag = false;

                    $.each(this.points, function(i, point) {
                        if(this.y >0){
                            flag = true;
                            s += '<br/>'+ '<b>'+ this.series.name + '</b>' +
                                ':  '+ this.y + '%';
                        }
                    });
                    if(flag)
                        return s;
                    return s+'<br/>".Yii::t('smith', 'Nenhum')."';
                }",
                    'crosshairs' => true,
                    'shared' => true,
                ),
                'series' => array(
                    $produzido,
                    $meta,
                ),
            )
        ));
        ?>
    </div>
</div>
<div class="widget">

        <?php foreach ($produzidoEquipe as $equipe => $produzidoEq) { ?>

            <div style="display: block" class="graficosExtras col-lg-12"
                 id="grafico_<?php echo array_search($equipe, array_keys($produzidoEquipe)) ?>">
            <?php
            $this->Widget('ext.highcharts.HighchartsWidget', array(
                'options' => array(
                    'exporting' => array(
                        'sourceHeight' => 800,
                        'sourceWidth' => 1280,
                        'filename' => '[VivaSmith] - Produtividade por equipe'

                    ),
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
                        'text' =>Yii::t('smith', 'Produtividade da equipe ').  $produzidoEq['name'] ." entre $data_inicio e $data_fim"
                    ),
                    'credits' => array('enabled' => false),
                    'chart' => array(
                        'type' => 'bar',
                    ),
                    'xAxis' => array(
                        'categories' => $categoriaEquipe[$equipe],
                        'plotLines' => array(array(
                                'value' => (date('w')),
                                'width' => 1,
                                'color' => '#808080'
                            )),
                    ),
                    'yAxis' => array(
                        'title' => array(
                            'text' => Yii::t('smith', 'Coeficiente de Produtividade (%)' ),
                        ),
                        'allowDecimals' => false,
                        'labels' => array(
                            'formatter' => "js:function(){
                                    return this.value+'%';
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
                                        return this.y+'%';
                                }",
                            ),
                        ),
                    ),
                    'tooltip' => array(
                        'formatter' => "js:function(){

                    var s = '<b>". Yii::t('smith', 'Colaborador') .": </b>'+ this.x +'';
                    var flag = false;

                    $.each(this.points, function(i, point) {
                        if(this.y >0){
                            flag = true;
                            s += '<br/>'+ '<b>".Yii::t('smith', 'Produtividade')."</b>' +
                                ':  '+ this.y + '%';
                        }
                    });
                    if(flag)
                        return s;
                    return s+'<br/>".Yii::t('smith', 'Nenhum')."';
                }",
                        'crosshairs' => true,
                        'shared' => true,
                    ),
                    'series' => array(
                        $produzidoEq
                    ),
                )
            ));
            ?>
        </div>
        <?php } ?>
</div>
<script>
    $(document).ready(function () {
        $(".graficosExtras").hide();
        // $(".graficosExtras").css('opacity','0');
    });

    $("#voltar").click(function () {
        $(".graficosExtras").hide();
        $("#grafico").show();
        $(this).hide();
    });

    function changeGrafico(id) {
        $("#grafico").hide();
        $(".graficosExtras").hide();
        $("#voltar").show();
        $("#grafico_" + id).show();
        resizeWindow();
    }
    ;
    // $("#equipe").change(function () {
    //     var equipe = $("#equipe").val();
    //     if (equipe != "") {
    //         $("#grafico").hide();
    //         $(".graficosExtras").hide();
    //         $("#grafico_" + equipe).show(function(){
    //             $(window).trigger('resize');
    //             $("#grafico_" + equipe).css('opacity','1');
    //         });
    //     }
    //     else {
    //         $(".graficosExtras").hide();
    //         $("#grafico").show();
    //     }
    // });
    // $("#detalhado").click(function () {
    //     var equipe = $("#equipeNome").val();
    //     $("#grafico").hide();
    //     $("#detalhado").hide();
    //     $("#voltar").show();
    //     $("#grafico_" + equipe).show(function(){
    //         $(window).trigger('resize');
    //         $("#grafico_" + equipe).css('opacity','1');
    //     });
    // });
    // $("#voltar").click(function () {
    //     var equipe = $("#equipeNome").val();
    //     $("#grafico_" + equipe).hide();
    //     $("#grafico").show();
    //     $("#detalhado").show();
    //     $("#voltar").hide();
    // });
</script>