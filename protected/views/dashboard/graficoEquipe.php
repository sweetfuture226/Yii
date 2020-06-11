<div class="panel minimal minimal-gray">
    <div class="panel-heading">
        <div class="panel-title">
            <?php echo CHtml::link(Yii::t("smith", 'Produtividade das equipes de'), array('Produtividade/RelatorioEquipe'), array('target' => '_blank')); ?><?php echo ' ' . $dateInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dateFim; ?>
        </div>
        <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
            <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <div class="buttons">
            <div style="float: left; ">
                <?php echo CHtml::button(Yii::t("smith", 'Voltar'), array('class' => 'btn btn-info submitForm', 'id' => 'voltar_eq', 'style' => 'display: none')); ?>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="widget">
            <div id="grafico">
                <?php
                $this->Widget('ext.highcharts.HighchartsWidget', array(
                    'scripts' => array(
                        'highcharts-more',
                        'modules/drilldown',
                    ),
                    'options' => array(
                        'colors' => array('#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'),
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
                            'text' => Yii::t('smith', 'Clique na barra para ver detalhado')
                        ),
                        'chart' => array(
                            'type' => 'bar',
                        ),
                        'xAxis' => array(
                            'categories' => $dadosGraficoEquipe[1],
                            'plotLines' => array(array(
                                'value' => (date('w')),
                                'width' => 1,
                                'color' => '#808080'
                            )),
                        ),
                        'yAxis' => array(
                            array(
                                'title' => array(
                                    'text' => Yii::t('smith', 'Coeficiente de Produtividade (%)'),
                                ),
                                'allowDecimals' => false,
                                'labels' => array(
                                    'formatter' => "js:function(){
                                            return this.value+'%';
                                        }",
                                ),
                                'min' => 0,
                            ), array(
                                'title' => array(
                                    'text' => Yii::t('smith', 'Meta (%)'),
                                ),
                                'allowDecimals' => false,
                                'labels' => array(
                                    'formatter' => "js:function(){
                                            return this.value+'%';
                                        }",
                                ),
                                'min' => 0,
                                'opposite' => true,
                            ),
                        ),
                        'plotOptions' => array(
                            'bar' => array(
                                'stacking' => 'normal',
                                'colorByPoint' => true,
                                'point' => array(
                                    'events' => array(
                                        'click' => 'js:function() {
                                            changeGrafico(this.x);
                                        }'
                                    )
                                )
                            ),
                            'spline' => array(
                                'color' => '#434348',
                            ),
                        ),
                        'tooltip' => array(
                            'formatter' => "js:function(){
                                var s = '<b>" . Yii::t("smith", "Equipe") . ": </b>'+ this.x +'';
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
                                return s+'<br/>" . Yii::t('smith', 'Nenhum') . "';
                            }",
                            'crosshairs' => true,
                            'shared' => true,
                        ),
                        'series' => array(
                            $dadosGraficoEquipe[0],
                            $dadosGraficoEquipe[5]
                        )
                    )
                ));
                ?>
            </div>

            <?php if (isset($dadosGraficoEquipe)) {
                foreach ($dadosGraficoEquipe[2] as $equipe => $produzidoEq) { ?>
                    <div style="display: block" class="graficosExtras"
                         id="grafico_<?php echo array_search($equipe, array_keys($dadosGraficoEquipe[2])) ?>">
                        <?php
                        $this->Widget('ext.highcharts.HighchartsWidget', array(
                            'scripts' => array(
                                'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                // adds Exporting button/menu to chart
                            ),
                            'options' => array(
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
                                    'text' => Yii::t('smith', 'Produtividade da equipe') . " " . $produzidoEq['name'] . " " . Yii::t('smith', 'entre') . " $dateInicio " . Yii::t('smith', 'e') . " $dateFim"
                                ),
                                'chart' => array(
                                    'type' => 'bar',

                                    ),
                                'xAxis' => array(
                                    'categories' => $dadosGraficoEquipe[3][$equipe],
                                    'plotLines' => array(array(
                                        'value' => (date('w')),
                                        'width' => 1,
                                        'color' => '#808080'
                                    )),
                                ),
                                'yAxis' => array(
                                    'title' => array(
                                        'text' => Yii::t('smith', 'Coeficiente de Produtividade (%)'),
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
                                        'colorByPoint' => true,
                                        'dataLabels' => array(
                                            'enabled' => true,
                                            'color' => 'black',
                                            'formatter' => "js:function(){
                                                    if (this.y > 0)
                                                        return this.y+'%';
                                                }",
                                        ),
                                    ),
                                    'bar' => array(
                                        'point' => array(
                                            'events' => array(
                                                'click' => 'js:function() {
                                                        $.ajax({
                                                            url: baseUrl +"/colaborador/getIdByNomeCompletoAjax",
                                                            type: "POST",
                                                            data: {nome: this.category},
                                                        }).done(function(data) {
                                                            $("#col_dias_id").val(data);
                                                        }).fail(function(data) {
                                                            alert("Colaborador não encontrado. Contate a equipe de desenvolvimento.");
                                                        });
                                                        $("#modal_title_dias").text(this.category);
                                                        $("#nome_col").text(this.category);
                                                        $("#produtividade_col").modal("show");
                                                    }'
                                            )
                                        )
                                    ),
                                ),
                                'tooltip' => array(
                                    'formatter' => "js:function(){

                                    var s = '<b>" . Yii::t('smith', 'Colaborador') . ": </b>'+ this.x +'';
                                    var flag = false;

                                    $.each(this.points, function(i, point) {
                                        if(this.y >0){
                                            flag = true;
                                            s += '<br/>'+ '<b>" . Yii::t('smith', 'Produtividade') . "</b>' +
                                                ':  '+ this.y + '%';
                                        }
                                    });
                                    if(flag)
                                        return s;
                                    return s+'<br/>" . Yii::t('smith', 'Nenhum') . "';
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
                <?php }
            } ?>
        </div>
    </div>
</div>

<div class="modal fade" id="produtividade_col" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" type="button" class="close">×</button>
                <h4 class="modal-title" id="modal_title_dias"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <p><?php echo Yii::t('smith', 'Deseja ver a produtividade detalhada de <span id="nome_col"></span> no período de ' . $dateInicio . ' a ' . $dateFim . '?'); ?></p>
                </div>
                <form id="colaborador_dias"
                      action="<?php echo Yii::app()->baseUrl ?>/produtividade/RelatorioIndividualDias" method="POST"
                      target="_blank">
                    <input id="col_dias_id" name="colaborador_id" type="hidden">
                    <input id="date_dias_from" name="date_from" type="hidden" value="<?php echo $dateInicio ?>">
                    <input id="date_dias_to" name="date_to" type="hidden" value="<?php echo $dateFim ?>">
                    <input id="button_dias" name="button" type="hidden">
                </form>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Gerar Planilha', array('class' => 'btn btn-info', 'onclick' => 'valida()')); ?>
                <?php echo CHtml::button('Gerar Gráfico', array('class' => 'btn btn-info', 'onclick' => 'valida2Grafico()')); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".graficosExtras").hide();
    });

    $("#voltar_eq").click(function () {
        $(".graficosExtras").hide();
        $("#grafico").show();
        $(this).hide();
    });

    function changeGrafico(id) {
        $("#grafico").hide();
        $(".graficosExtras").hide();
        $("#voltar_eq").show();
        $("#grafico_" + id).show();
        resizeWindow();
    }
    ;

    function valida() {
        $('#button_dias').val('excel');
        $('#colaborador_dias').submit();
        return true;
    }

    function valida2Grafico() {
        $('#button_dias').val('');
        $('#colaborador_dias').submit();
        return true;
    }
</script>
