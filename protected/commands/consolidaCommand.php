<?php

class ConsolidaCommand extends CConsoleCommand {

    
    public function run() {
        $data = date('Y-m-d');
        $sql = "SELECT usuario, programa, descricao, SEC_TO_TIME(SUM(TIME_TO_SEC(duracao))) as total_duracao, data,
                title_completo, nome_host, host_domain, serial_empresa, fk_empresa
                FROM log_atividade
                WHERE data LIKE '$data'  AND descricao NOT LIKE '' AND atividade_extra = 0
                GROUP BY descricao,usuario ";
        $command = Yii::app()->getDb()->createCommand($sql);
        $logsConsolidado =  $command->queryAll();

        foreach ($logsConsolidado as $log){
            $model = new LogAtividadeConsolidado;
            $model->usuario = $log['usuario'];
            $model->programa = $log['programa'];
            $model->descricao = $log['descricao'];
            $model->duracao = $log['total_duracao'];
            $model->data = $log['data'];
            $model->title_completo = $log['title_completo'];
            $model->nome_host = $log['nome_host'];
            $model->host_domain = $log['host_domain'];
            $model->serial_empresa = $log['serial_empresa'];
            $model->fk_empresa = $log['fk_empresa'];
            $model->save();
        }

        // -  Consolidar logs com base nos programas
        $sql2 = "SELECT usuario, programa, descricao, SEC_TO_TIME(SUM(TIME_TO_SEC(duracao))) as total_duracao, data, title_completo, nome_host, host_domain, serial_empresa
                FROM log_atividade 
                WHERE data LIKE '$data'  AND descricao NOT LIKE '' AND descricao NOT LIKE 'Ocioso'
                GROUP BY programa,usuario ";
        $command = Yii::app()->getDb()->createCommand($sql2);
        $logsConsolidadoPrograma =  $command->queryAll();
        
        foreach ($logsConsolidadoPrograma as $log){
            $model = new LogProgramaConsolidado;
            $model->usuario = $log['usuario'];
            $model->programa = $log['programa'];
            $model->descricao = $log['descricao'];
            $model->duracao = $log['total_duracao'];
            $model->data = $log['data'];
            $model->title_completo = $log['title_completo'];
            $model->nome_host = $log['nome_host'];
            $model->host_domain = $log['host_domain'];
            $model->serial_empresa = $log['serial_empresa'];
            $model->save();
        }
        
    }
    
    
}