<?php

/**
 * Class ConsolidaMetricaCommand
 *
 * CRON utilizado para consolidar os registros de produtividade baseados nas métricas cadastradas.
 */

class ConsolidaMetricaCommand extends CConsoleCommand
{
    public function run()
    {
        try {
            $dataAtual = gmdate('Y-m-d', time() - (3600 * 27));
            $metricas = Metrica::model()->findAll();
            foreach ($metricas as $metrica) {
                $logsMetricas = LogAtividadeConsolidado::model()->getConsolidaMetrica($metrica, $dataAtual);
                foreach ($logsMetricas as $value) {
                    $modelMetrica = new MetricaConsolidada;
                    $modelMetrica->entradas = $value->num_logs;
                    $modelMetrica->total = $value->duracao;
                    $modelMetrica->media = $value->tempoMedio;
                    $modelMetrica->data = $dataAtual;
                    $modelMetrica->fk_metrica = $metrica->id;
                    $modelMetrica->fk_colaborador = Colaborador::model()->findByAttributes(array("ad" => $value->usuario, "serial_empresa" => $metrica->serial_empresa))->id;
                    $modelMetrica->fk_empresa = $metrica->fk_empresa;
                    $modelMetrica->save();
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}