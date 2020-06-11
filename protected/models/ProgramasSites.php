<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 04/04/2016
 * Time: 14:14
 */
class ProgramasSites
{
    /**
     * @param $fkColaborador
     * @param $data
     * @param $fkEmpresa
     * @param string $programa
     * @return array|mixed|null
     */
    public static function getTempoTotalProdutividadeProgramasByColaborador($fkColaborador, $data, $fkEmpresa,$programa='')
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(TIME_TO_SEC(t.duracao)) AS total';
        $criteria->join = 'INNER JOIN colaborador AS pe ON pe.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition('pe.id = ' . $fkColaborador);
        $criteria->addCondition('(TRIM(t.`programa`) IN (SELECT nome FROM programa_permitido WHERE (fk_empresa = ' . $fkEmpresa . ' AND fk_equipe = pe.fk_equipe) OR (fk_empresa = ' . $fkEmpresa . ' AND fk_equipe IS NULL)))');
        $criteria->addCondition("descricao NOT LIKE 'CAcDynInputWndControl'");
        $criteria->compare('programa',$programa,true);
        return LogAtividadeConsolidado::model()->find($criteria);
    }

    /**
     * @param $fkColaborador
     * @param $data
     * @param $fkEmpresa
     * @param string $programa
     * @return array|mixed|null
     *
     * require data => formato americano
     */
    public static function getTempoTotalProgramasNaoProdutivosByColaborador($fkColaborador, $data, $fkEmpresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(TIME_TO_SEC(t.duracao)) AS total';
        $criteria->join = 'INNER JOIN colaborador AS pe ON pe.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition('pe.id = ' . $fkColaborador);
        $criteria->addCondition('(TRIM(t.`programa`) NOT IN (SELECT nome FROM programa_permitido WHERE (fk_empresa = ' . $fkEmpresa . ' AND fk_equipe = pe.fk_equipe) OR (fk_empresa = ' . $fkEmpresa . ' AND fk_equipe IS NULL)))');
        $criteria->addCondition("descricao NOT LIKE 'Ocioso'");
        $criteria->addCondition("descricao NOT LIKE ''");
        $criteria->addCondition("programa NOT LIKE '%Google Chrome%'");
        $criteria->addCondition("programa NOT LIKE '%Internet Explorer%'");
        $criteria->addCondition("programa NOT LIKE '%Mozilla%'");
        return LogAtividadeConsolidado::model()->find($criteria);
    }


    /**
     * @param $fkColaborador
     * @param $data
     * @param $site
     * @param $fkEmpresa
     * @return array|mixed|null
     */
    public static function getTempoTotalSitesPermitidosByColaborador($fkColaborador, $data, $site, $fkEmpresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(TIME_TO_SEC(t.duracao)) as total';
        $criteria->join = 'INNER JOIN colaborador AS pe ON pe.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition('pe.id = ' . $fkColaborador);
        $criteria->addCondition("descricao LIKE '%$site%'");
        $criteria->addCondition("programa  LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' ");
        return LogAtividadeConsolidado::model()->find($criteria);
    }

    /**
     * @param $data
     * @param $site
     * @param $fkEmpresa
     * @return CActiveRecord
     */
    public static function getTempoTotalSitesPermitidos($data, $site, $fkEmpresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(TIME_TO_SEC(t.duracao)) as total';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition("descricao LIKE '%$site%'");
        $criteria->addCondition("programa  LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' ");
        return LogAtividadeConsolidado::model()->find($criteria);
    }

    /**
     * @param $fkColaborador
     * @param $data
     * @param $site
     * @param $fkEmpresa
     * @return array|mixed|null
     */
    public static function getListaSitesPermitidosByColaborador($fkColaborador, $data, $site, $fkEmpresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.descricao as site';
        $criteria->join = 'INNER JOIN colaborador AS pe ON pe.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition('pe.id = ' . $fkColaborador);
        $criteria->addCondition("descricao LIKE '%$site%'");
        $criteria->addCondition("programa  LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' ");
        return LogAtividadeConsolidado::model()->findAll($criteria);
    }

    public static function getTempoTotalSitesNaoPermitidosByColaborador($fkColaborador, $data, $listaSite, $fkEmpresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(TIME_TO_SEC(t.duracao)) as total';
        $criteria->join = 'INNER JOIN colaborador AS pe ON pe.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition('pe.id = ' . $fkColaborador);
        $criteria->addCondition("descricao NOT LIKE 'Ocioso'");
        $criteria->addCondition("descricao NOT LIKE ''");
        $criteria->addCondition("programa  LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' ");
        $criteria->addNotInCondition('descricao', array($listaSite));
        return LogAtividadeConsolidado::model()->find($criteria);
    }

}