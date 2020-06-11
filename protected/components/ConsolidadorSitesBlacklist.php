<?php

/**
 * Class ConsolidadorSitesBlacklist
 * Componente para consolidar acesso a sites que não estão marcados como permitidos
 * utilizado pelo cron de consolidação de tabelas.
 */

class ConsolidadorSitesBlacklist {
    public static  function consolidar($idEmpresa, $serial) {
        try{
            $start_date = date("Y-m-d", strtotime("-20 days", strtotime(date("Y-m-d"))));
            $end_date = date("Y-m-d");

            $total = ConsolidadorSitesBlacklist::getTempoTotalSites($idEmpresa, $start_date, $end_date);
            if(!empty($total[0]['total'])){
                $sites = ConsolidadorSitesBlacklist::getSitesBlacklist($idEmpresa,$start_date,$end_date,$total[0]['total']);
                ListaNegraSite::model()->deleteAllByAttributes(array('fk_empresa' => $idEmpresa));
                foreach($sites as $blacklist){
                    $consolidar = new ListaNegraSite();
                    $consolidar->programa = $blacklist->programa;
                    $consolidar->site = $blacklist->descricao;
                    $consolidar->porcentagem = $blacklist->duracao;
                    $consolidar->tempo_absoluto = $blacklist->tempo_absoluto;
                    $consolidar->fk_empresa = $idEmpresa;
                    if (!$consolidar->save()) {
                        print_r($consolidar->errors);
                    }
                }
            }


        }
        catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    private static function getTempoTotalSites($idEmpresa, $start_date, $end_date)
    {
        try{

            $sql = "select  SUM(time_to_sec(duracao)) AS total
                    from log_atividade_consolidado
                    where fk_empresa = $idEmpresa
                    and TRIM(programa) in ('Google Chrome','Opera','Safari','Internet Explorer','Mozilla Firefox')
                    and data between '$start_date' and '$end_date'";
            $command = Yii::app()->getDb()->createCommand($sql);
            return $command->queryAll();

        }
        catch (Exception $e) {
            Logger::sendException($e);
        }

    }

    private static  function getSitesBlacklist($idEmpresa,$start_date,$end_date,$total){
        try{
            $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array('fk_empresa' => $idEmpresa));
            $criteria = new CDbCriteria();
            $criteria->select = "programa,descricao, FORMAT((SUM(duracao)*100)/$total,2) as duracao, FORMAT(sum(duracao)/3600,2) as tempo_absoluto";
            $criteria->addCondition("fk_empresa = $idEmpresa");
            $criteria->addCondition("TRIM(programa) in ('Google Chrome','Opera','Safari','Internet Explorer','Mozilla Firefox')");
            foreach ($sitesPermitidos as $value) {
                $criteria->addCondition("descricao not like '%$value->nome%' ");
            }
            $criteria->addBetweenCondition('data', $start_date, $end_date);
            $criteria->group = 'descricao';
            $criteria->having = 'duracao > 0.1';
            $criteria->order = 'duracao DESC';
            return LogAtividadeConsolidado::model()->findAll($criteria);
        }
        catch (Exception $e) {
            Logger::sendException($e);
        }

    }
}