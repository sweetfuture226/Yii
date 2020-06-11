<?php

/**
 * Class consolidaGraficoCommand
 *
 * CRON utilizado para consolidar os registros da tabela de log consolidada e auxiliar no processo de requisição
 * dos relatórios do sistema.
 */

class consolidaGraficoCommand extends CConsoleCommand {
    public function run() {
        try {
            $date = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
            $sql = 'SELECT id, serial FROM empresa';
            $command = Yii::app()->getDb()->createCommand($sql);
            $ids = $command->queryAll();
            foreach($ids as $value){
                $idEmpresa = $value['id'];
                $serial = ($idEmpresa!=41)? $value['serial']:'EY3I-0DA4-Z6KD-BC9M';
                $data = date('Y-m-d', strtotime($date));
                echo "$idEmpresa no dia $data\n";
                ConsolidadorProdutividade::consolidar($data, $idEmpresa);
                ConsolidadorPrograma::consolidar($data, $idEmpresa,$serial);
                ConsolidadorColaborador::consolidar($data, $idEmpresa,$serial);
                ConsolidadorBlacklist::consolidar($idEmpresa, $serial);
                ConsolidadorProjeto::consolidar($data, $idEmpresa,$serial);
                ConsolidadorDocumentosSemContrato::consolidar($idEmpresa, $serial,$data);
                ConsolidadorSubTela::consolidar($data,$idEmpresa,$serial);
                ConsolidaOciosidade::consolidar($idEmpresa, $data);
                //ConsolidadorSitesBlacklist::consolidar($idEmpresa,$value['serial']);
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}
