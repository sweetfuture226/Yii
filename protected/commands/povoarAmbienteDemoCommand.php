<?php

/**
 * Class PovoarAmbienteDemoCommand
 *
 * CRON para auxiliar a consolidação das tabelas manualmente determinando um intervalo de datas.
 */

class PovoarAmbienteDemoCommand extends CConsoleCommand {
    public function run() {
        try {
            // Start date
            $date = '2015-07-01';
            // End date
            //$end_date = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
            $end_date = '2015-07-31';
            $sql = 'SELECT id, serial FROM empresa WHERE id = 22  ';
            $command = Yii::app()->getDb()->createCommand($sql);
            $ids = $command->queryAll();
            foreach($ids as $value){
                //$idEmpresa = $value['id'];
                $idEmpresa = 41;
                //ConsolidadorBlacklist::consolidar($idEmpresa, $value['serial']);
                $data = date('Y-m-d', strtotime($date));
                while (strtotime($data) <= strtotime($end_date)) {
                    echo "$idEmpresa no dia $data\n";
                    //ConsolidadorProdutividade::consolidar($data, $idEmpresa);
                    //ConsolidadorPrograma::consolidar($data, $idEmpresa,$value['serial']);
                    ConsolidadorColaborador::consolidar($data, $idEmpresa,$value['serial']);
                    //ConsolidadorProjeto::consolidar($data, $idEmpresa,$value['serial']);
                    //ConsolidadorDocumentosSemContrato::consolidar($idEmpresa, $value['serial'],$data);
                    $data = date("Y-m-d", strtotime("+1 day", strtotime($data)));
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}