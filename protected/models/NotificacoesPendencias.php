<?php

/**
 * Created by Sublime.
 * User: Robson
 * Date: 04/07/2016
 * Time: 11:45
 */
class NotificacoesPendencias
{
    public function getNotificacoes()
    {
        $arrayNotificacoes = array();
        $fk_empresa = MetodosGerais::getEmpresaId();
        $arrayNotificacoes[] = array('/NotificacoesPendentes/modalInicio', true);
        if (!Notificacao::model()->alterarSenha())
            $arrayNotificacoes[] = array('/site/modalAlterarSenha', true, 'div_senha');

        if (!NotificacoesPendencias::getFirstProject())
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modalFirstProject', false);

        if (!NotificacoesPendencias::getFirstMetrica())
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modalFirstMetrica', false);

        $produzidoColaborador = NotificacoesPendencias::consultaProdutividade(true);
        if (!empty($produzidoColaborador))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/graficoAumento', $produzidoColaborador);

        $diminuidoColaborador = NotificacoesPendencias::consultaProdutividade(false);
        if (!empty($diminuidoColaborador))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/graficoDiminuir', $diminuidoColaborador);

        $ausentes = GrfOciosidadeConsolidado::model()->searchOcioso2Horas($fk_empresa);
        if (!empty($ausentes))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modalAusentes2Horas', $ausentes);

        $ausentes2Dias = NotificacoesPendencias::getColaboradoresSemProdutividade($fk_empresa);
        if (!empty($ausentes2Dias))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modalAusentes2Dias', $ausentes2Dias);

        $contratos = NotificacoesPendencias::getContratos($fk_empresa);
        if (!empty($contratos))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modalContratos', $contratos);

        $documentos = NotificacoesPendencias::getDocumentos($fk_empresa);
        if (!empty($documentos))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modal70PorCento', $documentos);

        $documentos100 = NotificacoesPendencias::getDocumentos100PorCento($fk_empresa);
        if (!empty($documentos100))
            $arrayNotificacoes[] = array('/NotificacoesPendentes/modal100PorCento', $documentos100);

        return $arrayNotificacoes;
    }

    public function consultaProdutividade($flag)
    {
        $data1 = date('Y-m-d', strtotime('-1 days'));
        $data2 = date('Y-m-d', strtotime('-7 days', strtotime($data1)));
        $data4 = date('Y-m-d', strtotime('-1 days', strtotime($data2)));
        $data3 = date('Y-m-d', strtotime('-7 days', strtotime($data4)));

        $produtividadeAtual = NotificacoesPendencias::getProdutividade($data2, $data1);
        $produtividadeAntiga = NotificacoesPendencias::getProdutividade($data3, $data4);

        $aumento = array();
        foreach ($produtividadeAntiga as $value) {
            foreach ($produtividadeAtual as $key => $value2) {
                if ($value2['fk_colaborador'] == $value['fk_colaborador']) {
                    if ($flag) {
                        if ($value2['produtividade'] > $value['produtividade']) {
                            $aumento[$value['fk_colaborador']]['Colaborador'] = $value['nome'];
                            $aumento[$value['fk_colaborador']]['Aumento'] = str_replace('.', ',', round(abs($value2['produtividade'] - $value['produtividade']), 2));
                        }
                    } else {
                        if ($value2['produtividade'] < $value['produtividade']) {
                            $aumento[$value['fk_colaborador']]['Colaborador'] = $value['nome'];
                            $aumento[$value['fk_colaborador']]['Aumento'] = str_replace('.', ',', round(abs($value2['produtividade'] - $value['produtividade']), 2));
                        }
                    }
                }
            }
        }
        return $aumento;
    }

