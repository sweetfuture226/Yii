<?php

/**
 * Class PovoarGraficoConsolidadoCommand
 * CRON para auxiliar a consolidação das tabelas manualmente determinando um intervalo de datas.
 */

class PovoarGraficoConsolidadoCommand extends CConsoleCommand {
    public function run() {
        try {
            // Start date
            $date = '2016-09-12';
            // End date
            //$end_date = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
            $end_date = '2016-09-16';
            $sql = 'SELECT id, serial FROM empresa';
            $command = Yii::app()->getDb()->createCommand($sql);
            $ids = $command->queryAll();
            foreach($ids as $value){
                $idEmpresa = $value['id'];
                //ConsolidadorSitesBlacklist::consolidar($idEmpresa,$value['serial']);
                //ConsolidadorBlacklist::consolidar($idEmpresa, $value['serial']);
                $data = date('Y-m-d', strtotime($date));
                while (strtotime($data) <= strtotime($end_date)) {
                    echo "$idEmpresa no dia $data\n";
                    ConsolidaOciosidade::consolidar($idEmpresa, $data);
                    //ConsolidadorSubTela::consolidar($data,$idEmpresa,$serial);
                    //ColaboradorAusente::consolidar($data);
                    //$this->consolidarSemProd($data);
                    //ConsolidadorProdutividade::consolidar($data, $idEmpresa);
                    //ConsolidadorPrograma::consolidar($data, $idEmpresa,$value['serial']);
                    //ConsolidadorColaborador::consolidar($data, $idEmpresa,$value['serial']);
                    //ConsolidadorProjeto::consolidar($data, $idEmpresa,$value['serial']);
                    //ConsolidadorDocumentosSemContrato::consolidar($idEmpresa, $value['serial'],$data);
                    $data = date("Y-m-d", strtotime("+1 day", strtotime($data)));
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public function consolidarSemProd($data){
        //$dataAtual = gmdate('Y-m-d', time()-(3600*27));
        $sql = "SELECT CONCAT(pe.nome,' ',pe.sobrenome) as nome , pe.fk_empresa, pe.id
                FROM  colaborador AS pe
                WHERE pe.id not in (SELECT fk_colaborador FROM grf_colaborador_consolidado WHERE data = '$data')
                AND ativo = 1
                AND status = 1
                ORDER BY fk_empresa";
        $command = Yii::app()->getDb()->createCommand($sql);
        $colaboradores = $command->queryAll();
        for($i=0;$i<count($colaboradores);$i++){
            $model = new ColaboradorSemProdutividade();
            $model->nome = $colaboradores[$i]['nome'];
            $model->data = $data;
            $model->fk_empresa = $colaboradores[$i]['fk_empresa'];
            $model->fk_colaborador = $colaboradores[$i]['id'];
            $model->save();
        }
    }
}
