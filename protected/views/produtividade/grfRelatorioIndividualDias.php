<?php $this->breadcrumbs=array(
    Yii::t("smith",'Produtividade em dias'),
); ?>

<?php foreach ($produzido as $mes=>$produzidoMes) {
    $mes = explode('/', $mes);
    $ano = $mes[1];
    $mesExtenso = MetodosGerais::mesString($mes[0]);
    $label = Yii::t('smith', $mesExtenso).'/'.$ano; ?>
    <div style="float: left; margin: 3px 0 0 3px">
        <?php echo CHtml::button( $label, array('class' => 'btn btn-info submitForm', 'id' => $mes[0],'onclick'=>'exibir(this);')); ?>
    </div>
<?php } ?>

<div style="clear: both"></div>

<div class="modal fade" id="col_prod" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" type="button" class="close">×</button>
                <h4 class="modal-title"><?php echo $colaborador; ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <p><?php echo Yii::t('smith', 'Deseja ver a produtividade detalhada de ' . $colaborador . ' no dia <span id="data_prod"></span>?'); ?></p>
                </div>
                <form id="colaborador-dia-form"
                      action="<?php echo Yii::app()->baseUrl ?>/produtividade/RelatorioIndividual" method="POST"
                      target="_blank">
                    <input id="colaborador_id" name="colaborador_id" type="hidden"
                           value="<?php echo $colaboradorAd; ?>">
                    <input id="tipo" name="tipo" type="hidden" value="dias">
                    <input id="dataDia" name="dataDia" type="hidden">
                    <input id="button" name="button" type="hidden">
                </form>
                <form id="colaborador-dia-form2"
                      action="<?php echo Yii::app()->baseUrl ?>/programasSites/RelatorioIndividual" method="POST"
                      target="_blank">
                    <input id="colaborador_id2" name="colaborador_id" type="hidden"
                           value="<?php echo $colaboradorId; ?>">
                    <input id="data" name="data" type="hidden">
                    <input id="button" name="button" type="hidden">
                </form>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Gerar PDF', array('class' => 'btn btn-info', 'onclick' => 'validaProd()')); ?>
                <?php echo CHtml::button('Gerar Gráfico', array('class' => 'btn btn-info', 'onclick' => 'valida2GraficoProd()')); ?>
            </div>
        </div>
    </div>
</div>

<?php
    $flag = false;
    foreach ($produzido as $mesAno=>$produzidoMes){
        $mes = explode('/', $mesAno);
        $class = (!$flag) ? "primeiro" : "proximos"; ?>
        <div class="<?=$class?>"   id="grafico_<?=$mes[0]?>" >
        <?php $mesExtenso = MetodosGerais::mesString($mes[0]);

            $this->Widget('ext.highcharts.HighchartsWidget', array(
            'options'=>array(
                'exporting' => array(
                    'sourceHeight' => 800,
                    'sourceWidth' => 1280,
                    'filename' => '[VivaSmith] - Produtividade em dias'

                ),
                'colors'=> array("#95CEFF","#34A878"),
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
                    'text' => Yii::t("smith",'Produtividade diária de')." $colaborador ". Yii::t("smith",'no mês de '). $mesExtenso,
                ),
                'credits' => array('enabled' => false),
                'chart' => array(
                    'height'=>'600',
                    'inverted'=>true,
                    'margin' => array(50, 50, 100, 100),
                    'type' => 'area',
                ),
                'xAxis' => array(
                    'categories' => $categorias[$mesAno],
                    'plotLines'=> array(array(
                        'value' => (date('w')),
                        'width' => 1,
                        'color' => '#808080'
                    )),
                ),
                'yAxis' => array(
                    'title' => array(
                        'text' => Yii::t("smith",'Total de horas'),
                    ),
                    'allowDecimals'=>false,
                    'labels' => array(
                        'formatter' => "js:function(){
                                            return this.value+' ". Yii::t('smith', 'hora(s)')."';
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
                    'area' => array(
                        'point' => array(
                            'events' => array(
                                'click' => 'js:function() {
                                    $("#data_prod").text(this.category);
                                    $("#dataDia").val(this.category);
                                    $("#data").val(this.category);
                                    $("#col_prod").modal("show");
                                }'
                            )
                        )
                    )
                ),
                'tooltip' => array(
                    'formatter' => "js:function(){
                        var s = '<b>".Yii::t('smith', 'Data').": </b>'+ this.x +'';
                        var flag = false;
                        $.each(this.points, function(i, point) {
                            if(this.y >0){
                                flag = true;
                                s += '<br/>'+ point.series.name +
                                    ':  '+ this.y + ' h';
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
                    $previsto[$mesAno],
                    $produzidoMes,
                ),
            )
            ));
?>
</div>
<?php $flag= true; } ?>

<script>
    $(document).ready(function () {
        $(".proximos").hide();
    });

    function exibir(obj) {
        var idGrafico = obj.id;
        $(".proximos").hide();
        $(".primeiro").hide();
        $("#grafico_" + idGrafico).show();
        resizeWindow()
    }

    function validaProd() {
        $('#colaborador-dia-form2').submit();
        return true;
    }

    function valida2GraficoProd() {
        $('#colaborador-dia-form').submit();
        return true;
    }
</script>