<?php

/* Class BackupRelatorios
Componente para gerar relatórios utilizados pelo cron de backup. */

class BackupRelatorios {
    public static function relatorioEquipe($dataInicio, $dataFim, $fk_empresa, $tipo, $src)
    {
        try {
            $diasUteis = BackupRelatorios::dias_uteis(strtotime($dataInicio), strtotime($dataFim), $fk_empresa);

            $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $fk_empresa, '');
            if (!empty($resultEquipe)) {
                $equipe = $categorias = $produzido = $produzidoEquipe = $categoriaEquipe = array();

                foreach ($resultEquipe as $value) {
                    $objEquipe = Equipe::model()->findByPk($value->fk_equipe);
                    if ($value->hora_total > 0 && isset($objEquipe)) {
                        $equipe[$objEquipe->nome][] = $value;
                        if (isset($equipe[$objEquipe->nome]['total']) && isset($equipe[$objEquipe->nome]['hora_total'])) {
                            $equipe[$objEquipe->nome]['total'] += (float)$value->duracao;
                            $equipe[$objEquipe->nome]['hora_total'] += (float)$value->hora_total;
                        } else {
                            if (isset($value->fk_equipe)) {
                                $equipe[$objEquipe->nome]['total'] = (float)$value->duracao;

                                $categorias[] = $objEquipe->nome;
                                $options[$value->fk_equipe] = $objEquipe->nome;
                                $equipe[$objEquipe->nome]['hora_total'] = (float)$value->hora_total;
                            }
                        }
                    }
                }
                $produzido['name'] = 'Produtividade';
                $produzido['type'] = 'bar';
                $produzido['yAxis'] = 0;
                $meta = array("name" => "Meta", "type" => "spline");
                foreach ($equipe as $key => $value) {
                    $idEquipe = Equipe::model()->findByAttributes(array("nome" => $key, "fk_empresa" => $fk_empresa));
                    if (isset($idEquipe)) {
                        $metaEquipe = $idEquipe->meta;
                        $porcentagemProduzidaEquipe = round(($value['total'] * 100) / ($value['hora_total'] * $diasUteis), 2);
                        $produzido['data'][] = $porcentagemProduzidaEquipe;
                        $meta['data'][] = (float)$metaEquipe;
                        $produzidoEquipe[$idEquipe->id]['name'] = $key;
                        foreach ($value as $chave => $dadoEquipe) {
                            if (($chave != 'total' && $chave != 'hora_total') || $chave == '0') {
                                $colaborador = Colaborador::model()->findByPk($dadoEquipe->fk_colaborador);
                                if (isset($colaborador)) {
                                    $ferias = ColaboradorHasFerias::model()->findAllByAttributes(array("fk_colaborador" => $colaborador->id));
                                    if (!empty($ferias)) {
                                        $diasUteisFerias = BackupRelatorios::diasUteisColaborador(strtotime($dataInicio), strtotime($dataFim), $ferias, $fk_empresa);
                                        if ($diasUteisFerias) {
                                            $categoriaEquipe[$idEquipe->id][] = MetodosGerais::reduzirNome($colaborador->nome . ' ' . $colaborador->sobrenome);
                                            $colaboradorDuracao = $dadoEquipe->duracao;
                                            if ((strtotime($dataInicio) > strtotime($ferias[0]->data_inicio) && strtotime($dataInicio) < strtotime($ferias[0]->data_fim)) && (strtotime($dataFim) > strtotime($ferias[0]->data_fim)))
                                                $colaboradorDuracao = GrfProdutividadeConsolidado::model()->graficoProdutividadeByColaborador($ferias[0]->data_fim, $dataFim, $fk_empresa, $dadoEquipe->fk_colaborador)[0]->duracao;
                                            $porcentagemProduzidaColaborador = round(($colaboradorDuracao * 100) / ($dadoEquipe->hora_total * $diasUteisFerias), 2);
                                            $produzidoEquipe[$idEquipe->id]['data'][] = $porcentagemProduzidaColaborador;
                                        }
                                    } else {
                                        $duracao = $dadoEquipe->duracao;
                                        $categoriaEquipe[$idEquipe->id][] = MetodosGerais::reduzirNome($colaborador->nome . ' ' . $colaborador->sobrenome);
                                        $porcentagemProduzidaColaborador = round(($duracao * 100) / ($dadoEquipe->hora_total * $diasUteis), 2);
                                        $produzidoEquipe[$idEquipe->id]['data'][] = $porcentagemProduzidaColaborador;
                                    }
                                }

                            }
                        }
                    }
                }

                // Export CSV
                $registros = array($produzido, $meta, $categorias, $produzidoEquipe, $categoriaEquipe);
                $src = $src .'/'. date('Y', strtotime($dataFim)) .'/';
                BackupRelatorios::checkDir($src);
                $src .= MetodosGerais::mesString(date('m', strtotime($dataFim))) . '/';
                BackupRelatorios::checkDir($src);
                BackupRelatorios::ExportCSVRelEquipe($registros, 'Todas', $dataInicio, $dataFim, Empresa::model()->findByPk($fk_empresa)->nome, $tipo, $src);
            }
        } catch (Exception $e) {
            $relatorio = explode('/', $src);
            Logger::sendException($e, $fk_empresa, $relatorio[9] .' - '. $relatorio[10]);
        }
    }

    public static function relatorioIndividual($ontem, $fk_empresa, $src)
    {
        try {
            $colaboradores = Colaborador::model()->findAll(array('condition' => 'fk_empresa = '. $fk_empresa .' AND fk_equipe != "" AND status = 1'));
            foreach ($colaboradores as $colaborador) {
                $produtividadeDia = BackupRelatorios::getProdutividadeDiaria($colaborador->id, $ontem, $fk_empresa);
                $registrosMes = BackupRelatorios::produtividadeDiariaPorMesAno(date('m', strtotime($ontem)), date('Y', strtotime($ontem)), $colaborador->id, $fk_empresa);
                $registrosAno = BackupRelatorios::produtividadeDiariaPorAno(date('Y', strtotime($ontem)), $colaborador->id, $fk_empresa);

                /* Produtividade diária */
                if (!empty($produtividadeDia)) {
                    BackupRelatorios::renderProdutividadeIndDia($colaborador, $produtividadeDia, $fk_empresa, $src, $ontem);
                }

                /* Produtividade mensal */
                if (!empty($registrosMes)) {
                    BackupRelatorios::renderProdutividadeIndMes($colaborador, $registrosMes, $fk_empresa, $src, $ontem);
                }

                /* Produtividade anual */
                if (!empty($registrosAno)) {
                    BackupRelatorios::renderProdutividadeIndAno($colaborador, $registrosAno, $fk_empresa, $src, $ontem);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioIndividualDias($ontem, $fk_empresa, $src)
    {
        try {
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'status' => 1));
            foreach ($colaboradores as $colaborador) {
                $registros = BackupRelatorios::produtividadeDiariaPorData($ontem, $ontem, $colaborador->id, $fk_empresa);
                $colaboradorNome = MetodosGerais::reduzirNome($colaborador->nomeCompleto);

                // Export CSV
                $titulo = Yii::t('smith', "Produtividade diária de $colaboradorNome");
                $filename = 'RelatorioProdutividadeDias_' . utf8_encode($colaboradorNome) . '_' . date('dmY', strtotime($ontem)) . '.csv';
                $colunas = array('65' => 'Data', '66' => 'Tempo Produzido (horas)', '67' => 'Tempo Previsto (horas)');
                $source = $src .'/'. date('Y', strtotime($ontem)) .'/';
                BackupRelatorios::checkDir($source);
                $source .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                BackupRelatorios::checkDir($source);
                $source .= date('d', strtotime($ontem)) . '/';
                BackupRelatorios::checkDir($source);
                BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInDias', $fk_empresa, $source, $ontem);
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioCusto($dataInicio, $dataFim, $fk_empresa, $src)
    {
        try {
            $dataInicio2 = strtotime($dataInicio);
            $dataFim2 = strtotime($dataFim);
            $dias_uteis = BackupRelatorios::dias_uteis($dataInicio2, $dataFim2, $fk_empresa);

            //////////////////// CUSTO POR EQUIPE ////////////////////
            $equipes = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($equipes as $equipeFind) {
                $equipeTrabalho = GrfProdutividadeConsolidado::model()->graficoProdutividadeCustoByEquipe($dataInicio, $dataFim, $fk_empresa, $equipeFind->id);
                if (!empty($equipeTrabalho)) {
                    $categorias = $ocioso = $produzido = $equipe = array();
                    foreach ($equipeTrabalho as $value) {
                        array_push($categorias, $value->equipe);
                        $dadosEquipe = BackupRelatorios::getSalarioTempoEquipe($dias_uteis, $equipeFind->id, $fk_empresa);
                        $equipe[$value->equipe]['salario'] = (float)($dadosEquipe[0]['salario_equipe']);
                        $equipe[$value->equipe]['hora_total'] = (float)($dias_uteis * $dadosEquipe[0]['hora_total']);
                        $equipe[$value->equipe]['horas_trabalhadas'] = (float)$value->duracao;
                        $equipe[$value->equipe]['horas_ociosas'] = $equipe[$value->equipe]['hora_total'] - $equipe[$value->equipe]['horas_trabalhadas'];
                    }
                    $produzido['name'] = Yii::t('smith', 'Custo produzido');
                    $ocioso['name'] = Yii::t('smith', 'Custo ausente do computador');
                    foreach ($equipe as $nomeEq => $value) {
                        $v = ((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'];
                        $produzido["data"][] = round($v, 2);
                        $x = ((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'];
                        $ocioso['data'][] = round($x, 2);
                    }
                    $splitedProduzido = array_chunk($produzido['data'], 15);
                    $splitedOcioso = array_chunk($ocioso['data'], 15);
                    $splitedCategorias = array_chunk($categorias, 15);

                    for ($i = 1; $i <= count($splitedProduzido); $i++) {
                        $options[$i] = ($i) . "° " . Yii::t('smith', 'página de resultados');
                    }

                    // Export CSV
                    $registros = array($produzido, $ocioso, $categorias);
                    $colunas = array('65' => 'Equipe', '66' => 'Produzido (R$)', '67' => 'Ausente do computador (R$)');
                    $titulo = 'Custo aproximado de ' . $equipeFind->nome . ' no mês de ' . MetodosGerais::mesString(date('m', $dataFim2));
                    $filename = 'RelatorioCustoEquipe_' . utf8_encode($equipeFind->nome) . '.csv';
                    $source = $src .'/'. date('Y', $dataFim2) .'/';
                    BackupRelatorios::checkDir($source);
                    $source .= MetodosGerais::mesString(date('m', strtotime($dataFim2))) . '/';
                    BackupRelatorios::checkDir($source);
                    $source .= 'Equipes/';
                    BackupRelatorios::checkDir($source);
                    BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relCusto', $fk_empresa, $source, $dataFim);
                }
            }
            //////////////////// CUSTO POR COLABORADOR ////////////////////
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($colaboradores as $colaboradorFind) {
                $colaboradorTrabalho = BackupRelatorios::getTempoProduzidoColaboradorPorAtributos($dataInicio, $dataFim, $colaboradorFind->id, $fk_empresa);
                if (!empty($colaboradorTrabalho)) {
                    $categorias = $ocioso = $produzido = $colaborador = array();
                    foreach ($colaboradorTrabalho as $value) {
                        if ($value['hora_total'] > 0) {
                            array_push($categorias, $value['nome']);
                            $ferias = ColaboradorHasFerias::model()->findAllByAttributes(array("fk_colaborador" => $value['fk_colaborador']));
                            if (!empty($ferias)) {
                                $diasUteisFerias = BackupRelatorios::diasUteisColaborador(strtotime($dataInicio), strtotime($dataFim), $ferias, $fk_empresa);
                                $colaborador[$value['nome']]['salario'] = (float)($diasUteisFerias * $value['salario_colaborador']);
                                $colaborador[$value['nome']]['hora_total'] = (float)($diasUteisFerias * $value['hora_total']);
                                $colaborador[$value['nome']]['horas_trabalhadas'] = (float)0;
                                $colaborador[$value['nome']]['horas_ociosas'] = (float)0;
                            } else {
                                $colaborador[$value['nome']]['salario'] = (float)($dias_uteis * $value['salario_colaborador']);
                                $colaborador[$value['nome']]['hora_total'] = (float)($dias_uteis * $value['hora_total']);
                                $colaborador[$value['nome']]['horas_trabalhadas'] = (float)0;
                                $colaborador[$value['nome']]['horas_ociosas'] = (float)0;
                            }

                        }
                    }

                    foreach ($colaboradorTrabalho as $value) {
                        if ($value['hora_total'] > 0) {
                            $colaborador[$value['nome']]['horas_trabalhadas'] = (float)($value['Duracao_colaborador'] / 3600);
                            $colaborador[$value['nome']]['horas_ociosas'] = $colaborador[$value['nome']]['hora_total'] - $colaborador[$value['nome']]['horas_trabalhadas'];
                        }
                    }

                    $produzido['name'] = 'Custo produzido';
                    $ocioso['name'] = 'Custo ausente do computador';

                    foreach ($colaborador as $nomeEq => $value) {
                        $v = ((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'];
                        $produzido['data'][] = round($v, 2);

                        $x = ((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'];
                        $ocioso['data'][] = round($x, 2);
                    }

                    $splitedProduzido = array_chunk($produzido['data'], 15);
                    $splitedOcioso = array_chunk($ocioso['data'], 15);
                    $splitedCategorias = array_chunk($categorias, 15);

                    for ($i = 1; $i <= count($splitedProduzido); $i++) {
                        $options[$i] = ($i) . "° página de resultados";
                    }

                    // Export CSV
                    $registros = array($produzido, $ocioso, $categorias);
                    $colunas = array('65' => 'Colaborador', '66' => 'Produzido (R$)', '67' => 'Ausente do computador (R$)');
                    $titulo = 'Custo aproximado de ' . $colaboradorFind->nomeCompleto . ' no mês de ' . MetodosGerais::mesString(date('m', $dataFim2));
                    $filename = 'RelatorioCustoColaborador_' . utf8_encode($colaboradorFind->nomeCompleto) . '.csv';
                    $source = $src .'/'. date('Y', $dataFim2) .'/';
                    BackupRelatorios::checkDir($source);
                    $source .= MetodosGerais::mesString(date('m', strtotime($dataFim2))) . '/';
                    BackupRelatorios::checkDir($source);
                    $source .= 'Colaboradores/';
                    BackupRelatorios::checkDir($source);
                    BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relCusto', $fk_empresa, $source, $dataFim);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioRanking($ontem, $fk_empresa, $src)
    {
        try {
            $existsProd = GrfProdutividadeConsolidado::model()->find(array("condition" => 'fk_empresa = ' . $fk_empresa . ' AND data BETWEEN "' . $ontem . '" AND "' . $ontem . '"'));
            if (isset($existsProd)) {
                // Export CSV
                $registros = BackupRelatorios::grfRelatorioRanking($ontem, $ontem, $fk_empresa);
                $colunas = array('65' => 'Equipe', '66' => 'Nome', '67' => 'Produtividade', '68' => 'Meta', '69' => 'Coeficiente');
                $titulo = 'Relatório de Ranking no dia ' . MetodosGerais::dataBrasileira($ontem);
                $filename = 'RelatorioRanking_'. date('dmY', strtotime($ontem)) .'.csv';
                $source = $src .'/'. date('Y', strtotime($ontem)) .'/';
                BackupRelatorios::checkDir($source);
                $source .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                BackupRelatorios::checkDir($source);
                BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relRanking', $fk_empresa, $source, $ontem);
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioHoraExtra($ontem, $fk_empresa, $src)
    {
        try {
            //////////////////// HORA EXTRA POR EQUIPE ////////////////////
            $equipes = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($equipes as $equipe) {
                $horaExtras = GrfHoraExtraConsolidado::model()->getHorasExtras($ontem, $ontem, $fk_empresa, 'equipe', $equipe->id);
                if ($horaExtras) {
                    $categorias = $duracao = $produtividade = $dados = array();
                    foreach ($horaExtras as $value) {
                        $colaborador = $value->fkColaborador;
                        if (isset($colaborador)) {
                            $nomeColaborador = MetodosGerais::reduzirNome($colaborador->nome . " " . $colaborador->sobrenome);
                            $categorias[$nomeColaborador] = $nomeColaborador;
                            if (empty($duracao[$nomeColaborador])) {
                                $duracao[$nomeColaborador] = (float)$value->duracao;
                                $produtividade[$nomeColaborador] = (float)$value->produtividade;
                            } else {
                                $duracao[$nomeColaborador] += (float)$value->duracao;
                                $produtividade[$nomeColaborador] += (float)$value->produtividade;
                            }
                        }
                    }

                    $sendcategorias = $sendprodutividade = $sendduracao = array();
                    foreach ($categorias as $categoria) {
                        array_push($sendcategorias, $categoria);
                        array_push($sendduracao, $duracao[$categoria]);
                        array_push($sendprodutividade, $produtividade[$categoria]);
                    }
                    $splitedProduzido = array_chunk($sendprodutividade, 15);
                    $splitedDuracao = array_chunk($sendduracao, 15);
                    $splitedCategorias = array_chunk($sendcategorias, 15);

                    for ($i = 1; $i <= count($splitedProduzido); $i++) {
                        $options[$i] = ($i) . "° " . Yii::t('smith', "página de resultados");
                    }

                    // Export CSV
                    $registros = array($sendduracao, $sendprodutividade, $sendcategorias);
                    $colunas = array('65' => 'Colaborador', '66' => 'Duração', '67' => 'Produtividade');
                    $titulo = 'Relatório de hora extra de equipe no dia ' . date('d/m/Y', strtotime($ontem));
                    $filename = 'RelatorioHoraExtraEquipe_' . utf8_encode($equipe->nome) . '_' . date('dmY', strtotime($ontem)) . '.csv';
                    $source = $src .'/'. date('Y', strtotime($ontem)) .'/';
                    BackupRelatorios::checkDir($source);
                    $source .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                    BackupRelatorios::checkDir($source);
                    $source .= 'Equipes/';
                    BackupRelatorios::checkDir($source);
                    $source .= date('d', strtotime($ontem)) . '/';
                    BackupRelatorios::checkDir($source);
                    BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relHoraExtra', $fk_empresa, $source, $ontem);
                }
            }

            //////////////////// HORA EXTRA POR COLABORADOR ////////////////////
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($colaboradores as $colaborador) {
                $horaExtras = GrfHoraExtraConsolidado::model()->getHorasExtras($ontem, $ontem, $fk_empresa, 'colaborador', $colaborador->id);
                if ($horaExtras) {
                    $categorias = $duracao = $produtividade = $dados = array();
                    foreach ($horaExtras as $value) {
                        $colaborador = $value->fkColaborador;
                        if (isset($colaborador)) {
                            $nomeColaborador = MetodosGerais::reduzirNome($colaborador->nome . " " . $colaborador->sobrenome);
                            $categorias[$nomeColaborador] = $nomeColaborador;
                            if (empty($duracao[$nomeColaborador])) {
                                $duracao[$nomeColaborador] = (float)$value->duracao;
                                $produtividade[$nomeColaborador] = (float)$value->produtividade;
                            } else {
                                $duracao[$nomeColaborador] += (float)$value->duracao;
                                $produtividade[$nomeColaborador] += (float)$value->produtividade;
                            }
                        }
                    }

                    $sendcategorias = $sendprodutividade = $sendduracao = array();
                    foreach ($categorias as $categoria) {
                        array_push($sendcategorias, $categoria);
                        array_push($sendduracao, $duracao[$categoria]);
                        array_push($sendprodutividade, $produtividade[$categoria]);
                    }
                    $splitedProduzido = array_chunk($sendprodutividade, 15);
                    $splitedDuracao = array_chunk($sendduracao, 15);
                    $splitedCategorias = array_chunk($sendcategorias, 15);

                    for ($i = 1; $i <= count($splitedProduzido); $i++) {
                        $options[$i] = ($i) . "° " . Yii::t('smith', "página de resultados");
                    }

                    // Export CSV
                    $registros = array($sendduracao, $sendprodutividade, $sendcategorias);
                    $colunas = array('65' => 'Colaborador', '66' => 'Duração', '67' => 'Produtividade');
                    $titulo = 'Relatório de hora extra de colaborador no dia ' . date('d/m/Y', strtotime($ontem));
                    $filename = 'RelatorioHoraExtraColaborador_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('dmY', strtotime($ontem)) . '.csv';
                    $source = $src .'/'. date('Y', strtotime($ontem)) .'/';
                    BackupRelatorios::checkDir($source);
                    $source .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                    BackupRelatorios::checkDir($source);
                    $source .= 'Colaboradores/';
                    BackupRelatorios::checkDir($source);
                    $source .= date('d', strtotime($ontem)) . '/';
                    BackupRelatorios::checkDir($source);
                    BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relHoraExtra', $fk_empresa, $source, $ontem);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioPonto($ontem, $fk_empresa, $src)
    {
        try {
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($colaboradores as $colaborador) {
                $pontos = GrfColaboradorConsolidado::model()->getPontos($ontem, $ontem, $fk_empresa, $colaborador->id);
                if (!empty($pontos)) {
                    $arrayPonto = array();
                    foreach ($pontos as $value) {
                        $arrayPonto[$value->data][] = $value;
                    }
                    $colunas = array('65' => 'Data', '66' => 'Equipe', '67' => 'Colaborador', '68' => 'Horário de entrada', '69' => 'Horário de saída');
                    $titulo = 'Controle de entrada e saída na data ' . MetodosGerais::dataBrasileira($ontem);
                    $filename = 'RelatorioPonto_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('dmY', strtotime($ontem)) . '.csv';
                    $source = $src .'/'. date('Y', strtotime($ontem)) .'/';
                    BackupRelatorios::checkDir($source);
                    $source .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                    BackupRelatorios::checkDir($source);
                    $source .= date('d', strtotime($ontem)) . '/';
                    BackupRelatorios::checkDir($source);
                    BackupRelatorios::ExportToCsv($arrayPonto, $colunas, $titulo, $filename, 'relPonto', $fk_empresa, $source, $ontem);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioSemProdutividade($ontem, $fk_empresa, $src)
    {
        try {
            $criteria = new CDbCriteria;
            $criteria->select = "t.nome, t.data, eq.nome as equipe";
            $criteria->compare('t.data', $ontem);
            $criteria->join = "INNER JOIN colaborador as p ON t.fk_colaborador = p.id ";
            $criteria->join .= "INNER JOIN equipe as eq ON eq.id = p.fk_equipe";
            $criteria->addCondition("p.fk_empresa = " . $fk_empresa);
            $criteria->addCondition("t.fk_empresa = " . $fk_empresa);
            $criteria->order = 'equipe, t.nome';

            $resultados = ColaboradorSemProdutividade::model()->findAll($criteria);

            if ($resultados) {
                $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
                $style = MetodosGerais::getStyleTable();
                $rodape = MetodosGerais::getRodapeTable();

                $header = '<page orientation="portrait" backtop="15mm" backbottom="20mm" format="A4" >'
                    .'<page_header>'
                        .'<div class="header_page">'
                            .'<div class="header_title">'
                                .'<span>RELATÓRIO DE COLABORADORES SEM PRODUTIVIDADE</span><br>'
                            .'</div>'
                            .'<div class="header_date">'
                                .'<p>Data:  "'. MetodosGerais::dataBrasileira($ontem) .'"'
                                    .'<br>Pág. ([[page_cu]]/[[page_nb]]) </p>'
                                .'</div>'
                        .'</div>'
                    .'</page_header>'
                .'</page>';
                $html = $header;
                $corpo = "";
                $html .= $rodape;
                $html .=  '<table class="table_custom" border="1px">
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>Equipe</th>
                        <th>Nome</th>
                        <th>Data</th>
                    </tr>';
                foreach ($resultados as $chave=>$resultado){
                    $resultadoData = explode('-', $resultado['data']);
                    $resultadoData = $resultadoData[2] . '/' . $resultadoData[1] . '/' . $resultadoData[0];
                    $html .= '<tr> '
                        . '<td style="text-align: center; width: 232.5px;">'.$resultado['equipe'].'</td>'
                        . '<td style="text-align: center; width: 232.5px;">'.$resultado['nome'].'</td>'
                        . '<td style="text-align: center; width: 232.5px;">'.$resultadoData.'</td></tr>';
                }
                $html .= "</table><br>";

                $filename = 'RelatorioColaboradoresSemProdutividade_'. date('dmY', strtotime($ontem)) .'.pdf';
                $src = $src .'/'. date('Y', strtotime($ontem)) .'/';
                BackupRelatorios::checkDir($src);
                $src .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                BackupRelatorios::checkDir($src);
                $html2pdf = Yii::app()->ePdf->HTML2PDF();
                $html2pdf->WriteHTML($html . $style);
                $html2pdf->Output($src . $filename, 'F');
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    // PROGRAMAS E SITES //
    public static function relatorioGeralProgramasSites($date_from, $date_to, $fk_empresa, $src)
    {
        try {
            $dataInicio = strtotime($date_from);
            $dataFim = strtotime($date_to);
            $dias_uteis = BackupRelatorios::dias_uteis($dataInicio, $dataFim, $fk_empresa);

            // EQUIPES //
            $equipes = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($equipes as $equipe) {
                $atividadesExternas = BackupRelatorios::getTempoAtividadeExterna($date_from, $date_to, $equipe->id, 'equipe', $fk_empresa);
                $producao = BackupRelatorios::getTempoProduzidoByAtributos($date_from, $date_to, $equipe->id, 'equipe', $fk_empresa);
                $sitesProducao = BackupRelatorios::getTempoProduzidoSitesByAtributos($date_from, $date_to, $equipe->id, 'equipe', $fk_empresa);
                $ocioso = BackupRelatorios::getTempoTotalEquipe($equipe->id, 'equipe', $fk_empresa);
                $nao_identificado = BackupRelatorios::getTempoProgramaNaoIdentificado($date_from, $date_to, $equipe->id, 'equipe', $fk_empresa);
                $site_nao_identificado = BackupRelatorios::getTempoSiteNaoIdentificado($date_from, $date_to, $equipe->id, 'equipe', $fk_empresa);

                $dataProduzido = $dataOcioso = $dataNaoIdentificado = $dataSiteNaoIdentificado = $dataSites = $dataAtividadeExterna = array();
                $somaProduzido = $somaOcioso = $somaNaoIdentificado = $somaSiteNaoIdentificado = $somaSites = $somaAtividadeExterna = 0;
                $arrayCategoriasProduzido = $arrayCategoriasNaoIdentificado = $arrayCategoriasSiteNaoIdentificado = $arrayCategoriasSites = array();

                $regSites = $regPrograma = $regAtivExt = $regProgramaNaoIdent = $regSiteNaoIdent = $regOcioso = array();

                /* Sites permitidos*/
                if (!empty($sitesProducao)) {
                    foreach ($sitesProducao as $value) {
                        $somaSites += $value['duracao'];
                        $arrayCategoriasSites[] = $value['programa'];
                        array_push($dataSites, (float)$value['duracao']);
                    }
                    $splitedSites = array_chunk($sitesProducao, 5);
                    $somaOutrosSite = 0;
                    foreach ($splitedSites[0] as $key => $value) {
                        $somaOutrosSite += $value['duracao'];
                        array_push($regSites, array('name' => $value['programa'], 'y' => (float)$value['duracao']));
                    }
                    array_push($regSites, array('name' => 'Outros', 'y' => (float)($somaSites - $somaOutrosSite)));
                }

                /* Programas não identificados*/
                if (!empty($nao_identificado)) {
                    foreach ($nao_identificado as $key => $value) {
                        $somaNaoIdentificado += $value['duracao'];
                        $arrayCategoriasNaoIdentificado[] = $value['descricao'];
                        array_push($dataNaoIdentificado, (float)$value['duracao']);
                    }
                }

                $splitedNaoIdent = array_chunk($nao_identificado, 5);
                $somaOutrosPNI = 0;

                if (!empty($splitedNaoIdent)) {
                    foreach ($splitedNaoIdent[0] as $key => $value) {
                        $somaOutrosPNI += $value['duracao'];
                        array_push($regProgramaNaoIdent, array('name' => $value['descricao'], 'y' => (float)$value['duracao']));
                    }
                }
                array_push($regProgramaNaoIdent, array('name' => 'Outros', 'y' => (float)($somaNaoIdentificado - $somaOutrosPNI)));

                /* Sites não identificados*/
                if (!empty($site_nao_identificado)) {
                    foreach ($site_nao_identificado as $value) {
                        $somaSiteNaoIdentificado += $value['duracao'];
                        $arrayCategoriasSiteNaoIdentificado[] = $value['descricao'];
                        array_push($dataSiteNaoIdentificado, (float)$value['duracao']);
                    }
                }
                $splitedSiteNaoIdent = array_chunk($site_nao_identificado, 5);
                $somaOutrosSNI = 0;
                if (!empty($splitedSiteNaoIdent)) {
                    foreach ($splitedSiteNaoIdent[0] as $key => $value) {
                        $somaOutrosSNI += $value['duracao'];
                        array_push($regSiteNaoIdent, array('name' => $value['descricao'], 'y' => (float)$value['duracao']));
                    }
                }
                array_push($regSiteNaoIdent, array('name' => 'Outros', 'y' => (float)($somaSiteNaoIdentificado - $somaOutrosSNI)));

                /* Programas Permitidos */
                if (!empty($producao)) {
                    foreach ($producao as $key => $value) {
                        $somaProduzido += $value['duracao'];
                        $arrayCategoriasProduzido[] = $value['programa'];
                        array_push($dataProduzido, (float)$value['duracao']);
                    }
                }
                $splitedProgramas = array_chunk($producao, 5);
                $somaOutrosPrograma = 0;
                if (!empty($splitedProgramas)) {
                    foreach ($splitedProgramas[0] as $key => $value) {
                        $somaOutrosPrograma += $value['duracao'];
                        array_push($regPrograma, array('name' => $value['programa'], 'y' => (float)$value['duracao']));
                    }
                }
                array_push($regPrograma, array('name' => 'Outros', 'y' => (float)($somaProduzido - $somaOutrosPrograma)));

                /* Atividades externas*/
                if (!empty($atividadesExternas)) {
                    foreach ($atividadesExternas as $value) {
                        $somaAtividadeExterna += $value->duracao;
                        array_push($dataAtividadeExterna, (float)$value->duracao);
                    }
                    $splitedAtivExt = array_chunk($atividadesExternas, 5);
                    $somaOutrosAtivExt = 0;
                    if (!empty($splitedAtivExt)) {
                        foreach ($splitedAtivExt[0] as $key => $value) {
                            $somaOutrosAtivExt += $value->duracao;
                            array_push($regAtivExt, array('name' => $value->descricao, 'y' => (float)$value->duracao));
                        }
                    }
                    array_push($regAtivExt, array('name' => 'Outros', 'y' => (float)($somaAtividadeExterna - $somaOutrosAtivExt)));
                }
                /* Ocioso */
                $somaOcioso = ($ocioso[0]['tempo_total'] * $dias_uteis) - ($somaProduzido + $somaSites + $somaNaoIdentificado + $somaSiteNaoIdentificado + $somaAtividadeExterna);
                array_push($dataOcioso, (float)$somaOcioso);
                array_push($regOcioso, array('name' => 'Ocioso', 'y' => (float)$somaOcioso));

                $registros = array(
                    'Site Não Identificado' => array($dataSiteNaoIdentificado, $arrayCategoriasSiteNaoIdentificado),
                    'Programas' => array($dataProduzido, $arrayCategoriasProduzido),
                    'Sites' => array($dataSites, $arrayCategoriasSites),
                    'Atividade Externa' => array($dataAtividadeExterna, array('Atividade Externas')),
                    'Não Identificado' => array($dataNaoIdentificado, $arrayCategoriasNaoIdentificado),
                    'Ausente do computador' => array($dataOcioso, array('Ausente do computador'))
                );

                // Export CSV
                $colunas = array('65' => 'Categoria', '66' => 'Programa/Site', '67' => 'Duração');
                $titulo = 'Relatório geral de ' . $equipe->nome . ' entre ' . MetodosGerais::dataBrasileira($date_from) . ' e ' . MetodosGerais::dataBrasileira($date_to);
                $filename = 'RelatorioGeralEquipe_' . utf8_encode($equipe->nome) . '_' . date('mY', $dataFim) . '.csv';
                $source = $src .'/'. date('Y', $dataFim) .'/';
                BackupRelatorios::checkDir($source);
                $source .= MetodosGerais::mesString(date('m', $dataFim)) . '/';
                BackupRelatorios::checkDir($source);
                $source .= 'Equipes/';
                BackupRelatorios::checkDir($source);
                BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relGeral', $fk_empresa, $source, $date_to);
            }

            // COLABORADORES //
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($colaboradores as $colaborador) {
                $atividadesExternas = BackupRelatorios::getTempoAtividadeExterna($date_from, $date_to, $colaborador->id, 'colaborador', $fk_empresa);
                $producao = BackupRelatorios::getTempoProduzidoByAtributos($date_from, $date_to, $colaborador->id, 'colaborador', $fk_empresa);
                $sitesProducao = BackupRelatorios::getTempoProduzidoSitesByAtributos($date_from, $date_to, $colaborador->id, 'colaborador', $fk_empresa);
                $ocioso = BackupRelatorios::getTempoTotalEquipe($colaborador->id, 'colaborador', $fk_empresa);
                $nao_identificado = BackupRelatorios::getTempoProgramaNaoIdentificado($date_from, $date_to, $colaborador->id, 'colaborador', $fk_empresa);
                $site_nao_identificado = BackupRelatorios::getTempoSiteNaoIdentificado($date_from, $date_to, $colaborador->id, 'colaborador', $fk_empresa);

                $dataProduzido = $dataOcioso = $dataNaoIdentificado = $dataSiteNaoIdentificado = $dataSites = $dataAtividadeExterna = array();
                $somaProduzido = $somaOcioso = $somaNaoIdentificado = $somaSiteNaoIdentificado = $somaSites = $somaAtividadeExterna = 0;
                $arrayCategoriasProduzido = $arrayCategoriasNaoIdentificado = $arrayCategoriasSiteNaoIdentificado = $arrayCategoriasSites = array();

                $regSites = $regPrograma = $regAtivExt = $regProgramaNaoIdent = $regSiteNaoIdent = $regOcioso = array();

                /* Sites permitidos*/
                if (!empty($sitesProducao)) {
                    foreach ($sitesProducao as $value) {
                        $somaSites += $value['duracao'];
                        $arrayCategoriasSites[] = $value['programa'];
                        array_push($dataSites, (float)$value['duracao']);
                    }
                    $splitedSites = array_chunk($sitesProducao, 5);
                    $somaOutrosSite = 0;
                    foreach ($splitedSites[0] as $key => $value) {
                        $somaOutrosSite += $value['duracao'];
                        array_push($regSites, array('name' => $value['programa'], 'y' => (float)$value['duracao']));
                    }
                    array_push($regSites, array('name' => 'Outros', 'y' => (float)($somaSites - $somaOutrosSite)));
                }

                /* Programas não identificados*/
                if (!empty($nao_identificado)) {
                    foreach ($nao_identificado as $key => $value) {
                        $somaNaoIdentificado += $value['duracao'];
                        $arrayCategoriasNaoIdentificado[] = $value['descricao'];
                        array_push($dataNaoIdentificado, (float)$value['duracao']);
                    }
                }

                $splitedNaoIdent = array_chunk($nao_identificado, 5);
                $somaOutrosPNI = 0;

                if (!empty($splitedNaoIdent)) {
                    foreach ($splitedNaoIdent[0] as $key => $value) {
                        $somaOutrosPNI += $value['duracao'];
                        array_push($regProgramaNaoIdent, array('name' => $value['descricao'], 'y' => (float)$value['duracao']));
                    }
                }
                array_push($regProgramaNaoIdent, array('name' => 'Outros', 'y' => (float)($somaNaoIdentificado - $somaOutrosPNI)));

                /* Sites não identificados*/
                if (!empty($site_nao_identificado)) {
                    foreach ($site_nao_identificado as $value) {
                        $somaSiteNaoIdentificado += $value['duracao'];
                        $arrayCategoriasSiteNaoIdentificado[] = $value['descricao'];
                        array_push($dataSiteNaoIdentificado, (float)$value['duracao']);
                    }
                }
                $splitedSiteNaoIdent = array_chunk($site_nao_identificado, 5);
                $somaOutrosSNI = 0;
                if (!empty($splitedSiteNaoIdent)) {
                    foreach ($splitedSiteNaoIdent[0] as $key => $value) {
                        $somaOutrosSNI += $value['duracao'];
                        array_push($regSiteNaoIdent, array('name' => $value['descricao'], 'y' => (float)$value['duracao']));
                    }
                }
                array_push($regSiteNaoIdent, array('name' => 'Outros', 'y' => (float)($somaSiteNaoIdentificado - $somaOutrosSNI)));

                /* Programas Permitidos */
                if (!empty($producao)) {
                    foreach ($producao as $key => $value) {
                        $somaProduzido += $value['duracao'];
                        $arrayCategoriasProduzido[] = $value['programa'];
                        array_push($dataProduzido, (float)$value['duracao']);
                    }
                }
                $splitedProgramas = array_chunk($producao, 5);
                $somaOutrosPrograma = 0;
                if (!empty($splitedProgramas)) {
                    foreach ($splitedProgramas[0] as $key => $value) {
                        $somaOutrosPrograma += $value['duracao'];
                        array_push($regPrograma, array('name' => $value['programa'], 'y' => (float)$value['duracao']));
                    }
                }
                array_push($regPrograma, array('name' => 'Outros', 'y' => (float)($somaProduzido - $somaOutrosPrograma)));

                /* Atividades externas*/
                if (!empty($atividadesExternas)) {
                    foreach ($atividadesExternas as $value) {
                        $somaAtividadeExterna += $value->duracao;
                        array_push($dataAtividadeExterna, (float)$value->duracao);
                    }
                    $splitedAtivExt = array_chunk($atividadesExternas, 5);
                    $somaOutrosAtivExt = 0;
                    if (!empty($splitedAtivExt)) {
                        foreach ($splitedAtivExt[0] as $key => $value) {
                            $somaOutrosAtivExt += $value->duracao;
                            array_push($regAtivExt, array('name' => $value->descricao, 'y' => (float)$value->duracao));
                        }
                    }
                    array_push($regAtivExt, array('name' => 'Outros', 'y' => (float)($somaAtividadeExterna - $somaOutrosAtivExt)));
                }
                /* Ocioso */
                $somaOcioso = ($ocioso[0]['tempo_total'] * $dias_uteis) - ($somaProduzido + $somaSites + $somaNaoIdentificado + $somaSiteNaoIdentificado + $somaAtividadeExterna);
                array_push($dataOcioso, (float)$somaOcioso);
                array_push($regOcioso, array('name' => 'Ocioso', 'y' => (float)$somaOcioso));

                $registros = array(
                    'Site Não Identificado' => array($dataSiteNaoIdentificado, $arrayCategoriasSiteNaoIdentificado),
                    'Programas' => array($dataProduzido, $arrayCategoriasProduzido),
                    'Sites' => array($dataSites, $arrayCategoriasSites),
                    'Atividade Externa' => array($dataAtividadeExterna, array('Atividade Externas')),
                    'Não Identificado' => array($dataNaoIdentificado, $arrayCategoriasNaoIdentificado),
                    'Ausente do computador' => array($dataOcioso, array('Ausente do computador'))
                );

                // Export CSV
                $colunas = array('65' => 'Categoria', '66' => 'Programa/Site', '67' => 'Duração');
                $titulo = 'Relatório geral de ' . $colaborador->nome . ' entre ' . MetodosGerais::dataBrasileira($date_from) . ' e ' . MetodosGerais::dataBrasileira($date_to);
                $filename = 'RelatorioGeralColaborador_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('mY', $dataFim) . '.csv';
                $source = $src .'/'. date('Y', $dataFim) .'/';
                BackupRelatorios::checkDir($source);
                $source .= MetodosGerais::mesString(date('m', $dataFim)) . '/';
                BackupRelatorios::checkDir($source);
                $source .= 'Colaboradores/';
                BackupRelatorios::checkDir($source);
                BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relGeral', $fk_empresa, $source, $date_to);
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioIndividualProgramasSites($ontem, $fk_empresa, $src)
    {
        try {
            $data = MetodosGerais::dataBrasileira($ontem);
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'status' => 1));
            foreach ($colaboradores as $colaborador) {
                $arrayPDF = array();
                $produtivo = BackupRelatorios::getProgramasProdutivosDia($colaborador->id, $data, $fk_empresa);
                if (!empty($produtivo)) {
                    $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
                    $almocoInicio = MetodosGerais::setHoraServidor($parametros->almoco_inicio);
                    $almocoFim = MetodosGerais::setHoraServidor($parametros->almoco_fim);
                    $produtivoAlmoco = BackupRelatorios::getProgramasProdutivosAlmoco($colaborador->id, $data, $almocoInicio, $almocoFim, $fk_empresa);
                    $improdutivo = BackupRelatorios::getProgramasNaoProdutivosDia($colaborador->id, $data, $fk_empresa);
                    $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));
                    $horarios = BackupRelatorios::getHorarioEntrada($colaborador->id, $data, $fk_empresa);
                    $horario_entrada = $horarios[0]['data_hora_servidor'];
                    $horario_saida = array_pop($horarios);
                    $horario_saida = $horario_saida['data_hora_servidor'];
                    $horario_entrada = MetodosGerais::getHoraServidor($horario_entrada, "");
                    $horario_saida = MetodosGerais::getHoraServidor($horario_saida, "");

                    $duracaoAlmoco = MetodosGerais::time_to_seconds($almocoFim) - MetodosGerais::time_to_seconds($almocoInicio);
                    $ociosoAlmoco = BackupRelatorios::getTempoAlmoco($colaborador->id, $data, $almocoFim, $fk_empresa);
                    $ociosoAlmoco = BackupRelatorios::calcularOcioAlmoco($ociosoAlmoco, $duracaoAlmoco);

                    if (strtotime($parametros->almoco_fim) > strtotime($horario_saida))
                        $duracaoAlmoco = MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($parametros->almoco_inicio);


                    $colecaoSites = array();
                    foreach ($sitesPermitidos as $value) {
                        $sitesProdutivos = BackupRelatorios::getSitesProdutivos($colaborador->id, $data, $value->nome, $fk_empresa);
                        if (!empty($sitesProdutivos))
                            array_push($colecaoSites, array($value->nome => $sitesProdutivos));
                    }


                    $ocioso = BackupRelatorios::getOciosoDia($colaborador->id, $data, $fk_empresa);

                    $colecaoAtivExterna = $colecaoProgramas = $colecaoImprodutivo = $sitesColecao = $colecaoSitesImprodutivos = $colecaoAlmoco = array();
                    $total_parcial_ativ_externa = $total_parcial_programas = $total_parcial_improdutivo = $total_parcial_sites = $total_parcial_sites_improdutivos = $total_parcial_almoco = 0;
                    $sites = "";
                    $i = 1;
                    foreach ($colecaoSites as $key => $value) {
                        foreach ($value as $chave => $valor) {
                            $j = 1;
                            foreach ($valor as $site) {

                                $sitesColecao[$chave]['duracao_total'] = (isset($sitesColecao[$chave]['duracao_total'])) ? $sitesColecao[$chave]['duracao_total'] + $site['duracao'] : $site['duracao'];
                                $sitesColecao[$chave][] = array($site['descricao'], $site['duracao']);
                                $total_parcial_sites += $site['duracao'];
                                if ($i == count($colecaoSites) && $j == count($valor))
                                    $sites .= "'" . addslashes($site['descricao']) . "'";
                                else
                                    $sites .= "'" . addslashes($site['descricao']) . "',";
                                $j++;
                            }
                            $i++;
                        }
                    }

                    $sitesImprodutivos = BackupRelatorios::getSitesImprodutivos($colaborador->id, $data, $sites, $fk_empresa);

                    foreach ($produtivo as $value) {
                        if ($value['programa'] == 'Atividade Externa') {
                            $colecaoAtivExterna[$value['programa']]['duracao_total'] = (isset($colecaoAtivExterna[$value['programa']]['duracao_total'])) ? $colecaoAtivExterna[$value['programa']]['duracao_total'] + $value['duracao'] : $value['duracao'];
                            $colecaoAtivExterna[$value['programa']][] = array($value['descricao'], $value['duracao']);
                            $total_parcial_ativ_externa += $value['duracao'];
                        } else {
                            $colecaoProgramas[$value['programa']]['duracao_total'] = (isset($colecaoProgramas[$value['programa']]['duracao_total'])) ? $colecaoProgramas[$value['programa']]['duracao_total'] + $value['duracao'] : $value['duracao'];
                            $colecaoProgramas[$value['programa']][] = array($value['descricao'], $value['duracao']);
                            $total_parcial_programas += $value['duracao'];
                        }

                    }

                    foreach ($sitesImprodutivos as $value) {
                        $colecaoSitesImprodutivos[$value['programa']]['duracao_total'] = (isset($colecaoSitesImprodutivos[$value['programa']]['duracao_total'])) ? $colecaoSitesImprodutivos[$value['programa']]['duracao_total'] + $value['duracao'] : $value['duracao'];
                        $colecaoSitesImprodutivos[$value['programa']][] = array($value['descricao'], $value['duracao']);
                        $total_parcial_sites_improdutivos += $value['duracao'];
                    }

                    foreach ($improdutivo as $value) {
                        $colecaoImprodutivo[$value['programa']]['duracao_total'] = (isset($colecaoImprodutivo[$value['programa']]['duracao_total'])) ? $colecaoImprodutivo[$value['programa']]['duracao_total'] + $value['duracao'] : $value['duracao'];
                        $colecaoImprodutivo[$value['programa']][] = array($value['descricao'], $value['duracao']);
                        $total_parcial_improdutivo += $value['duracao'];
                    }
                    foreach ($produtivoAlmoco as $value) {
                        $colecaoAlmoco[$value['programa']]['duracao_total'] = (isset($colecaoAlmoco[$value['programa']]['duracao_total'])) ? $colecaoAlmoco[$value['programa']]['duracao_total'] + $value['duracao'] : $value['duracao'];
                        $colecaoAlmoco[$value['programa']][] = array($value['descricao'], $value['duracao']);
                        $total_parcial_almoco += $value['duracao'];
                    }
                    $duracaoAlmoco -= $total_parcial_almoco;
                    if (($total_parcial_almoco > $duracaoAlmoco) || (strtotime($horario_entrada) > strtotime($parametros->almoco_fim)))
                        $duracaoAlmoco = 0;

                    array_push($colecaoAtivExterna, $total_parcial_ativ_externa);
                    array_push($colecaoProgramas, $total_parcial_programas);
                    $colecaoImprodutivo[0] = $total_parcial_improdutivo;
                    array_push($colecaoAlmoco, $total_parcial_almoco);
                    array_push($sitesColecao, $total_parcial_sites);
                    array_push($colecaoSitesImprodutivos, $total_parcial_sites_improdutivos);

                    // Exportar CSV
                    $tempoOcioso = BackupRelatorios::calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $total_parcial_improdutivo, $colecaoSitesImprodutivos, (MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($horario_entrada)));
                    $registros = array($colecaoProgramas, $sitesColecao, $colecaoAtivExterna, $colecaoImprodutivo, $colecaoSitesImprodutivos, $colecaoAlmoco, $tempoOcioso, $duracaoAlmoco);
                    BackupRelatorios::ExportCSVRelIndividualCol($registros, $colaborador->nomeCompleto, $data, $horario_entrada, $horario_saida, $src);

                    // PDF
                    // $caminhoPDF = BackupRelatorios::getRelatorioIndividual($colecaoAtivExterna, $horario_entrada, $horario_saida, $colecaoProgramas, $colecaoImprodutivo, $ocioso, $sitesColecao, $colecaoSitesImprodutivos, $colaborador->nomeCompleto, $data, $colecaoAlmoco, $duracaoAlmoco, $ociosoAlmoco, $fk_empresa, $src);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    // MÉTRICAS //
    public static function relatorioMetrica($ontem, $fk_empresa, $source)
    {
        try {
            $serial = Empresa::model()->findByPK($fk_empresa)->serial;
            $metricas = Metrica::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($metricas as $metrica) {
                $isSite = SitePermitido::model()->findByAttributes(array('nome' => $metrica->programa, 'fk_empresa' => $fk_empresa));

                // EQUIPES //
                $equipes = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
                foreach ($equipes as $equipe) {
                    $logs = (isset($isSite)) ? BackupRelatorios::getLogsMetricasEquipe($metrica->programa, $metrica->criterio, 1, $ontem, $ontem, $equipe->id, 1, $serial)
                        : BackupRelatorios::getLogsMetricasEquipe($metrica->programa, $metrica->criterio, $metrica->sufixo, $ontem, $ontem, $equipe->id, 0, $serial);

                    if (!empty($logs)) {
                        // Calcular Dias Uteis
                        $dias_uteis = BackupRelatorios::dias_uteis(strtotime($ontem), strtotime($ontem), $fk_empresa);

                        $responsavel = '<span><b>Equipe: ' . $equipe->nome . '</b></span><br>';
                        $selecionado = $equipe->nome;

                        // $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
                        $style = MetodosGerais::getStyleTable();
                        $rodape = '<page_footer>
                                            <div style="text-align: center ; font-size: 10px; color: #9C9C9C">
                                            ' . Yii::t('smith', 'Relatório gerado na plataforma Viva Smith') . '
                                            </div>
                                            </page_footer>';
                        $responsaveis = (isset($responsavel)) ? $responsavel : "";
                        $criterio = (!empty($metrica->criterio)) ? $metrica->criterio : $metrica->programa;
                        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                                        <page_header>
                                        <div class="header_page">
                                        <div class="header_title">
                                            <span>' . Yii::t('smith', 'RELATÓRIO DE ACOMPANHAMENTO DE MÉTRICAS') . '</span><br>
                                            <span style="font-size: 10px">' . Yii::t('smith', 'No dia') . ' ' . $ontem . ' </span>
                                        </div>
                                        <span><b>' . Yii::t('smith', 'Métrica') . ': ' . $metrica->titulo . '</b></span><br>
                                        <span><b>' . Yii::t('smith', 'Área de atuação') . ': ' . $metrica->atuacao . '</b></span><br>
                                        <span><b>' . Yii::t('smith', 'Aplicação Medida') . ': ' . $metrica->programa . '</b></span><br>
                                        <span><b>' . Yii::t('smith', 'Critério de filtragem') . ': ' . $criterio . '</b></span><br>

                                            ' . $responsaveis . '
                                        <div class="header_date">
                                        <p>Data:  ' . date('d/m/Y', strtotime($ontem)) . '
                                            <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                                        </div>
                                        </div>
                                        </page_header>
                                        </page>';
                        $html = $header;
                        $corpo = "";
                        $html .= $rodape;
                        $tempoTotal = 0;
                        $i = 0;

                        foreach ($logs as $log) {
                            $tempoTotal += MetodosGerais::time_to_seconds($log->duracao);
                            $corpo .= '<tr>
                                        <td>' . ($i + 1) . ' </td>
                                        <td>' . MetodosGerais::dataBrasileira($log->data) . '</td>
                                        <td>' . MetodosGerais::reduzirNome(Colaborador::model()->findByAttributes(array('ad' => $log->usuario, 'serial_empresa' => $serial))->nomeCompleto) . '</td>
                                        <td style="width: 440px">' . $log->descricao . ' </td>
                                        <td style="width: 93px">' . $log->duracao . '</td>
                                       </tr>';
                            $i++;
                        }

                        $html .= '<table  class="table_custom" border="1px">';
                        $html .= '<tr><td style="border-right-color: #FFFFFF; width: 615px ;text-align: left">Total: '.$i.' '.Yii::t('smith', 'entradas').'</td>';
                        $html .= '<td style="text-align: left"><span>'.Yii::t('smith', 'Tempo Total').': '.  MetodosGerais::formataTempo($tempoTotal).'</span><br>';
                        $tempoTotal = $tempoTotal/60;
                        $html .=  '<span>'.Yii::t('smith', 'Média').': '.  MetodosGerais::formataTempo((($tempoTotal/$dias_uteis))*60).' '.Yii::t('smith', 'por dia').'</span></td></tr></table> <p style="margin-top: 10px"></p>';

                        $html .= '  <table  class="table_custom" border="1px">
                                    <tr style="background-color: #CCC;text-decoration: bold;">
                                    <th>#</th>
                                    <th>'.Yii::t('smith', 'Data').'</th>
                                    <th>'.Yii::t('smith', 'Colaborador').'</th>
                                    <th>'.Yii::t('smith', 'Entrada').'</th>
                                    <th>'.Yii::t('smith', 'Tempo').'</th>
                                    </tr>';
                        $html .= $corpo;
                        $html .= '</table> ';

                        $src = $source .'/'. date('Y', strtotime($ontem)) .'/';
                        BackupRelatorios::checkDir($src);
                        $src .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                        BackupRelatorios::checkDir($src);
                        $src .= utf8_encode($metrica->titulo) . '/';
                        BackupRelatorios::checkDir($src);
                        $src .= utf8_encode('Equipes') . '/';
                        BackupRelatorios::checkDir($src);
                        $src .= date('d', strtotime($ontem)) . '/';
                        BackupRelatorios::checkDir($src);
                        $filename = 'acompanhamentoMetricasEquipe_' . utf8_encode($equipe->nome) . '_' . date('dmY', strtotime($ontem)) . '.pdf';
                        $html2pdf = Yii::app()->ePdf->HTML2PDF();
                        $html2pdf->WriteHTML($html . $style);
                        $html2pdf->Output($src . $filename, 'F');
                    }
                }

                // COLABORADORES //
                $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
                foreach ($colaboradores as $colaborador) {
                    $logs = (isset($isSite)) ? BackupRelatorios::getLogsMetricas($metrica->programa, $metrica->criterio, 1, $ontem, $ontem, $colaborador->ad, 1, $serial)
                        : BackupRelatorios::getLogsMetricas($metrica->programa, $metrica->criterio, $metrica->sufixo, $ontem, $ontem, $colaborador->ad, 0, $serial);

                    if (!empty($logs)) {
                        // Calcular Dias Uteis
                        $dias_uteis = BackupRelatorios::dias_uteis(strtotime($ontem), strtotime($ontem), $fk_empresa);

                        $responsavel = '<span><b>Equipe: ' . $colaborador->nome . '</b></span><br>';
                        $selecionado = $colaborador->nome;

                        // $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
                        $style = MetodosGerais::getStyleTable();
                        $rodape = '<page_footer>
                                            <div style="text-align: center ; font-size: 10px; color: #9C9C9C">
                                            ' . Yii::t('smith', 'Relatório gerado na plataforma Viva Smith') . '
                                            </div>
                                            </page_footer>';
                        $responsaveis = (isset($responsavel)) ? $responsavel : "";
                        $criterio = (!empty($metrica->criterio)) ? $metrica->criterio : $metrica->programa;
                        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                                        <page_header>
                                        <div class="header_page">
                                        <div class="header_title">
                                            <span>' . Yii::t('smith', 'RELATÓRIO DE ACOMPANHAMENTO DE MÉTRICAS') . '</span><br>
                                            <span style="font-size: 10px">' . Yii::t('smith', 'No dia') . ' ' . $ontem . ' </span>
                                        </div>
                                        <span><b>' . Yii::t('smith', 'Métrica') . ': ' . $metrica->titulo . '</b></span><br>
                                        <span><b>' . Yii::t('smith', 'Área de atuação') . ': ' . $metrica->atuacao . '</b></span><br>
                                        <span><b>' . Yii::t('smith', 'Aplicação Medida') . ': ' . $metrica->programa . '</b></span><br>
                                        <span><b>' . Yii::t('smith', 'Critério de filtragem') . ': ' . $criterio . '</b></span><br>

                                            ' . $responsaveis . '
                                        <div class="header_date">
                                        <p>Data:  ' . date('d/m/Y', strtotime($ontem)) . '
                                            <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                                        </div>
                                        </div>
                                        </page_header>
                                        </page>';
                        $html = $header;
                        $corpo = "";
                        $html .= $rodape;
                        $tempoTotal = 0;
                        $i = 0;

                        foreach ($logs as $log) {
                            $tempoTotal += MetodosGerais::time_to_seconds($log->duracao);
                            $corpo .= '<tr>
                                        <td>' . ($i + 1) . ' </td>
                                        <td>' . MetodosGerais::dataBrasileira($log->data) . '</td>
                                        <td>' . MetodosGerais::reduzirNome(Colaborador::model()->findByAttributes(array('ad' => $log->usuario, 'serial_empresa' => $serial))->nomeCompleto) . '</td>
                                        <td style="width: 440px">' . $log->descricao . ' </td>
                                        <td style="width: 93px">' . $log->duracao . '</td>
                                       </tr>';
                            $i++;
                        }

                        $html .= '<table  class="table_custom" border="1px">';
                        $html .= '<tr><td style="border-right-color: #FFFFFF; width: 615px ;text-align: left">Total: '.$i.' '.Yii::t('smith', 'entradas').'</td>';
                        $html .= '<td style="text-align: left"><span>'.Yii::t('smith', 'Tempo Total').': '.  MetodosGerais::formataTempo($tempoTotal).'</span><br>';
                        $tempoTotal = $tempoTotal/60;
                        $html .=  '<span>'.Yii::t('smith', 'Média').': '.  MetodosGerais::formataTempo((($tempoTotal/$dias_uteis))*60).' '.Yii::t('smith', 'por dia').'</span></td></tr></table> <p style="margin-top: 10px"></p>';

                        $html .= '  <table  class="table_custom" border="1px">
                                    <tr style="background-color: #CCC;text-decoration: bold;">
                                    <th>#</th>
                                    <th>'.Yii::t('smith', 'Data').'</th>
                                    <th>'.Yii::t('smith', 'Colaborador').'</th>
                                    <th>'.Yii::t('smith', 'Entrada').'</th>
                                    <th>'.Yii::t('smith', 'Tempo').'</th>
                                    </tr>';
                        $html .= $corpo;
                        $html .= '</table> ';

                        $src = $source .'/'. date('Y', strtotime($ontem)) .'/';
                        BackupRelatorios::checkDir($src);
                        $src .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                        BackupRelatorios::checkDir($src);
                        $src .= utf8_encode($metrica->titulo) . '/';
                        BackupRelatorios::checkDir($src);
                        $src .= utf8_encode('Colaboradores') . '/';
                        BackupRelatorios::checkDir($src);
                        $src .= date('d', strtotime($ontem)) . '/';
                        BackupRelatorios::checkDir($src);
                        $filename = 'acompanhamentoMetricasColaborador_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('dmY', strtotime($ontem)) . '.pdf';
                        $html2pdf = Yii::app()->ePdf->HTML2PDF();
                        $html2pdf->WriteHTML($html . $style);
                        $html2pdf->Output($src . $filename, 'F');
                    }
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    // CONTRATOS //
    public static function relatorioIndividualContratos($ontem, $fk_empresa, $src)
    {
        try {
            $contratos = Contrato::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'ativo' => 1));
            foreach ($contratos as $contrato) {
                $prefixo = $contrato->codigo;
                $dataInicio = BackupRelatorios::getDataInicio($prefixo, $fk_empresa);
                if (!empty($dataInicio)) {
                    $dataInicio = $dataInicio[0]['data'];
                    if (empty($contrato->documento)) {
                        $colaboradores = BackupRelatorios::getProdutividadeContratoPorColaborador('', $contrato, $dataInicio, $ontem, $fk_empresa);
                        $documentosLog = GrfProjetoConsolidado::model()->getDocumentosRelatorio('', $contrato, $dataInicio, $ontem, $fk_empresa);
                    } else {
                        $documentos = Contrato::model()->getDocumentos($contrato->id);
                        $tam = count($documentos);
                        $colaboradores = BackupRelatorios::getTempoPorContratoByAtt($prefixo, $dataInicio, $ontem, '', '', $fk_empresa);

                        for ($i = 0; $i < $tam; $i++) {
                            $padrao = $documentos[$i]['documento'];
                            $documentos[$i]['logs'] = GrfProjetoConsolidado::model()->findProjetosByPrefixo($padrao, $dataInicio, $ontem, $fk_empresa);
                        }
                    }
                    $coordenador = (isset(UserGroupsUser::model()->findByPk($contrato->coordenador)->nome)) ? UserGroupsUser::model()->findByPk($contrato->coordenador)->nome : 'Não definido';
                    $disciplinas = Disciplina::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));

                    if (empty($contrato->documento))
                        BackupRelatorios::GerarRelatorioDadosPrefixo($documentosLog, $contrato->nome, $coordenador, $colaboradores, $prefixo, $contrato->data_inicio, $ontem, $contrato->valor, $contrato->tempo_previsto, $contrato->data_final, $dataInicio, $fk_empresa, $src);
                    else
                        if (!empty($documentos)) BackupRelatorios::GerarRelatorioDados($documentos, $contrato->nome, $coordenador, $disciplinas, $colaboradores, $prefixo, $dataInicio, $ontem, $fk_empresa, $src);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioProdutividadeColaborador($date_from, $date_to, $fk_empresa, $src)
    {
        try {
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($colaboradores as $colaborador) {
                $registros = BackupRelatorios::getProdutividadeColaboradorPorContrato($colaborador->id, $date_from, $date_to, $fk_empresa);
                if (!empty($registros)) {
                    $categorias = $produzido = array();
                    $produzido['name'] = 'Contratos';
                    foreach ($registros as $value) {
                        $tempoProduzido = round((float)$value->duracao / 3600, 2);
                        if ($tempoProduzido > 0) {
                            $categorias[] = Contrato::model()->findByPk($value->fk_obra)->nome;
                            $produzido['data'][] = $tempoProduzido;
                        }
                    }
                    // Export CSV
                    $registrosCsv = array($produzido, $categorias);
                    $colunas = array('65' => 'Contrato', '66' => 'Duração');
                    $titulo = 'Produtividade de ' . $colaborador->nomeCompleto .' no mês de ' . MetodosGerais::mesString(date('m', strtotime($date_to)));
                    $filename = 'RelatorioProdutividadeColaboradorContratos_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('dmY', strtotime($date_to)) . '.csv';
                    $source = $src .'/'. date('Y', strtotime($date_to)) .'/';
                    BackupRelatorios::checkDir($source);
                    $source .= MetodosGerais::mesString(date('m', strtotime($date_to))) . '/';
                    BackupRelatorios::checkDir($source);
                    $source .= date('d', strtotime($date_to)) . '/';
                    BackupRelatorios::checkDir($source);
                    BackupRelatorios::ExportToCsv($registrosCsv, $colunas, $titulo, $filename, 'relPrdColContrato', $fk_empresa, $source, null, $date_to);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioGeralContratos($ontem, $fk_empresa, $src)
    {
        try {
            $dados = array();

            // EQUIPES //
            $equipes = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($equipes as $equipe) {
                $registros = BackupRelatorios::getProdutividadeProjetosByAtt($equipe->id, 'equipe', $ontem, $ontem, $fk_empresa);
                if (!empty($registros)) {
                    $dados = Contrato::model()->formatDataRelatorioGeralContratoOpEquipe($registros);
                    BackupRelatorios::ExportCSVRelGeralContrato($dados, $ontem, 'equipe', utf8_encode($equipe->nome), $src);
                }
            }

            // COLABORADORES //
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($colaboradores as $colaborador) {
                $registros = BackupRelatorios::getProdutividadeProjetosByAtt($colaborador->id, 'colaborador', $ontem, $ontem, $fk_empresa);
                if (!empty($registros)) {
                    $dados = Contrato::model()->formatDataRelatorioGeralContratoOpColaborador($registros);
                    BackupRelatorios::ExportCSVRelGeralContrato($dados, $ontem, 'colaborador', utf8_encode($colaborador->nomeCompleto), $src);
                }
            }

            // CONTRATOS //
            $contratos = Contrato::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($contratos as $contrato) {
                $registros = BackupRelatorios::getProdutividadeProjetosByAtt($contrato->id, 'contrato', $ontem, $ontem, $fk_empresa);
                if (!empty($registros)) {
                    $dados = Contrato::model()->formatDataRelatorioGeralContratoOpContrato($registros);
                    BackupRelatorios::ExportCSVRelGeralContrato($dados, $ontem, 'contrato', utf8_encode($contrato->nome), $src);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function relatorioCustoEnergia($inicioMes, $ontem, $fk_empresa, $src)
    {
        try {
            $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
            $tarifa = $parametros->tarifa_energia;

            $contratos = Contrato::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
            foreach ($contratos as $contrato) {
                $tempoGasto = BackupRelatorios::getTempoTotalContrato($contrato->id, $inicioMes, $ontem, $fk_empresa);
                if (!empty($tempoGasto) && $tempoGasto->duracao != 0) {
                    $potenciaPC = 200;
                    $valor = round((($potenciaPC * ($tempoGasto->duracao / 3600)) / 1000) * $tarifa, 2);
                    if ($valor != 0) {
                        $categorias = $produzido = array();

                        $categorias[] = MetodosGerais::mesString($tempoGasto->mes) . "-" . $tempoGasto->ano;
                        $produzido['name'] = $contrato->nome;
                        $produzido['data'][] = $valor;

                        $registros = array($produzido, $categorias);
                        $colunas = array('65' => 'Mês-Ano', '66' => 'Custo consumo (R$)');
                        $titulo = 'Cosumo de energia '. $contrato->nome;
                        $filename = 'RelatorioCustoEnergia_'. $contrato->nome .'_'. date('dmY', strtotime($ontem)) .'.csv';
                        $source = $src .'/'. date('Y', strtotime($ontem)) .'/';
                        BackupRelatorios::checkDir($source);
                        $source .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
                        BackupRelatorios::checkDir($source);
                        $source .= date('d', strtotime($ontem)) . '/';
                        BackupRelatorios::checkDir($source);
                        BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relCustoEnergia', $fk_empresa, $source, null, $ontem);
                    }
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function checkDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0755);
        }
    }

    public static function dias_uteis($diaIni, $diaFim, $fk_empresa)
    {
        $countUteis = 0;
        $feriados = CalendarioFeriados::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'ativo' => 1));
        while ($diaIni <= $diaFim) {
            $dS = date("w", $diaIni);
            if ($dS != "0" && $dS != "6") {
                $countUteis++;
                foreach ($feriados as $feriado) {
                    $diaInicial = date("Y-m-d", $diaIni);
                    if ($feriado->data == $diaInicial) {
                        $countUteis--;
                        break;
                    }
                }
            }
            $diaIni += 86400;
        }
        return $countUteis;
    }

    public static function diasUteisColaborador($diaIni, $diaFim, $ferias, $fk_empresa)
    {
        $countUteis = 0;
        $feriados = CalendarioFeriados::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));

        while ($diaIni <= $diaFim) {
            $dS = date("w", $diaIni);
            if ($dS != "0" && $dS != "6") {
                $countUteis++;
                foreach ($feriados as $feriado) {
                    $diaInicial = date("Y-m-d", $diaIni);
                    if ($feriado->data == $diaInicial) {
                        $countUteis--;
                        break;
                    }
                }
                foreach ($ferias as $obj) {
                    if ($diaIni >= (strtotime($obj->data_inicio)) && $diaIni <= (strtotime($obj->data_fim))) {
                        $countUteis--;
                        break;
                    }
                }
            }
            $diaIni += 86400;
        }
        return $countUteis;
    }

    public static function datas_uteis_mes($mes, $ano, $fk_empresa) {
        $dias_no_mes = $num = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $arrayDatas = $arrayFeriados = array();
        for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
            $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
            $semana = date("N", $timestamp);
            if ($semana < 6) {
                array_push($arrayDatas, date("d/m", $timestamp));
            }
        }
        $feriados = CalendarioFeriados::model()->findAll(array("condition" => "fk_empresa = " . $fk_empresa . " AND data like '$ano-$mes%' AND ativo = 1"));
        foreach ($feriados as $feriado) {
            $arrayFeriados[] = date('d/m', strtotime($feriado->data));
        }
        $resultado = array_diff($arrayDatas, $arrayFeriados);
        return $resultado;
    }

    private function formataDataRelIndDiasCSV($registros, $phpExcel) {
        $index = 3;
        foreach ($registros as $colaborador) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, MetodosGerais::dataBrasileira($colaborador->data))
                ->setCellValue('B' . $index, round((float)$colaborador->duracao, 2))
                ->setCellValue('C' . $index, round((float)$colaborador->hora_total, 2));
            $index++;
        }
        return $phpExcel;
    }

    private function formataDataRelCustoCSV($registros, $phpExcel) {
        $index = 3;
        $numCategoria = count($registros[2]);
        for ($i = 0; $i < $numCategoria; $i++) {
            if ($registros[0]['data'][$i] !== 0 && $registros[1]['data'][$i] !== 0) {
                $numProduzido = ($registros[0]['data'][$i] >= 0) ? $registros[0]['data'][$i] : 0;
                $numOcioso = ($registros[1]['data'][$i] >= 0) ? $registros[1]['data'][$i] : 0;
                $phpExcel->getActiveSheet()
                    ->setCellValue('A' . $index, $registros[2][$i])
                    ->setCellValue('B' . $index, 'R$' . number_format($numProduzido, 2, ',', '.'))
                    ->setCellValue('C' . $index, 'R$' . number_format($numOcioso, 2, ',', '.'));
                $index++;
            }
        }

        return $phpExcel;
    }

    private function formataDataRelIndCSV($registros, $phpExcel, $tipo) {
        $index = 3;
        $periodo = '';
        foreach ($registros[3] as $key => $value) {
            if ($tipo == 'RelatorioIndividualDiário.csv')
                $periodo = ($value - 1) . ':00 às ' . $value . ':00';
            else
                $periodo = $value;
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $periodo)
                ->setCellValue('B' . $index, MetodosGerais::formataTempo($registros[0]['data'][$key]))
                ->setCellValue('C' . $index, MetodosGerais::formataTempo($registros[1]['data'][$key]))
                ->setCellValue('D' . $index, MetodosGerais::formataTempo($registros[2]['data'][$key]));
            $index++;
        }
        return $phpExcel;
    }

    private function formatDataRelRanking($registros, $phpExcel) {
        $index = 3;
        foreach ($registros as $key => $valor) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, ucwords(mb_convert_case($valor->equipe, MB_CASE_LOWER, mb_detect_encoding($valor->equipe))))
                ->setCellValue('B' . $index, Colaborador::model()->findByPk($valor->fk_colaborador)->nomeCompleto)
                ->setCellValue('C' . $index, GrfProdutividadeConsolidado::formatarProdutividade($valor->produtividade))
                ->setCellValue('D' . $index, GrfProdutividadeConsolidado::formatarProdutividade($valor->meta))
                ->setCellValue('E' . $index, str_replace(".", ",", round($valor->coeficiente, 2)));
            $index++;
        }
        return $phpExcel;
    }

    private function formataDataRelHoraExtra($registros, $phpExcel) {
        $index = 3;
        foreach ($registros[2] as $key => $value) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $value)
                ->setCellValue('B' . $index, MetodosGerais::formataTempo($registros[0][$key] * 3600))
                ->setCellValue('C' . $index, MetodosGerais::formataTempo($registros[1][$key] * 3600));
            $index++;
        }
        return $phpExcel;
    }

    private function formataDataRelPrdGeral($registros, $phpExcel) {
        $index = 3;
        foreach ($registros as $key => $value) {
            if (!empty($value[0])) {
                foreach ($value[1] as $chave => $valor) {
                    $phpExcel->getActiveSheet()->setCellValue('A' . $index, $key)
                        ->setCellValue('B' . $index, $valor)
                        ->setCellValue('C' . $index, MetodosGerais::formataTempo($value[0][$chave]));
                    $index++;
                }
            }
        }
        return $phpExcel;
    }

    private function formataDataRelPrdColContrato($registros, $phpExcel) {
        $index = 3;
        foreach ($registros[1] as $key => $value) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $value)
                ->setCellValue('B' . $index, MetodosGerais::formataTempo($registros[0]['data'][$key] * 3600));
            $index++;
        }
        return $phpExcel;
    }

    private function formataDataRelCustoEnergia($registros, $phpExcel) {
        $index = 3;
        foreach ($registros[1] as $key => $value) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $value)
                ->setCellValue('B' . $index, 'R$' . MetodosGerais::float2real($registros[0]['data'][$key]));
            $index++;
        }
        return $phpExcel;
    }

    private function formataDataRelPonto($registros, $phpExcel) {
        $index = 3;
        foreach ($registros as $key => $value) {
            foreach ($value as $chave => $valor) {
                if ($valor->nome != '' && !empty($valor->nome)) {
                    $nomeCompleto = Colaborador::model()->findByPk($valor->fk_colaborador)->nomeCompleto;
                    $phpExcel->getActiveSheet()
                        ->setCellValue('A' . $index, MetodosGerais::dataBrasileira($valor->data))
                        ->setCellValue('B' . $index, $valor->nomeEquipe)
                        ->setCellValue('C' . $index, $nomeCompleto)
                        ->setCellValue('D' . $index, $valor->hora_entrada)
                        ->setCellValue('E' . $index, $valor->hora_saida);
                    $index++;
                }
            }
        }
        return $phpExcel;
    }

    public static function formataDataRelPontoAtual($registros, $phpExcel)
    {
        $index = 3;
        foreach ($registros as $key => $value) {
            foreach ($value as $chave => $valor) {
                $phpExcel->getActiveSheet()
                    ->setCellValue('A' . $index, MetodosGerais::dataBrasileira($valor['data']))
                    ->setCellValue('B' . $index, $valor['nome'])
                    ->setCellValue('C' . $index, date('H:i:s', strtotime(MetodosGerais::getHoraServidor($valor['hora_inicio']))))
                    ->setCellValue('D' . $index, date('H:i:s', strtotime(MetodosGerais::getHoraServidor($valor['hora_final']))));
                $index++;
            }
        }
        return $phpExcel;
    }

    public static function produtividadeDiariaPorData($dataInicio, $dataFim, $fkColaborador, $fkEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'nome, duracao, hora_total, data';
        $criteria->addCondition("fk_empresa=".$fkEmpresa);
        $criteria->addCondition("fk_colaborador = $fkColaborador");
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->order = 'data ASC';
        return GrfProdutividadeConsolidado::model()->findAll($criteria);
    }

    public static function getProdutividadeDiaria($colaborador, $data, $fkEmpresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT DISTINCT (HOUR( at.hora_host )+1) AS hora, SUM( TIME_TO_SEC( at.duracao ) ) AS duracao, p.nome
        FROM log_atividade AS at
        INNER JOIN colaborador AS p ON p.ad = at.usuario
        WHERE
          at.fk_empresa = $fkEmpresa
         AND p.id = $colaborador
         AND at.data LIKE '$data' AND
        (at.`programa` IN
            (SELECT nome FROM programa_permitido WHERE (fk_empresa = $fkEmpresa AND fk_equipe = p.fk_equipe)
            OR (fk_empresa = $fkEmpresa AND fk_equipe IS NULL)))
            AND at.descricao NOT LIKE ''
            GROUP BY hora";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function produtividadeDiariaPorMesAno($mes, $ano, $fkColaborador, $fk_empresa)
    {
        $fkEmpresa = ($fk_empresa == 41) ? '22' : $fk_empresa; //filtro ambiente demo
        $criteria = new CDbCriteria;
        $criteria->select = 'nome, duracao*3600 as duracao, data';
        $criteria->addCondition("fk_empresa=".$fkEmpresa);
        $criteria->addCondition("fk_colaborador = $fkColaborador");
        $criteria->addCondition("MONTH(data) = $mes");
        $criteria->addCondition("YEAR(data) = $ano");
        $criteria->order = 'data ASC';
        return GrfProdutividadeConsolidado::model()->findAll($criteria);
    }

    public static function produtividadeDiariaPorAno($ano, $fkColaborador, $fk_empresa)
    {
        $fkEmpresa = ($fk_empresa == 41) ? '22' : $fk_empresa; //filtro ambiente demo
        $criteria = new CDbCriteria;
        $criteria->select = 'nome, (SUM(duracao))*3600 as duracao, MONTH(data) as data';
        $criteria->addCondition("fk_empresa=".$fkEmpresa);
        $criteria->addCondition("fk_colaborador = $fkColaborador");
        $criteria->addCondition("YEAR(data) = $ano");
        $criteria->group = "MONTH(data)";
        $criteria->order = 'data ASC';
        return GrfProdutividadeConsolidado::model()->findAll($criteria);
    }

    public static function renderProdutividadeIndDia($colaborador, $produtividadeDia, $fk_empresa, $src, $ontem)
    {
        $mediaEquipe = BackupRelatorios::getMediaProdEquipeDia($colaborador->fk_equipe, $ontem, $fk_empresa);
        $meta = ((60 * $colaborador->equipes->meta) / 100) * 60;
        $produtivo['name'] = Yii::t('smith', 'Produtividade');
        for ($i = 7; $i <= 18; $produzido['data'][$i] = (float)0, $categorias[] = "$i", $produzidoMedia['data'][$i] = (float)0, $produzidoMeta['data'][$i] = $meta, $i++) ;
        foreach ($produtividadeDia as $value) {
            ($value['duracao'] > 3600) ? $produzido['data'][$value['hora']] = 3200 : $produzido['data'][$value['hora']] = (float)$value['duracao'];
        }
        foreach ($mediaEquipe as $value) {
            $produzidoMedia['data'][$value['hora']] = (float)$value['duracao'];
        }
        $produtivoMedia['name'] = Yii::t('smith', 'Média da equipe');
        $produtivoMeta['name'] = Yii::t('smith', 'Meta estabelecida');
        $contador = array_keys($produzido['data']);
        $contador = array_pop($contador);
        for ($i = 7; $i <= $contador; $i++) {
            $produtivo['data'][] = (!isset($produzido['data'][$i])) ? (float)0 : $produzido['data'][$i];
            $produtivoMedia['data'][] = (!isset($produzidoMedia['data'][$i])) ? (float)0 : $produzidoMedia['data'][$i];
            $produtivoMeta['data'][] = (!isset($produzidoMeta['data'][$i])) ? $meta : $produzidoMeta['data'][$i];
            if (!in_array($i, $categorias)) {
                array_push($categorias, "$i");
            }
        }
        $registros = array($produtivo, $produtivoMedia, $produtivoMeta, $categorias);
        $colunas = array('65' => 'Período (h)', '66' => 'Produtividade', '67' => 'Média equipe', '68' => 'Meta estabelecida');
        $titulo = Yii::t('smith', 'Produtividade de ') . $colaborador->nomeCompleto . Yii::t('smith', ' no dia ') . date('d/m/Y', strtotime($ontem));
        $filename = utf8_encode('RelatorioIndividualDiário_') . utf8_encode($colaborador->nomeCompleto) . '_' . date('dmY', strtotime($ontem)) . '.csv';
        $src = $src .'/'. date('Y') .'/';
        BackupRelatorios::checkDir($src);
        $src .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
        BackupRelatorios::checkDir($src);
        $src .= date('d', strtotime($ontem)) . '/';
        BackupRelatorios::checkDir($src);
        BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInd', $fk_empresa, $src, $ontem);
    }

    public static function renderProdutividadeIndMes($colaborador, $registrosMes, $fk_empresa, $src, $ontem)
    {
        $meta = ((($colaborador->horas_semana / 5) * $colaborador->equipes->meta) / 100) * 3600;
        $datasUteis = BackupRelatorios::datas_uteis_mes(date('m', strtotime($ontem)), date('Y', strtotime($ontem)), $fk_empresa);
        $mediaEquipe = BackupRelatorios::getMediaProdEquipeMes($colaborador->fk_equipe, date('m', strtotime($ontem)), date('Y', strtotime($ontem)), $fk_empresa);
        foreach ($datasUteis as $data) {
            $categorias[$data] = $data;
            $produtivo['data'][$data] = (float)0;
            $produtivoMeta['data'][$data] = $meta;
            $produtivoMedia['data'][$data] = (float)0;
        }
        foreach ($registrosMes as $value) {
            $categorias[date('d/m', strtotime($value->data))] = date('d/m', strtotime($value->data));
            $produtivo['data'][date('d/m', strtotime($value->data))] = (float)$value->duracao;
            $produtivoMeta['data'][date('d/m', strtotime($value->data))] = $meta;
        }
        foreach ($mediaEquipe as $value) {
            $produtivoMedia['data'][date('d/m', strtotime($value->data))] = (float)$value->duracao * 3600;
        }
        ksort($categorias);
        ksort($produtivo['data']);
        ksort($produtivoMeta['data']);
        ksort($produtivoMedia['data']);
        $diff = array_keys(array_diff_key($produtivoMedia['data'], $produtivoMeta['data']));
        foreach ($diff as $value) {
            unset($produtivoMedia['data'][$value]);
        }
        $splitedProduzido = array_chunk($produtivo['data'], 8);
        $splitedProduzidoMeta = array_chunk($produtivoMeta['data'], 8);
        $splitedProduzidoMedia = array_chunk($produtivoMedia['data'], 8);
        $splitedCategorias = array_chunk($categorias, 8);
        for ($i = 1; $i <= count($splitedProduzido); $i++) {
            $options[$i] = ($i) . "° " . Yii::t('smith', 'página de resultados');
        }
        $registros = array($produtivo, $produtivoMedia, $produtivoMeta, $categorias);
        $colunas = array('65' => 'Período (dias)', '66' => 'Produtividade', '67' => 'Média equipe', '68' => 'Meta estabelecida');
        $titulo = Yii::t('smith', 'Produtividade de ') . $colaborador->nomeCompleto . Yii::t('smith', ' no mês ') . date('m', strtotime($ontem));
        $filename = 'RelatorioIndividualMensal_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('mY', strtotime($ontem)) . '.csv';
        $src = $src .'/'. date('Y', strtotime($ontem)) .'/';
        BackupRelatorios::checkDir($src);
        $src .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
        BackupRelatorios::checkDir($src);
        BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInd', $fk_empresa, $src, null, $ontem);
    }

    public static function renderProdutividadeIndAno($colaborador, $registrosAno, $fk_empresa, $src, $ontem)
    {
        $meta = ((($colaborador->horas_semana * 4) * $colaborador->equipes->meta) / 100) * 3600;
        for ($i = 1; $i <= 12; $produzido['data'][$i] = (float)0, $produtivoMedia['data'][$i] = (float)0, $categorias[$i] = MetodosGerais::mesString($i), $categoriasGrf[] = MetodosGerais::mesString($i), $produtivoMeta['data'][$i] = $meta, $i++) ;
        $mediaEquipe = BackupRelatorios::getMediaProdEquipeAno($colaborador->fk_equipe, date('Y', strtotime($ontem)), $fk_empresa);

        foreach ($registrosAno as $value) {
            $produzido['data'][$value->data] = (float)$value->duracao;
        }
        foreach ($mediaEquipe as $value) {
            $produtivoMedia['data'][$value->data] = (float)$value->duracao * 3600;
        }
        $registros = array($produzido, $produtivoMedia, $produtivoMeta, $categorias);
        $colunas = array('65' => 'Período (meses)', '66' => 'Produtividade', '67' => 'Média equipe', '68' => 'Meta estabelecida');
        $titulo = Yii::t('smith', 'Produtividade de ') . $colaborador->nomeCompleto . Yii::t('smith', ' no ano ') . date('Y');
        $filename = 'RelatorioIndividualAnual_' . utf8_encode($colaborador->nomeCompleto) . '_' . date('Y', strtotime($ontem)) . '.csv';
        $src = $src .'/'. date('Y', strtotime($ontem)) .'/';
        BackupRelatorios::checkDir($src);
        BackupRelatorios::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInd', $fk_empresa, $src, null, $ontem);
    }

    public static function getMediaProdEquipeDia($equipe, $data, $fkEmpresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT DISTINCT (HOUR( at.hora_host )+1) AS hora , SUM(TIME_TO_SEC(at.duracao))/count(distinct(at.usuario)) AS duracao
        FROM log_atividade AS at
        INNER JOIN colaborador AS p ON p.ad = at.usuario
        WHERE  at.fk_empresa = $fkEmpresa
            AND p.fk_equipe = $equipe
            AND at.data LIKE '$data'
            AND at.descricao NOT LIKE ''
            AND (at.`programa` IN
            (SELECT nome FROM programa_permitido WHERE (fk_empresa = $fkEmpresa AND fk_equipe = p.fk_equipe)
              OR (fk_empresa = $fkEmpresa AND fk_equipe IS NULL)))
            GROUP BY hora;";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getMediaProdEquipeMes($fkEquipe, $mes, $ano, $fk_empresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '(sum(t.duracao))/count(1) as duracao,  t.data';
        $criteria->join = 'inner join colaborador as c on t.fk_colaborador = c.id';
        $criteria->addCondition('c.fk_equipe = ' . $fkEquipe);
        $criteria->addCondition('t.fk_empresa = ' . $fk_empresa);
        $criteria->addCondition("t.data like '$ano-$mes%' ");
        $criteria->group = 't.data';
        return GrfProdutividadeConsolidado::model()->findAll($criteria);
    }

    public static function getMediaProdEquipeAno($fkEquipe, $ano, $fk_empresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '(sum(t.duracao))/count(distinct(t.fk_colaborador)) as duracao,  MONTH(t.data) as data';
        $criteria->join = 'inner join colaborador as c on t.fk_colaborador = c.id';
        $criteria->addCondition('c.fk_equipe = ' . $fkEquipe);
        $criteria->addCondition('t.fk_empresa = ' . $fk_empresa);
        $criteria->addCondition("t.data like '$ano%' ");
        $criteria->group = 'MONTH(t.data)';
        return GrfProdutividadeConsolidado::model()->findAll($criteria);
    }

    public static function getSalarioTempoEquipe($dias, $fk_equipe, $fk_empresa)
    {
        $sql = "SELECT  (SUM( p.salario ) * $dias)/30 AS salario_equipe,
        (SUM(TIME_TO_SEC( p.`horas_semana` )) /3600) /5 AS hora_total, eq.nome
        FROM  `colaborador` p
        INNER JOIN equipe AS eq ON p.fk_equipe = eq.id
        AND eq.fk_empresa = $fk_empresa
        AND eq.id = $fk_equipe
        GROUP BY p.fk_equipe
        ORDER BY eq.id ASC ";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTempoProduzidoColaboradorPorAtributos($date_from, $date_to, $colaborador, $fk_empresa)
    {
        $sql = " SELECT  (pe.salario) /22 AS salario_colaborador, ((TIME_TO_SEC( pe.`horas_semana` )) /3600) /5 AS hora_total, CONCAT(pe.nome,' ',pe.sobrenome) as nome,
        SUM( TIME_TO_SEC( at.`duracao` ) ) AS Duracao_colaborador, pe.id as fk_colaborador
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        WHERE at.data BETWEEN  '" . $date_from . "' AND  '" . $date_to . "'
        AND  (at.`programa` IN
        (SELECT nome FROM programa_permitido WHERE (fk_empresa = $fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $fk_empresa AND fk_equipe IS NULL)))
        AND at.descricao NOT LIKE ''
        AND pe.fk_empresa = $fk_empresa";
        if ($colaborador != 'todos_colaboradores')
        $sql .= ' AND pe.id = ' . $colaborador . ' ';
        $sql .= ' GROUP BY pe.nome
        ORDER BY pe.id';
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function grfRelatorioRanking($inicio, $fim, $fk_empresa)
    {
        $criteria = new CDbCriteria;
        $dias_uteis = BackupRelatorios::dias_uteis(strtotime($inicio), strtotime($fim), $fk_empresa);
        $criteria->select = "SUM((t.duracao))*100 AS duracao, "
            . "((t.hora_total))*$dias_uteis AS hora_total, "
            . "(SUM((t.duracao))*100)/((t.hora_total)*$dias_uteis) AS produtividade,"
            . "equipe, t.nome, fk_colaborador, pe.fk_equipe as fk_equipe, e.meta as meta, ((SUM((t.duracao))*100)/((t.hora_total)*$dias_uteis)/e.meta) as coeficiente";
        $criteria->join = "JOIN colaborador as pe ON pe.id = t.fk_colaborador";
        $criteria->join .= " JOIN equipe as e ON pe.fk_equipe = e.id";
        $criteria->condition = 't.fk_empresa=:fk_empresa';
        $criteria->params = array(':fk_empresa'=>$fk_empresa);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        $criteria->group = 'pe.id';
        $criteria->order = 'coeficiente DESC';

        return GrfProdutividadeConsolidado::model()->findAll($criteria);
    }

    public static function getRelatorioColaborador2($dados, $dataInicio, $dataFim, $fk_empresa)
    {
        $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                <page_header>
                    <div class="header_page">
                        <img class="header_logo_page" src="' . $imagem . '">
                        <div class="header_title"><p>' . Yii::t("smith", 'CONTROLE DE ENTRADA E SAÍDA') . '</p></div>
                        <span><b>' . Yii::t("smith", 'Período de datas:') . ' ' . $dataInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dataFim . '</b></span>
                        <br>
                        <div class="header_date">
                                <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
                                <br>' . Yii::t('smith', 'Pág.') . ' ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                    </div>
                </page_header>
            </page>';

        $rodape = MetodosGerais::getRodapeTable();
        $html = $header;
        $html .= $rodape;
        $i = 0;

        foreach ($dados as $data => $ponto) {
            if ($i > 0 && count($ponto) > 1) {
                $html .= '<page pageset="old">';
            }
            $html .= '<p style="margin-top: 5px"></p>
                        <table  class="table_custom" border="1px">
                            <tr>
                                <th colspan="4" style="text-align: left;">
                                    ' . Yii::t("smith", 'Data') . ': ' . MetodosGerais::dataBrasileira($data) .
                '</th>
                            </tr>
                            <tr style="background-color: #CCC; text-decoration: bold;">
                                <th>' . Yii::t("smith", 'Equipe') . '</th>
                                <th>' . Yii::t("smith", 'Colaborador') . '</th>
                                <th>' . Yii::t("smith", 'Horário de Entrada') . '</th>
                                <th>' . Yii::t("smith", 'Horário de Saída') . '</th>
                            </tr>';
            foreach ($ponto as $valor) {
                if ($valor->nome != '' && !empty($valor->nome)) {
                    $aux = mb_convert_case($valor->nomeEquipe, MB_CASE_LOWER, mb_detect_encoding($valor->nomeEquipe));
                    $hora_inicio = $valor->hora_entrada;
                    $hora_final = $valor->hora_saida;
                    $nomeCompleto = Colaborador::model()->findByPk($valor->fk_colaborador)->nomeCompleto;
                    $html .= '<tr>'
                        . '<td style="text-align: left; width: 200px">' . ucwords($aux) . '</td>'
                        . '<td style="text-align: left; width: 250px">' . $nomeCompleto . '</td>'
                        . '<td >' . $hora_inicio . '</td>'
                        . '<td>' . $hora_final . '</td>'
                        . '</tr>';
                }
            }

            $html .= "</table>";
            if ($i > 0 && count($ponto) > 1)
                $html .= '</page>';
            $i++;
        }

        $style = MetodosGerais::getStyleTable();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $inicio = str_replace('/', '-', $dataInicio);
        $final = str_replace('/', '-', $dataFim);
        if ($_POST['colaborador_id'] != '') {
            $colaborador = MetodosGerais::reduzirNome(Colaborador::model()->findByPk($_POST['colaborador_id'])->nomeCompleto);
            $colaborador = MetodosGerais::reduzirNome($colaborador);
            $colaborador = explode(' ', $colaborador);
            $colaborador = $colaborador[0] . $colaborador[1];
        } else {
            $colaborador = 'todos';
        }
        $html2pdf->Output(Yii::t('smith', 'acompanhamentoColaboradores') . '_' . $colaborador . '_' . $inicio . '_' . Yii::t('smith', 'ate') . '_' . $final . '.pdf');
    }

    public static function getTempoAtividadeExterna($dataInicio, $dataFim, $opcao, $tipo, $fk_empresa)
    {
        $serialEmpresa = Empresa::model()->findByPk($fk_empresa)->serial;
        $criteria = new CDbCriteria;
        $criteria->select = 'programa, sum(TIME_TO_SEC(duracao)) as duracao,t.descricao';
        $criteria->join = 'INNER JOIN colaborador as pe ON pe.ad = t.usuario';
        $criteria->join .= ' INNER JOIN equipe as eq ON pe.fk_equipe = eq.id';
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->addCondition("t.serial_empresa = '$serialEmpresa'");
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $criteria->addCondition("eq.id = {$opcao}");
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $criteria->addCondition("pe.id = {$opcao}");
        $criteria->group = 't.descricao';
        $criteria->order = 'duracao desc';
        return AtividadeExterna::model()->findAll($criteria);
    }

    public static function getTempoProduzidoByAtributos($date_from, $date_to, $opcao, $tipo, $fk_empresa)
    {
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $sql = "SELECT eq.nome, at.`programa`, SUM(TIME_TO_SEC(at.`duracao`)) as duracao
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE
        at.fk_empresa = {$fk_empresa} AND
        at.programa NOT LIKE 'Atividade Externa' AND
        (at.`programa` IN
            (SELECT nome FROM programa_permitido WHERE (fk_empresa = $fk_empresa AND fk_equipe = pe.fk_equipe)
            OR (fk_empresa = $fk_empresa AND fk_equipe IS NULL)))
            AND eq.fk_empresa = '" . $fk_empresa . "' "
            . "AND at.data BETWEEN '{$date_from}' AND '{$date_to}' AND at.descricao NOT LIKE '' ";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao} ";
        $sql .= " GROUP BY at.programa";
        $sql .= " ORDER BY duracao desc";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTempoProduzidoSitesByAtributos($date_from, $date_to, $opcao, $tipo, $fk_empresa)
    {
        $sql = "SELECT DISTINCT p.nome as programa , log.descricao , sum(log.duracao) as duracao "
        . "FROM site_permitido AS p INNER JOIN log_atividade_consolidado AS log ON log.descricao LIKE CONCAT( '%', p.nome, '%' )"
        . " INNER JOIN colaborador AS pe ON pe.ad = log.usuario "
        . "INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id "
        . "WHERE pe.fk_empresa = $fk_empresa "
        . "AND p.fk_empresa = $fk_empresa "
        . "AND log.data BETWEEN '$date_from' AND '$date_to' ";

        if ($tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        else
            $sql .= " AND pe.id = {$opcao}";
        $sql .= " GROUP BY p.nome order by duracao desc";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTempoTotalEquipe($opcao, $tipo, $fk_empresa)
    {
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $sql = "SELECT nome , SUM(TIME_TO_SEC(horas_semana)/5) as tempo_total FROM colaborador
        WHERE serial_empresa like '$serial' AND ativo = 1";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND fk_equipe = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND id = {$opcao}";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTempoProgramaNaoIdentificado($date_from, $date_to, $opcao, $tipo, $fk_empresa)
    {
        $sql = "SELECT
        at.`programa`, at.descricao,
        SUM(TIME_TO_SEC(at.`duracao`)) as duracao
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario AND pe.fk_empresa = at.fk_empresa
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE pe.fk_empresa = $fk_empresa AND at.data BETWEEN '{$date_from}' AND '{$date_to}' AND at.programa not in ('Google Chrome','Internet Explorer','Firefox','Mozilla Firefox')
        AND (TRIM(at.`programa`) NOT IN
        (SELECT TRIM(nome) FROM programa_permitido WHERE (fk_empresa = $fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $fk_empresa AND fk_equipe IS NULL)))
        AND at.descricao not like '' AND at.descricao not like 'Ocioso'
        ";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao}";
        $sql .= " GROUP BY at.descricao, at.programa ORDER BY duracao desc ";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTempoSiteNaoIdentificado($date_from, $date_to, $opcao, $tipo, $fk_empresa)
    {
        $sql = "SELECT
        at.`programa`, at.descricao,
        SUM(TIME_TO_SEC(at.`duracao`)) as duracao
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario AND pe.fk_empresa = at.fk_empresa
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE pe.fk_empresa = $fk_empresa AND at.data BETWEEN '{$date_from}' AND '{$date_to}' AND at.programa in ('Google Chrome','Opera','Safari','Internet Explorer','Mozilla Firefox')";

        $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
        foreach ($sitesPermitidos as $site) {
            $sql .= " AND TRIM(at.descricao) NOT LIKE '%$site->nome%'";
        }

        $sql .= " AND at.descricao NOT LIKE '' AND at.descricao NOT LIKE 'Ocioso'";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao}";
        $sql .= " GROUP BY at.descricao, at.programa ORDER BY duracao desc ";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getProgramasProdutivosDia($colaborador, $data, $fk_empresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $sql = "SELECT at.programa , at.descricao, SUM(TIME_TO_SEC (at.duracao)) as duracao FROM log_atividade_consolidado as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        WHERE (TRIM(at.`programa`) IN
        (SELECT (nome) FROM programa_permitido WHERE (fk_empresa = $fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $fk_empresa AND fk_equipe IS NULL)))
        AND data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial' AND descricao not like 'CAcDynInputWndControl'"
        . " GROUP BY descricao ORDER BY descricao ASC";

        return ($sql);
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getProgramasProdutivosAlmoco($colaborador, $data, $almocoInicio, $almocoFim, $fk_empresa)
    {
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT at.programa , at.descricao, SUM(TIME_TO_SEC (at.duracao)) as duracao FROM log_atividade as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        INNER JOIN contrato obra ON at.descricao LIKE CONCAT('%', obra.codigo,  '%' )
        WHERE obra.fk_empresa = $fk_empresa AND (TRIM(at.`programa`) IN
        (SELECT (nome) FROM programa_permitido WHERE (fk_empresa = $fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $fk_empresa AND fk_equipe IS NULL)))
        AND data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial'
        and data_hora_servidor BETWEEN '$data $almocoInicio'  AND '$data $almocoFim'"
        . " GROUP BY descricao ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getProgramasNaoProdutivosDia($colaborador, $data, $fk_empresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $sql = "SELECT at.programa , at.descricao, TIME_TO_SEC (at.duracao) as duracao FROM log_atividade_consolidado as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        WHERE (TRIM(at.`programa`) NOT IN
        (SELECT TRIM(nome) FROM programa_permitido WHERE (fk_empresa = $fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $fk_empresa AND fk_equipe IS NULL)))
        AND data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial' AND descricao not like '' AND descricao not like 'Ocioso' "
        . "AND programa NOT LIKE '%Google Chrome%' AND programa NOT LIKE '%Internet Explorer%' AND programa NOT LIKE '%Mozilla%' "
        . "ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getHorarioEntrada($colaborador, $data, $fk_empresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $sql = "SELECT data_hora_servidor FROM log_atividade as lc INNER JOIN colaborador as pe ON pe.ad = lc.usuario"
        . " WHERE pe.id = $colaborador AND lc.serial_empresa like '$serial'
        AND data like '$data' ORDER BY lc.id ASC";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTempoAlmoco($usuario, $data, $horario, $fkEmpresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $hora_servidor = "$data $horario";
        $usuario = Colaborador::model()->findByPk($usuario)->ad;
        $criteria = new CDbCriteria();
        $criteria->addCondition("fk_empresa = $fkEmpresa");
        $criteria->addCondition("usuario = '$usuario'");
        $criteria->addCondition("data_hora_servidor > '$hora_servidor'");
        return LogAtividade::model()->findAll($criteria);
    }

    public static function calcularOcioAlmoco($ociosoAlmoco, $duracaoAlmoco)
    {
        if (!empty($ociosoAlmoco)) {
            if ($ociosoAlmoco[0]->descricao == 'Ausente do computador') {
                return true;
            } else
                return 0;
        } else
            return 0;
    }

    public static function getSitesProdutivos($colaborador, $data, $site, $fk_empresa)
    {
        $data = MetodosGerais::dataAmericana($data);
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $sql = "SELECT lc.programa , lc.descricao, TIME_TO_SEC (lc.duracao) as duracao
        FROM log_atividade_consolidado as lc
        INNER JOIN colaborador as pe ON pe.ad = lc.usuario
        WHERE data like '$data' AND pe.id = $colaborador
        AND lc.serial_empresa like '$serial'
        AND lc.descricao  like '%$site%'
        AND (programa LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' )
        ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getOciosoDia($colaborador, $data, $fk_empresa)
    {
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
        $hora_alomoco_inicio = MetodosGerais::setHoraServidor($parametros->almoco_inicio);
        $hora_alomoco_fim = MetodosGerais::setHoraServidor($parametros->almoco_fim);
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT at.programa , at.descricao, SUM(TIME_TO_SEC (at.duracao)) as duracao FROM log_atividade as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        WHERE at.duracao <= '04:00:00' AND at.data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial' AND at.descricao like 'Ocioso'  "
            . "AND at.data_hora_servidor NOT  BETWEEN '$data $hora_alomoco_inicio' AND '$data $hora_alomoco_fim' AND at.serial_empresa like '$serial'"
        . " ORDER BY duracao DESC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getSitesImprodutivos($colaborador, $data, $sites, $fk_empresa)
    {
        $serial = Empresa::model()->findByPk($fk_empresa)->serial;
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT programa , descricao, TIME_TO_SEC (duracao) as duracao FROM log_atividade_consolidado as lc
        INNER JOIN colaborador as pe ON pe.ad = lc.usuario
        WHERE data like '$data' AND pe.id = $colaborador AND lc.serial_empresa like '$serial'"
        . " AND descricao not like '' AND descricao not like 'Ocioso' "
        . "AND (programa LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' ) "
        ;
        if ($sites != "")
        $sql .= "AND descricao not in ($sites) ";
        $sql .= "ORDER BY descricao ASC";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $total_parcial_improdutivo, $colecaoSitesImprodutivos, $diff) {
        $tempoOcioso = (!empty($ocioso)) ? $ocioso[0]['duracao'] : 0;
        if ($ociosoAlmoco)
            $tempoOcioso -= $duracaoAlmoco;
        $total_parcial_site = !empty($sitesColecao) ? $sitesColecao['0'] : '00:00:00';
        $total_parcial = $colecaoProgramas['0'] + $total_parcial_site + $total_parcial_improdutivo + $colecaoSitesImprodutivos['0'] + $tempoOcioso + $duracaoAlmoco;
        if ($diff > $total_parcial) {
            $restante = $diff - $total_parcial;
            $tempoOcioso += $restante;
        }
        return $tempoOcioso;
    }

    public static function getLogsMetricas($programa, $criterio, $sufixo, $data_inicio, $data_fim, $colaborador, $site, $serial)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "usuario, data,descricao, SEC_TO_TIME(SUM(TIME_TO_SEC(duracao))) as duracao";
        $criteria->addCondition("serial_empresa like '$serial'");
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");
        ($sufixo == 1) ? $criteria->compare("descricao", $criterio, true) : $criteria->compare("descricao", $criterio, false);
        $criteria->addBetweenCondition("data", $data_inicio, $data_fim);
        if ($colaborador != 'todos_colaboradores')
            $criteria->addCondition("usuario like '$colaborador'");
        $criteria->group = "descricao";
        $criteria->order = 'data DESC, usuario ASC, descricao ASC, duracao DESC';
        return LogAtividadeConsolidado::model()->findAll($criteria);
    }

    public static function getLogsMetricasEquipe($programa, $criterio, $sufixo, $data_inicio, $data_fim, $equipe, $site, $serial)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "usuario, data,descricao , SEC_TO_TIME(SUM(TIME_TO_SEC(duracao))) as duracao";
        $criteria->join = " INNER JOIN colaborador as p ON p.ad = usuario ";
        $criteria->addCondition("t.serial_empresa like '$serial'");
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");
        ($sufixo == 1) ? $criteria->compare("descricao", $criterio, true) : $criteria->compare("descricao", $criterio, false);
        $criteria->addBetweenCondition("data", $data_inicio, $data_fim);
        if ($equipe != 'todas_equipes')
            $criteria->addCondition("p.fk_equipe = $equipe");
        $criteria->group = "descricao";
        $criteria->order = 'data DESC, usuario ASC, descricao ASC, duracao DESC';
        return LogAtividadeConsolidado::model()->findAll($criteria);
    }

    public static function getProdutividadeContratoPorColaborador($finalizado, $obra, $dataInicial, $dataFinal, $empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(p.nome,' ',p.sobrenome) as colaborador, t.documento,sum(t.duracao) as duracao,t.fk_colaborador,p.fk_equipe as equipe, (p.salario)/((time_to_sec(p.horas_semana)/3600)*4) as salario";
        $criteria->join = 'INNER JOIN colaborador as p ON p.id = t.fk_colaborador';
        $criteria->addBetweenCondition('data', $dataInicial, $dataFinal);
        $criteria->addCondition("t.fk_empresa = $empresa");
        $criteria->addCondition("t.fk_obra = $obra->id");
        $criteria->addCondition("p.ativo = 1");
        $criteria->addCondition("p.status = 1");
        if ($obra->finalizada && $obra->data_finalizacao != NULL && !$finalizado)
            $criteria->addCondition('data < "' . $obra->data_finalizacao . '"');
        $criteria->group = "fk_colaborador";
        $criteria->order = "duracao DESC";
        return GrfProjetoConsolidado::model()->findAll($criteria);
    }

    public static function getTempoPorContratoByAtt($contrato, $date_from, $date_to, $programa = "", $colaborador = "", $fk_empresa)
    {
        $sql = "SELECT pe.nome, pe.sobrenome, at.descricao, pe.fk_equipe as equipe,
        SUM(TIME_TO_SEC(at.`duracao`)) as duracao, (pe.salario/220) as valor_hora
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        WHERE pe.fk_empresa = '{$fk_empresa}'
        AND at.descricao LIKE '%{$contrato}%'
        AND pe.ativo = 1
        AND pe.status = 1 ";

        if ($date_from != '' && $date_to != '')
        $sql .= " AND at.data BETWEEN '{$date_from}' AND '{$date_to}'";
        if ($colaborador != '')
        $sql .= " AND pe.ad = '{$colaborador}' ";
        if ($programa != '')
        $sql .= " AND at.programa LIKE '{$programa}' ";
        $sql .= " GROUP BY pe.nome"
        . " ORDER BY duracao DESC";

        $command = Yii::app()->getDb()->createCommand($sql);

        return $command->queryAll();
    }

    public static function getDataInicio($projeto, $fk_empresa)
    {
        if ($fk_empresa == 1)
            $sql = "SELECT distinct (data) FROM log_atividade_consolidado "
                    . "WHERE descricao like '%SArq.$projeto%'
                   ORDER BY id ASC LIMIT 1";
        else
            $sql = "SELECT distinct (data) FROM log_atividade_consolidado "
                    . "WHERE descricao like '%$projeto%'
                   ORDER BY id ASC LIMIT 1";


        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public static function getProdutividadeColaboradorPorContrato($idColaborador, $dataInicial, $dataFinal, $empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "sum(duracao) as duracao,fk_colaborador,fk_obra";
        $criteria->addCondition("fk_obra in (select c.id from contrato as c where c.fk_empresa = $empresa)");
        $criteria->addBetweenCondition('data', $dataInicial, $dataFinal);
        $criteria->addCondition("fk_empresa = $empresa");
        $criteria->addCondition("fk_colaborador = $idColaborador");
        $criteria->group = "fk_obra";
        $criteria->order = "duracao DESC";
        return GrfProjetoConsolidado::model()->findAll($criteria);
    }

    public static function getProdutividadeProjetosByAtt($opcao, $selecionado, $dataInicio, $dataFim, $empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "sum(duracao) as duracao, CONCAT(p.nome,' ',p.sobrenome) as colaborador,"
            . " e.nome as equipe, p.salario/((TIME_TO_SEC(p.horas_semana)/3600)*4) as salario,"
            . " o.nome as obra, o.codigo as codigo, o.tempo_previsto as tempo_previsto,"
            . " o.valor as valor_previsto, fk_obra";
        $criteria->join = "INNER JOIN colaborador as p ON p.id = fk_colaborador ";
        $criteria->join .= "INNER JOIN equipe as e ON e.id = p.fk_equipe ";
        $criteria->join .= "INNER JOIN contrato as o ON o.id = fk_obra";
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->addCondition("t.fk_empresa = $empresa");

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes'
            && $opcao != 'todos_contratos' && $selecionado == 'equipe'
        )
            $criteria->addCondition("e.id = $opcao");

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes'
            && $opcao != 'todos_contratos' && $selecionado == 'colaborador'
        )
            $criteria->addCondition("fk_colaborador = $opcao");

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes'
            && $opcao != 'todos_contratos' && $selecionado == 'contrato'
        )
            $criteria->addCondition("fk_obra = $opcao");

        if ($opcao == "contrato") {
            $criteria->group = "fk_colaborador";
        }else{
            $criteria->group = "fk_obra,fk_colaborador";
        }

        $criteria->order = "e.nome, p.nome, o.nome ASC";
        return GrfProjetoConsolidado::model()->findAll($criteria);
    }

    public static function getTempoTotalContrato($idContrato, $dataInicio, $dataFim, $empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "fk_obra, MONTH(data) as mes , year(data) as ano , SUM(time_to_sec(duracao)) as duracao";
        $criteria->addCondition("fk_empresa = $empresa");
        $criteria->addBetweenCondition("data", $dataInicio, $dataFim);
        if ($idContrato != "")
            $criteria->addCondition("fk_obra = $idContrato");
        $criteria->group = 'mes , fk_obra';
        $criteria->order = "ano";

        return GrfProjetoConsolidado::model()->findAll($criteria);
    }

    public static function ExportCSVRelEquipe($registros, $equipe, $dataInicio, $dataFim, $empresa, $tipo, $src)
    {
        if (!empty($registros)) {
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';
            $equipe = ($equipe == 'Todas') ? 'todas equipes' : Equipe::model()->findByPk($equipe)->nome;
            $titulo = 'Relatório de produtividade de ' . $equipe . ' entre ' . MetodosGerais::dataBrasileira($dataInicio) . ' e ' . MetodosGerais::dataBrasileira($dataFim);
            $phpExcel = new PHPExcel();
            $phpExcel->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
            $phpExcel->setActiveSheetIndex()->mergeCells('A1:C1');
            $phpExcel->getActiveSheet()->setCellValue('A1', $titulo);

            $phpExcel->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Equipe'));
            $phpExcel->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Produtividade'));
            $phpExcel->getActiveSheet()->setCellValue('C2', Yii::t('smith', 'Meta'));
            $index = 3;
            foreach ($registros[2] as $key => $value) {
                $phpExcel->getActiveSheet()
                    ->setCellValue('A' . $index, $value)
                    ->setCellValue('B' . $index, str_replace('.', ',', $registros[0]['data'][$key]) . '%')
                    ->setCellValue('C' . $index, str_replace('.', ',', $registros[1]['data'][$key]) . '%');
                $index++;
            }
            $arrayCsvFiles = array();

            $source = $src . 'Geral/';
            BackupRelatorios::checkDir($source);
            if ($tipo == 'mes')
                $filename = 'RelatorioGeralMensal.csv';
            else {
                $source .= date('d', strtotime($dataFim)) .'/';
                BackupRelatorios::checkDir($source);
                $filename = 'RelatorioGeral_'. date('dmY', strtotime($dataFim)) .'.csv';
            }
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'CSV');
            $objWriter->save($source . $filename);
            array_push($arrayCsvFiles, $filename);

            foreach ($registros[4] as $key => $value) {
                $titulo2 = 'Relatório de produtividade de ' . $registros[3][$key]['name'] . ' entre ' . MetodosGerais::dataBrasileira($dataInicio) . ' e ' . MetodosGerais::dataBrasileira($dataFim);
                $phpExcel2 = new PHPExcel();
                $phpExcel2->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo2);
                $phpExcel2->setActiveSheetIndex()->mergeCells('A1:B1');
                $phpExcel2->getActiveSheet()->setCellValue('A1', $titulo2);

                $phpExcel2->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Colaborador'));
                $phpExcel2->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Produtividade'));

                $index2 = 3;
                foreach ($value as $chave => $valor) {
                    $phpExcel2->getActiveSheet()
                        ->setCellValue('A' . $index2, $valor)
                        ->setCellValue('B' . $index2, str_replace('.', ',', $registros[3][$key]['data'][$chave]) . '%');
                    $index2++;
                }

                $source = $src . 'Equipes/';
                BackupRelatorios::checkDir($source);
                if ($tipo == 'mes')
                    $filename = 'RelatorioEquipeMensal_' . utf8_encode($registros[3][$key]['name']) . '.csv';
                else {
                    $source .= date('d', strtotime($dataFim)) .'/';
                    BackupRelatorios::checkDir($source);
                    $filename = 'RelatorioEquipe_' . utf8_encode($registros[3][$key]['name']) . '_' . date('dmY', strtotime($dataFim)) . '.csv';
                }
                $objWriter = PHPExcel_IOFactory::createWriter($phpExcel2, 'CSV');
                $objWriter->save($source . $filename);
                array_push($arrayCsvFiles, $filename);
            }
        }
    }

    public static function ExportToCsv($registros, $colunas, $titulo, $filename, $tipo, $fk_empresa, $src, $ontem) {
        $flag = false;
        if (!empty($registros)) {
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

            $phpExcel = new PHPExcel();
            $phpExcel->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
            $phpExcel->setActiveSheetIndex()->mergeCells('A1:C1');
            $phpExcel->getActiveSheet()->setCellValue('A1', $titulo);
            foreach ($colunas as $key => $value) {
                $phpExcel->getActiveSheet()->setCellValue(chr($key) . '2', Yii::t('smith', $value));
            }
            switch ($tipo) {
                case 'relInDias':
                    $phpExcel = BackupRelatorios::formataDataRelIndDiasCSV($registros, $phpExcel);
                    break;
                case 'relCusto':
                    $phpExcel = BackupRelatorios::formataDataRelCustoCSV($registros, $phpExcel);
                    break;
                case 'relInd':
                    $phpExcel = BackupRelatorios::formataDataRelIndCSV($registros, $phpExcel, $filename);
                    break;
                case 'relRanking':
                    $phpExcel = BackupRelatorios::formatDataRelRanking($registros, $phpExcel);
                    break;
                case 'relHoraExtra':
                    $phpExcel = BackupRelatorios::formataDataRelHoraExtra($registros, $phpExcel);
                    break;
                case 'relGeral':
                    $phpExcel = BackupRelatorios::formataDataRelPrdGeral($registros, $phpExcel);
                    break;
                case 'relPrdColContrato':
                    $phpExcel = BackupRelatorios::formataDataRelPrdColContrato($registros, $phpExcel);
                    break;
                case 'relCustoEnergia':
                    $phpExcel = BackupRelatorios::formataDataRelCustoEnergia($registros, $phpExcel);
                    break;
                case 'relPonto':
                    $phpExcel = BackupRelatorios::formataDataRelPonto($registros, $phpExcel);
                    break;
                case 'relPontoAtual':
                    $phpExcel = BackupRelatorios::formataDataRelPontoAtual($registros, $phpExcel);
                    break;
            }
            $flag = true;

            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'CSV');
            $objWriter->save($src . $filename);
        }
        return $flag;
    }

    public static function ExportCSVRelIndividualCol($registros, $colaborador, $data, $horarioEntrada, $horarioSaida, $src) {
        if (!empty($registros)) {
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

            $titulo = 'Acompanhamento de produtividade de ' . $colaborador . ' em ' . $data . ' - Horário início: ' . $horarioEntrada . ' - Horário fim: ' . $horarioSaida;
            $phpExcel = new PHPExcel();
            $phpExcel->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
            $phpExcel->setActiveSheetIndex()->mergeCells('A1:C1');
            $phpExcel->getActiveSheet()->setCellValue('A1', $titulo);

            $phpExcel->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Tipo'));
            $phpExcel->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Tempo Gasto'));

            $total_consolidado = $registros[2][0] + $registros[0][0] + $registros[1][0] + $registros[3][0] + $registros[4][0] + $registros[6] + $registros[7];
            $phpExcel->getActiveSheet()->setCellValue('A3', Yii::t("smith", 'Programas permitidos'))->setCellValue('B3', MetodosGerais::formataTempo($registros[0][0]));
            $phpExcel->getActiveSheet()->setCellValue('A4', Yii::t("smith", 'Sites permitidos'))->setCellValue('B4', MetodosGerais::formataTempo($registros[1][0]));
            $phpExcel->getActiveSheet()->setCellValue('A5', Yii::t("smith", 'Atividades Externas'))->setCellValue('B5', MetodosGerais::formataTempo($registros[2][0]));
            $phpExcel->getActiveSheet()->setCellValue('A6', Yii::t("smith", 'Programas não permitidos'))->setCellValue('B6', MetodosGerais::formataTempo($registros[3][0]));
            $phpExcel->getActiveSheet()->setCellValue('A7', Yii::t("smith", 'Sites não permitidos'))->setCellValue('B7', MetodosGerais::formataTempo($registros[4][0]));
            $phpExcel->getActiveSheet()->setCellValue('A8', Yii::t("smith", 'Ausente do computador'))->setCellValue('B8', MetodosGerais::formataTempo($registros[6]));
            $phpExcel->getActiveSheet()->setCellValue('A9', Yii::t("smith", 'Subtotal'))->setCellValue('B9', MetodosGerais::formataTempo($total_consolidado - $registros[7]));
            $phpExcel->getActiveSheet()->setCellValue('A10', Yii::t("smith", 'Horário de almoço'))->setCellValue('B10', MetodosGerais::formataTempo($registros[7]));
            $phpExcel->getActiveSheet()->setCellValue('A11', Yii::t("smith", 'Total'))->setCellValue('B11', MetodosGerais::formataTempo($total_consolidado));
            $arrayCsvFiles = array();

            $data = MetodosGerais::dataAmericana($data);
            $src = $src .'/'. date('Y', strtotime($data)) .'/';
            BackupRelatorios::checkDir($src);
            $src .= MetodosGerais::mesString(date('m', strtotime($data))) . '/';
            BackupRelatorios::checkDir($src);
            $src .= date('d', strtotime($data)) . '/';
            BackupRelatorios::checkDir($src);
            $filename = 'RelatorioIndividual_' . utf8_encode($colaborador) . '_' . date('dmY', strtotime($data)) . '.csv';
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'CSV');
            $objWriter->save($src . $filename);
        }
    }

    public static function getRelatorioIndividual($colecaoAtivExterna, $horario_entrada, $horario_saida, $colecaoProgramas, $colecaoImprodutivo, $ocioso, $sitesColecao, $colecaoSitesImprodutivos, $colaborador, $data, $colecaoAlmoco, $duracaoAlmoco, $ociosoAlmoco, $fk_empresa, $src)
    {
        // $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
        $diff = (MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($horario_entrada));
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
            <page_header>
            <div class="header_page">
            <div class="header_title">
                <p>' . Yii::t("smith", 'ACOMPANHAMENTO DE PRODUTIVIDADE') . '</p>
            </div>

            <span><b>' . Yii::t("smith", 'Colaborador') . ': ' . $colaborador . ' - ' . Yii::t("smith", 'Data') . ': ' . $data . '</b></span><br>
            <span><b>' . Yii::t("smith", 'Horário início') . ': ' . $horario_entrada . ' - ' . Yii::t("smith", 'Horário fim') . ': ' . $horario_saida . '</b></span><br>
            <div class="header_date">
            <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
                <br>' . Yii::t('smith', 'Pág.') . ' ([[page_cu]]/[[page_nb]]) </p>
            </div>
            </div>

            </page_header>
            </page>';

        $rodape = MetodosGerais::getRodapeTable();
        $html = $header;
        $html .= $rodape;
        $total_parcial_improdutivo = array_pop($colecaoImprodutivo);
        $total_parcial_site = !empty($sitesColecao) ? $sitesColecao['0'] : '00:00:00';
        $tempoOcioso = BackupRelatorios::calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $total_parcial_improdutivo, $colecaoSitesImprodutivos, $diff);

        $total_consolidado = $colecaoAtivExterna['0'] + $colecaoProgramas['0'] + $total_parcial_site + $total_parcial_improdutivo + $colecaoSitesImprodutivos['0'] + $tempoOcioso + $duracaoAlmoco;

        /*
         * Tabela de resumo
         */
        $html .= '<table class="table_custom" border="1px"><tr><th colspan="2">' . Yii::t("smith", 'Resumo') . '</th></tr>
            <tr style="background-color: #CCC;text-decoration: bold;">
                <th>' . Yii::t("smith", 'Tipo') . '</th>
                <th>' . Yii::t("smith", 'Tempo Gasto') . '</th>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Programas permitidos') . '</td>
                <td>' . gmdate("H:i:s", $colecaoProgramas['0']) . '</td>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Sites permitidos') . '</td>
                <td>' . gmdate("H:i:s", $total_parcial_site) . '</td>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Atividades Externas') . '</td>
                <td>' . gmdate("H:i:s", $colecaoAtivExterna['0']) . '</td>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Programas não permitidos') . '</td>
                <td>' . gmdate("H:i:s", $total_parcial_improdutivo) . '</td>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Sites não permitidos') . '</td>
                <td>' . gmdate("H:i:s", $colecaoSitesImprodutivos['0']) . '</td>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Ausente do computador') . '</td>
                <td>' . gmdate("H:i:s", $tempoOcioso) . '</td>
            </tr>
            <tr style="background-color: #CCC;text-decoration: bold;">
                <td>' . Yii::t("smith", 'Subtotal') . '</td>
                <td>' . gmdate("H:i:s", $total_consolidado - $duracaoAlmoco) . '</td>
            </tr>
            <tr>
                <td style="text-align: left">' . Yii::t("smith", 'Horário de almoço') . '</td>
                <td>' . gmdate("H:i:s", $duracaoAlmoco) . '</td>
            </tr>
            <tr style="background-color: #CCC;text-decoration: bold;">
                <td style="text-align: left">' . Yii::t("smith", 'Total') . '</td>
                <td>' . gmdate("H:i:s", $total_consolidado) . '</td>
            </tr>
        </table>';

        /*
         * Programas permitidos
         */
        $html .= '<div style="width:600px"><h4>' . Yii::t("smith", 'Programas permitidos - duração') . ': ' . gmdate("H:i:s", $colecaoProgramas['0']) . '</h4></div>';
        foreach ($colecaoProgramas as $key => $value) {
            if ($key != '0') {
                $html .= '<table  class="table_custom" border="1px">
                    <tr>
                        <th colspan="2" style="text-align: left;">' . Yii::t("smith", 'Programa') . ': ' . strip_tags($key) . ' -  ' . Yii::t("smith", 'Tempo total') . ': ' . gmdate("H:i:s", $value['duracao_total']) . '</th>
                    </tr>
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>' . Yii::t("smith", 'Descrição') . '</th>
                        <th>' . Yii::t("smith", 'Tempo Gasto') . '</th>
                    </tr>';

                for ($i = 0; $i < count($value) - 1; $i++) {
                    $html .= '<tr>'
                        . '<td style="text-align: left; width: 600px">' . strip_tags($value[$i][0]) . '</td>'
                        . '<td style="text-align: center; width: 110px">' . gmdate("H:i:s", $value[$i][1]) . '</td>'
                        . '</tr>';
                }
                $html .= "</table>";
                $html .= "<p style='margin-bottom: 5px'></p>";
            }
        }
        /*
         * Sites permitidos
         */

        if (!empty($sitesColecao)) {
            $html .= '<div style="width:600px"><h4>' . Yii::t("smith", 'Sites permitidos - duração') . ': ' . gmdate("H:i:s", $sitesColecao['0']) . '</h4></div>';
            foreach ($sitesColecao as $key => $value) {
                if ($key != '0') {
                    $html .= '<table  class="table_custom" border="1px">
                        <tr>
                        <th colspan="2" style="text-align: left;">' . Yii::t("smith", 'Programa') . ': ' . strip_tags($key) . ' -  ' . Yii::t("smith", 'Tempo total') . ': ' . gmdate("H:i:s", $value['duracao_total']) . '</th>
                    </tr>
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>' . Yii::t("smith", 'Descrição') . '</th>
                        <th>' . Yii::t("smith", 'Tempo Gasto') . '</th>
                    </tr>';

                    for ($i = 0; $i < count($value) - 1; $i++) {
                        $html .= '<tr>'
                            . '<td style="text-align: left; width: 600px">' . strip_tags(substr($value[$i][0], 0, 80)) . '</td>'
                            . '<td style="text-align: center; width: 110px">' . gmdate("H:i:s", $value[$i][1]) . '</td>'
                            . '</tr>';
                    }
                    $html .= "</table>";
                    $html .= "<p style='margin-bottom: 5px'></p>";
                }
            }
        }

        /*
         * Atividades Externas
         */

        $html .= '<div style="width:600px"><h4>' . Yii::t("smith", 'Atividades externas - duração') . ': ' . gmdate("H:i:s", $colecaoAtivExterna['0']) . '</h4></div>';
        foreach ($colecaoAtivExterna as $key => $value) {
            if ($key != '0') {
                $html .= '<table  class="table_custom" border="1px">
                    <tr>
                        <th colspan="2" style="text-align: left;">' . Yii::t("smith", 'Programa') . ': ' . strip_tags($key) . ' -  ' . Yii::t("smith", 'Tempo total') . ': ' . gmdate("H:i:s", $value['duracao_total']) . '</th>
                    </tr>
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>' . Yii::t("smith", 'Descrição') . '</th>
                        <th>' . Yii::t("smith", 'Tempo Gasto') . '</th>
                    </tr>';

                for ($i = 0; $i < count($value) - 1; $i++) {
                    $html .= '<tr>'
                        . '<td style="text-align: left; width: 600px">' . strip_tags($value[$i][0]) . '</td>'
                        . '<td style="text-align: center; width: 110px">' . gmdate("H:i:s", $value[$i][1]) . '</td>'
                        . '</tr>';
                }
                $html .= "</table>";
                $html .= "<p style='margin-bottom: 5px'></p>";
            }
        }

        /*
         * Programas não permitidos
         */

        $html .= '<div style="width:600px"><h4>' . Yii::t("smith", 'Programas não permitidos - duração') . ': ' . gmdate("H:i:s", $total_parcial_improdutivo) . '</h4></div>';
        foreach ($colecaoImprodutivo as $key => $value) {
            if ($key != '0') {
                $html .= '<table  class="table_custom" border="1px">
                    <tr>
                        <th colspan="2" style="text-align: left;">' . Yii::t("smith", 'Programa') . ': ' . strip_tags($key) . ' -  ' . Yii::t("smith", 'Tempo total') . ': ' . gmdate("H:i:s", $value['duracao_total']) . '</th>
                    </tr>
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>' . Yii::t("smith", 'Descrição') . '</th>
                        <th>' . Yii::t("smith", 'Tempo Gasto') . '</th>
                    </tr>';


                for ($i = 0; $i < count($value) - 1; $i++) {
                    $html .= '<tr>'
                        . '<td style="text-align: left; width: 600px">' . strip_tags(substr($value[$i][0], 0, 80)) . '</td>'
                        . '<td style="text-align: center; width: 110px">' . gmdate("H:i:s", $value[$i][1]) . '</td>'
                        . '</tr>';
                }
                $html .= "</table>";
                $html .= "<p style='margin-bottom: 5px'></p>";
            }
        }

        /*
         * Sites não permitidos
         */

        $html .= '<div style="width:600px"><h4>' . Yii::t("smith", 'Sites não permitidos - duração') . ': ' . gmdate("H:i:s", $colecaoSitesImprodutivos['0']) . '</h4></div>';
        foreach ($colecaoSitesImprodutivos as $key => $value) {
            if ($key != '0') {
                $html .= '<table  class="table_custom" border="1px">
                    <tr>
                        <th colspan="2" style="text-align: left;">' . Yii::t("smith", 'Programa') . ': ' . strip_tags($key) . ' -  ' . Yii::t("smith", 'Tempo total') . ': ' . gmdate("H:i:s", $value['duracao_total']) . '</th>
                    </tr>
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>' . Yii::t("smith", 'Descrição') . '</th>
                        <th>' . Yii::t("smith", 'Tempo Gasto') . '</th>
                    </tr>';

                for ($i = 0; $i < count($value) - 1; $i++) {
                    $html .= '<tr>'
                        . '<td style="text-align: left; width: 600px">' . strip_tags(substr($value[$i][0], 0, 80)) . '</td>'
                        . '<td style="text-align: center; width: 110px">' . gmdate("H:i:s", $value[$i][1]) . '</td>'
                        . '</tr>';
                }
                $html .= "</table>";
                $html .= "<p style='margin-bottom: 5px'></p>";
            }
        }

        /*
         * Ocioso
         */

        $html .= '<div style="width:600px"><h4>' . Yii::t("smith", 'Ausente do computador') . '</h4></div>';
        $html .= '<table  class="table_custom" border="1px">
            <tr>
                <th colspan="2" style="text-align: left;">' . Yii::t("smith", 'Tempo total') . ': ' . gmdate("H:i:s", $tempoOcioso) . '</th>
            </tr>
            <tr style="background-color: #CCC; text-decoration: bold;">
                <th>' . Yii::t('smith', 'Descrição') . '</th>
                <th>' . Yii::t('smith', 'Tempo Gasto') . '</th>
            </tr>';

        $html .= '<tr>'
            . '<td style="text-align: left; width: 600px">' . Yii::t("smith", 'Ausente do computador') . '</td>'
            . '<td>' . gmdate("H:i:s", $tempoOcioso) . '</td>'
            . '</tr>';

        $html .= "</table>";
        $html .= "<p style='margin-bottom: 5px'></p>";

        $html .= '<p><b>' . Yii::t("smith", 'Total consolidado') . ' </b>: ' . gmdate("H:i:s", $total_consolidado) . '</p>';
        $style = MetodosGerais::getStyleTable();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $colaborador = MetodosGerais::reduzirNome($colaborador);
        $colaborador = explode(' ', $colaborador);
        $colaborador = $colaborador[0] . $colaborador[1];
        $nomeRelatorio = 'relatorioIndividualColaborador_' . utf8_encode($colaborador) . '_' . date('dmY') . '.pdf';
        $src = $src .'/'. date('Y') .'/';
        BackupRelatorios::checkDir($src);
        $src .= MetodosGerais::mesString(date('m')) . '/';
        BackupRelatorios::checkDir($src);
        $html2pdf->Output($src . $nomeRelatorio, 'F');
        return $nomeRelatorio;
    }

    public static function ExportCSVRelGeralContrato($registros, $dataInicio, $tipo, $nome, $src) {
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

        foreach ($registros as $key => $value) {
            $titulo = 'Relatório geral de acompanhamento de contratos - ' . ucfirst($tipo) . ': ' . $key . ' - no dia ' . MetodosGerais::dataBrasileira($dataInicio);
            $phpExcel = new PHPExcel();
            $phpExcel->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
            $phpExcel->setActiveSheetIndex()->mergeCells('A1:C1');
            $phpExcel->getActiveSheet()->setCellValue('A1', $titulo);
            if ($tipo != 'contrato') {
                $phpExcel->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Contrato'));
                $phpExcel->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Código'));
                $phpExcel->getActiveSheet()->setCellValue('C2', Yii::t('smith', 'Tempo realizado'));
                $phpExcel->getActiveSheet()->setCellValue('D2', Yii::t('smith', 'Tempo previsto'));
                $phpExcel->getActiveSheet()->setCellValue('E2', Yii::t('smith', 'Orçamento realizado'));
                $phpExcel->getActiveSheet()->setCellValue('F2', Yii::t('smith', 'Orçamento previsto'));
                $index = 3;
                foreach ($value as $chave => $valor) {
                    $phpExcel->getActiveSheet()
                        ->setCellValue('A' . $index, $chave)
                        ->setCellValue('B' . $index, $valor['codigo_obra'])
                        ->setCellValue('C' . $index, MetodosGerais::formataTempo($valor['horas'] * 3600))
                        ->setCellValue('D' . $index, (isset($valor['tempoPrevisto'])) ? MetodosGerais::formataTempo($valor['tempoPrevisto'] * 3600) : '-')
                        ->setCellValue('E' . $index, 'R$' . MetodosGerais::float2real($valor['valor_horas']))
                        ->setCellValue('F' . $index, (isset($valor['valorPrevisto'])) ? 'R$' . MetodosGerais::float2real($valor['valorPrevisto']) : '-');
                    $index++;
                }
            } else {
                $phpExcel->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Equipe'));
                $phpExcel->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Colaborador'));
                $phpExcel->getActiveSheet()->setCellValue('C2', Yii::t('smith', 'Tempo realizado'));
                $phpExcel->getActiveSheet()->setCellValue('D2', Yii::t('smith', 'Orçamento realizado'));
                $index = 3;
                foreach ($value as $chave => $valor) {
                    if ($chave != "data") {
                        $phpExcel->getActiveSheet()
                            ->setCellValue('A' . $index, $valor['equipe'])
                            ->setCellValue('B' . $index, $chave)
                            ->setCellValue('C' . $index, MetodosGerais::formataTempo($valor['tempo_trab'] * 3600))
                            ->setCellValue('D' . $index, 'R$' . MetodosGerais::float2real($valor['custo']));
                        $index++;
                    }
                }
            }

            $src = $src .'/'. date('Y', strtotime($dataInicio)) .'/';
            BackupRelatorios::checkDir($src);
            $src .= MetodosGerais::mesString(date('m', strtotime($dataInicio))) . '/';
            BackupRelatorios::checkDir($src);
            $src .= ucfirst($tipo) . '/';
            BackupRelatorios::checkDir($src);
            $src .= date('d', strtotime($dataInicio)) . '/';
            BackupRelatorios::checkDir($src);

            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'CSV');
            $objWriter->save($src . 'RelatorioGeral'. ucfirst($tipo) .'_'. $nome .'_'. date('dmY', strtotime($dataInicio)) .'.csv');
        }
    }

    public static function GerarRelatorioDados($documentos, $contrato, $coordenador, $disciplinas, $colaboradores, $prefixo, $data, $ontem, $fk_empresa, $src)
    {
        if (!empty($colaboradores)) {
            // $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
            $style = MetodosGerais::getStyleTable();
            $rodape = MetodosGerais::getRodapeTable();

            $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                <page_header>
                    <div class="header_page">
                        <div class="header_title">
                            <p>'.Yii::t('smith', 'RELATÓRIO DE ACOMPANHAMENTO').'</p>
                        </div>
                        <span><b>' . Yii::t("smith", 'Projeto') . ': ' . $contrato . ' - ' . Yii::t("smith", 'Prefixo') . ': ' . $prefixo . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Data de inicio') . ': ' . MetodosGerais::dataBrasileira($data) . '</b></span>
                        <br>
                        <span><b>'.Yii::t('smith', 'Coordenador').': ' . $coordenador . '</b></span>
                        <div class="header_date">
                            <p>'.Yii::t('smith', 'Data').':  ' . date('d/m/Y', strtotime($ontem)) . '
                            <br>'.Yii::t('smith', 'Pág.').' ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                    </div>
                </page_header>
            </page>';

            $html = $header;
            $html .= $rodape;
            $tempoTotal = $custoTotal = 0;
            $corpo = '';
            foreach ($colaboradores as $colaborador) {
                $tempoTotal += $colaborador['duracao'];
                $custoTotal += $colaborador['valor_hora'] * ($colaborador['duracao'] / 3600);
            }

            foreach ($colaboradores as $colaborador) {
                $equipe = Equipe::model()->findByPk($colaborador['equipe']);
                if (isset($equipe->nome)) {
                    $corpo .= '<tr >
                    <td style="width: 223px">'
                        . MetodosGerais::reduzirNome($colaborador['nome'] . ' ' . $colaborador['sobrenome'])
                        . '</td>
                    <td style="width: 163px">' . $equipe->nome . ' </td>
                    <td style="width: 50px">' . MetodosGerais::formataTempo($colaborador['duracao']) . ' </td>
                    <td style="width: 50px">' . round(($colaborador['duracao'] * 100) / $tempoTotal, 2) . '% </td>
                    <td style="width: 50px"> R$' . MetodosGerais::float2real(round($colaborador['valor_hora'] * ($colaborador['duracao'] / 3600), 2)) . '</td>
                </tr>';
                }

            }

            $html .= '<table  class="table_custom" border="1px">
                <tr>
                    <th colspan="5" style="text-align: left;">' . Yii::t('smith', 'Tempo Total Gasto') . ': ' . MetodosGerais::formataTempo($tempoTotal) . ' ' . Yii::t('smith', 'horas') . ' </th>
                </tr>
                <tr style="background-color: #CCC; text-decoration: bold;">
                    <th>'.Yii::t('smith', 'Colaborador').'</th>
                    <th>'.Yii::t('smith', 'Equipe').'</th>
                    <th>'.Yii::t('smith', 'Tempo Realizado').'</th>
                    <th>' . Yii::t('smith', 'Participação <br> no projeto') . '</th>
                    <th>'.Yii::t('smith', 'Orçamento').'</th>
                </tr>';
            $html .= $corpo;
            $html .= '</table> <p style="margin-top: 10px"></p>';
            $total_previsto = $total_documentos_realizados = $custo_total_documentos = 0;
            foreach ($disciplinas as $disciplina) {
                $html .= '<p style="margin-top: 5px"></p>
                <table  class="table_custom" border="1px">
                    <tr>
                        <th colspan="6" style="text-align: left;">' . Yii::t('smith', 'Disciplina') . ': ' . $disciplina->codigo . '</th>
                    </tr>
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>'.Yii::t('smith', 'Nome do documento').'</th>
                        <th>'.Yii::t('smith', 'Tempo previsto').'</th>
                        <th>'.Yii::t('smith', 'Tempo realizado').'</th>
                        <th>'.Yii::t('smith', 'Porcentagem').'</th>
                        <th>' . Yii::t('smith', 'Custo') . '</th>
                        <th>'.Yii::t('smith', 'Status').'</th>

                    </tr>';

                $totalPrevistoDisciplina = $totalRealizadosDisciplina = $custoTotalDisciplina = 0;
                foreach ($documentos as $documento) {
                    if ($disciplina->codigo == $documento['disciplina']) {
                        $horas_realizadas = $documento['logs'][0]['duracao'] / 3600;
                        $horas_total = $tempoTotal / 3600;

                        $total_previsto += MetodosGerais::time_to_seconds($documento['previsto']);
                        $totalPrevistoDisciplina += MetodosGerais::time_to_seconds($documento['previsto']);

                        $total_documentos_realizados += $documento['logs'][0]['duracao'];
                        $totalRealizadosDisciplina += $documento['logs'][0]['duracao'];

                        $custo_total_documentos += round(($horas_realizadas * $custoTotal) / $horas_total, 2);
                        $custoTotalDisciplina += round(($horas_realizadas * $custoTotal) / $horas_total, 2);

                        $status = $documento['finalizado'] == 0 ? Yii::t('smith', "Em andamento") : Yii::t('smith', "Finalizado");
                        if (($documento['logs'][0]['duracao'] > MetodosGerais::time_to_seconds($documento['previsto'])))
                            $status = Yii::t('smith', "Atrasado");
                        $html .= '<tr>
                            <td style="width: 260px ; text-align: center" >' . $documento['documento'] . '</td>
                            <td style="width: 20px ; text-align: center">' . $documento['previsto'] . '</td>
                            <td style="width: 40px ;text-align: center">' . MetodosGerais::formataTempo($documento['logs'][0]['duracao']) . ' </td>
                            <td style="width: 40px ;text-align: center">' . str_replace(".", ",", round(($documento['logs'][0]['duracao'] * 100) / MetodosGerais::time_to_seconds($documento['previsto']), 2)) . '%</td>
                            <td style="width: 40px ;text-align: center">R$ ' . MetodosGerais::float2real(round(($horas_realizadas * $custoTotal) / $horas_total, 2)) . '</td>
                            <td style="width: 60px ;text-align: center">' . $status . '</td>
                        </tr>';
                    }
                }

                $porcentagem = ($totalPrevistoDisciplina == 0) ? 0 : str_replace(".", ",", round(($totalRealizadosDisciplina * 100) / $totalPrevistoDisciplina, 2));
                $html .= '<tr style="background-color: #CCC; text-decoration: bold;">
                    <th>'.Yii::t('smith', 'Total').'</th>
                    <th>'.Yii::t('smith', 'Tempo previsto').'</th>
                    <th>'.Yii::t('smith', 'Tempo realizado').'</th>
                    <th>'.Yii::t('smith', 'Porcentagem').'</th>
                    <th>'.Yii::t('smith', 'Custo').'</th>
                </tr>
                <tr>
                    <td style="width: 200px" > </td>
                    <td>' . MetodosGerais::formataTempo($totalPrevistoDisciplina) . ' </td>
                    <td>' . MetodosGerais::formataTempo($totalRealizadosDisciplina) . ' </td>
                    <td>' . $porcentagem . '%</td>
                    <td>R$ ' . MetodosGerais::float2real($custoTotalDisciplina) . '</td>
                </tr>';

                $html .= '</table>';
            }

            $html .= "<p style='margin-top: 5px'></p>"
                    . "<table class='table_custom' border='1px'>"
                    . "<tr style='background-color: #CCC; text-decoration: bold;'>
                    <th></th>
                    <th>".Yii::t('smith', 'Tempo total realizado')."</th>
                    <th>".Yii::t('smith', 'Custo')."</th>
                </tr>
                <tr>"
                    . "<td><b>Não cadastrado na Lista de Documentos</b></td>"
                    . "<td>" . MetodosGerais::formataTempo($tempoTotal - $total_documentos_realizados) . "</td>"
                    . "<td>R$ " . MetodosGerais::float2real(round(((($tempoTotal - $total_documentos_realizados) / 3600) * $custoTotal) / $horas_total, 2)) . "</td>"
                    . "</tr></table>";

            $html .= "<p style='margin-top: 15px'></p>"
                    . "<table class='table_custom' border='1px'>"
                    . "<tr style='background-color: #CCC; text-decoration: bold;'>
                        <th></th>
                        <th>".Yii::t('smith', 'Tempo total previsto')."</th>
                        <th>".Yii::t('smith', 'Tempo total realizado')."</th>
                        <th>".Yii::t('smith', 'Custo total')."</th>
                    </tr>
                    <tr>"
                    . "<td><b>".Yii::t('smith', 'Total Consolidado')."</b></td>"
                    . "<td>" . MetodosGerais::formataTempo($total_previsto) . "</td>"
                    . "<td>" . MetodosGerais::formataTempo($total_documentos_realizados + ($tempoTotal - $total_documentos_realizados)) . "</td>"
                    . "<td>R$ " . MetodosGerais::float2real($custo_total_documentos + round(((($tempoTotal - $total_documentos_realizados) / 3600) * $custoTotal) / $horas_total, 2)) . "</td></tr></table>";
            $src = $src .'/'. date('Y', strtotime($ontem)) .'/';
            BackupRelatorios::checkDir($src);
            $src .= MetodosGerais::mesString(date('m', strtotime($ontem))) . '/';
            BackupRelatorios::checkDir($src);
            $src .= date('d', strtotime($ontem)) . '/';
            BackupRelatorios::checkDir($src);
            $filename = 'relatorioAcompanhamento_' . utf8_encode($contrato) . '_' . date('dmY', strtotime($ontem)) . '.pdf';
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);
            $html2pdf->Output($src . $filename, 'F');
        }
    }


    public static function geraRelGlobalPDF($arrayProdutividade, $arrayMetrica, $arrayProjeto, $arrayInformacoes, $empresa, 
        $dataInicio, $dataFim)
    {       
        $imagem = Yii::getPathOfAlias('application') . '/../' . $empresa->logo;
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                            <page_header>
                            <div class="header_page">
                            <img class="header_logo_page" src="' . $imagem . '">
                            <div class="header_title">
                                <span>' . Yii::t("smith", 'AVALIAÇÃO GLOBAL') . '</span><br>
                                <span style="font-size: 10px">' . Yii::t("smith", 'No período de') . ' ' . $dataInicio . ' ' . Yii::t("smith", 'até') . ' ' . $dataFim . ' </span>
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
        $hasMetrica = count(Metrica::model()->findAllByAttributes(array('fk_empresa' => $empresa->id)));
        if ($hasMetrica) {
            $html .= '<span>- ' . $arrayMetrica['metricaMetaAlcancada'] . '% das áreas de atuação atingiram as metas estabelecidas para as métricas configuradas.</span><br>';
            $html .= '<span>- Considerando a média do período, ' . $arrayMetrica['metricaMaximoLimite']['meta'] . ' métricas atenderam a meta estabelecida, sendo que ' . $arrayMetrica['metricaMaximoLimite']['maximo'] . ' dessas atingiram o limite máximo.</span><br>';
            $html .= '<span>- Considerando a média do período, ' . $arrayMetrica['metricaMinimoLimite']['meta'] . ' métricas não atenderam a meta estabelecida, sendo que ' . $arrayMetrica['metricaMinimoLimite']['maximo'] . ' dessas não atingiram o limite mínimo.</span><br><br>';
        } else
            $html .= '<span>Para efetuar este acompanhamento, é necessário que seja cadastrado pelo menos uma métrica.</span><br><br>';


        $html .= '<h5>PROJETOS</h5>';
        $hasProjeto = count(Contrato::model()->findAllByAttributes(array('fk_empresa' => $empresa->id)));
        if ($hasProjeto) {
            $html .= '<span>- ' . $arrayProjeto['projetoAdiantado'] . ' projetos estão adiantados em relação ao cronograma</span><br>';
            $html .= '<span>- ' . $arrayProjeto['projetoAtrasado'] . ' projetos possui um atraso superior 30%</span><br><br>';
        } else
            $html .= '<span>Para efetuar este acompanhamento, é necessário que seja cadastrado pelo menos um projeto.</span><br><br>';

        $html .= '<h5>INFORMAÇÕES COMPLEMENTARES</h5>';

        if(empty($arrayInformacoes['programasNaoPermitidos'])){
            $html .= '<span>Não há programas não permitidos cadastrados</span><br><br>';

            print_r($arrayInformacoes['programasNaoPermitidos']);
            echo "\n\n";

        }else{
            
            print_r($arrayInformacoes['programasNaoPermitidos']);
            echo "\n\n";
            echo $empresa->nome;
            echo "\n\n";
                $html .= '<span> -  Programas não permitidos com maior índice de acesso: ';
            foreach ($arrayInformacoes['programasNaoPermitidos'] as $programas) {
                $html .=  $programas['programa'] . ' (' . round($programas['porcentagem'], 0) . '%), ';
            }

            $html .= '</span><br>';
        }

        if(empty($arrayInformacoes['sitesNaoPermitidos'])){
            $html .= '<span>Não há sites não permitidos cadastrados</span><br><br>';
        }else{
            $html .= '<span> - Sites não permitidos com maior índice de acesso: ';
            foreach ($arrayInformacoes['sitesNaoPermitidos'] as $sites) {
                $html .=  $sites['site'] . ' (' . round($sites['porcentagem'], 0) . '%), ';
            }
            $html .= '.</span><br>';
        }


        $html .= '</div>';
        $html .= $rodape;
        $style = MetodosGerais::getStyleTable();
        $nome = Yii::app()->baseUrl . "/../public/".Yii::t('smith', 'Relatorio Avaliacao Global').'.pdf';
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output(utf8_decode($nome), 'F');

        return "Relatorio Avaliacao Global.pdf";

    }











    public static function GerarRelatorioDadosPrefixo($documentos, $contrato, $coordenador, $colaboradores, $prefixo, $data_inicio, $data_fim, $previsto, $tempo_previsto, $estimativaData, $data, $fk_empresa, $src)
    {
        if (!empty($colaboradores)) {
            $tempo_previsto = ($tempo_previsto) ? $tempo_previsto . ' ' . Yii::t('smith', 'horas') : Yii::t('smith', 'Não informado');
            // $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
            $style = MetodosGerais::getStyleTable();
            $rodape = MetodosGerais::getRodapeTable();

            $estimativaData = ($estimativaData == NULL) ? Yii::t('smith', 'Não informado') : MetodosGerais::dataBrasileira($estimativaData);
            $previsto = ($previsto == NULL) ? Yii::t('smith', 'Não informado') : 'R$' . MetodosGerais::float2real($previsto);

            $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                <page_header>
                    <div class="header_page">
                        <div class="header_title">
                            <span>'.Yii::t("smith", 'RELATÓRIO DE ACOMPANHAMENTO').'</span><br>
                            <span style="font-size: 10px">'.Yii::t("smith", 'No período de').' ' . $data_inicio . ' '.Yii::t("smith", 'até').' ' . MetodosGerais::dataBrasileira($data_fim) . ' </span>
                        </div>
                        <span><b>' . Yii::t("smith", 'Contrato') . ': ' . $contrato . ' - ' . Yii::t("smith", 'Código') . ': ' . $prefixo . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Data de inicio') . ': ' . MetodosGerais::dataBrasileira($data) . '</b></span>
                        <span><b>- ' . Yii::t("smith", 'Estimativa de conclusão') . ': ' . $estimativaData . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Orçamento previsto') . ': ' . $previsto . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Coordenador') . ': ' . $coordenador . '</b></span>
                        <div class="header_date">
                            <p>' . Yii::t("smith", 'Data') . ':  ' . date('d/m/Y', strtotime($data_fim)) . '
                            <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                    </div>
                </page_header>
            </page>';
            $html = $header;

            $tempoTotal = 0;

            $corpo = "";
            $html .= $rodape;
            $custoTotal = 0;
            foreach ($colaboradores as $colaborador) {
                $tempoTotal += $colaborador->duracao;
                $custoTotal += $colaborador->salario * ($colaborador->duracao / 3600);
            }

            foreach ($colaboradores as $colaborador) {
                $equipe = Equipe::model()->findByPk($colaborador->equipe);
                if (isset($equipe)) {
                    $corpo .= '<tr>
                    <td style="width: 150px">'
                            . MetodosGerais::reduzirNome($colaborador->colaborador)
                            . '</td>
                    <td style="width: 178px">' . $equipe->nome . ' </td>
                    <td style="width: 83px; text-align: center">' . MetodosGerais::formataTempo($colaborador->duracao) . ' </td>
                    <td style="width: 83px; text-align: center">' . round(($colaborador->duracao * 100) / $tempoTotal, 2) . '% </td>
                    <td style="width: 75px; text-align: center"> R$' . MetodosGerais::float2real(round($colaborador->salario * ($colaborador->duracao / 3600), 2)) . ' </td>

                </tr>';
                }
            }

            $html .= '<table  class="table_custom" border="1px">
                <tr>
                    <th colspan="2" style="text-align: left;">'.
                Yii::t("smith", 'Tempo previsto') . ': ' . $tempo_previsto .
                    '</th>
                    <th colspan="3" style="text-align: left;">' .
                Yii::t("smith", 'Tempo total realizado') . ': ' . MetodosGerais::formataTempo($tempoTotal) . ' ' . Yii::t('smith', 'horas') .
                    '</th>
                </tr>
                <tr style="background-color: #CCC; text-decoration: bold;">
                    <th>' . Yii::t("smith", 'Colaborador') . '</th>
                    <th style="width: 173px">' . Yii::t("smith", 'Equipe') . '</th>
                    <th style="width: 80px;">' . Yii::t("smith", 'Tempo') . '<br>' . Yii::t("smith", 'realizado') . '</th>
                    <th style="width: 80px;">' . Yii::t("smith", 'Participação') . '<br>' . Yii::t("smith", 'no projeto') . '</th>
                    <th style="width: 73px; text-align: center">' . Yii::t("smith", 'Orçamento realizado') . '</th>

                </tr>';
            $html .= $corpo;
            $html .= '</table> <p style="margin-top: 10px"></p>';
            $total_previsto = $total_documentos_realizados = $custo_total_documentos = 0;
            $corpo = "";
            $tempoTotalDocumentos = 0;
            foreach ($documentos as $documento) {

                $horas_realizadas = $documento->duracao / 3600;
                $horas_total = $tempoTotal / 3600;

                $total_documentos_realizados += $documento->duracao;
                $custo_total_documentos += round(($horas_realizadas * $custoTotal) / $horas_total, 2);

                $tempoTotalDocumentos += $documento->duracao;
                if ($documento->duracao != NULL) {
                    $corpo .= '<tr>
                        <td style="width: 520px; text-align: left" >' . $documento->documento . ' </td>
                        <td style="width: 65px; text-align: center">' . MetodosGerais::formataTempo($documento->duracao) . ' </td>
                        <td style="width: 65px; text-align: center">R$ ' . MetodosGerais::float2real(round(($horas_realizadas * $custoTotal) / $horas_total, 2)) . '</td>
                    </tr>';
                }
            }

            $html .= '<table  class="table_custom" border="1px">
                <tr>
                    <th colspan="3" style="text-align: left;">'.
                Yii::t("smith", 'Tempo previsto') . ': ' . $tempo_previsto . ' | ' .
                Yii::t("smith", 'Tempo total realizado') . ': ' . MetodosGerais::formataTempo($tempoTotalDocumentos) . ' ' . Yii::t('smith', 'horas') .
                    '</th>
                </tr>
                <tr style="background-color: #CCC; text-decoration: bold;">
                    <th>' . Yii::t("smith", 'Nome do arquivo') . '</th>
                    <th>' . Yii::t("smith", 'Tempo realizado') . '</th>
                    <th>' . Yii::t("smith", 'Custo') . '</th>
                </tr>';
            $html .= $corpo;
            $html .= '</table>';
            $html .= "<p style='margin-top: 15px'></p>"
                    . "<table class='table_custom' border='1px'>"
                    . "<tr style='background-color: #CCC; text-decoration: bold;'>
                        <th></th>
                        <th>" . Yii::t("smith", 'Tempo previsto') . '</th>
                        <th>' . Yii::t("smith", 'Tempo total realizado') . '</th>
                        <th>' . Yii::t("smith", 'Custo total') . "</th>
                </tr>
                <tr>"
                    . "<td><b>" . Yii::t("smith", 'Total consolidado') . "</b></td>"
                    . "<td>" . $tempo_previsto . "</td>"
                    . "<td>" . MetodosGerais::formataTempo($total_documentos_realizados + ($tempoTotal - $total_documentos_realizados)) . "</td>"
                    . "<td>R$ " . MetodosGerais::float2real($custo_total_documentos + round(((($tempoTotal - $total_documentos_realizados) / 3600) * $custoTotal) / $horas_total, 2)) . "</td></tr></table>";

            $src = $src .'/'. date('Y', strtotime($data_fim)) .'/';
            BackupRelatorios::checkDir($src);
            $src .= MetodosGerais::mesString(date('m', strtotime($data_fim))) . '/';
            BackupRelatorios::checkDir($src);
            $src .= date('d', strtotime($data_fim)) . '/';
            BackupRelatorios::checkDir($src);
            $filename = 'relatorioAcompanhamento_' . trim(utf8_encode($contrato)) . '_' . date('dmY', strtotime($data_fim)) . '.pdf';
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);
            $html2pdf->Output($src . $filename, 'F');
        }
    }
}