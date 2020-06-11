<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Métricas'),
);
?>
<div id="container2" data-json='<?= $jsonMetricaPorDia ?>' style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script>
    $(function () {

        var json = $("#container2").data('json');

        var categorias = [];
        var min = [];
        var max = [];
        var total = [];
        var flag = false;
        for (var i = 0; i < json.length; i++) {
            categorias[i] = json[i].MC_data;
            if (json[i].M_meta !== null)
            {
                min[i] = parseInt(json[i].M_min);
                max[i] = parseInt(json[i].M_max);
                total[i] = json[i].MC_entradas;
                flag = true;
            }
            else
            {
                min[i] = parseInt(json[i].M_min) / 60;
                max[i] = parseInt(json[i].M_max) / 60;
                total[i] = time_to_minute(json[i].MC_total);
            }
            
        }

        $('#container2').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Gráfico de Métrica por Equipe',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categorias
            },
            yAxis: {
                title: {
                    text: flag ? 'Quantidade de métricas' : 'Minutos'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            tooltip: {
                valueSuffix: flag ? '' : 'm'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: 'Máximo',
                    data: max
                }, {
                    name: 'Mínimo',
                    data: min
                }, {
                    name: 'Total',
                    data: total
                }]
        });
    });
</script>