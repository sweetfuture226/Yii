<?php

/**
 * Class ConsolidaMetricaCommand
 *
 * CRON utilizado para consolidar os registros de produtividade baseados nas métricas cadastradas.
 */
class PovoaMetricaConsolidadaCommand extends CConsoleCommand
{
    public function run()
    {
        try {
            //$dataAtual = gmdate('Y-m-d', time() - (3600 * 27));
            $metricas = Metrica::model()->findAll(array('condition' => 'fk_empresa = 54'));
            $date = date('Y-m-01');
            $end_date = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
            $data = date('Y-m-d', strtotime($date));
            while (strtotime($data) <= strtotime($end_date)) {
                foreach ($metricas as $metrica) {
                    $logsMetricas = LogAtividadeConsolidado::model()->getConsolidaMetrica($metrica, $data);
                    foreach ($logsMetricas as $value) {
                        $modelMetrica = new MetricaConsolidada;
                        $modelMetrica->entradas = $value->num_logs;
                        $modelMetrica->total = $value->duracao;
                        $modelMetrica->media = $value->tempoMedio;
                        $modelMetrica->data = $data;
                        $modelMetrica->fk_metrica = $metrica->id;
                        $modelMetrica->fk_colaborador = Colaborador::model()->findByAttributes(array("ad" => $value->usuario, "serial_empresa" => $metrica->serial_empresa))->id;
                        $modelMetrica->fk_empresa = $metrica->fk_empresa;
                        $modelMetrica->save();
                    }
                }
                $data = date("Y-m-d", strtotime("+1 day", strtotime($data)));
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}