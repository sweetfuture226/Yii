<?php

class ProgramasSitesController extends Controller
{
    public $title_action = "";

    public function filters()
    {
        return array(
            'userGroupsAccessControl'
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'view' action
                'actions' => array('RelatorioGeral', 'RelatorioIndividual'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionRelatorioGeral()
    {
        $this->pageTitle = Yii::t("smith", "Relatório geral");
        $this->title_action = Yii::t("smith", "Relatório geral");
        if (isset($_POST['selecionado'])) {
            $start = MetodosGerais::inicioContagem();

            if (strtotime(MetodosGerais::dataAmericana($_POST['date_to'])) > strtotime(MetodosGerais::dataAmericana($_POST['date_from']))) {
                $dataInicio = strtotime(MetodosGerais::dataAmericana($_POST['date_from']));
                $dataFim = strtotime(MetodosGerais::dataAmericana($_POST['date_to']));
                $dias_uteis = MetodosGerais::dias_uteis($dataInicio, $dataFim);

                $atividadesExternas = AtividadeExterna::model()->getTempoAtividadeExterna($_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], $_POST['opcao']);
                $producao = LogAtividade::model()->getTempoProduzidoByAtributos($_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], $_POST['opcao']);
                $sitesProducao = LogAtividade::model()->getTempoProduzidoSitesByAtributos($_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], $_POST['opcao']);
                $ocioso = LogAtividade::model()->getTempoTotalEquipe($_POST['selecionado'], $_POST['opcao']);
                $nao_identificado = LogAtividade::model()->getTempoProgramaNaoIdentificado($_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], $_POST['opcao']);
                $site_nao_identificado = LogAtividade::model()->getTempoSiteNaoIdentificado($_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], $_POST['opcao']);

                if ($_POST['opcao'] == 'equipe')
                    $title_grafico = ($_POST['selecionado'] != 'todas_equipes') ? Equipe::model()->findByPk($_POST['selecionado'])->nome : 'todas equipes';
                elseif ($_POST['opcao'] == 'colaborador')
                    $title_grafico = ($_POST['selecionado'] != 'todos_colaboradores') ? Colaborador::model()->findByPk($_POST['selecionado'])->nome : 'todos colaboradores';

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

                    $splitedNaoIdent = array_chunk($nao_identificado, 5);
                    $somaOutrosPNI = 0;
                    foreach ($splitedNaoIdent[0] as $key => $value) {
                        $somaOutrosPNI += $value['duracao'];
                        array_push($regProgramaNaoIdent, array('name' => $value['descricao'], 'y' => (float)$value['duracao']));
                    }
                    array_push($regProgramaNaoIdent, array('name' => 'Outros', 'y' => (float)($somaNaoIdentificado - $somaOutrosPNI)));
                }

                /* Sites não identificados*/
                if (!empty($site_nao_identificado)) {
                    foreach ($site_nao_identificado as $value) {
                        $somaSiteNaoIdentificado += $value['duracao'];
                        $arrayCategoriasSiteNaoIdentificado[] = $value['descricao'];
                        array_push($dataSiteNaoIdentificado, (float)$value['duracao']);
                    }
                    $splitedSiteNaoIdent = array_chunk($site_nao_identificado, 5);
                    $somaOutrosSNI = 0;
                    foreach ($splitedSiteNaoIdent[0] as $key => $value) {
                        $somaOutrosSNI += $value['duracao'];
                        array_push($regSiteNaoIdent, array('name' => $value['descricao'], 'y' => (float)$value['duracao']));
                    }
                    array_push($regSiteNaoIdent, array('name' => 'Outros', 'y' => (float)($somaSiteNaoIdentificado - $somaOutrosSNI)));
                }
                /* Programas Permitidos */
                if (!empty($producao)) {
                    foreach ($producao as $key => $value) {
                        $somaProduzido += $value['duracao'];
                        $arrayCategoriasProduzido[] = $value['programa'];
                        array_push($dataProduzido, (float)$value['duracao']);
                    }
                    $splitedProgramas = array_chunk($producao, 5);
                    $somaOutrosPrograma = 0;
                    foreach ($splitedProgramas[0] as $key => $value) {
                        $somaOutrosPrograma += $value['duracao'];
                        array_push($regPrograma, array('name' => $value['programa'], 'y' => (float)$value['duracao']));
                    }
                    array_push($regPrograma, array('name' => 'Outros', 'y' => (float)($somaProduzido - $somaOutrosPrograma)));
                }
                /* Atividades externas*/
                if (!empty($atividadesExternas)) {
                    foreach ($atividadesExternas as $value) {
                        $somaAtividadeExterna += $value->duracao;
                        array_push($dataAtividadeExterna, (float)$value->duracao);
                    }
                    $splitedAtivExt = array_chunk($atividadesExternas, 5);
                    $somaOutrosAtivExt = 0;
                    foreach ($splitedAtivExt[0] as $key => $value) {
                        $somaOutrosAtivExt += $value->duracao;
                        array_push($regAtivExt, array('name' => $value->descricao, 'y' => (float)$value->duracao));
                    }
                    array_push($regAtivExt, array('name' => 'Outros', 'y' => (float)($somaAtividadeExterna - $somaOutrosAtivExt)));
                }
                /* Ocioso */
                $somaOcioso = ($ocioso[0]['tempo_total'] * $dias_uteis) - ($somaProduzido + $somaSites + $somaNaoIdentificado + $somaSiteNaoIdentificado + $somaAtividadeExterna);
                array_push($dataOcioso, (float)$somaOcioso);
                array_push($regOcioso, array('name' => 'Ocioso', 'y' => (float)$somaOcioso));

