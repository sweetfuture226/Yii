<?php

class ProdutividadeController extends Controller {

    public $title_action = "";

    public function filters() {
        return array(
            'userGroupsAccessControl'
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'view' action
                'actions' => array('RelatorioIndividualDias', 'RelatorioEquipe', 'RelatorioIndividual', 'RelatorioCusto', 'RelatorioRanking', 'RelatorioRankingAjax'
                , 'RelatorioHoraExtra', 'RelatorioPonto', 'RelatorioComparativo'),
                'groups' => array('coordenador', 'empresa', 'root','demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    public function actionRelatorioIndividualDias()
    {
        $this->pageTitle = Yii::t("smith", "Produtividade em dias");
        $this->title_action = Yii::t("smith", "Produtividade em dias");
        if(!empty($_POST)){
            $start = MetodosGerais::inicioContagem();
            $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
            $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);
            $registros = GrfProdutividadeConsolidado::model()->produtividadeDiariaPorData($dataInicio, $dataFim, $_POST['colaborador_id']);
            $dadosColaborador = Colaborador::model()->findByPk($_POST['colaborador_id']);
            $colaboradorNome = $dadosColaborador->nomeCompleto;
            $colaboradorNome = MetodosGerais::reduzirNome($colaboradorNome);

            // Export CSV
            if(!empty($_POST['button'])){
                $titulo = Yii::t('smith', "Produtividade diária de $colaboradorNome");
                $filename = 'RelatorioProdutividadeDias.' . $_POST['button'];
                $colunas = array('65' => 'Data', '66' => 'Tempo Produzido (horas)', '67' => 'Tempo Previsto (horas)');
                if (!MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInDias', $_POST['button'])) {
                    Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não existe dados no período solicitado.'));
                    $this->render('relatorioIndividualDias');
                }

            } // Gráfico
            else {
                if(!empty($registros)){
                    $categorias = $produzido = $previsto = array();
                    foreach ($registros as $colaborador){
                        $mes = date('m/Y', strtotime($colaborador->data));
                        $produzido[$mes]['name'] = Yii::t("smith",'Tempo Produzido');
                        $previsto[$mes]['name'] = Yii::t("smith",'Tempo Previsto');
                        $categorias[$mes][] = MetodosGerais::dataBrasileira($colaborador->data);
                        $produzido[$mes]['data'][] = round((float)$colaborador->duracao,2);
                        $previsto[$mes]['data'][] = round((float)$colaborador->hora_total,2);
                    }


                    LogAcesso::model()->saveAcesso('Produtividade', 'Relatório individual em dias', 'Produtividade em dias', MetodosGerais::tempoResposta($start));
                    $this->render('grfRelatorioIndividualDias',
                            array('produzido'=>$produzido, 'previsto'=>$previsto, 'categorias'=>$categorias,
                                'data_inicio'=>  MetodosGerais::dataBrasileira($dataInicio),
                                'data_fim'=>  MetodosGerais::dataBrasileira($dataFim),
                                'colaborador' => $colaboradorNome, 'colaboradorAd' => $dadosColaborador->ad,
                                'colaboradorId' => $dadosColaborador->id));
                } else {
                    Yii::app()->user->setFlash('warning',Yii::t("smith", 'Não existe dados no período solicitado.'));
                    $this->render('relatorioIndividualDias');
                }
            }
        } else $this->render('relatorioIndividualDias');
    }



    public function actionRelatorioEquipe()
    {
        $this->pageTitle = Yii::t("smith", "Produtividade por equipe");
        $this->title_action = Yii::t("smith", "Produtividade por equipe");
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND id = " . MetodosGerais::getEquipe();
        }
        if (!empty($_POST)) {
            $start = MetodosGerais::inicioContagem();
            $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
            $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);
            $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $fk_empresa, $_POST['equipe']);
            if (!empty($resultEquipe)) {
                $options = $categoriaEquipe = $categorias = $produzidoEquipe = $produzido = array();
                foreach ($resultEquipe as $value) {
                    $obj = GrfProdutividadeConsolidado::model()->getQuantidadeDiasTrabalhadosPorColaborador($value->fk_colaborador, $dataInicio, $dataFim);
                    if (!isset($_POST['hora_extra'])) {
                        $horaExtra = GrfHoraExtraConsolidado::model()->getHorasExtrasEquipe($dataInicio, $dataFim, $fk_empresa, $value->fk_colaborador);
                        $colaboradorDuracao = (float)($value->duracao - $horaExtra->duracao);
                    } else
                        $colaboradorDuracao = $value->duracao;
                    $porcentagemCol = round(($colaboradorDuracao * 100) / ($value->hora_total * $obj->dias_trabalhados), 2);
                    $produzidoEquipe[$value->fk_equipe]['data'][] = $porcentagemCol;
                    $categoriaEquipe[$value->fk_equipe][] = Colaborador::model()->findByPk($value->fk_colaborador)->nomeCompleto;
                }
                $produzido = array('name' => 'Produtividade', 'type' => 'bar', 'yAxis' => 0);
                $meta = array("name" => "Meta", "type" => "spline");
                foreach ($produzidoEquipe as $key => $value) {
                    $equipe = Equipe::model()->findByPk($key);
                    $options[$key] = $equipe->nome;
                    $meta['data'][] = (float)$equipe->meta;
                    $categorias[] = $equipe->nome;
                    $total = array_sum(array_values($value['data']));
                    $produzido['data'][] = round($total / count($value['data']), 2);
                    $produzidoEquipe[$key]['name'] = $equipe->nome;
                }
                $equipeNome = (!empty($_POST['equipe'])) ? $_POST['equipe'] : 'Todas';
                LogAcesso::model()->saveAcesso('Produtividade', 'Relatório por equipe', 'Produtividade por equipe', MetodosGerais::tempoResposta($start));
                // Export CSV
                if (!empty($_POST['button'])) {
                    $registros = array($produzido, $meta, $categorias, $produzidoEquipe, $categoriaEquipe);
                    MetodosCSV::ExportCSVRelEquipe($registros, $equipeNome, $dataInicio, $dataFim, $_POST['button']);
                } // Gráfico
                else {
                    $this->render('grfRelatorioEquipe', array('produzido' => $produzido, 'meta' => $meta,
                        'categorias' => $categorias, 'produzidoEquipe' => $produzidoEquipe,
                        'categoriaEquipe' => $categoriaEquipe, 'equipe' => $equipeNome,
                        'data_inicio' => MetodosGerais::dataBrasileira($dataInicio),
                        'data_fim' => MetodosGerais::dataBrasileira($dataFim),
                        'options' => $options));
                }

            } else {
                Yii::app()->user->setFlash('warning', Yii::t("smith", 'Esta equipe não teve produtividade nesta data.'));
                $this->redirect(array('RelatorioEquipe'));
            }
        } else
            $this->render('RelatorioEquipe', array("condicao" => $condicao));
    }


    public function actionRelatorioIndividual()
    {
        $this->title_action = Yii::t("smith", 'Produtividade individual');
        $this->pageTitle = Yii::t("smith", "Produtividade individual");
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();
        }
        if (!empty($_POST)) {
            // ddd($_POST);
            $start = MetodosGerais::inicioContagem();
            $colaborador = Colaborador::model()->findByAttributes(array('id' => $_POST['colaborador_id'], 'fk_empresa' => MetodosGerais::getEmpresaId()));
            //dd($colaborador);
            $tipo = $_POST['tipo'];
            switch ($tipo) {
                case 'dias':
                    $produtividadeDia = LogAtividade::model()->getProdutividadeDiaria($colaborador->ad, $_POST['dataDia']);
                    break;
                case 'mes':
                    $explode = explode('/', $_POST['dataMes']);
                    $registrosMes = GrfProdutividadeConsolidado::model()->produtividadeDiariaPorMesAno($explode[0], $explode[1], $_POST['colaborador_id']);
                    break;
                case 'ano':
                    $registrosAno = GrfProdutividadeConsolidado::model()->produtividadeDiariaPorAno($_POST['dataAno'], $_POST['colaborador_id']);
                    break;
            }
            /*
             * Produtividade diária
             */
            if (!empty($produtividadeDia)) {
                $this->renderProdutividadeIndDia($colaborador, $produtividadeDia, $start, $_POST['button']);
            } /*
             * Produtividade mensal
             */
            elseif (!empty($registrosMes)) {
                $this->renderProdutividadeIndMes($colaborador, $registrosMes, $start, $_POST['button']);

            } /*
             * Produtividade anual
             */
            elseif (!empty($registrosAno)) {
                $this->renderProdutividadeIndAno($colaborador, $registrosAno, $start, $_POST['button']);

            } else {
                Yii::app()->user->setFlash('warning', Yii::t("smith", 'Este colaborador não teve produtividade nesta data.'));
                $this->redirect(array('RelatorioIndividual'));
            }
        } else
            $this->render("RelatorioIndividual", array("condicao" => $condicao));
    }

    /**
     * @param $colaborador
     * @param $produtividadeDia
     * @param $start
     *
     * Método responsável pelo calculo de produtividade individual diária e renderização do resultado em gráfico de linha.
     */
    public function renderProdutividadeIndDia($colaborador, $produtividadeDia, $start, $csv)
    {
        $mediaEquipe = LogAtividade::model()->getMediaProdEquipeDia($colaborador->fk_equipe, $_POST['dataDia']);
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
        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório individual', 'Produtividade individual', MetodosGerais::tempoResposta($start));
        if (!empty($csv)) {
            $registros = array($produtivo, $produtivoMedia, $produtivoMeta, $categorias);
            $colunas = array('65' => 'Período (h)', '66' => 'Produtividade', '67' => 'Média equipe', '68' => 'Meta estabelecida');
            $titulo = Yii::t('smith', 'Produtividade de ') . $colaborador->nomeCompleto . Yii::t('smith', ' no dia ') . $_POST['dataDia'];
            $filename = 'RelatorioIndividualDiário.' . $csv;
            MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInd', $csv);
        } else {
            $this->render("grfRelatorioIndividualDia", array(
                'categorias' => $categorias,
                'produzido' => $produtivo,
                'produtivoMedia' => $produtivoMedia,
                'produtivoMeta' => $produtivoMeta,
                'data' => $_POST['dataDia'],
                'colaborador' => $colaborador->nomeCompleto,
            ));
        }


    }

    /**
     * @param $colaborador
     * @param $registrosMes
     *
     * Método responsável pelo calculo de produtividade individual mensal e renderização do resultado em gráfico de linha.
     */
    public function renderProdutividadeIndMes($colaborador, $registrosMes, $start, $csv)
    {
        $meta = ((($colaborador->horas_semana / 5) * $colaborador->equipes->meta) / 100) * 3600;
        $explode = explode('/', $_POST['dataMes']);
        $datasUteis = MetodosGerais::datas_uteis_mes($explode[0], $explode[1]);
        $mediaEquipe = GrfProdutividadeConsolidado::model()->getMediaProdEquipeMes($colaborador->fk_equipe, $explode[0], $explode[1]);
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
        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório individual', 'Produtividade individual', MetodosGerais::tempoResposta($start));
        if (!empty($csv)) {
            $registros = array($produtivo, $produtivoMedia, $produtivoMeta, $categorias);
            $colunas = array('65' => 'Período (dias)', '66' => 'Produtividade', '67' => 'Média equipe', '68' => 'Meta estabelecida');
            $titulo = Yii::t('smith', 'Produtividade de ') . $colaborador->nomeCompleto . Yii::t('smith', ' no mês ') . $_POST['dataMes'];
            $filename = 'RelatorioIndividualMensal.' . $csv;
            MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInd', $csv);
        } else {
            $this->render("grfRelatorioIndividualMes", array(
                'options' => $options,
                'categorias' => $splitedCategorias,
                'produzido' => $splitedProduzido,
                'produzidoMeta' => $splitedProduzidoMeta,
                'produzidoMedia' => $splitedProduzidoMedia,
                'data' => $_POST['dataMes'],
                'colaborador' => $colaborador->nomeCompleto,
            ));
        }

    }

    /**
     * @param $colaborador
     * @param $registrosMes
     * @param $start
     *
     * Método responsável pelo calculo de produtividade individual anual e renderização do resultado em gráfico de linha.
     */
    public function renderProdutividadeIndAno($colaborador, $registrosAno, $start, $csv)
    {
        $meta = ((($colaborador->horas_semana * 4) * $colaborador->equipes->meta) / 100) * 3600;
        for ($i = 1; $i <= 12; $produzido['data'][$i] = (float)0, $produtivoMedia['data'][$i] = (float)0, $categorias[$i] = MetodosGerais::mesString($i), $categoriasGrf[] = MetodosGerais::mesString($i), $produtivoMeta['data'][$i] = $meta, $i++) ;
        $mediaEquipe = GrfProdutividadeConsolidado::model()->getMediaProdEquipeAno($colaborador->fk_equipe, $_POST['dataAno']);
        foreach ($registrosAno as $value) {
            $produzido['data'][$value->data] = (float)$value->duracao;
        }
        foreach ($mediaEquipe as $value) {
            $produtivoMedia['data'][$value->data] = (float)$value->duracao * 3600;
        }
        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório individual', 'Produtividade individual', MetodosGerais::tempoResposta($start));
        if (!empty($csv)) {
            $registros = array($produzido, $produtivoMedia, $produtivoMeta, $categorias);
            $colunas = array('65' => 'Período (meses)', '66' => 'Produtividade', '67' => 'Média equipe', '68' => 'Meta estabelecida');
            $titulo = Yii::t('smith', 'Produtividade de ') . $colaborador->nomeCompleto . Yii::t('smith', ' no ano ') . $_POST['dataAno'];
            $filename = 'RelatorioIndividualAnual.' . $csv;
            MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relInd', $csv);

        } else {
            $this->render("grfRelatorioIndividualAno", array(
                'categorias' => $categoriasGrf,
                'produzido' => $produzido,
                'produtivoMeta' => $produtivoMeta,
                'produtivoMedia' => $produtivoMedia,
                'data' => $_POST['dataAno'],
                'colaborador' => $colaborador->nomeCompleto,
            ));
        }


    }

    public function actionRelatorioCusto()
    {
        $this->pageTitle = Yii::t("smith", "Custo");
        $this->title_action = Yii::t("smith", "Custo");
        if (!empty($_POST['opcao'])) {
            $start = MetodosGerais::inicioContagem();
            $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
            $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);

            $empresaId = MetodosGerais::getEmpresaId();


            //////////////////// CUSTO POR EQUIPE ////////////////////////////////////////
            if ($_POST['opcao'] == 'equipe') {
                $opcao = ($_POST['selecionado'] == 'todas_equipes') ? '' : $_POST['selecionado'];
                $produtividadeColaborador = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $empresaId, $opcao);
                $title_grafico = ($_POST['selecionado'] != 'todas_equipes') ? Equipe::model()->findByPk($_POST['selecionado'])->nome : 'todas as equipes';
                if (!empty($produtividadeColaborador)) {
                    list($produzido, $ocioso, $categorias, $splitedProduzido, $splitedOcioso, $splitedCategorias, $options) = Produtividade::graficoCustoByEquipe($produtividadeColaborador, $dataInicio, $dataFim);
                    // Export CSV
                    if (!empty($_POST['button'])) {
                        $registros = array($produzido, $ocioso, $categorias);
                        $colunas = array('65' => 'Equipe', '66' => 'Produzido (R$)', '67' => 'Ausente do computador (R$)');
                        $titulo = 'Custo aproximado de ' . $title_grafico . ' entre ' . $_POST['date_from'] . ' e ' . $_POST['date_to'];
                        $filename = 'RelatorioCusto.' . $_POST['button'];
                        MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relCusto', $_POST['button']);
                    } // Gráfico
                    else {
                        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório por custo', 'Custo', MetodosGerais::tempoResposta($start));
                        $this->render('grfRelatorioCusto', array('heightGrafico' => '600',
                            'categorias' => $splitedCategorias, 'produzido' => $splitedProduzido,
                            'ocioso' => $splitedOcioso, 'title' => $title_grafico,
                            'data_inicio' => $_POST['date_from'], 'data_fim' => $_POST['date_to'],
                            'options' => $options));
                    }
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não houve registros de produtividade no período solicitado'));
                    $this->redirect(array('relatorioCusto'));
                }
            } //////////////////// CUSTO POR COLABORADOR //////////////////////////////////////
            elseif ($_POST['opcao'] == 'colaborador') {
                $title_grafico = ($_POST['selecionado'] != 'todos_colaboradores') ? Colaborador::model()->findByPk($_POST['selecionado'])->nomeCompleto : 'todos os colaboradores';
                $opcao = ($_POST['selecionado'] == 'todos_colaboradores') ? '' : $_POST['selecionado'];
                $produtividadeColaborador = GrfProdutividadeConsolidado::model()->graficoProdutividadeByColaborador($dataInicio, $dataFim, $empresaId, $opcao);
                if (!empty($produtividadeColaborador)) {
                    list($produzido, $ocioso, $categorias, $splitedProduzido, $splitedOcioso, $splitedCategorias, $options) = Produtividade::graficoCustoByColaborador($produtividadeColaborador, $dataInicio, $dataFim);

                    // Export CSV
                    if (!empty($_POST['button'])) {
                        $registros = array($produzido, $ocioso, $categorias);
                        $colunas = array('65' => 'Colaborador', '66' => 'Produzido (R$)', '67' => 'Ausente do computador (R$)');
                        $titulo = 'Custo aproximado de ' . $title_grafico . ' entre ' . $_POST['date_from'] . ' e ' . $_POST['date_to'];
                        $filename = 'RelatorioCusto.csv';
                        MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relCusto');
                    } // Gráfico
                    else {
                        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório por custo', 'Custo', MetodosGerais::tempoResposta($start));
                        $this->render('grfRelatorioCusto', array('heightGrafico' => '600',
                            'categorias' => $splitedCategorias, 'produzido' => $splitedProduzido,
                            'ocioso' => $splitedOcioso, 'title' => $title_grafico,
                            'data_inicio' => $_POST['date_from'], 'data_fim' => $_POST['date_to'], 'options' => $options));
                    }
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não houve registros de produtividade no período solicitado'));
                    $this->redirect(array('relatorioCusto'));
                }
            }
        } else {
            $this->render('relatorioCusto');
        }
    }

    public function actionRelatorioRanking()
    {
        $this->pageTitle = Yii::t("smith", "Ranking por período");
        $this->title_action = Yii::t("smith", "Ranking por período");

        if (isset($_POST['date_from'])) {
            $start = MetodosGerais::inicioContagem();
            $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            $ranking = new GrfProdutividadeConsolidado();
            $ranking->unsetAttributes();  // clear any default values
            if (isset($_GET['ranking']))
                $ranking->attributes = $_GET['ranking'];
            $existsProd = GrfProdutividadeConsolidado::model()->find(array("condition" => 'fk_empresa = ' . MetodosGerais::getEmpresaId() . ' AND data BETWEEN "' . MetodosGerais::dataAmericana($_POST['date_from']) . '" AND "' . MetodosGerais::dataAmericana($_POST['date_to']) . '"'));
            if (isset($existsProd)) {
                LogAcesso::model()->saveAcesso('Produtividade', 'Relatório de ranking', 'Ranking por período', MetodosGerais::tempoResposta($start));
                // Export CSV
                if (!empty($_POST['button'])) {
                    $registros = GrfProdutividadeConsolidado::model()->relatorioRanking(MetodosGerais::dataAmericana($_POST['date_from']), MetodosGerais::dataAmericana($_POST['date_to']), MetodosGerais::getEmpresaId());
                    $colunas = array('65' => 'Equipe', '66' => 'Nome', '67' => 'Produtividade', '68' => 'Meta', '69' => 'Coeficiente');
                    $titulo = 'Relatório Ranking no período de ' . $_POST['date_from'] . ' á ' . $_POST['date_to'];
                    $filename = 'RelatorioRanking.' . $_POST['button'];
                    MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relRanking',
                        $_POST['button']);
                } // Gráfico
                else {
                    // Se o relatórioi solicitado for o de PDF, gero o relatório de PDF;
                    if ($_POST['flagPDF'] == "1") {
                        $this->RelatorioRankingAjax($_POST['date_from'], $_POST['date_to'], "pdf", $ranking);
                    }
                    $this->render('grfRelatorioRanking', array('ranking' => $ranking,
                        'idEmpresa' => $usuario->fk_empresa,
                        'dataInicio' => $_POST['date_from'],
                        'dataFim' => $_POST['date_to']));
                }
            } else {
                Yii::app()->user->setFlash('warning', Yii::t('smith', 'Não há registros de produtividade para este período solicitado.'));
                $this->redirect(array('RelatorioRanking'));
            }

        } else {
            $this->render('relatorioRanking');
        }
    }


    public function RelatorioRankingAjax($date_from, $date_to, $formato, $ranking)
    {

        if ($formato == 'pdf') {
            $fk_empresa = MetodosGerais::getEmpresaId();
            $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
            $header = '<page orientation="landscape" backtop="30mm" backbottom="20mm" format="A4" >
                    <page_header>
                        <div class="header_page">
                            <img class="header_logo_page" src="' . $imagem . '">
                            <div class="header_title" ><p>' . Yii::t("smith", 'RANKING POR PERÍODO') . '</p>
                            <span><b>' . Yii::t("smith", 'Período de datas:') . ' ' . $date_from . ' ' . Yii::t('smith', 'até') . ' ' . $date_to . '</b></span>
                            <br>
                            </div>
                            
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

            $html .= '<p style="margin-top: 5px"></p>
                <table  class="table_custom" border="1px">
                    <tr style="background-color: #CCC; text-decoration: bold;">
                        <th>' . Yii::t("smith", 'Equipe') . '</th>
                        <th>' . Yii::t("smith", 'Colaborador') . '</th>
                        <th>' . Yii::t("smith", 'Produtividade') . '</th>
                        <th>' . Yii::t("smith", 'Meta') . '</th>
                        <th>' . Yii::t("smith", 'Coeficiente') . '</th>
                        <th>' . Yii::t("smith", 'Ausência do computador') . '</th>
                    </tr>';
            // Substituir os a consulta para a mesma consulta da tela da cGridView para o relatório ficar igual
            foreach ($ranking->ranking(MetodosGerais::dataAmericana($date_from),
                MetodosGerais::dataAmericana($date_to),
                $fk_empresa)->getData() as $valor) {
                if ($valor['nome'] != '' && !empty($valor['nome'])) {
                    $html .= '<tr>'
                        . '<td style="text-align: left; width: 250px">' . $valor['equipe'] . '</td>'
                        . '<td style="text-align: left; width: 200px">' . $valor['nome'] . '</td>'
                        . '<td style="text-align: center; width: 150px">' . GrfProdutividadeConsolidado::formatarProdutividade($valor['produtividade']) . '</td>'
                        . '<td style="text-align: left; width: 100px">' . $valor['meta'] . '</td>'
                        . '<td style="text-align: left; width: 120px">' .  str_replace(".",",", $valor['coeficiente']). '</td>'
                        . '<td style="text-align: left; width: 120px">' . GrfProdutividadeConsolidado::formatarProdutividade($valor['ocioso']) . '</td>'
                        . '</tr>';
                }
            }

            $html .= "</table>";
            $style = MetodosGerais::getStyleTableLand();
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);

            $html2pdf->Output(Yii::t('smith', 'rankingProdutividade') . '_' . $date_from . '_' . Yii::t('smith', 'ate') . '_' . $date_to . '.pdf');
        }
    }

    public function getNomeColaborador($data, $row)
    {
        $colaborador = Colaborador::model()->findByPk($data->fk_colaborador);
        if (isset($colaborador)) {
            return $colaborador->nomeCompleto;
        }
        return '';
    }

    public function actionRelatorioHoraExtra()
    {
        $this->title_action = Yii::t("smith", 'Produtividade no horário não comercial');
        $this->pageTitle = Yii::t("smith", "Produtividade no horário não comercial");

        if (!empty($_POST)) {
            $start = MetodosGerais::inicioContagem();

            $empresa = MetodosGerais::getEmpresaId();
            $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
            $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);

            $idOpcao = 0;
            $opcao = 'todos';
            if ($_POST['opcao'] == 'equipe') {
                $opcao = 'equipe';
                $idOpcao = ($_POST['selecionado'] != 'todas_equipes') ? Equipe::model()->findByPk($_POST['selecionado'])->id : 'todos';
            } elseif ($_POST['opcao'] == 'colaborador') {
                $opcao = 'colaborador';
                $idOpcao = ($_POST['selecionado'] != 'todos_colaboradores') ? Colaborador::model()->findByPk($_POST['selecionado'])->id : 'todos';
            }

            $horaExtras = GrfHoraExtraConsolidado::model()->getHorasExtras($dataInicio, $dataFim, $empresa, $opcao, $idOpcao);
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
            LogAcesso::model()->saveAcesso('Produtividade', 'Relatório de hora extra', 'Produtividade no horário não comercial', MetodosGerais::tempoResposta($start));
            // Export CSV
            if (!empty($_POST['button'])) {
                $registros = array($sendduracao, $sendprodutividade, $sendcategorias);
                $colunas = array('65' => 'Colaborador', '66' => 'Duração', '67' => 'Produtividade');
                $titulo = 'Relatório de hora extra dos colaboradores de ' . MetodosGerais::dataBrasileira($dataInicio) . ' até ' . MetodosGerais::dataBrasileira($dataFim);
                $filename = 'RelatorioHoraExtra.' . $_POST['button'];
                MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relHoraExtra', $_POST['button']);
            } // Gráfico
            else {
                $this->render('grfRelatorioHoraExtra', array('categorias' => $splitedCategorias,
                    'duracao' => $splitedDuracao, 'produtividade' => $splitedProduzido,
                    'dataInicio' => MetodosGerais::dataBrasileira($dataInicio),
                    'dataFim' => MetodosGerais::dataBrasileira($dataFim),
                    'options' => $options));
            }

        } else {
            $equipe = "";
            $this->render('relatorioHoraExtra', array("equipe" => $equipe));
        }
    }

    public function actionRelatorioPonto()
    {
        $this->title_action = Yii::t("smith", 'Acompanhamento de colaboradores');
        $this->pageTitle = Yii::t("smith", "Acompanhamento de colaboradores");
        if (!empty($_POST)) {
            $start = MetodosGerais::inicioContagem();

            $today = date('d/m/Y');
            if ($_POST['date_to'] == $today && $_POST['date_from'] == $_POST['date_to']) {
                $inicio = LogAtividade::model()->getColaboradorInicio($_POST['date_from'], $_POST['date_to'], $_POST['colaborador_id']);
                if (!empty($inicio)) {
                    $i = 0;
                    foreach ($inicio as $valor) {
                        $final = LogAtividade::model()->getColaboradorFim($valor['data'], $valor['usuario']);
                        $inicio[$i]['hora_final'] = $final[0]['hora_final'];
                        $i++;
                    }
                    $arrayPonto = array();
                    foreach ($inicio as $value) {
                        $arrayPonto[$value['data']][] = $value;
                    }

                    if (!empty($_POST['button'])) {

                        $colunas = array('65' => 'Data', '66' => 'Colaborador', '67' => 'Horário de entrada', '68' => 'Horário de saída');
                        $titulo = Yii::t('smith', 'Controle de entrada e saída') . ' ' . Yii::t('smith', 'entre') . ' ' . $_POST['date_from'] . ' ' . Yii::t('smith', 'e') . ' ' . $_POST['date_to'];
                        $filename = 'RelatorioPonto.' . $_POST['button'];
                        MetodosCSV::ExportToCsv($arrayPonto, $colunas, $titulo, $filename, 'relPontoAtual',
                            $_POST['button']);
                    } else
                        $this->getRelatorioColaborador($arrayPonto, $_POST['date_from'], $_POST['date_to'], $start);
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não houve registros no período solicitado.'));
                    $this->redirect(array('RelatorioPonto'));
                }

            } else {
                $idEmpresa = MetodosGerais::getEmpresaId();
                $data_inicio = MetodosGerais::dataAmericana($_POST['date_from']);
                $data_fim = MetodosGerais::dataAmericana($_POST['date_to']);
                $pontos = GrfColaboradorConsolidado::model()->getPontos($data_inicio, $data_fim, $idEmpresa, $_POST['colaborador_id']);

                if (!empty($pontos)) {
                    $arrayPonto = array();
                    foreach ($pontos as $value) {
                        $arrayPonto[$value->data][] = $value;
                    }
                    if (!empty($_POST['button'])) {
                        $colunas = array('65' => 'Data', '66' => 'Equipe', '67' => 'Colaborador', '68' => 'Horário de entrada', '69' => 'Horário de saída');
                        $titulo = Yii::t('smith', 'Controle de entrada e saída') . ' ' . Yii::t('smith', 'entre') . ' ' . $_POST['date_from'] . ' ' . Yii::t('smith', 'e') . ' ' . $_POST['date_to'];
                        $filename = 'RelatorioPonto.' . $_POST['button'];
                        MetodosCSV::ExportToCsv($arrayPonto, $colunas, $titulo, $filename, 'relPonto', $_POST['button']);
                    } else
                        $this->getRelatorioColaborador2($arrayPonto, $_POST['date_from'], $_POST['date_to'], $start);
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não houve registros no período solicitado.'));
                    $this->redirect(array('RelatorioPonto'));
                }

            }
        } else {
            $this->render('relatorioPonto');
        }
    }

    public function getRelatorioColaborador2($dados, $dataInicio, $dataFim, $start)
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
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
        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório de ponto', 'Acompanhamento de colaboradores', MetodosGerais::tempoResposta($start));
        $html2pdf->Output(Yii::t('smith', 'acompanhamentoColaboradores') . '_' . $colaborador . '_' . $inicio . '_' . Yii::t('smith', 'ate') . '_' . $final . '.pdf');
    }

    public function getRelatorioColaborador($dados, $dataInicio, $dataFim, $start)
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                            <page_header>
                            <div class="header_page">
                            <img class="header_logo_page" src="' . $imagem . '">
                            <div class="header_title">
                                <p>' . Yii::t("smith", 'CONTROLE DE ENTRADA E SAÍDA') . '</p>
                            </div>

                            <span><b>' . Yii::t("smith", 'Período de datas: ') . '' . $dataInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dataFim . '</b></span><br>

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

            if ($i > 0 && count($ponto) > 1)
                $html .= '<page pageset="old">';
            $html .= '<p style="margin-top: 5px"></p>
                      <table  class="table_custom" border="1px">
                                        <tr>
                                  <th colspan="3" style="text-align: left;">' . Yii::t("smith", 'Data') . ': ' . MetodosGerais::dataBrasileira($data) . '</th>
                                  </tr>
                                      <tr style="background-color: #CCC;
                                                text-decoration: bold;">
                                      <th>' . Yii::t("smith", 'Colaborador') . '</th>
                                      <th>' . Yii::t("smith", 'Horário de Entrada') . '</th>
                                      <th>' . Yii::t("smith", 'Horário de Saída') . '</th>
                                      </tr>';
            foreach ($ponto as $valor) {
                $hora_inicio = MetodosGerais::getHoraServidor($valor['hora_inicio']);
                $hora_final = MetodosGerais::getHoraServidor($valor['hora_final']);
                $html .= '<tr>'
                    . '<td style="text-align: left; width: 450px">' . $valor['nome'] . '</td>'
                    . '<td >' . $hora_inicio . '</td>'
                    . '<td>' . $hora_final . '</td>'
                    . '</tr>';
            }
            $html .= "</table>";
            if ($i > 0 && count($ponto) > 1)
                $html .= '</page>';
            $i++;
        }
        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório de ponto', 'Acompanhamento de colaboradores', $start);
        $style = MetodosGerais::getStyleTable();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output();
    }

    public function actionRelatorioComparativo()
    {
        $this->title_action = Yii::t('smith', 'Relatório comparativo entre colaboradores');
        $this->pageTitle = Yii::t('smith', 'Relatório comparativo entre colaboradores');
        $condicao = ' fk_empresa = ' . MetodosGerais::getEmpresaId();
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=" . MetodosGerais::getEmpresaId() . "AND fk_equipe = " . MetodosGerais::getEquipe();
        }
        if (!empty($_POST)) {
            $produtividade = $custoColaborador = $porcentagemColaboradorContrato = $produtividadeProgramas = $produtividadeSites = $incidenciaHoraExtra = array();
            foreach ($_POST['colaborador'] as $colaborador) {
                // Produtividade
                $prodColaborador = GrfProdutividadeConsolidado::model()->getProdutividadeColaborador($_POST['date_from'], $_POST['date_to'], $colaborador);
                if (isset($prodColaborador->hora_total)) {
                    $produtividade[$colaborador] = round(($prodColaborador->duracao * 100) / $prodColaborador->hora_total, 2);
                    // Produtividade x Custo
                    $objColaborador = Colaborador::model()->findByPk($colaborador);
                    $valorHoraColaborador = $objColaborador->salario / ($objColaborador->horas_semana * 4);
                    $custoColaborador[$colaborador] = MetodosGerais::float2real(round(($prodColaborador->duracao * $valorHoraColaborador), 2));
                    //Programas e sites (5+)
                    $topProgramas = LogAtividade::model()->getTempoProduzidoByAtributos($_POST['date_from'], $_POST['date_to'], $colaborador, 'colaborador');
                    $topSites = LogAtividade::model()->getTempoProduzidoSitesByAtributos($_POST['date_from'], $_POST['date_to'], $colaborador, 'colaborador');
                    $produtividadeProgramas[$colaborador] = (!empty($topProgramas)) ? array_chunk($topProgramas, 5)[0] : array();
                    $produtividadeSites[$colaborador] = (!empty($topSites)) ? array_chunk($topSites, 5)[0] : array();
                    // Incidencia Hora Extra
                    $horaExtras = GrfHoraExtraConsolidado::model()->getHorasExtras(MetodosGerais::dataAmericana($_POST['date_from']), MetodosGerais::dataAmericana($_POST['date_to']), MetodosGerais::getEmpresaId(), 'colaborador', $colaborador);
                    $incidenciaHoraExtra[$colaborador]['duracao'] = (isset($horaExtras[0]->duracao)) ? $horaExtras[0]->duracao : 0;
                    $incidenciaHoraExtra[$colaborador]['produtividade'] = (isset($horaExtras[0]->produtividade)) ? $horaExtras[0]->produtividade : 0;
                    // Participação em projetos
                    $prdColContrato = GrfProjetoConsolidado::model()->getProdutividadeColaboradorPorContrato($colaborador, $_POST['date_from'], $_POST['date_to']);
                    foreach ($prdColContrato as $contrato) {
                        $contratoDuracaoTotal = GrfProjetoConsolidado::model()->getDuracaoTotalContrato($contrato->fk_obra);
                        $porcentagemContrato = round(($contrato->duracao * 100) / $contratoDuracaoTotal->duracao, 2);
                        $porcentagemColaboradorContrato[$colaborador][$contrato->fk_obra] = $porcentagemContrato;
                    }
                }

            }
            $this->getRelatorioComparativo($_POST['colaborador'], $produtividade, $custoColaborador, $porcentagemColaboradorContrato, $produtividadeProgramas, $produtividadeSites, $incidenciaHoraExtra, $_POST['date_from'], $_POST['date_to']);

        } else
            $this->render('relatorioComparativo', array('condicao' => $condicao));

    }

    public function getRelatorioComparativo($colaboradores, $produtividade, $custo, $contratos, $programas, $sites, $horaExtra, $dataInicio, $dataFim)
    {
        $imagem = Empresa::model()->findByPK(MetodosGerais::getEmpresaId())->logo;
        $header = '<page orientation="landscape" backtop="30mm" backbottom="20mm" format="A4" >
                            <page_header>
                            <div class="header_page">
                                <img class="header_logo_page" src="' . $imagem . '">
                                <div class="header_title">
                                    <span>' . Yii::t("smith", 'RELATÓRIO COMPARATIVO ENTRE COLABORADORES') . '</span><br>
                                    <span style="font-size: 10px">' . Yii::t("smith", 'No período de') . ' ' . $dataInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dataFim . ' </span>
                                </div>
                                <div class="header_date">
                                <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
                                    <br>' . Yii::t('smith', 'Pág.') . ' ([[page_cu]]/[[page_nb]]) </p>
                                </div>
                            </div>

                            </page_header>
                            </page>';

        $rodape = MetodosGerais::getRodapeTable();
        $html = $header;
        $html .= '<p style="margin-top: 5px"></p>
                        <table  class="table_custom" border="1px">
                            <tr style="background-color: #CCC; text-decoration: bold;">
                                <th style="text-align: center; width: 130px">' . Yii::t("smith", 'Colaborador') . '</th>
                                <th style="text-align: center; width: 80px">' . Yii::t("smith", 'Produtividade ') . '</th>
                                <th style="text-align: center; width: 80px">' . Yii::t("smith", 'Custo') . '</th>
                                <th style="text-align: center; width: 220px">' . Yii::t("smith", 'Programas mais utilizados') . '</th>
                                <th style="text-align: center; width: 220px">' . Yii::t("smith", 'Sites mais utilizados') . '</th>
                                <th style="text-align: center; width: 80px">' . Yii::t("smith", 'Incidência de hora extra') . '</th>
                            </tr>';
        $i = 1;
        foreach ($colaboradores as $colaborador) {
            $nomeColaborador = Colaborador::model()->findByPk($colaborador)->nomeCompleto;
            $produtividadeCol = (isset($produtividade[$colaborador])) ? $produtividade[$colaborador] . '%' : Yii::t("smith", 'Não houve ocorrências no período');
            $custoCol = (isset($custo[$colaborador])) ? 'R$' . $custo[$colaborador] : Yii::t("smith", 'Não houve ocorrências no período');
            $horaExtraCol = (isset($horaExtra[$colaborador])) ? round($horaExtra[$colaborador]['duracao'], 2) . ' horas' : Yii::t("smith", 'Não houve ocorrências no período');
            $countContratos = isset($contratos[$colaborador]) ? count($contratos[$colaborador]) + 1 : 0;
            $html .= '<tr>
                        <td style="text-align: center; width: 90px">' . $nomeColaborador . '</td>
                        <td style="text-align: center; width: 80px">' . $produtividadeCol . '</td>
                        <td style="text-align: center; width: 70px">' . $custoCol . '</td>';
            $html .= '<td style="text-align: left; width: 220px"> ';
            if (isset($programas[$colaborador])) {
                foreach ($programas[$colaborador] as $valor) {
                    $html .= wordwrap($valor['programa'], 35, "\n", true) . '; <br>';
                }
            } else
                $html .= Yii::t("smith", 'Não houve ocorrências no período');

            $html .= '</td>';
            $html .= '<td style="text-align: left; width: 220px"> ';
            if (!empty($sites[$colaborador])) {
                foreach ($sites[$colaborador] as $valor) {
                    $html .= wordwrap($valor['programa'], 35, "\n", true) . '; <br>';
                }
            } else
                $html .= Yii::t("smith", 'Não houve ocorrências no período');

            $html .= '</td>';
            $html .= '<td style="text-align: center; width: 80px">' . $horaExtraCol . '</td>';

            $html .= '</tr>';
            //TR DE CONTRATOS
            $html .= '<tr style="background-color: #CCC; text-decoration: bold;"><th colspan="6">' . Yii::t("smith", 'Contratos envolvidos') . '</th></tr>';
            if (isset($contratos[$colaborador])) {
                $html .= '<tr><th colspan="4" >Projeto</th><th colspan="2" >Participação</th></tr>';
                foreach ($contratos[$colaborador] as $idContrato => $valor) {
                    $html .= '<tr><td colspan="4" >' . Contrato::model()->findByPk($idContrato)->nome . '</td><td colspan="2" style="text-align: center"> ' . $valor . '%' . '</td></tr>';
                }
            } else
                $html .= '<tr><td>Não há registros de participação em contratos</td></tr>';
            if (!(count($colaboradores) == $i))
                $html .= ' <tr style="background-color: #CCC; text-decoration: bold;">
                                <th style="text-align: center; width: 130px">' . Yii::t("smith", 'Colaborador') . '</th>
                                <th style="text-align: center; width: 80px">' . Yii::t("smith", 'Produtividade ') . '</th>
                                <th style="text-align: center; width: 80px">' . Yii::t("smith", 'Custo') . '</th>
                                <th style="text-align: center; width: 220px">' . Yii::t("smith", 'Programas mais utilizados') . '</th>
                                <th style="text-align: center; width: 220px">' . Yii::t("smith", 'Sites mais utilizados') . '</th>
                                <th style="text-align: center; width: 80px">' . Yii::t("smith", 'Incidência de hora extra') . '</th>
                            </tr>';
            $i++;
        }
        $html .= '</table>';
        $html .= $rodape;
        $style = MetodosGerais::getStyleTableLand();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output();

    }
}
