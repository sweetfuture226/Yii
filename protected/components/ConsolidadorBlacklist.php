<?php

/**
 * Class ConsolidadorBlacklist
 * Componente para consolidar acesso a programas que não estão marcados como permitidos
 * utilizado pelo cron de consolidação de tabelas.
 */
class ConsolidadorBlacklist {
    public static function consolidar($idEmpresa, $serial) {
        try {
            $start_date = date("Y-m-d", strtotime("-3 months", strtotime(date("Y-m-d"))));
            $end_date = date("Y-m-d");
            
            $criteriaTotal = new CDbCriteria;
            $criteriaTotal->select = 'SUM(time_to_sec(t.duracao)) AS total';
            $criteriaTotal->addCondition("t.serial_empresa like '$serial'");
            $criteriaTotal->addBetweenCondition('data', $start_date, $end_date);
            $total = LogAtividadeConsolidado::model()->find($criteriaTotal)->total;
            $total = ($total == '') ? 1 : $total;

            $criteria=new CDbCriteria;
            $criteria->select = 't.programa, '
                    . 'FORMAT((SUM(t.duracao)*100)/'.$total.',2) as duracao, FORMAT(sum(duracao)/3600,2) as tempo_absoluto ';
            $criteria->addCondition("t.serial_empresa like '$serial'");
            $criteria->addBetweenCondition('t.data', $start_date, $end_date);
            $criteria->addCondition("t.descricao NOT LIKE 'Ocioso'");
            $criteria->addCondition("t.descricao NOT LIKE ''");
            $criteria->addCondition("t.programa NOT LIKE 'Não Identificado'");
            $criteria->addCondition("t.programa NOT LIKE '%Screen Saver%' "
                . "AND t.programa NOT LIKE '%Google Chrome%' "
                . "AND t.programa NOT LIKE '%Opera%' "
                . "AND t.programa NOT LIKE '%Safari%' "
                . "AND t.programa NOT LIKE '%Internet Explorer%' "
                . "AND t.programa NOT LIKE '%Firefox%'");
            $criteria->addCondition("TRIM(t.programa) NOT IN (SELECT TRIM(nome) as nome "
                    . "FROM programa_permitido WHERE serial_empresa = '$serial')");
            $criteria->group = 't.programa';
            $criteria->having = "duracao > 0.1";
            $criteria->order = 'duracao DESC';
            $blacklists = LogAtividadeConsolidado::model()->findAll($criteria);
            ListaNegraPrograma::model()->deleteAllByAttributes(array('fk_empresa' => $idEmpresa));
            foreach($blacklists as $blacklist){
                $consolidar = new ListaNegraPrograma();
                $consolidar->programa = $blacklist['programa'];
                $consolidar->porcentagem = $blacklist['duracao'];
                $consolidar->tempo_absoluto = $blacklist['tempo_absoluto'];
                $consolidar->fk_empresa = $idEmpresa;
                if (!$consolidar->save()) {
                    print_r($consolidar->errors);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}

