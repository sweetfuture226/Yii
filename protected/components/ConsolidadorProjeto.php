<?php

/**
 * Class ConsolidadorProjeto
 * Componente para consolidar a produtividade dos contratos cadastrados
 * utilizado pelo cron de consolidação de tabelas.
 */
 class ConsolidadorProjeto {
     public static function consolidar($data, $idEmpresa,$serial) {
        try {
            $contratos = Contrato::model()->findAllByAttributes(array('fk_empresa' => $idEmpresa));
            foreach ($contratos as $value) {
                $logs = array();
                $padrao = explode(',', $value->codigo);
                foreach ($padrao as $codigo) {
                    $sql2 = "SELECT log.data as data , log.descricao as descricao, 
                    p.id as id, SUM(TIME_TO_SEC(log.duracao)) as duracao
                    FROM log_atividade_consolidado as log 
                    INNER JOIN colaborador as p on log.usuario = p.ad
                    WHERE  log.data = '$data' 
                    AND log.descricao like '%" . trim($codigo) . "%'
                    AND log.fk_empresa = $idEmpresa
                    AND p.fk_empresa = $idEmpresa";
                    $sql2 .= " GROUP BY log.descricao, p.nome";

                    $command2 = Yii::app()->getDb()->createCommand($sql2);
                    $logs = array_merge($logs, $command2->queryAll());
                }
                if(!empty($logs)){
                    foreach ($logs as $log){
                        $projeto = new GrfProjetoConsolidado;
                        $projeto->data = $data;
                        $projeto->documento = $log['descricao'];
                        $projeto->duracao = $log['duracao'];
                        $projeto->fk_empresa = $idEmpresa;
                        $projeto->fk_colaborador = $log['id'];
                        $projeto->fk_obra = $value->id;
                        if (!$projeto->save()) {
                            print_r($projeto->errors);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
 }
