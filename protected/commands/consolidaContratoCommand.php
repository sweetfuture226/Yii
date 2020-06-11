<?php

/**
 * Class ConsolidaContratoCommand
 *
 * CRON utilizado para consolidar os registros de produtividade dos contratos cadastrados a partir da tabela de log
 * consolidada.
 */
class ConsolidaContratoCommand extends CConsoleCommand {

    
    public function run() {
        $data = date('Y-m-d');
        $sql = "SELECT at.usuario, at.programa, at.descricao, at.duracao,at.data, at.serial_empresa, obra.codigo
                FROM log_atividade_consolidado as at
                INNER JOIN  contrato as obra ON (at.descricao LIKE CONCAT('%',obra.codigo,'%') AND at.serial_empresa like obra.serial_empresa)
                WHERE data LIKE '$data'  AND descricao NOT LIKE ''";
        $command = Yii::app()->getDb()->createCommand($sql);
        $logsConsolidado =  $command->queryAll();
        
        foreach ($logsConsolidado as $log){
            $model = new LogContrato;
            $model->usuario = $log['usuario'];
            $model->programa = $log['programa'];
            $model->descricao = $log['descricao'];
            $model->duracao = $log['duracao'];
            $model->data = $log['data'];
            $model->serial_empresa = $log['serial_empresa'];
            $model->codigo = $log['codigo'];
            $model->save();
        }
        
    }
    
    
}
