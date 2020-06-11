<?php

/**
 * Class NotificaMetricaCommand
 *
 * CRON para verificar se houve captura de produtividade em métricas que os colaboradores não estão associados.
 */
class NotificaMetricaCommand extends CConsoleCommand{
    public function run(){
        try{
            $data = date('Y-m-d',strtotime('-1 day'));
            $metricas = MetricaConsolidada::model()->findAllByAttributes(array('data'=>$data));
            foreach($metricas as $obj){
                if(isset($obj->metrica)) {
                    $usuarios = LogAtividadeConsolidado::model()->getCronColaboradorMetrica($obj->metrica->fk_empresa, $data, $obj->metrica->programa, $obj->metrica->criterio);
                    foreach($usuarios as $key=>$objColaborador){
                        $model = new ColaboradorSemMetrica();
                        $model->fk_metrica = $obj->metrica->id;
                        $model->fk_colaborador = $objColaborador->id;
                        $model->fk_empresa = $obj->metrica->fk_empresa;
                        $model->data = $data;
                        $model->save();
                    }
                }
            }
        }
        catch(Exception $e){
            Logger::sendException($e);
        }
    }
}

?>