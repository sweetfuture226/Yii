<?php

/**
* Class AlertaColaboradorCommand
*
* CRON utilizado para realização de backup dos relatórios das empresas
*/

class backupRelatoriosCommand extends CConsoleCommand {
    public $hora_alomoco_inicio = "12:00:00";
    public $hora_alomoco_fim = "14:00:00";

    public function run() {
        set_error_handler(array('Logger', 'my_error_handler'));

        Yii::setPathOfAlias('userGroups.models.UserGroupsGroup', Yii::getPathOfAlias('webroot') . '/modules/userGroups/models/UserGroupsGroup');
        Yii::setPathOfAlias('userGroups.models.UserGroupsAccess', Yii::getPathOfAlias('webroot') . '/modules/userGroups/models/UserGroupsAccess');

        $absolutePath = Yii::getPathOfAlias('webroot') .'/../public/backupRelatorios/';
        $produtividade = 'Produtividade/';
        $programasSites = 'Programas e Sites/';
        $metricas = utf8_encode('Métricas/');
        $contratos = 'Contratos/';

        $ontem = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        $inicioMes = date('Y-m-01');

        $empresas = Empresa::model()->findAll(array('condition' => 'ativo = 1 AND id != 41'));
        foreach ($empresas as $empresa) {
            $empresaPath = $absolutePath . utf8_encode($empresa->nome) . '/';
            BackupRelatorios::checkDir($empresaPath);

            // PRODUTIVIDADE //
            $tipo = $empresaPath . $produtividade;
            BackupRelatorios::checkDir($tipo);

            $relatorio = $tipo . utf8_encode('Relatório por Equipe');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioEquipe($ontem, $ontem, $empresa->id, '', $relatorio);
            BackupRelatorios::relatorioEquipe($inicioMes, $ontem, $empresa->id, 'mes', $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório Individual');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioIndividual($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório Individual em Dias');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioIndividualDias($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório por Custo');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioCusto($inicioMes, $ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório de Ranking');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioRanking($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório de Hora Extra');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioHoraExtra($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório de Ponto');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioPonto($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório de Dias sem Produtividade');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioSemProdutividade($ontem, $empresa->id, $relatorio);

            // PROGRAMAS E SITES //
            $tipo = $empresaPath . $programasSites;
            BackupRelatorios::checkDir($tipo);

            $relatorio = $tipo . utf8_encode('Relatório Geral');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioGeralProgramasSites($inicioMes, $ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório Individual');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioIndividualProgramasSites($ontem, $empresa->id, $relatorio);

            // MÉTRICAS //
            $tipo = $empresaPath . $metricas;
            BackupRelatorios::checkDir($tipo);
            BackupRelatorios::relatorioMetrica($ontem, $empresa->id, $tipo);

            // CONTRATOS //
            $tipo = $empresaPath . $contratos;
            BackupRelatorios::checkDir($tipo);

            $relatorio = $tipo . utf8_encode('Relatório Individual');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioIndividualContratos($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório de Produtividade do Colaborador');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioProdutividadeColaborador($inicioMes, $ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório Geral');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioGeralContratos($ontem, $empresa->id, $relatorio);

            $relatorio = $tipo . utf8_encode('Relatório de Consumo de Energia');
            BackupRelatorios::checkDir($relatorio);
            BackupRelatorios::relatorioCustoEnergia($inicioMes, $ontem, $empresa->id, $relatorio);
        }
    }
} ?>
