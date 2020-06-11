<?php

/**
 * Class ConsolidadorPrograma
 * Componente para consolidar a produtividade dos programas autorizados
 * utilizado pelo cron de consolidação de tabelas.
 */
class ConsolidadorPrograma
{
    public static function consolidar($data, $idEmpresa, $serial)
    {
        try {
            $programa = ConsolidadorPrograma::getTempoProduzidoByPrograma($data, $idEmpresa, $serial);
            foreach ($programa as $value) {
                $consolidar = new GrfProgramaConsolidado();
                $consolidar->categoria = 'programa';
                $consolidar->programa = $value['programa'];
                $consolidar->duracao = $value['duracao'];
                $consolidar->data = $data;
                $consolidar->fk_empresa = $idEmpresa;
                if (!$consolidar->save()) {
                    print_r($consolidar->errors);
                }
            }
            $sites = SitePermitido::model()->findAllByAttributes(array("fk_empresa" => $idEmpresa));
            foreach ($sites as $value) {
                $duracaoSite = ConsolidadorPrograma::getTempoProduzidoBySites($data, $value->nome, $idEmpresa);
                $consolidar = new GrfProgramaConsolidado();
                $consolidar->categoria = 'site';
                $consolidar->programa = $value->nome;
                $consolidar->duracao = $duracaoSite;
                $consolidar->data = $data;
                $consolidar->fk_empresa = $idEmpresa;
                if (!$consolidar->save()) {
                    print_r($consolidar->errors);
                }
            }

            $nao_identificado = ConsolidadorPrograma::getTempoProgramaNaoIdentificado($data, $idEmpresa);
            foreach ($nao_identificado as $value) {
                $consolidar = new GrfProgramaConsolidado();
                $consolidar->categoria = 'nao_identificado';
                $consolidar->programa = $value['programa'];
                $consolidar->duracao = $value['duracao'];
                $consolidar->data = $data;
                $consolidar->fk_empresa = $idEmpresa;
                if (!$consolidar->save()) {
                    print_r($consolidar->errors);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    private static function getTempoProduzidoByPrograma($data, $empresa, $serial)
    {
        try {
            $sql = "SELECT eq.nome, at.`programa`, SUM(TIME_TO_SEC(at.`duracao`))/3600 as duracao
                FROM  `log_atividade_consolidado` AS at
                INNER JOIN colaborador AS pe ON pe.ad = at.usuario
                INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
                WHERE (at.`programa` IN 
                (SELECT nome FROM programa_permitido WHERE (fk_empresa = $empresa
                AND fk_equipe = pe.fk_equipe)
                OR (fk_empresa = $empresa AND fk_equipe IS NULL)))
                AND eq.fk_empresa = '" . $empresa . "' "
                . "AND at.data ='{$data}' AND at.descricao NOT LIKE '' "
                . "AND at.serial_empresa like '$serial'";
            $sql .= " GROUP BY at.programa";
            $sql .= " ORDER BY duracao desc";

            $command = Yii::app()->getDb()->createCommand($sql);
            return $command->queryAll();
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    private static function getTempoProduzidoBySites($data, $site, $empresa)
    {
        try {
            $duracaoSite = 0;
            $sitesProdutivos = ProgramasSites::getTempoTotalSitesPermitidos($data, $site, $empresa);
            if (!is_null($sitesProdutivos->total)) {
                $duracaoSite += $sitesProdutivos->total;
            }
            return $duracaoSite / 3600;
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    private static function getTempoProgramaNaoIdentificado($data, $empresa)
    {
        try {
            $sql = "SELECT
                    eq.nome,
                    at.`programa`, at.descricao,
                    SUM(TIME_TO_SEC(at.`duracao`))/3600 as duracao
                    FROM  `log_atividade_consolidado` AS at
                    INNER JOIN colaborador AS pe ON pe.ad = at.usuario AND pe.serial_empresa = at.serial_empresa
                    INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
                    WHERE pe.fk_empresa = $empresa AND at.data = '$data' AND at.programa not in ('Google Chrome','Internet Explorer','Firefox')
                    AND (TRIM(at.`programa`) NOT IN
                    (SELECT TRIM(nome) FROM programa_permitido WHERE (fk_empresa = $empresa AND fk_equipe = pe.fk_equipe)
                    OR (fk_empresa = $empresa AND fk_equipe IS NULL)))
                    AND at.descricao not like '' AND at.descricao not like 'Ocioso'
                    ";
            $sql .= " GROUP BY at.descricao, at.programa ORDER BY duracao desc LIMIT 5";

            $command = Yii::app()->getDb()->createCommand($sql);
            return $command->queryAll();
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}
