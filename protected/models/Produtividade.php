<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 23/05/2016
 * Time: 14:16
 */
class Produtividade
{
    /**
     * @param $produtividadeColaborador
     * @param $dataInicio
     * @param $dataFim
     * @return array
     */
    public static function graficoCustoByEquipe($produtividadeColaborador, $dataInicio, $dataFim)
    {
        $options = array();
        $categorias = $ocioso = $produzido = $equipe = array();
        foreach ($produtividadeColaborador as $item) {
            $calculoCustoColaborador = self::getCalculoCustoColaboradorByData($item->fk_colaborador, $dataInicio, $dataFim, $item->duracao);
            if (isset($equipe[$item->fk_equipe])) {
                $equipe[$item->fk_equipe]['produzido'] += $calculoCustoColaborador['produzido'];
                $equipe[$item->fk_equipe]['ocioso'] += $calculoCustoColaborador['ocioso'];
            } else {
                $equipe[$item->fk_equipe]['produzido'] = $calculoCustoColaborador['produzido'];
                $equipe[$item->fk_equipe]['ocioso'] = $calculoCustoColaborador['ocioso'];
            }
        }

        foreach ($equipe as $key => $value) {
            array_push($categorias, Equipe::model()->findByPk($key)->nome);
            $produzido['data'][] = $value['produzido'];
            $ocioso['data'][] = $value['ocioso'];
        }
        $splitedProduzido = array_chunk($produzido['data'], 15);
        $splitedOcioso = array_chunk($ocioso['data'], 15);
        $splitedCategorias = array_chunk($categorias, 15);

        for ($i = 1; $i <= count($splitedProduzido); $i++) {
            $options[$i] = ($i) . "° " . Yii::t('smith', 'página de resultados');
        }
        return array($produzido, $ocioso, $categorias, $splitedProduzido, $splitedOcioso, $splitedCategorias, $options);
    }

    /**
     * @param $produtividadeColaborador
     * @param $dataInicio
     * @param $dataFim
     * @return array
     */
    public static function graficoCustoByColaborador($produtividadeColaborador, $dataInicio, $dataFim)
    {
        $options = array();
        $categorias = $ocioso = $produzido = $colaborador = array();
        foreach ($produtividadeColaborador as $item) {
            $calculoCustoColaborador = self::getCalculoCustoColaboradorByData($item->fk_colaborador, $dataInicio, $dataFim, $item->duracao);
            $colaborador[$item->fk_colaborador]['produzido'] = $calculoCustoColaborador['produzido'];
            $colaborador[$item->fk_colaborador]['ocioso'] = $calculoCustoColaborador['ocioso'];
        }

        foreach ($colaborador as $key => $value) {
            array_push($categorias, Colaborador::model()->findByPk($key)->nomeCompleto);
            $produzido['data'][] = $value['produzido'];
            $ocioso['data'][] = $value['ocioso'];
        }
        $splitedProduzido = array_chunk($produzido['data'], 15);
        $splitedOcioso = array_chunk($ocioso['data'], 15);
        $splitedCategorias = array_chunk($categorias, 15);

        for ($i = 1; $i <= count($splitedProduzido); $i++) {
            $options[$i] = ($i) . "° " . Yii::t('smith', 'página de resultados');
        }
        return array($produzido, $ocioso, $categorias, $splitedProduzido, $splitedOcioso, $splitedCategorias, $options);
    }

    /**
     * @param $fkColaborador
     * @param $dataInicio
     * @param $dataFim
     * @param $tempoRealizado
     * @return array
     */
    public static function getCalculoCustoColaboradorByData($fkColaborador, $dataInicio, $dataFim, $tempoRealizado)
    {
        $custo = $custoOcioso = 0;
        $colaborador = Colaborador::model()->with('hasSalario')->findByPk($fkColaborador);
        $arrayCusto['produtivo'] = $arrayCusto['ocioso'] = array();
        if (count($colaborador->hasSalario) > 1) {
            foreach ($colaborador->hasSalario as $item) {
                $i = 0;
                if (strtotime($dataInicio) > strtotime($item->data_inicio) && isset($colaborador->hasSalario[$i + 1])) {
                    if (strtotime($colaborador->hasSalario[$i + 1]->data_inicio) > strtotime($dataFim))
                        $data = $dataFim;
                    else
                        $data = date('Y-m-d', strtotime('-1 days', strtotime($colaborador->hasSalario[$i + 1]->data_inicio)));

                    $tempoRealizado = GrfProdutividadeConsolidado::model()->graficoProdutividadeByColaborador($dataInicio, $data, $colaborador->fk_empresa, $fkColaborador)[0]->duracao;
                    $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($data));
                    $custoPrevisto = $diasUteis * ($item->valor / 22);
                    $tempoPrevisto = $diasUteis * ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) / 5);
                    $custoParcial = ($custoPrevisto * $tempoRealizado) / $tempoPrevisto;
                    $custoParcialOcioso = ($custoPrevisto * ($tempoPrevisto - $tempoRealizado)) / $tempoPrevisto;
                    $arrayCusto['produtivo'][$dataInicio . '-' . $data] = $custoParcial;
                    $arrayCusto['ocioso'][$dataInicio . '-' . $data] = $custoParcialOcioso;
                } elseif (strtotime($item->data_inicio) < strtotime($dataFim)) {
                    $dataInicio = $item->data_inicio;
                    $tempoRealizado = GrfProdutividadeConsolidado::model()->graficoProdutividadeByColaborador($dataInicio, $dataFim, $colaborador->fk_empresa, $fkColaborador)[0]->duracao;
                    $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
                    $custoPrevisto = $diasUteis * ($item->valor / 22);
                    $tempoPrevisto = $diasUteis * ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) / 5);
                    $custoParcial = ($custoPrevisto * $tempoRealizado) / $tempoPrevisto;
                    $custoParcialOcioso = ($custoPrevisto * ($tempoPrevisto - $tempoRealizado)) / $tempoPrevisto;
                    $arrayCusto['produtivo'][$dataInicio . '-' . $dataFim] = $custoParcial;
                    $arrayCusto['ocioso'][$dataInicio . '-' . $dataFim] = $custoParcialOcioso;
                }
                $i++;
            }
        } else {
            $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
            $custoPrevisto = $diasUteis * ($colaborador->salario / 22);
            $tempoPrevisto = $diasUteis * ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) / 5);
            $custo = ($custoPrevisto * $tempoRealizado) / $tempoPrevisto;
            $custoOcioso = ($custoPrevisto * ($tempoPrevisto - $tempoRealizado)) / $tempoPrevisto;
        }
        return array('produzido' => $custo + array_sum($arrayCusto['produtivo']), 'ocioso' => $custoOcioso + array_sum($arrayCusto['ocioso']));
    }


}