<?php

/**
 * Class AlertaColaboradorMetricaCommand
 *
 * CRON utilizado para verificar a incidência de acesso as métricas por parte dos colaboradores e informar se houve produtividade
 * acima ou abaixo dos limites definidos para cada métrica; caso haja algum resultado será criado uma notificação.
 */
class AlertaColaboradorMetricaCommand extends CConsoleCommand{
    public function run(){
        try{
            $data = date('Y-m-d',strtotime('-1 day'));
            $metricas = MetricaConsolidada::model()->findAllByAttributes(array('data'=>$data));
            foreach($metricas as $objMetrica) {
                if ($objMetrica->metrica->tempo != NULL) {
                    if (MetodosGerais::time_to_seconds($objMetrica->total) < MetodosGerais::time_to_seconds($objMetrica->metrica->min)) {
                        echo "abaixo do minimo de tempo \t colaborador : $objMetrica->fk_colaborador  \t minimo: " . $objMetrica->metrica->min . ", duracao : " . $objMetrica->total . "\n";
                        $modelNotificacao = new Notificacao();
                        $modelNotificacao->notificacao = utf8_encode(Colaborador::model()->findByPk($objMetrica->fk_colaborador)->nomeCompleto . "est� com produtividade na m�trica abaixo do limite m�nimo");
                        $modelNotificacao->fk_empresa = $objMetrica->fk_empresa;
                        $modelNotificacao->fk_usuario = 0;
                        $modelNotificacao->tipo = 7;
                        $modelNotificacao->save();
                    } elseif (MetodosGerais::time_to_seconds($objMetrica->total) > MetodosGerais::time_to_seconds($objMetrica->metrica->max)) {
                        echo "acima do maximo de tempo \t colaborador : $objMetrica->fk_colaborador  \t maximo: " . $objMetrica->metrica->max . ", duracao : " . $objMetrica->total . "\n";
                        $modelNotificacao = new Notificacao();
                        $modelNotificacao->notificacao = utf8_encode(Colaborador::model()->findByPk($objMetrica->fk_colaborador)->nomeCompleto . "est� com produtividade na m�trica acima do limite m�ximo");
                        $modelNotificacao->fk_empresa = $objMetrica->fk_empresa;
                        $modelNotificacao->fk_usuario = 0;
                        $modelNotificacao->tipo = 7;
                        $modelNotificacao->save();
                    }
                }
                else{
                    if ($objMetrica->entradas < $objMetrica->metrica->min) {
                        echo "abaixo do minimo de entradas \t colaborador : $objMetrica->fk_colaborador  \t minimo: " . $objMetrica->metrica->min . ", entradas : " . $objMetrica->entradas . "\n";
                        $modelNotificacao = new Notificacao();
                        $modelNotificacao->notificacao = utf8_encode(Colaborador::model()->findByPk($objMetrica->fk_colaborador)->nomeCompleto . "est� com produtividade na m�trica abaixo do limite m�nimo");
                        $modelNotificacao->fk_empresa = $objMetrica->fk_empresa;
                        $modelNotificacao->fk_usuario = 0;
                        $modelNotificacao->tipo = 7;
                        $modelNotificacao->save();
                    } elseif ($objMetrica->entradas > $objMetrica->metrica->max) {
                        echo "acima do maximo de entradas \t colaborador : $objMetrica->fk_colaborador  \t maximo: " . $objMetrica->metrica->max . ", entradas : " . $objMetrica->entradas . "\n";
                        $modelNotificacao = new Notificacao();
                        $modelNotificacao->notificacao = utf8_encode(Colaborador::model()->findByPk($objMetrica->fk_colaborador)->nomeCompleto . "est� com produtividade na m�trica acima do limite m�ximo");
                        $modelNotificacao->fk_empresa = $objMetrica->fk_empresa;
                        $modelNotificacao->fk_usuario = 0;
                        $modelNotificacao->tipo = 7;
                        $modelNotificacao->save();
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