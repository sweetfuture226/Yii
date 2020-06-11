<?php

/**
 * Class AlertaMetricaCommand
 *
 * CRON utilizado para verificar a incidência de acesso as métricas por parte dos colaboradores e informar se houve produtividade
 * acima ou abaixo dos limites definidos para cada métrica; caso haja algum resultado será enviado um email ao gestor informando as
 * incidêcias semanalmente.
 */
class AlertaMetricaCommand extends CConsoleCommand
{
    public function run()
    {
        try {
            $dataInicio = date('Y-m-d', strtotime('-7 day'));
            $dataFim = date('Y-m-d', strtotime('-3 day'));
            $metricas = Metrica::model()->alertaMetrica($dataInicio, $dataFim);
            $html = "";
            $arrayMetricas = array();
            foreach ($metricas as $obj) {
                if ($obj->meta_tempo) {
                    if (!isset($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Tempo Mínimo']))
                        $arrayMetricas[$obj->fk_empresa][$obj->titulo]['Tempo Mínimo'] = array('parametro' => 'minimo', 'minimo' => $obj->min_t, 'maximo' => $obj->max_t);
                    if (!isset($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Tempo Máximo']))
                        $arrayMetricas[$obj->fk_empresa][$obj->titulo]['Tempo Máximo'] = array('parametro' => 'maximo', 'minimo' => $obj->min_t, 'maximo' => $obj->max_t);

                    if (MetodosGerais::time_to_seconds($obj->total) < MetodosGerais::time_to_seconds($obj->min_t)) {
                        array_push($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Tempo Mínimo'], array(
                            'colaborador' => $obj->colaborador,
                            'valor' => $obj->total,
                            'parametro' => 'minimo',
                            'minimo' => $obj->min_t,
                            'maximo' => $obj->max_t,
                            'data' => $obj->data
                        ));
                    }
                    if (MetodosGerais::time_to_seconds($obj->total) > MetodosGerais::time_to_seconds($obj->max_t)) {
                        array_push($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Tempo Máximo'], array(
                            'colaborador' => $obj->colaborador,
                            'valor' => $obj->total,
                            'parametro' => 'maximo',
                            'minimo' => $obj->min_t,
                            'maximo' => $obj->max_t,
                            'data' => $obj->data
                        ));
                    }
                }
                if ($obj->meta_entrada) {
                    if (!isset($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Mínimo de entradas']))
                        $arrayMetricas[$obj->fk_empresa][$obj->titulo]['Mínimo de entradas'] = array('parametro' => 'minimo', 'minimo' => $obj->min_e, 'maximo' => $obj->max_e);
                    if (!isset($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Máximo de entradas']))
                        $arrayMetricas[$obj->fk_empresa][$obj->titulo]['Máximo de entradas'] = array('parametro' => 'maximo', 'minimo' => $obj->min_e, 'maximo' => $obj->max_e);

                    if ($obj->entradas < $obj->min_e) {
                        array_push($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Mínimo de entradas'], array(
                            'colaborador' => $obj->colaborador,
                            'valor' => $obj->entradas,
                            'parametro' => 'minimo',
                            'minimo' => $obj->min_e,
                            'maximo' => $obj->max_e,
                            'data' => $obj->data
                        ));

                    }
                    if ($obj->entradas > $obj->max_e) {
                        array_push($arrayMetricas[$obj->fk_empresa][$obj->titulo]['Máximo de entradas'], array(
                            'colaborador' => $obj->colaborador,
                            'valor' => $obj->entradas,
                            'parametro' => 'maximo',
                            'minimo' => $obj->min_e,
                            'maximo' => $obj->max_e,
                            'data' => $obj->data
                        ));

                    }
                }
            }
            $this->sendMetricaEmail($arrayMetricas);
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public function sendMetricaEmail($arrayMetrica)
    {
        foreach ($arrayMetrica as $empresa => $metrica) {
            $html = '';
            foreach ($metrica as $id => $limites) {
                $html .= '<table  border="1px" class="table_custom" style="font-family: arial,sans-serif; border-spacing: 0;border: 0;border-collapse:collapse;">';
                $html .= '<tr style="font-size: 15px;padding: 10px;text-align:center;border: 1px solid #CCC;"><th colspan="4" style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;text-align: center">' . $id . '</th></tr>';
                $html .= '<tr style="font-size: 14px;padding: 10px;text-align:center;border: 1px solid #CCC;"><th>Limite</th><th>Colaborador</th><th>Valor</th><th>Data</th></tr>';
                foreach ($limites as $tipo => $dados) {
                    $rowSpan = isset($dados[0]) ? count($dados) - 3 : 1;
                    $strLimites = '<br> Previsto: <b>' . $dados[$dados['parametro']] . '</b>';
                    $html .= '<tr style="font-size: 14px;padding: 10px;text-align:center;border: 1px solid #CCC;"><td style="width: 250px;font-size: 11px; border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;" rowspan="' . ($rowSpan + 1) . '"><b>' . $tipo . '</b><br>' . $strLimites . '</td>';
                    if (isset($dados[0])) {
                        unset($dados['parametro']);
                        unset($dados['minimo']);
                        unset($dados['maximo']);
                        foreach ($dados as $value) {
                            $html .= '<tr><td style="width: 200px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">' . MetodosGerais::reduzirNome(Colaborador::model()->findByPk($value['colaborador'])->nomeCompleto) . '</td>';
                            $html .= '<td style="width: 150px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:center;border-collapse:collapse;">' . $value['valor'] . '</td>';
                            $html .= '<td style="width: 150px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:center;border-collapse:collapse;">' . MetodosGerais::dataBrasileira($value['data']) . '</td></tr>';
                        }
                        $html .= '</tr>';
                    } else {
                        $html .= '<tr><td colspan="3" style="width: 200px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">Não houve registros para este limite</td></tr></tr>';
                    }
                }
                $html .= '</table><br>';
            }
            $mensagem = "<p>Seguem registros de Otimização de Processos baseados em métricas, conforme limites máximos e mínimos:</p> <br>";
            // SendMail::send('Resumo semanal de Métricas', 'lucascardoso@vivainovacao.com', 'gestor', $mensagem . $html);
            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), 'Resumo semanal de métricas', $mensagem . $html);
        }
    }
}