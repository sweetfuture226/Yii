<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MetodosCSV
{

    /**
     * @param $registros
     * @param $dataInicio
     * @param $dataFim
     * @param $tipo
     * @throws PHPExcel_Exception
     *
     * Método utilizado para criar lista planilhas CSV do relatorio geral do contrato e exportar em arquivo ZIP
     */
    public static function ExportCSVRelGeralContrato($registros, $dataInicio, $dataFim, $tipo, $extensao = null)
    {
        $src = dirname(Yii::app()->request->scriptFile) . '/public/';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';
        $arrayCsvFiles = array();
        foreach ($registros as $key => $value) {
            $titulo = 'Relatório geral de acompanhamento de contratos - ' . ucfirst($tipo) . ': ' . $key . ' - no período de ' . $dataInicio . ' até ' . $dataFim;
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
            try {

                $objWriter = $extensao == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
                $objWriter->save($src . "RelatorioGeralContrato - " . utf8_decode($key) . "." . $extensao);
                array_push($arrayCsvFiles, "RelatorioGeralContrato - " . utf8_decode($key) . "." . $extensao);
            } catch (Exception $e) {
                Logger::saveError($e);
            }
        }
        MetodosCSV::zipCsvFiles('RelatoriosProdutividadeGeralContrato.zip', $arrayCsvFiles);        
    }

    /**
     * @param $registros
     * @param $contrato
     * @param $coordenador
     * @param $dataInicio
     * @param $dataFim
     * @throws PHPExcel_Exception
     *
     * Método utilizado para criar lista planilhas CSV do relatorio individual do contrato e exportar em arquivo ZIP
     */
    public static function ExportCSvRelIndividualContrato($registros, $contrato, $coordenador, $dataInicio, $dataFim, $extensao = null)
    {
        $src = dirname(Yii::app()->request->scriptFile) . '/public/';
        $tempoTotal = $custoTotal = 0;
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

        $titulo = 'Relatório de acompanhamento do contrato ' . $contrato->nome . ' no período de ' . $dataInicio . ' até ' . $dataFim;
        $phpExcel = new PHPExcel();
        $phpExcel->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
        $phpExcel->setActiveSheetIndex()->mergeCells('A1:C1');
        $phpExcel->getActiveSheet()->setCellValue('A1', $titulo);

        $phpExcel->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Colaborador'));
        $phpExcel->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Equipe'));
        $phpExcel->getActiveSheet()->setCellValue('C2', Yii::t('smith', 'Tempo realizado'));
        $phpExcel->getActiveSheet()->setCellValue('D2', Yii::t('smith', 'Participação no projeto'));
        $phpExcel->getActiveSheet()->setCellValue('E2', Yii::t('smith', 'Orçamento realizado'));
        foreach ($registros[0] as $colaborador) {
            $tempoTotal += $colaborador->duracao;
            $custoTotal += $colaborador->salario * ($colaborador->duracao / 3600);
        }
        $index = 3;
        foreach ($registros[0] as $colaborador) {
            $equipe = Equipe::model()->findByPk($colaborador->equipe);
            if (isset($equipe)) {
                $phpExcel->getActiveSheet()
                    ->setCellValue('A' . $index, $colaborador->colaborador)
                    ->setCellValue('B' . $index, $equipe->nome)
                    ->setCellValue('C' . $index, MetodosGerais::formataTempo($colaborador->duracao))
                    ->setCellValue('D' . $index, round(($colaborador->duracao * 100) / $tempoTotal, 2) . '%')
                    ->setCellValue('E' . $index, 'R$' . MetodosGerais::float2real(round($colaborador->salario * ($colaborador->duracao / 3600), 2)));
                $index++;
            }
        }
        $arrayCsvFiles = array();
        try {
            $objWriter = $extensao == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
            $objWriter->save($src . 'RelatorioIndividualContrato - Resumo.' . $extensao);
            array_push($arrayCsvFiles, 'RelatorioIndividualContrato - Resumo.csv' . $extensao);
        } catch (Exception $e) {
            Logger::saveError($e);
        }

        $phpExcel2 = new PHPExcel();
        $phpExcel2->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
        $phpExcel2->setActiveSheetIndex()->mergeCells('A1:C1');
        $phpExcel2->getActiveSheet()->setCellValue('A1', $titulo);

        $phpExcel2->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Nome do arquivo'));
        $phpExcel2->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Tempo realizado'));
        $phpExcel2->getActiveSheet()->setCellValue('C2', Yii::t('smith', 'Custo'));
        $index2 = 3;
        foreach ($registros[1] as $value) {
            $horas_realizadas = $value->duracao / 3600;
            $horas_total = $tempoTotal / 3600;
            $phpExcel2->getActiveSheet()
                ->setCellValue('A' . $index2, $value->documento)
                ->setCellValue('B' . $index2, MetodosGerais::formataTempo($value->duracao))
                ->setCellValue('C' . $index2, 'R$' . MetodosGerais::float2real(round(($horas_realizadas * $custoTotal) / $horas_total, 2)));
            $index2++;
        }

        try {
            $objWriter = $extensao == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel2, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel2, 'Excel2007');;
            $objWriter->save($src . 'RelatorioIndividualContrato - LDP.' . $extensao);
            array_push($arrayCsvFiles, 'RelatorioIndividualContrato - LDP.' . $extensao);
        } catch (Exception $e) {
            Logger::saveError($e);
        }
        MetodosCSV::zipCsvFiles('RelatoriosProdutividadeIndividualContrato.zip', $arrayCsvFiles);
    }

    /**
     * @param $registros
     * @param $colaborador
     * @param $data
     * @param $horarioEntrada
     * @param $horarioSaida
     * @throws PHPExcel_Exception
     *
     * Método utilizado para criar lista planilhas CSV do relatorio individual do colaborador e exportar em arquivo ZIP
     */
    public static function ExportCSVRelIndividualCol($registros, $colaborador, $data, $horarioEntrada, $horarioSaida, $extensao = null)
    {
        if (!empty($registros)) {
            $src = dirname(Yii::app()->request->scriptFile) . '/public/';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

            $titulo = 'Acompanhamento de produtividade de ' . $colaborador . ' em ' . $data . ' - ' . Yii::t("smith", 'Horário início') . ': ' . $horarioEntrada . ' - ' . Yii::t("smith", 'Horário fim') . ': ' . $horarioSaida;
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
            try {
                $objWriter = $extensao == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
                $objWriter->save($src . 'RelatorioIndividual - Resumo.' . $extensao);
                array_push($arrayCsvFiles, 'RelatorioIndividual - Resumo.' . $extensao);
            } catch (Exception $e) {
                Logger::saveError($e);
            }

            for ($i = 0; $i <= 4; $i++) {
                $phpExcel2 = new PHPExcel();
                $phpExcel2->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith")->setTitle($titulo);
                $phpExcel2->setActiveSheetIndex()->mergeCells('A1:B1');
                $phpExcel2->getActiveSheet()->setCellValue('A1', $titulo);

                $phpExcel2->getActiveSheet()->setCellValue('A2', Yii::t('smith', 'Programa/Site'));
                $phpExcel2->getActiveSheet()->setCellValue('B2', Yii::t('smith', 'Descrição'));
                $phpExcel2->getActiveSheet()->setCellValue('C2', Yii::t('smith', 'Tempo gasto'));

                $index2 = 3;
                foreach ($registros[$i] as $key => $value) {
                    if ($key != '0') {
                        for ($j = 0; $j < count($value) - 1; $j++) {
                            $phpExcel2->getActiveSheet()
                                ->setCellValue('A' . $index2, $key)
                                ->setCellValue('B' . $index2, strip_tags($value[$j][0]))
                                ->setCellValue('C' . $index2, MetodosGerais::formataTempo($value[$j][1]));
                            $index2++;
                        }
                    }
                }
                try {
                    $filename = 'RelatorioIndividual - ' . MetodosCSV::getTipoRelatorioIndividual($i) . '.' . $extensao;
                    $objWriter = $extensao == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
                    $objWriter->save($src . $filename);
                    array_push($arrayCsvFiles, $filename);
                } catch (Exception $e) {
                    Logger::saveError($e);
                }
            }
            MetodosCSV::zipCsvFiles('RelatoriosProdutividadeIndividual.zip', $arrayCsvFiles);
        }
    }

    /**
     * @param $registros
     * @param $equipe
     * @param $dataInicio
     * @param $dataFim
     * @throws PHPExcel_Exception
     *
     * Método utilizado para criar lista de relatorios das equipes para csv e exportar para arquivo ZIP
     */
    public static function ExportCSVRelEquipe($registros, $equipe, $dataInicio, $dataFim, $tipo = null)
    {
        if (!empty($registros)) {
            $src = dirname(Yii::app()->request->scriptFile) . '/public/';
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
            try {
                $objWriter = $tipo == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
                $objWriter->save($src . 'RelatorioEquipe.' . $tipo);
                array_push($arrayCsvFiles, 'RelatorioEquipe.' . $tipo);
            } catch (Exception $e) {
                Logger::saveError($e);
            }

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
                try {
                    $filename = 'RelatorioEquipe' . utf8_decode($registros[3][$key]['name']) . '.' . $tipo;
                    $objWriter = $tipo == 'csv' ? PHPExcel_IOFactory::createWriter($phpExcel2, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel2, 'Excel2007');
                    $objWriter->save($src . $filename);
                    array_push($arrayCsvFiles, $filename);
                } catch (Exception $e) {
                    Logger::saveError($e);
                }

            }
            MetodosCSV::zipCsvFiles('RelatoriosEquipe.zip', $arrayCsvFiles);
        }
    }

    /**
     * @param $registros
     * @param $colunas
     * @param $titulo
     * @param $filename
     * @param $tipo
     * @return bool
     * @throws PHPExcel_Exception
     *
     * Método genérico para criar um documento csv.
     */
    public static function ExportToCsv($registros, $colunas, $titulo, $filename, $tipo, $extensao = null)
    {
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
                    $phpExcel = MetodosCSV::formataDataRelIndDiasCSV($registros, $phpExcel);
                    break;
                case 'relCusto':
                    $phpExcel = MetodosCSV::formataDataRelCustoCSV($registros, $phpExcel);
                    break;
                case 'relInd':
                    $phpExcel = MetodosCSV::formataDataRelIndCSV($registros, $phpExcel, $filename);
                    break;
                case 'relRanking':
                    $phpExcel = MetodosCSV::formatDataRelRanking($registros, $phpExcel);
                    break;
                case 'relHoraExtra':
                    $phpExcel = MetodosCSV::formataDataRelHoraExtra($registros, $phpExcel);
                    break;
                case 'relGeral':
                    $phpExcel = MetodosCSV::formataDataRelPrdGeral($registros, $phpExcel);
                    break;
                case 'relPrdColContrato':
                    $phpExcel = MetodosCSV::formataDataRelPrdColContrato($registros, $phpExcel);
                    break;
                case 'relCustoEnergia':
                    $phpExcel = MetodosCSV::formataDataRelCustoEnergia($registros, $phpExcel);
                    break;
                case 'relPonto':
                    $phpExcel = MetodosCSV::formataDataRelPonto($registros, $phpExcel);
                    break;
                case 'relPontoAtual':
                    $phpExecl = MetodosCSV::formataDataRelPontoAtual($registros, $phpExcel);
                    break;
            }
            $flag = true;
            try {
                $objWriter = $extensao == "csv" ? PHPExcel_IOFactory::createWriter($phpExcel, 'CSV') : PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
                $objWriter->save($filename);
                header('Content-type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                flush();
                readfile($filename);
                unlink($filename);
            } catch (Exception $e) {
                Logger::saveError($e);
            }
        }
        return $flag;

    }

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório individual em dias (Produtividade)
     */
    private function formataDataRelIndDiasCSV($registros, $phpExcel)
    {
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

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório de custo (Produtividade)
     */
    private function formataDataRelCustoCSV($registros, $phpExcel)
    {
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

    /**
     * @param $registros
     * @param $phpExcel
     * @param $tipo
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório Individual (diário,mensal,anual) (Produtividade)
     */
    private function formataDataRelIndCSV($registros, $phpExcel, $tipo)
    {
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

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório Ranking (Produtividade)
     */
    private function formatDataRelRanking($registros, $phpExcel)
    {
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

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório de Hora Extra (Produtividade)
     */
    private function formataDataRelHoraExtra($registros, $phpExcel)
    {
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

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório Geral (Programas e Sites)
     */
    private function formataDataRelPrdGeral($registros, $phpExcel)
    {
        $index = 3;
        foreach ($registros as $key => $value) {
            foreach ($value[1] as $chave => $valor) {
                $phpExcel->getActiveSheet()->setCellValue('A' . $index, $key)
                    ->setCellValue('B' . $index, $valor)
                    ->setCellValue('C' . $index, isset($value[0][$chave]) ? MetodosGerais::formataTempo($value[0][$chave]) : 0);
                $index++;
            }

        }
        return $phpExcel;
    }

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório de Produtividade do Colaborador (Contratos)
     */
    private function formataDataRelPrdColContrato($registros, $phpExcel)
    {
        $index = 3;
        foreach ($registros[1] as $key => $value) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $value)
                ->setCellValue('B' . $index, MetodosGerais::formataTempo($registros[0]['data'][$key] * 3600));
            $index++;
        }
        return $phpExcel;
    }

    /**
     * @param $registros
     * @param $phpExcel
     * @return mixed
     *
     * Formatação dos dados para o CSV gerado do Relatório de Custo de energia (Contratos)
     */
    private function formataDataRelCustoEnergia($registros, $phpExcel)
    {
        $index = 3;
        foreach ($registros[1] as $key => $value) {
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $value)
                ->setCellValue('B' . $index, 'R$' . MetodosGerais::float2real($registros[0]['data'][$key]));
            $index++;
        }
        return $phpExcel;
    }

    /**
     * @param $registros
     * @param $phpExcel
     *
     * Formatação dos dados para o CSV gerado do Relatório ponto (Produtividade)
     */
    private function formataDataRelPonto($registros, $phpExcel)
    {
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

    /**
     * @param $registros
     * @param $phpExcel
     *
     * Formatação dos dados para o CSV gerado do Relatório ponto, considerando a data de pesquisa como a data atual (Produtividade)
     */
    public function formataDataRelPontoAtual($registros, $phpExcel)
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

    private function zipCsvFiles($nomeZip, $arrayCsv)
    {
        $src = dirname(Yii::app()->request->scriptFile) . '/public/';
        if (is_file($src . $nomeZip))
            unlink($src . $nomeZip);
        $result = MetodosGerais::create_zip($arrayCsv, $nomeZip);
        if ($result) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"" . $nomeZip . "\"");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($src . $nomeZip));
            readfile($src . $nomeZip);

            foreach ($arrayCsv as $filename) {
                flush();
                readfile($src . $filename);
                unlink($src . $filename);
            }
            flush();
            readfile($src . $nomeZip);
            unlink($src . $nomeZip);
        }
    }

    public static function formatTempoExcel($tempo)
    {
        $seconds = $tempo * 86400;
        $h = (int)($seconds / 3600);
        $m = (int)(($seconds - $h * 3600) / 60);
        $s = (int)($seconds - $h * 3600 - $m * 60);
        return (($h) ? (($h < 10) ? ("0" . $h) : $h) : "00") . ":" . (($m) ? (($m < 10) ? ("0" . $m) : $m) : "00") . ":" . (($s) ? (($s < 10) ? ("0" . $s) : $s) : "00");
    }

    public static function strTempoByTipo($tempo, $type)
    {
        if ($type === 'n') {
            return MetodosCSV::formatTempoExcel($tempo);
        } else if ($type === 's') {
            list($hora, $minuto, $segundo) = explode(":", $tempo);
            return $hora . ':' . $minuto . ':' . $segundo;
        } else {
            throw new SmithException('Tipo não reconhecido: ' . $type . ' (' . $tempo . ')',
                SmithException::FORMAT_EXCELL);
        }
    }

    public function getTimeByTipo($tempo, $type)
    {
        if ($type === 'n') {
            return (float)($tempo * 86400) / 3600;
        } else if ($type === 's') {
            list($hora, $minuto, $segundo) = explode(":", $tempo);
            $total = $hora + (float)$minuto / 60 + (float)$segundo / 60;
            return $total;
        } else {
            throw new SmithException('Tipo não reconhecido: ' . $type . ' (' . $tempo . ')',
                SmithException::FORMAT_EXCELL);
        }
    }

    private function getTipoRelatorioIndividual($tipo)
    {
        switch ($tipo) {
            case 0:
                return 'ProgramasPermitidos';
            case 1:
                return 'SitesPermitidos';
            case 2:
                return 'AtividadeExterna';
            case 3:
                return 'ProgramasNaoPermitidos';
            case 4:
                return 'SitesNaoPermitidos';
        }
    }
}