                $empresaId = MetodosGerais::getEmpresaId();
                $registros = array(
                    'Site Não Identificado' => array($dataSiteNaoIdentificado, $arrayCategoriasSiteNaoIdentificado),
                    'Programas' => array($dataProduzido, $arrayCategoriasProduzido),
                    'Sites' => array($dataSites, $arrayCategoriasSites),
                    'Atividade Externa' => array($dataAtividadeExterna, array('Atividade Externas')),
                    'Não Identificado' => array($dataNaoIdentificado, $arrayCategoriasNaoIdentificado),
                    'Ausente do computador' => array($dataOcioso, array('Ausente do computador')));

                $periodoDatas = ' entre ' . $_POST['date_from'] . ' e ' . $_POST['date_to'];

                $tempoResposta = MetodosGerais::tempoResposta($start);
                LogAcesso::model()->saveAcesso('Programas e Sites', 'Relatório geral', 'Relatório geral', $tempoResposta);
                $seriesArray = array($regPrograma, $regSites, $regAtivExt, $regProgramaNaoIdent, $regSiteNaoIdent, $regOcioso);
                // Export CSV
                if (!empty($_POST['button'])) {
                    $colunas = array('65' => 'Categoria', '66' => 'Programa/Site', '67' => 'Duração');
                    $titulo = 'Relatório geral de ' . $title_grafico . $periodoDatas;
                    $filename = 'RelatorioGeral.' . $_POST['button'];
                    MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relGeral', $_POST['button']);
                } // Gráfico
                else {
                    $this->render('grfRelatorioGeral', array(
                        'heightGrafico' => '600',
                        'somaAtividadeExterna' => $somaAtividadeExterna,
                        'somaProduzido' => $somaProduzido,
                        'somaOcioso' => $somaOcioso,
                        'somaNaoIdentificado' => $somaNaoIdentificado,
                        'somaSiteNaoIdentificado' => $somaSiteNaoIdentificado,
                        'somaSites' => $somaSites,
                        'seriesArray' => $seriesArray,
                        'periodoDatas' => $periodoDatas,
                        'title' => $title_grafico,
                        'empresaId' => $empresaId
                    ));
                }

            } else {
                Yii::app()->user->setFlash('warning', Yii::t('smith', 'Por favor, verifique se o periodo de datas está correto'));
                $this->redirect(array('relatorioGeral'));
            }
        } else {
            $equipe = "";
            $this->render('relatorioGeral', array("equipe" => $equipe));
        }
    }


    public function actionRelatorioIndividual()
    {
        $this->title_action = Yii::t("smith", 'Relatório individual');
        $this->pageTitle = Yii::t("smith", "Relatório individual");
        $fk_empresa = MetodosGerais::getEmpresaId();

        $condicao = ' fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();
        }
        if (!empty($_POST)) {
            $start = MetodosGerais::inicioContagem();
            ($_POST['button'] == 'zip') ? $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'status' => 1)) : $colaboradores = Colaborador::model()->findAllByPk($_POST['colaborador_id']);
            $resultado = $this->getRegistrosRelatorioIndividual($colaboradores, $_POST, $start);
            if ($resultado) {
                Yii::app()->user->setFlash('warning', Yii::t('smith', 'Este colaborador não teve produtividade nesta data.'));
                $this->render('relatorioIndividual', array("condicao" => $condicao));
            }
        } else
            $this->render("relatorioIndividual", array("condicao" => $condicao));
    }

    public function getRegistrosRelatorioIndividual($colaborador, $dadosView, $start)
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $arrayPDF = array();
        $tmp = 0;
        foreach ($colaborador as $objColaborador) {
            $produtivo = LogAtividade::model()->getProgramasProdutivosDia($objColaborador->id, $dadosView['data']);
            if (!empty($produtivo)) {
                $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
                $almocoInicio = MetodosGerais::setHoraServidor($parametros->almoco_inicio);
                $almocoFim = MetodosGerais::setHoraServidor($parametros->almoco_fim);
                $produtivoAlmoco = LogAtividade::model()->getProgramasProdutivosAlmoco($objColaborador->id, $dadosView['data'], $almocoInicio, $almocoFim);
                $improdutivo = LogAtividade::model()->getProgramasNaoProdutivosDia($objColaborador->id, $dadosView['data']);
                $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));
                $horarios = LogAtividade::model()->getHorarioEntrada($objColaborador->id, $dadosView['data']);
                $horario_entrada = $horarios[0]['hora_host'];
                $horario_saida = array_pop($horarios);
                $horario_saida = $horario_saida['hora_host'];
                //$horario_entrada = MetodosGerais::getHoraServidor($horario_entrada, "");
                //$horario_saida = MetodosGerais::getHoraServidor($horario_saida, "");

                $duracaoAlmoco = MetodosGerais::time_to_seconds($almocoFim) - MetodosGerais::time_to_seconds($almocoInicio);
                $ociosoAlmoco = LogAtividade::model()->getTempoAlmoco($objColaborador->id, $dadosView['data'], $almocoFim);
                $ociosoAlmoco = $this->calcularOcioAlmoco($ociosoAlmoco, $duracaoAlmoco);

                if (strtotime($parametros->almoco_fim) > strtotime($horario_saida))
                    $duracaoAlmoco = MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($parametros->almoco_inicio);


                $colecaoSites = array();
                foreach ($sitesPermitidos as $value) {
                    $sitesProdutivos = LogAtividade::model()->getSitesProdutivos($objColaborador->id, $dadosView['data'], $value->nome);
                    if (!empty($sitesProdutivos))
                        array_push($colecaoSites, array($value->nome => $sitesProdutivos));
                }


                $ocioso = LogAtividade::model()->getOciosoDia($objColaborador->id, $dadosView['data']);

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
                                $sites .= "'" . $site['descricao'] . "'";
                            else
                                $sites .= "'" . $site['descricao'] . "',";
                            $j++;
                        }
                        $i++;
                    }
                }

                $sitesImprodutivos = LogAtividade::model()->getSitesImprodutivos($objColaborador->id, $dadosView['data'], $sites);

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
                array_push($colecaoImprodutivo, $total_parcial_improdutivo);
                array_push($colecaoAlmoco, $total_parcial_almoco);
                array_push($sitesColecao, $total_parcial_sites);
                array_push($colecaoSitesImprodutivos, $total_parcial_sites_improdutivos);

                // Exportar CSV
                if ($dadosView['button'] == 'csv' || $dadosView['button'] == 'xlsx') {
                    $tempoOcioso = $this->calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $total_parcial_improdutivo, $colecaoSitesImprodutivos, (MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($horario_entrada)));
                    $nomeColaborador = Colaborador::model()->findByPk($objColaborador->id)->nomeCompleto;
                    $registros = array($colecaoProgramas, $sitesColecao, $colecaoAtivExterna, $colecaoImprodutivo, $colecaoSitesImprodutivos, $colecaoAlmoco, $tempoOcioso, $duracaoAlmoco);
                    MetodosCSV::ExportCSVRelIndividualCol($registros, $nomeColaborador, $dadosView['data'], $horario_entrada, $horario_saida, $dadosView['button']);
                } // PDF
                else {
                    $tipo = ($dadosView['button'] == 'zip') ? 1 : 0;
                    $caminhoPDF = $this->getRelatorioIndividual($colecaoAtivExterna, $horario_entrada, $horario_saida, $colecaoProgramas, $colecaoImprodutivo, $ocioso, $sitesColecao, $colecaoSitesImprodutivos, $objColaborador->id, $dadosView['data'], $colecaoAlmoco, $duracaoAlmoco, $ociosoAlmoco, $tipo, $start);
                    array_push($arrayPDF, $caminhoPDF);
                }
            } else {
                $tmp += 1;
            }
        }
        if (!empty($arrayPDF)) {
            $src = dirname(Yii::app()->request->scriptFile) . '/public/';
            $nomeZip = 'relatoriosIndividuais' . '.zip';
            $result = MetodosGerais::create_zip($arrayPDF, $nomeZip);
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

                foreach ($arrayPDF as $filename) {
                    flush();
                    readfile($src . $filename);
                    unlink($src . $filename);
                }
                flush();
                readfile($src . $nomeZip);
                unlink($src . $nomeZip);
            }
        }
        return $tmp;
    }

    public function getRelatorioIndividual($colecaoAtivExterna, $horario_entrada, $horario_saida, $colecaoProgramas, $colecaoImprodutivo, $ocioso, $sitesColecao, $colecaoSitesImprodutivos, $colaborador, $data, $colecaoAlmoco, $duracaoAlmoco, $ociosoAlmoco, $tipo, $start)
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
        $colaborador = Colaborador::model()->findByPk($colaborador)->nomeCompleto;
        $diff = (MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($horario_entrada));
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
            <page_header>
            <div class="header_page">
            <img class="header_logo_page" src="' . $imagem . '">
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
        /*$tempoOcioso = (!empty($ocioso)) ? $ocioso[0]['duracao'] : 0;
        if ($ociosoAlmoco)
            $tempoOcioso -= $duracaoAlmoco;
        $total_parcial_site = !empty($sitesColecao) ? $sitesColecao['0'] : '00:00:00';
        $total_parcial = $colecaoProgramas['0'] + $total_parcial_site + $total_parcial_improdutivo + $colecaoSitesImprodutivos['0'] + $tempoOcioso + $duracaoAlmoco;
        if ($diff > $total_parcial) {
            $restante = $diff - $total_parcial;
            $tempoOcioso += $restante;
        }*/
        $total_parcial_site = !empty($sitesColecao) ? $sitesColecao['0'] : '00:00:00';
        $tempoOcioso = $this->calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $total_parcial_improdutivo, $colecaoSitesImprodutivos, $diff);

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
        $data = str_replace('/', '-', $data);
        $colaborador = MetodosGerais::reduzirNome($colaborador);
        $colaborador = explode(' ', $colaborador);
        $colaborador = $colaborador[0] . $colaborador[1];
        $nomeRelatorio = 'relatorioIndividualColaborador_' . $colaborador . '_' . $data . '.pdf';
        $caminhoArquivo = dirname(Yii::app()->request->scriptFile) . '/public/' . $nomeRelatorio;
        LogAcesso::model()->saveAcesso('Programas e Sites', 'Relatório individual', 'Relatório individual', MetodosGerais::tempoResposta($start));
        if ($tipo) {
            $html2pdf->Output($caminhoArquivo, 'F');
            return $nomeRelatorio;
        } else {
            $html2pdf->Output(Yii::t('smith', 'relatorioIndividualColaborador') . '_' . $colaborador . '_' . $data . '.pdf');
        }
    }

    public function calcularOcioAlmoco($ociosoAlmoco, $duracaoAlmoco)
    {
        if (!empty($ociosoAlmoco)) {
            if ($ociosoAlmoco[0]->descricao == 'Ausente do computador') {
                return true;
            } else
                return 0;
        } else
            return 0;

    }

    private function calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $total_parcial_improdutivo, $colecaoSitesImprodutivos, $diff)
    {
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



}