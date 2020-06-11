<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 04/03/2016
 * Time: 11:45
 */
class PainelControle
{
    public static function getProdutividadeEquipesRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior)
    {

        $porcentagemAtual = PainelControle::calculoProdEquipe($dataInicio, $dataFim);
        $porcentagemAnterior = PainelControle::calculoProdEquipe($dataIincioAnterior, $dataFimAnterior);
        return array('atual' => $porcentagemAtual, 'anterior' => $porcentagemAnterior);
    }

    public static function getProdutividadeEquipesRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, 
        $fk_empresa)
    {

        $porcentagemAtual = PainelControle::calculoProdEquipeApi($dataInicio, $dataFim, $fk_empresa);
        $porcentagemAnterior = PainelControle::calculoProdEquipeApi($dataIincioAnterior, $dataFimAnterior, $fk_empresa);
        return array('atual' => $porcentagemAtual, 'anterior' => $porcentagemAnterior);
    }


    private static function calculoProdEquipeApi($dataInicio, $dataFim, $fk_empresa)
    {
        $porcentagemProduzidaEquipe = array();
        $diasUteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);
        $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $fk_empresa, '');
        if (!empty($resultEquipe)) {
            $equipe = array();
            foreach ($resultEquipe as $value) {
                if (!isset($equipe[$value->fk_equipe]['total']) && !isset($equipe[$value->fk_equipe]['hora_total'])) {
                    $equipe[$value->fk_equipe]['total'] = (float)$value->duracao;
                    $equipe[$value->fk_equipe]['hora_total'] = (float)$value->hora_total;
                } else {
                    $equipe[$value->fk_equipe]['total'] += (float)$value->duracao;
                    $equipe[$value->fk_equipe]['hora_total'] += (float)$value->hora_total;
                }
            }
            foreach ($equipe as $key => $value) {
                $porcentagemProduzidaEquipe[$key] = round(($value['total'] * 100) / ($value['hora_total'] * $diasUteis), 2);
            }
             return round(array_sum(array_values($porcentagemProduzidaEquipe)) / count($porcentagemProduzidaEquipe), 0);
        }else
            return 0;
       
    }

    private static function calculoProdEquipe($dataInicio, $dataFim)
    {
        $porcentagemProduzidaEquipe = array();
        $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
        $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $_POST['empresa'], '');
        if (!empty($resultEquipe)) {
            $equipe = array();
            foreach ($resultEquipe as $value) {
                if (!isset($equipe[$value->fk_equipe]['total']) && !isset($equipe[$value->fk_equipe]['hora_total'])) {
                    $equipe[$value->fk_equipe]['total'] = (float)$value->duracao;
                    $equipe[$value->fk_equipe]['hora_total'] = (float)$value->hora_total;
                } else {
                    $equipe[$value->fk_equipe]['total'] += (float)$value->duracao;
                    $equipe[$value->fk_equipe]['hora_total'] += (float)$value->hora_total;
                }
            }
            foreach ($equipe as $key => $value) {
                $porcentagemProduzidaEquipe[$key] = round(($value['total'] * 100) / ($value['hora_total'] * $diasUteis), 2);
            }
        }
        return round(array_sum(array_values($porcentagemProduzidaEquipe)) / count($porcentagemProduzidaEquipe), 0);
    }

    public static function getCustoRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior)
    {
        $custoAtual = PainelControle::calculoCustoRelGlobal($dataInicio, $dataFim);
        $custoAnterior = PainelControle::calculoCustoRelGlobal($dataIincioAnterior, $dataFimAnterior);
        $diffCusto = $custoAtual - $custoAnterior;
        return $diffCusto;
    }

    public static function getCustoRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $fk_empresa)
    {
        $custoAtual = PainelControle::calculoCustoRelGlobalApi($dataInicio, $dataFim, $fk_empresa);
        $custoAnterior = PainelControle::calculoCustoRelGlobalApi($dataIincioAnterior, $dataFimAnterior, $fk_empresa);
        $diffCusto = $custoAtual - $custoAnterior;
        return $diffCusto;
    }

    private static function calculoCustoRelGlobalApi($dataInicio, $dataFim, $fk_empresa)
    {
        $custo = 0;
        $equipeTrabalho = GrfProdutividadeConsolidado::model()->getCustoByEquipe($dataInicio, $dataFim, $fk_empresa, 'todas_equipes');
        if (!empty($equipeTrabalho)) {
            $dias_uteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);
            foreach ($equipeTrabalho as $value) {
                $dadosEquipe = BackupRelatorios::getSalarioTempoEquipe($dias_uteis, $value->equipe, $fk_empresa);
                $equipe[$value->equipe]['salario'] = (float)($dadosEquipe[0]['salario_equipe']);
                $equipe[$value->equipe]['hora_total'] = (float)($dias_uteis * $dadosEquipe[0]['hora_total']);
                $equipe[$value->equipe]['horas_trabalhadas'] = (float)$value->duracao;
            }
            $custo = 0;
            foreach ($equipe as $nomeEq => $value) {
                $v = ((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'];
                $custo += round($v, 2);
            }
        }
        return $custo;
    }

    private static function calculoCustoRelGlobal($dataInicio, $dataFim)
    {
        $custo = 0;
        $equipeTrabalho = GrfProdutividadeConsolidado::model()->getCustoByEquipe($dataInicio, $dataFim, $_POST['empresa'], 'todas_equipes');
        if (!empty($equipeTrabalho)) {
            $dias_uteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
            foreach ($equipeTrabalho as $value) {
                $dadosEquipe = LogAtividade::model()->getSalarioTempoEquipe($dias_uteis, $value->equipe);
                $equipe[$value->equipe]['salario'] = (float)($dadosEquipe[0]['salario_equipe']);
                $equipe[$value->equipe]['hora_total'] = (float)($dias_uteis * $dadosEquipe[0]['hora_total']);
                $equipe[$value->equipe]['horas_trabalhadas'] = (float)$value->duracao;
            }
            $custo = 0;
            foreach ($equipe as $nomeEq => $value) {
                $v = ((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'];
                $custo += round($v, 2);
            }
        }
        return $custo;
    }

    public static function getProdutividadeColaboradorRelGlobal($dataInicio, $dataFim)
    {
        $porcentagem = 0;
        $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
        $produtividadeColAtual = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $_POST['empresa'], "");
        if (!empty($produtividadeColAtual)) {
            $meta_alcancada = 0;
            foreach ($produtividadeColAtual as $item) {
                $meta = Colaborador::model()->with('equipes')->findByPk($item->fk_colaborador)->equipes->meta;
                $porcentagem = round(($item->duracao * 100) / ($item->hora_total * $diasUteis), 2);
                if ($porcentagem >= $meta)
                    $meta_alcancada++;

            }
            $porcentagem = round((($meta_alcancada * 100) / count($produtividadeColAtual)), 0);
        }
        return $porcentagem;
    }

    public static function getProdutividadeColaboradorRelGlobalApi($dataInicio, $dataFim, $fk_empresa)
    {
        $porcentagem = 0;
        $diasUteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);
        $produtividadeColAtual = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, 
            $fk_empresa, "");
        if (!empty($produtividadeColAtual)) {
            $meta_alcancada = 0;
            foreach ($produtividadeColAtual as $item) {
                $meta = Colaborador::model()->with('equipes')->findByPk($item->fk_colaborador)->equipes->meta;
                $porcentagem = round(($item->duracao * 100) / ($item->hora_total * $diasUteis), 2);
                if ($porcentagem >= $meta)
                    $meta_alcancada++;

            }
            $porcentagem = round((($meta_alcancada * 100) / count($produtividadeColAtual)), 0);
        }
        return $porcentagem;
    }

    public static function getHoraExtraRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior)
    {
        $hora_extra = $porcentagemHoraExtra = 0;
        $horaExtraAtual = GrfHoraExtraConsolidado::model()->getHoraExtraEmpresa($dataInicio, $dataFim, $_POST['empresa']);
        $horaExtraAnterior = GrfHoraExtraConsolidado::model()->getHoraExtraEmpresa($dataIincioAnterior, $dataFimAnterior, $_POST['empresa']);
        if ($horaExtraAtual[0]->duracao != null && $horaExtraAnterior[0]->duracao != null) {
            $hora_extra = round($horaExtraAtual[0]->duracao, 0);
            $diffHoraExtra = $horaExtraAtual[0]->duracao - $horaExtraAnterior[0]->duracao;
            $porcentagemHoraExtra = round(($diffHoraExtra * 100) / $horaExtraAnterior[0]->duracao, 0);
        }
        return array('hora_extra' => $hora_extra, 'porcentagem' => $porcentagemHoraExtra);
    }

    public static function getHoraExtraRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $fk_empresa)
    {
        $hora_extra = $porcentagemHoraExtra = 0;
        $horaExtraAtual = GrfHoraExtraConsolidado::model()->getHoraExtraEmpresa($dataInicio, $dataFim, $fk_empresa);
        $horaExtraAnterior = GrfHoraExtraConsolidado::model()->getHoraExtraEmpresa($dataIincioAnterior, $dataFimAnterior, $fk_empresa);
        if ($horaExtraAtual[0]->duracao != null && $horaExtraAnterior[0]->duracao != null) {
            $hora_extra = round($horaExtraAtual[0]->duracao, 0);
            $diffHoraExtra = $horaExtraAtual[0]->duracao - $horaExtraAnterior[0]->duracao;
            $porcentagemHoraExtra = round(($diffHoraExtra * 100) / $horaExtraAnterior[0]->duracao, 0);
        }
        return array('hora_extra' => $hora_extra, 'porcentagem' => $porcentagemHoraExtra);
    }

    public static function getAusenciaColaboradoresRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior)
    {
        $ausencia = $porcentagemAusencia = 0;
        $ausenciaAtual = ColaboradorSemProdutividade::model()->getTotalColSemProdByDatas($_POST['empresa'], $dataInicio, $dataFim);
        $ausenciaAnterior = ColaboradorSemProdutividade::model()->getTotalColSemProdByDatas($_POST['empresa'], $dataIincioAnterior, $dataFimAnterior);
        if ($ausenciaAtual->total && $ausenciaAnterior->total) {
            $diffAusencia = $ausenciaAtual->total - $ausenciaAnterior->total;
            $porcentagemAusencia = round(($diffAusencia * 100) / $ausenciaAnterior->total, 0);
            $ausencia = round($ausenciaAtual->total, 0);
        }
        return array('ausencia' => $ausencia, 'porcentagem' => $porcentagemAusencia);
    }


    public static function getAusenciaColaboradoresRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $fk_empresa)
    {
        $ausencia = $porcentagemAusencia = 0;
        $ausenciaAtual = ColaboradorSemProdutividade::model()->getTotalColSemProdByDatas($fk_empresa, $dataInicio, $dataFim);
        $ausenciaAnterior = ColaboradorSemProdutividade::model()->getTotalColSemProdByDatas($fk_empresa, $dataIincioAnterior, $dataFimAnterior);
        if ($ausenciaAtual->total && $ausenciaAnterior->total) {
            $diffAusencia = $ausenciaAtual->total - $ausenciaAnterior->total;
            $porcentagemAusencia = round(($diffAusencia * 100) / $ausenciaAnterior->total, 0);
            $ausencia = round($ausenciaAtual->total, 0);
        }
        return array('ausencia' => $ausencia, 'porcentagem' => $porcentagemAusencia);
    }

    public static function getMediaHorasTrabalhadasRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $diffDias)
    {
        $mediaHorasTrabalhadas = $porcentagemMediaHoras = 0;
        $mediaHorasAtual = GrfColaboradorConsolidado::model()->getSumHorasTrabalhadas($dataInicio, $dataFim, $_POST['empresa']);
        $mediaHorasAnterior = GrfColaboradorConsolidado::model()->getSumHorasTrabalhadas($dataIincioAnterior, $dataFimAnterior, $_POST['empresa']);
        $colaboradoresAtivosAtual = GrfColaboradorConsolidado::model()->getQuantidadeColaboradoresHorasTrabalhadas($dataInicio, $dataFim, $_POST['empresa'])[0]['qtd'];
        $colaboradoresAtivosAnterior = GrfColaboradorConsolidado::model()->getQuantidadeColaboradoresHorasTrabalhadas($dataIincioAnterior, $dataFimAnterior, $_POST['empresa'])[0]['qtd'];
        if ($mediaHorasAtual->duracao != null && $mediaHorasAnterior->duracao != null) {
            $diffMediaHoras = (($mediaHorasAtual->duracao / $diffDias) / $colaboradoresAtivosAtual) - (($mediaHorasAnterior->duracao / $diffDias) / $colaboradoresAtivosAnterior);
            $porcentagemMediaHoras = round(($diffMediaHoras * 100) / (($mediaHorasAnterior->duracao / $diffDias) / $colaboradoresAtivosAnterior), 0);
            $mediaHorasTrabalhadas = round((($mediaHorasAtual->duracao / $diffDias) / $colaboradoresAtivosAtual) / 3600, 0);
        }
        return array('media_horas_trabalhadas' => $mediaHorasTrabalhadas, 'porcentagem' => $porcentagemMediaHoras);
    }

    public static function getMediaHorasTrabalhadasRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, 
        $diffDias, $fk_empresa)
    {
        $mediaHorasTrabalhadas = $porcentagemMediaHoras = 0;
        $mediaHorasAtual = GrfColaboradorConsolidado::model()->getSumHorasTrabalhadas($dataInicio, $dataFim, $fk_empresa);
        $mediaHorasAnterior = GrfColaboradorConsolidado::model()->getSumHorasTrabalhadas($dataIincioAnterior, $dataFimAnterior, $fk_empresa);
        $colaboradoresAtivosAtual = GrfColaboradorConsolidado::model()->getQuantidadeColaboradoresHorasTrabalhadas($dataInicio, $dataFim, $fk_empresa)[0]['qtd'];
        $colaboradoresAtivosAnterior = GrfColaboradorConsolidado::model()->getQuantidadeColaboradoresHorasTrabalhadas($dataIincioAnterior, $dataFimAnterior, $fk_empresa)[0]['qtd'];
        if ($mediaHorasAtual->duracao != null && $mediaHorasAnterior->duracao != null) {
            $diffMediaHoras = (($mediaHorasAtual->duracao / $diffDias) / $colaboradoresAtivosAtual) - (($mediaHorasAnterior->duracao / $diffDias) / $colaboradoresAtivosAnterior);
            $porcentagemMediaHoras = round(($diffMediaHoras * 100) / (($mediaHorasAnterior->duracao / $diffDias) / $colaboradoresAtivosAnterior), 0);
            $mediaHorasTrabalhadas = round((($mediaHorasAtual->duracao / $diffDias) / $colaboradoresAtivosAtual) / 3600, 0);
        }
        return array('media_horas_trabalhadas' => $mediaHorasTrabalhadas, 'porcentagem' => $porcentagemMediaHoras);
    }

    public static function getAtividadesExternasRelGlobal($dataInicio, $dataFim)
    {
        $atividadesExternas = count(AtividadeExterna::model()->findAll(array('condition' => 'serial_empresa = :serial_empresa AND data BETWEEN :data_inicio AND :data_fim',
            'params' => array('serial_empresa' => MetodosGerais::getSerial(), 'data_inicio' => $dataInicio, 'data_fim' => $dataFim))));
        return $atividadesExternas;

    }

    public static function getAtividadesExternasRelGlobalApi($dataInicio, $dataFim, $fk_empresa)
    {
        $atividadesExternas = count(AtividadeExterna::model()->findAll(array('condition' => 'serial_empresa = :serial_empresa AND data BETWEEN :data_inicio AND :data_fim',
            'params' => array('serial_empresa' => MetodosGerais::getSerialApi($fk_empresa), 'data_inicio' => $dataInicio, 'data_fim' => $dataFim))));
        return $atividadesExternas;

    }


    public static function getMetaAlcancadaMetricaRelGlobal($dataInicio, $dataFim)
    {
        $qtdMetrica = count(Metrica::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa'])));
        $porcentagemMetricaMeta = 0;
        if ($qtdMetrica) {
            $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
            $entradasMetricasAtual = MetricaConsolidada::model()->getSomaEntradasByDatas($_POST['empresa'], $dataInicio, $dataFim);
            $metaAlcancada = 0;
            foreach ($entradasMetricasAtual as $item) {
                $qtdColaboradoresMetrica = count(ColaboradorHasMetrica::model()->findAllByAttributes(array('fk_metrica' => $item->fk_metrica)));
                $metaMetrica = ($item->metrica->meta * $qtdColaboradoresMetrica * $diasUteis);
                if ($item->entradas >= $metaMetrica)
                    $metaAlcancada++;
            }
            $porcentagemMetricaMeta = round(($metaAlcancada * 100) / count(Metrica::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa']))), 2);
        }
        return $porcentagemMetricaMeta;
    }

    public static function getMetaAlcancadaMetricaRelGlobalApi($dataInicio, $dataFim, $fk_empresa)
    {
        $qtdMetrica = count(Metrica::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa)));
        $porcentagemMetricaMeta = 0;
        if ($qtdMetrica) {
            $diasUteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);
            $entradasMetricasAtual = MetricaConsolidada::model()->getSomaEntradasByDatas($fk_empresa, $dataInicio, $dataFim);
            $metaAlcancada = 0;
            foreach ($entradasMetricasAtual as $item) {
                $qtdColaboradoresMetrica = count(ColaboradorHasMetrica::model()->findAllByAttributes(array('fk_metrica' => $item->fk_metrica)));
                $metaMetrica = ($item->metrica->meta * $qtdColaboradoresMetrica * $diasUteis);
                if ($item->entradas >= $metaMetrica)
                    $metaAlcancada++;
            }
            $porcentagemMetricaMeta = round(($metaAlcancada * 100) / count(Metrica::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa))), 2);
        }
        return $porcentagemMetricaMeta;
    }

    public static function getMetricaLimiteMaximoRelGlobal($dataInicio, $dataFim)
    {
        $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
        $entradasMetricasAtual = MetricaConsolidada::model()->getSomaEntradasByDatas($_POST['empresa'], $dataInicio, $dataFim);
        $metaAlcancada = $maximoLimite = 0;
        foreach ($entradasMetricasAtual as $item) {
            $qtdColaboradoresMetrica = count(ColaboradorHasMetrica::model()->findAllByAttributes(array('fk_metrica' => $item->fk_metrica)));
            $metaMetrica = ($item->metrica->meta * $qtdColaboradoresMetrica * $diasUteis);
            $maximoMetrica = ($item->metrica->max_e * $qtdColaboradoresMetrica * $diasUteis);
            if ($item->entradas >= $metaMetrica) {
                $metaAlcancada++;
                if ($item->entradas >= $maximoMetrica)
                    $maximoLimite++;
            }
        }
        return array('meta' => $metaAlcancada, 'maximo' => $maximoLimite);
    }

    public static function getMetricaLimiteMaximoRelGlobalApi($dataInicio, $dataFim, $fk_empresa)
    {
        $diasUteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);
        $entradasMetricasAtual = MetricaConsolidada::model()->getSomaEntradasByDatas($fk_empresa, $dataInicio, $dataFim);
        $metaAlcancada = $maximoLimite = 0;
        foreach ($entradasMetricasAtual as $item) {
            $qtdColaboradoresMetrica = count(ColaboradorHasMetrica::model()->findAllByAttributes(array('fk_metrica' => $item->fk_metrica)));
            $metaMetrica = ($item->metrica->meta * $qtdColaboradoresMetrica * $diasUteis);
            $maximoMetrica = ($item->metrica->max_e * $qtdColaboradoresMetrica * $diasUteis);
            if ($item->entradas >= $metaMetrica) {
                $metaAlcancada++;
                if ($item->entradas >= $maximoMetrica)
                    $maximoLimite++;
            }
        }
        return array('meta' => $metaAlcancada, 'maximo' => $maximoLimite);
    }

    public static function getMetricaLimiteMinimoRelGlobal($dataInicio, $dataFim)
    {
        $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
        $entradasMetricasAtual = MetricaConsolidada::model()->getSomaEntradasByDatas($_POST['empresa'], $dataInicio, $dataFim);
        $metaNAlcancada = $minimoLimite = 0;
        foreach ($entradasMetricasAtual as $item) {
            $qtdColaboradoresMetrica = count(ColaboradorHasMetrica::model()->findAllByAttributes(array('fk_metrica' => $item->fk_metrica)));
            $metaMetrica = ($item->metrica->meta * $qtdColaboradoresMetrica * $diasUteis);
            $minimoMetrica = ($item->metrica->min_e * $qtdColaboradoresMetrica * $diasUteis);
            if ($item->entradas <= $metaMetrica) {
                $metaNAlcancada++;
                if ($item->entradas <= $minimoMetrica)
                    $minimoLimite++;
            }
        }
        return array('meta' => $metaNAlcancada, 'maximo' => $minimoLimite);
    }

    public static function getMetricaLimiteMinimoRelGlobalApi($dataInicio, $dataFim, $fk_empresa)
    {
        $diasUteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);
        $entradasMetricasAtual = MetricaConsolidada::model()->getSomaEntradasByDatas($fk_empresa, $dataInicio, $dataFim);
        $metaNAlcancada = $minimoLimite = 0;
        foreach ($entradasMetricasAtual as $item) {
            $qtdColaboradoresMetrica = count(ColaboradorHasMetrica::model()->findAllByAttributes(array('fk_metrica' => $item->fk_metrica)));
            $metaMetrica = ($item->metrica->meta * $qtdColaboradoresMetrica * $diasUteis);
            $minimoMetrica = ($item->metrica->min_e * $qtdColaboradoresMetrica * $diasUteis);
            if ($item->entradas <= $metaMetrica) {
                $metaNAlcancada++;
                if ($item->entradas <= $minimoMetrica)
                    $minimoLimite++;
            }
        }
        return array('meta' => $metaNAlcancada, 'maximo' => $minimoLimite);
    }

    public static function getContratosAdiantados($dataInicio, $dataFim, $diffDatas)
    {
        $contratos = Contrato::model()->findAll(array('condition' => 'fk_empresa = :fk_empresa AND finalizada = :finalizada AND ativo = :ativo AND tempo_previsto IS NOT NULL',
            'params' => array('fk_empresa' => $_POST['empresa'], 'finalizada' => 0, 'ativo' => 1)));
        $projetosAdiantados = 0;
        foreach ($contratos as $contrato) {
            $diffDataContrato = MetodosGerais::DataDiff(MetodosGerais::dataAmericana($contrato->data_inicio), MetodosGerais::dataAmericana($contrato->data_final));
            $diffDataContrato = (!$diffDataContrato) ? 1 : $diffDataContrato;
            $previstoDia = (MetodosGerais::time_to_seconds($contrato->tempo_previsto) / 3600) / $diffDataContrato;
            $previstoIntervaloDatas = ($previstoDia * $diffDatas) * 3600;
            $produzidoContrato = GrfProjetoConsolidado::model()->getDuracaoTotalContratoByDatas($contrato->id, $dataInicio, $dataFim);
            if ($produzidoContrato->duracao > $previstoIntervaloDatas)
                $projetosAdiantados++;
        }
        return $projetosAdiantados;
    }

    public static function getContratosAdiantadosApi($dataInicio, $dataFim, $diffDatas, $fk_empresa)
    {
        $contratos = Contrato::model()->findAll(array('condition' => 'fk_empresa = :fk_empresa AND finalizada = :finalizada AND ativo = :ativo AND tempo_previsto IS NOT NULL',
            'params' => array('fk_empresa' => $fk_empresa, 'finalizada' => 0, 'ativo' => 1)));
        $projetosAdiantados = 0;
        foreach ($contratos as $contrato) {
            $diffDataContrato = MetodosGerais::DataDiff(MetodosGerais::dataAmericana($contrato->data_inicio), MetodosGerais::dataAmericana($contrato->data_final));
            $diffDataContrato = (!$diffDataContrato) ? 1 : $diffDataContrato;
            $previstoDia = (MetodosGerais::time_to_seconds($contrato->tempo_previsto) / 3600) / $diffDataContrato;
            $previstoIntervaloDatas = ($previstoDia * $diffDatas) * 3600;
            $produzidoContrato = GrfProjetoConsolidado::model()->getDuracaoTotalContratoByDatas($contrato->id, $dataInicio, $dataFim);
            if ($produzidoContrato->duracao > $previstoIntervaloDatas)
                $projetosAdiantados++;
        }
        return $projetosAdiantados;
    }

    public static function getContratosAtrasados()
    {
        $contratos = Contrato::model()->findAll(array('condition' => 'fk_empresa = :fk_empresa AND finalizada = :finalizada AND ativo = :ativo AND tempo_previsto IS NOT NULL',
            'params' => array('fk_empresa' => $_POST['empresa'], 'finalizada' => 0, 'ativo' => 1)));
        $projetosAtraso = 0;
        foreach ($contratos as $contrato) {
            $diffDatas = MetodosGerais::DataDiff(MetodosGerais::dataAmericana($contrato->data_inicio), date('Y-m-d'));
            $diffDataContrato = MetodosGerais::DataDiff(MetodosGerais::dataAmericana($contrato->data_inicio), MetodosGerais::dataAmericana($contrato->data_final));
            $diffDataContrato = (!$diffDataContrato) ? 1 : $diffDataContrato;
            $previstoDia = (MetodosGerais::time_to_seconds($contrato->tempo_previsto) / 3600) / $diffDataContrato;
            $previstoIntervaloDatas = ($previstoDia * $diffDatas);
            $produzidoContrato = GrfProjetoConsolidado::model()->getDuracaoTotalContrato($contrato->id);
            $porcentagemAtraso = ($previstoIntervaloDatas * 30) / 100;
            $porcaoImproduzida = $previstoIntervaloDatas - ($produzidoContrato->duracao / 3600);
            if ($porcaoImproduzida > $porcentagemAtraso)
                $projetosAtraso++;
        }
        return $projetosAtraso;
    }


    public static function getContratosAtrasadosApi($fk_empresa)
    {
        $contratos = Contrato::model()->findAll(array('condition' => 'fk_empresa = :fk_empresa AND finalizada = :finalizada AND ativo = :ativo AND tempo_previsto IS NOT NULL',
            'params' => array('fk_empresa' => $fk_empresa, 'finalizada' => 0, 'ativo' => 1)));
        $projetosAtraso = 0;
        foreach ($contratos as $contrato) {
            $diffDatas = MetodosGerais::DataDiff(MetodosGerais::dataAmericana($contrato->data_inicio), date('Y-m-d'));
            $diffDataContrato = MetodosGerais::DataDiff(MetodosGerais::dataAmericana($contrato->data_inicio), MetodosGerais::dataAmericana($contrato->data_final));
            $diffDataContrato = (!$diffDataContrato) ? 1 : $diffDataContrato;
            $previstoDia = (MetodosGerais::time_to_seconds($contrato->tempo_previsto) / 3600) / $diffDataContrato;
            $previstoIntervaloDatas = ($previstoDia * $diffDatas);
            $produzidoContrato = GrfProjetoConsolidado::model()->getDuracaoTotalContratoApi($contrato->id, $fk_empresa);
            $porcentagemAtraso = ($previstoIntervaloDatas * 30) / 100;
            $porcaoImproduzida = $previstoIntervaloDatas - ($produzidoContrato->duracao / 3600);
            if ($porcaoImproduzida > $porcentagemAtraso)
                $projetosAtraso++;
        }
        return $projetosAtraso;
    }
    public static function geraRelGlobalPDF($arrayProdutividade, $arrayMetrica, $arrayProjeto, $arrayInformacoes, $empresa)
    {
        $imagem = $empresa->logo;
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                            <page_header>
                            <div class="header_page">
                            <img class="header_logo_page" src="' . $imagem . '">
                            <div class="header_title">
                                <span>' . Yii::t("smith", 'AVALIAÇÃO GLOBAL') . '</span><br>
                                <span style="font-size: 10px">' . Yii::t("smith", 'No período de') . ' ' . $_POST['date_from'] . ' ' . Yii::t("smith", 'até') . ' ' . $_POST['date_to'] . ' </span>
                            </div>
                            <span><b>Cliente: ' . $empresa->nome . '</b></span><br>
                            <span><b>' . $arrayInformacoes['colaboradores'] . ' usuários cadastrados, sendo ' . $arrayInformacoes['colaboradoresAtivos'] . ' ativos. </b></span><br>
                            <span><b>' . $arrayInformacoes['equipes'] . ' equipes e ' . $arrayInformacoes['coordenadores'] . ' coordenadores.</b></span><br>

                            <div class="header_date">
                            <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
                                <br>' . Yii::t('smith', 'Pág.') . ' ([[page_cu]]/[[page_nb]]) </p>
                            </div>
                            </div>

                            </page_header>
                            </page>';

        $rodape = MetodosGerais::getRodapeTable();
        $html = $header;
        $html .= '<div style="width: 750px">';
        $html .= '<h5>PRODUTIVIDADE</h5>';
        $diffProdEquipe = $arrayProdutividade['produtividadeEquipe']['atual'] - $arrayProdutividade['produtividadeEquipe']['anterior'];
        if ($diffProdEquipe > 0)
            $html .= '<span>- Aumento de ' . min($arrayProdutividade['produtividadeEquipe']) . '% para ' . max($arrayProdutividade['produtividadeEquipe']) . '% da produtividade das equipes em relação ao mesmo período anterior.</span><br>';
        else
            $html .= '<span>- Redução de ' . max($arrayProdutividade['produtividadeEquipe']) . '% para ' . min($arrayProdutividade['produtividadeEquipe']) . '% da produtividade das equipes em relação ao mesmo período anterior.</span><br>';
        if ($arrayProdutividade['custo'] > 0)
            $html .= '<span>- Economia de R$' . MetodosGerais::float2real($arrayProdutividade['custo']) . ' em relação ao mesmo período anterior.</span><br>';
        else
            $html .= '<span>- Perda de R$' . MetodosGerais::float2real(abs($arrayProdutividade['custo'])) . ' em relação ao mesmo período anterior.</span><br>';

        $html .= '<span>- ' . $arrayProdutividade['produtividadeColaborador'] . '% dos colaboradores alcançaram a meta estabelecida para a produtividade.</span><br>';

        if ($arrayProdutividade['mediaHorasTrabalhadas']['porcentagem'] > 0)
            $html .= '<span>- Média de ' . $arrayProdutividade['mediaHorasTrabalhadas']['media_horas_trabalhadas'] . ' horas diárias trabalhadas por colaborador, representando um aumento de ' . abs($arrayProdutividade['mediaHorasTrabalhadas']['porcentagem']) . '% em relação ao mesmo período anterior.</span><br>';
        else
            $html .= '<span>- Média de ' . $arrayProdutividade['mediaHorasTrabalhadas']['media_horas_trabalhadas'] . ' horas diárias trabalhadas por colaborador, representando um decréscimo de ' . abs($arrayProdutividade['mediaHorasTrabalhadas']['porcentagem']) . '% em relação ao mesmo período anterior.</span><br>';
        if ($arrayProdutividade['hora_extra']['porcentagem'] > 0)
            $html .= '<span>- ' . $arrayProdutividade['hora_extra']['hora_extra'] . ' horas extras registradas, representando um aumento de ' . abs($arrayProdutividade['hora_extra']['porcentagem']) . '% em relação ao mesmo período anterior.</span><br>';
        else
            $html .= '<span>- ' . $arrayProdutividade['hora_extra']['hora_extra'] . ' horas extras registradas, representando um decréscimo de ' . abs($arrayProdutividade['hora_extra']['porcentagem']) . '% em relação ao mesmo período anterior.</span><br>';
        if ($arrayProdutividade['ausenciaColaborador']['porcentagem'] > 0)
            $html .= '<span>- ' . $arrayProdutividade['ausenciaColaborador']['ausencia'] . ' ausências registradas, representando um aumento de ' . abs($arrayProdutividade['ausenciaColaborador']['porcentagem']) . '% em relação ao mesmo período anterior.</span><br>';
        else
            $html .= '<span>- ' . $arrayProdutividade['ausenciaColaborador']['ausencia'] . ' ausências registradas, representando um decréscimo de ' . abs($arrayProdutividade['ausenciaColaborador']['porcentagem']) . '% em relação ao mesmo período anterior.</span><br>';

        $html .= '<span>- ' . $arrayProdutividade['atividadeExternas'] . ' atividades externas foram cadastradas no período.</span><br><br>';

        $html .= '<h5>MÉTRICAS</h5>';
        $hasMetrica = count(Metrica::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa'])));
        if ($hasMetrica) {
            $html .= '<span>- ' . $arrayMetrica['metricaMetaAlcancada'] . '% das áreas de atuação atingiram as metas estabelecidas para as métricas configuradas.</span><br>';
            $html .= '<span>- Considerando a média do período, ' . $arrayMetrica['metricaMaximoLimite']['meta'] . ' métricas atenderam a meta estabelecida, sendo que ' . $arrayMetrica['metricaMaximoLimite']['maximo'] . ' dessas atingiram o limite máximo.</span><br>';
            $html .= '<span>- Considerando a média do período, ' . $arrayMetrica['metricaMinimoLimite']['meta'] . ' métricas não atenderam a meta estabelecida, sendo que ' . $arrayMetrica['metricaMinimoLimite']['maximo'] . ' dessas não atingiram o limite mínimo.</span><br><br>';
        } else
            $html .= '<span>Para efetuar este acompanhamento, é necessário que seja cadastrado pelo menos uma métrica.</span><br><br>';


        $html .= '<h5>PROJETOS</h5>';
        $hasProjeto = count(Contrato::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa'])));
        if ($hasProjeto) {
            $html .= '<span>- ' . $arrayProjeto['projetoAdiantado'] . ' projetos estão adiantados em relação ao cronograma</span><br>';
            $html .= '<span>- ' . $arrayProjeto['projetoAtrasado'] . ' projetos possui um atraso superior 30%</span><br><br>';
        } else
            $html .= '<span>Para efetuar este acompanhamento, é necessário que seja cadastrado pelo menos um projeto.</span><br><br>';

        $html .= '<h5>INFORMAÇÕES COMPLEMENTARES</h5>';
        $html .= '<span>- Programas não permitidos com maior índice de acesso: ' . $arrayInformacoes['programasNaoPermitidos'][0]['programa'] . ' (' . round($arrayInformacoes['programasNaoPermitidos'][0]['porcentagem'], 0) . '%), ' . $arrayInformacoes['programasNaoPermitidos'][1]['programa'] . ' (' . round($arrayInformacoes['programasNaoPermitidos'][1]['porcentagem'], 0) . '%) e ' . $arrayInformacoes['programasNaoPermitidos'][2]['programa'] . ' (' . round($arrayInformacoes['programasNaoPermitidos'][2]['porcentagem'], 0) . '%).</span><br>';
        $html .= '<span>- Sites não permitidos com maior índice de acesso: ' . $arrayInformacoes['sitesNaoPermitidos'][0]['site'] . ' (' . round($arrayInformacoes['sitesNaoPermitidos'][0]['porcentagem'], 0) . '%), ' . $arrayInformacoes['sitesNaoPermitidos'][1]['site'] . ' (' . round($arrayInformacoes['sitesNaoPermitidos'][1]['porcentagem'], 0) . '%) e ' . $arrayInformacoes['sitesNaoPermitidos'][2]['site'] . ' (' . round($arrayInformacoes['sitesNaoPermitidos'][2]['porcentagem'], 0) . '%).</span><br>';

        $html .= '</div>';
        $html .= $rodape;
        $style = MetodosGerais::getStyleTable();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output(Yii::t('smith', 'Relatorio_Avaliacao_Global') . '_' . $empresa->nome . '_' . $_POST['date_from'] . '_' . Yii::t('smith', 'ate') . '_' . $_POST['date_to'] . '.pdf');

    }

}