    public function getProdutividade($dataInicio, $dataFim)
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $fk_empresa, '');

        $produzidoColaborador = array();
        foreach ($resultEquipe as $value) {
            $obj = GrfProdutividadeConsolidado::model()->getQuantidadeDiasTrabalhadosPorColaborador($value->fk_colaborador, $dataInicio, $dataFim);

            $colaboradorDuracao = $value->duracao;
            $porcentagemCol = round(($colaboradorDuracao * 100) / ($value->hora_total * $obj->dias_trabalhados), 2);
            $produzidoColaborador[$value->fk_colaborador]['fk_colaborador'] = $value->fk_colaborador;
            $produzidoColaborador[$value->fk_colaborador]['produtividade'] = $porcentagemCol;
            $produzidoColaborador[$value->fk_colaborador]['nome'] = Colaborador::model()->findByPk($value->fk_colaborador)->nomeCompleto;
        }
        return $produzidoColaborador;
    }

    public function getFirstProject()
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $project = Contrato::model()->findAll(array('condition' => 'fk_empresa = ' . $fk_empresa));
        if (count($project) > 0)
            return true;
        else
            return false;
    }

    public function getFirstMetrica()
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $metrica = Metrica::model()->findAll(array('condition' => 'fk_empresa = ' . $fk_empresa));
        if (count($metrica) > 0)
            return true;
        else
            return false;
    }

    public function getColaboradoresSemProdutividade($fk_empresa)
    {
        $colaboradores = array();
        $colaboradoresDaEmpresa = Colaborador::model()->findAll(array('condition' => 'fk_empresa = ' . $fk_empresa . ' and 
            status = 1'));

        foreach ($colaboradoresDaEmpresa as $key => $value) {
            $ausentes2Dias = ColaboradorSemProdutividade::model()->getTotalColSemProdFromColaborador($fk_empresa, $value->id);
            $diasConsecutivos = self::getDiasConsecutivos($ausentes2Dias);
            for ($i = 0; $i < count($diasConsecutivos); $i++) {
                if (count($diasConsecutivos[$i]) >= 2) {
                    $colaboradores[$value->id][$i]['id'] = $value->id;
                    $colaboradores[$value->id][$i]['nome'] = $value->nome . ' ' . $value->sobrenome;
                    $colaboradores[$value->id][$i]['data_inicial'] = $diasConsecutivos[$i][0];
                    $colaboradores[$value->id][$i]['data_final'] = end($diasConsecutivos[$i]);
                }
            }
        }
        return $colaboradores;
    }


    public function getDiasConsecutivos($dates)
    {
        $conseq = array();
        $ii = 0;
        $max = count($dates);

        for ($i = 0; $i < count($dates); $i++) {
            $conseq[$ii][] = $dates[$i]['data'];

            if ($i + 1 < $max) {
                $dif = strtotime($dates[$i + 1]['data']) - strtotime($dates[$i]['data']);
                if ($dif >= 90000) {
                    $ii++;
                }
            }
        }
        return $conseq;
    }

    public function getContratos($fk_empresa)
    {
        $contratos = DocumentoSemContrato::model()->findAll(array('condition' => 'flagExcluir = 0 and fk_empresa = ' . $fk_empresa, 'limit' => 10));
        return $contratos;
    }

    public function getDocumentos($fk_empresa)
    {
        $contratos = Documento::model()->findAll(array('condition' => 'finalizado = 1 and fk_empresa = ' . $fk_empresa, 'limit' => 10));
        $documentos = array();
        foreach ($contratos as $key => $value) {
            $projetoConsolidado = GrfProjetoConsolidado::model()->getDuracaoDocumentoContrato($value['nome'], $fk_empresa);
            $estouroPrazo = $projetoConsolidado[0]['duracao'] - MetodosGerais::time_to_seconds($value['previsto']);
            if ($projetoConsolidado[0]['duracao'] != NULL && $estouroPrazo > 0) {
                $porcentagemEstourado = round(($estouroPrazo * 100) / MetodosGerais::time_to_seconds($value['previsto']), 2);
                if ($porcentagemEstourado >= 70 && $porcentagemEstourado < 100) {
                    $documentos[] = $value;
                }
            }
            //  $documentos[] = $value;

        }
        return $documentos;
    }

    public function getDocumentos100PorCento($fk_empresa)
    {
        $contratos = Documento::model()->findAll(array('condition' => 'finalizado = 1 and fk_empresa = ' . $fk_empresa, 'limit' => 10));
        $documentos = array();
        foreach ($contratos as $key => $value) {
            $projetoConsolidado = GrfProjetoConsolidado::model()->getDuracaoDocumentoContrato($value['nome'], $fk_empresa);
            $estouroPrazo = $projetoConsolidado[0]['duracao'] - MetodosGerais::time_to_seconds($value['previsto']);
            if ($projetoConsolidado[0]['duracao'] != NULL && $estouroPrazo > 0) {
                $porcentagemEstourado = round(($estouroPrazo * 100) / MetodosGerais::time_to_seconds($value['previsto']), 2);
                if ($porcentagemEstourado == 100)
                    $documentos[] = $value;
            }
            // $documentos[] = $value;
        }
        return $documentos;
    }

    public function getUltimoUsuarioAbrirDocumento($doc)
    {
        # code...
        $log = GrfProjetoConsolidado::model()->findAll(array('condition' => 'documento LIKE "%' . $doc . '%" and fk_empresa = ' . MetodosGerais::getEmpresaId() . " ORDER BY data DESC", 'limit' => 1));
    }
}