<?php

/**
 * Class ConsolidadorProdutividade
 * Componente para consolidar produtividade dos colaboradores
 * utilizado pelo cron de consolidação de tabelas.
 */
class ConsolidadorProdutividade
{
    public static function consolidar($data, $idEmpresa)
    {
        try {
            $sql = " SELECT FORMAT( (pe.salario) /30, 2 ) AS salario_colaborador,
                ((TIME_TO_SEC( pe.`horas_semana` )) /3600) /5 AS hora_total,
                pe.nome as colaborador, pe.id as idColaborador,
                SUM( TIME_TO_SEC( at.`duracao` ) )/3600 AS Duracao_colaborador , eq.nome
                FROM  `log_atividade_consolidado` AS at
                INNER JOIN colaborador AS pe ON pe.serial_empresa = at.serial_empresa AND pe.ad = at.usuario
                INNER JOIN equipe AS eq ON eq.id = pe.fk_equipe
                WHERE at.data = '$data'
                AND  (at.`programa` IN
                (SELECT nome FROM programa_permitido
                WHERE (fk_empresa = '$idEmpresa' AND fk_equipe = pe.fk_equipe)
                OR (fk_empresa = '$idEmpresa' AND fk_equipe IS NULL)))
                AND at.descricao NOT LIKE ''
                AND pe.fk_empresa = '$idEmpresa'";
            $sql .= ' GROUP BY pe.ad
                            ORDER BY colaborador ASC';
            $command = Yii::app()->getDb()->createCommand($sql);
            $resultEquipe = $command->queryAll();

            if (!empty($resultEquipe)) {
                foreach ($resultEquipe as $value) {
                    $duracao_sites = ConsolidadorProdutividade::calculaTempoSiteProdutivoByColaborador($value['idColaborador'], $data, $idEmpresa);
                    $newGPC = new GrfProdutividadeConsolidado();
                    $newGPC->equipe = $value['nome'];
                    $newGPC->duracao = (float)($value['Duracao_colaborador'] + $duracao_sites);
                    $newGPC->data = $data;
                    $newGPC->hora_total = $value['hora_total'];
                    $newGPC->nome = $value['colaborador'];
                    $newGPC->fk_colaborador = $value['idColaborador'];
                    $newGPC->fk_empresa = $idEmpresa;
                    if (!$newGPC->save()) {
                        print_r($newGPC->errors);
                    }
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function calculaTempoSiteProdutivoByColaborador($fkColaborador, $data, $fkEmpresa)
    {
        $duracaoSite = 0;
        $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array("fk_empresa" => $fkEmpresa));
        foreach ($sitesPermitidos as $value) {
            $sitesProdutivos = ProgramasSites::getTempoTotalSitesPermitidosByColaborador($fkColaborador, $data, $value->nome, $fkEmpresa);
            if (!is_null($sitesProdutivos->total)) {
                $duracaoSite += $sitesProdutivos->total;
            }
        }
        return $duracaoSite / 3600;
    }
}
