<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MetricaController extends Controller {

    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
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
                'actions' => array('index', 'chart'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'chart', 'delete', 'update', 'view',
                    'getCriterioDinamico', 'getPreVisualizacao', 'visualizarMetrica',
                    'getColaboradores', 'RelatorioMetrica', 'Favorito', 'detalheMetrica', 'EntradasFavorito','GetColaboradoresPorEquipe',
                    'modalFavoritar'),
                'groups' => array('empresa', 'coordenador', 'demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionDetalheMetrica($id) {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Detalhamento da métrica") . ' - ' . $this->loadModel($id)->titulo;
        $this->pageTitle = Yii::t("smith", "Detalhamento da métrica");

        $model = new Metrica('searchDetalheMetrica');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Metrica']))
            $model->attributes = $_GET['Metrica'];
        LogAcesso::model()->saveAcesso('Métricas', 'Detalhamento da métrica', 'Detalhamento da métrica', MetodosGerais::tempoResposta($start));
        $this->render('detalheMetrica', array(
            'model' => $model,
            'fkMetrica' => $id,
        ));
    }

    /**
     * @throws CHttpException
     * Action utlizada para favoritar métricas a partir da grid de index
     */
    public function actionFavorito() {
        Metrica::model()->updateAll(array('favorito' => 0), 'fk_empresa=' . MetodosGerais::getEmpresaId());
        if (!empty($_POST['selectedItens'])) {
            foreach ($_POST['selectedItens'] as $value) {
                if (!empty($value)) {
                    $modelMetrica = $this->loadModel($value);
                    $modelMetrica->favorito = 1;
                    $modelMetrica->save(false);
                }
            }
        }
    }

    public function actionChart() {
        $listaMetricaPorDia = ViewMetricaConsolidada::model()->getResultadoMetricaPorDia();

        $this->render('_chart_metrica', array('jsonMetricaPorDia' => CJSON::encode($listaMetricaPorDia)));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->title_action = Yii::t("smith", "Criar Métrica");
        $this->pageTitle = Yii::t("smith", "Criar Métrica");
        $model = new Metrica;
        $modelLogs = new LogAtividadeConsolidado();

        if (isset($_POST['Metrica'])) {
            $start = MetodosGerais::inicioContagem();

            $model->attributes = $_POST['Metrica'];
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->serial_empresa = LogAtividade::model()->getSerial();

            if ($model->meta_tempo != null) {
                $model->min_t = $model->min_t != NULL ? MetodosGerais::time_to_seconds($model->min_t . ':00') : 0;
                $model->max_t = $model->max_t != NULL ? MetodosGerais::time_to_seconds($model->max_t . ':00') : 0;
            }
            if ($model->meta_entrada == null) {
                $model->min_e = 0;
                $model->max_e = 0;
                $model->meta = 0;
            }

            // salvar historio de maximo e minimo
            $metricaHistorico = new MetricaHasLimite();
            $metricaHistorico->meta_tempo = $model->meta_tempo;
            $metricaHistorico->min_t = $model->min_t;
            $metricaHistorico->max_t = $model->max_t;
            $metricaHistorico->meta_entrada = $model->meta_entrada;
            $metricaHistorico->min_e = $model->min_e;
            $metricaHistorico->max_e = $model->max_e;
            $metricaHistorico->data = date('Y-m-d');
            $metricaHistorico->fk_empresa = MetodosGerais::getEmpresaId();
            if ($model->save()) {
                $metricaHistorico->fk_metrica = $model->id;
                $metricaHistorico->save();
                if (isset($_POST['Membros'])) {
                    foreach ($_POST['Membros'] as $id) {
                        $metricaEquipe = new ColaboradorHasMetrica;
                        $metricaEquipe->fk_metrica = $model->getPrimaryKey();
                        $metricaEquipe->fk_colaborador = $id;
                        $metricaEquipe->data = DATE('Y-m-d H:i:s');
                        $metricaEquipe->save();
                    }
                }
                LogAcesso::model()->saveAcesso('Métricas', 'Criar Métrica', 'Criar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Métrica inserida com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Métrica não pôde ser inserida.'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'modelLogs' => $modelLogs,
        ));
    }

    public function actionUpdate($id) {
        $this->title_action = Yii::t("smith", "Atualizar Métrica");
        $this->pageTitle = Yii::t("smith", "Atualizar Métrica");
        $model = $this->loadModel($id);
        $modelLogs = new LogAtividadeConsolidado();

        if (isset($_POST['Metrica'])) {
            $start = MetodosGerais::inicioContagem();
            $model->meta_tempo = 0;
            $model->meta_entrada = 0;
            $model->alerta = 0;
            $model->attributes = $_POST['Metrica'];

            if ($model->meta_tempo != 0) {
                $model->min_t = $model->min_t != NULL ? MetodosGerais::time_to_seconds($model->min_t . ':00') : 0;
                $model->max_t = $model->max_t != NULL ? MetodosGerais::time_to_seconds($model->max_t . ':00') : 0;
            }
            else if($model->meta_tempo == 0)
            {
                $model->min_t = 0;
                $model->max_t = 0;
            }
            if ($model->meta_entrada == 0) {
                $model->min_e = 0;
                $model->max_e = 0;
                $model->meta = 0;
            }

            // salvar historio de maximo e minimo
            $metricaHistorico = new MetricaHasLimite();
            $metricaHistorico->meta_tempo = $model->meta_tempo;
            $metricaHistorico->min_t = $model->min_t;
            $metricaHistorico->max_t = $model->max_t;
            $metricaHistorico->meta_entrada = $model->meta_entrada;
            $metricaHistorico->min_e = $model->min_e;
            $metricaHistorico->max_e = $model->max_e;
            $metricaHistorico->data = date('Y-m-d');
            $metricaHistorico->fk_empresa = MetodosGerais::getEmpresaId();
            $metricaHistorico->fk_metrica = $id;
            if ($model->save()) {
                $metricaHistorico->save();
                if (isset($_POST['Membros'])) {
                    ColaboradorHasMetrica::model()->deleteAllByAttributes(array('fk_metrica' => $id));
                    foreach ($_POST['Membros'] as $id) {
                        $metricaEquipe = new ColaboradorHasMetrica;
                        $metricaEquipe->fk_metrica = $model->id;
                        $metricaEquipe->fk_colaborador = $id;
                        $metricaEquipe->data = DATE('Y-m-d H:i:s');
                        $metricaEquipe->save();
                    }
                }
                LogAcesso::model()->saveAcesso('Métricas', 'Atualizar Métrica', 'Atualizar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Metrica atualizada com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Metrica não pôde ser atualizada.'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'modelLogs' => $modelLogs,
        ));
    }

    public function actionDelete($id) {
        $this->loadModel($id)->delete();
    }

    public function actionRelatorioMetrica() {
        $metrica = Metrica::model()->findByPk($_POST['id_metrica']);
        $isSite = SitePermitido::model()->findByAttributes(array('nome' => $metrica->programa, 'fk_empresa' => MetodosGerais::getEmpresaId()));
        $start = MetodosGerais::inicioContagem();
        if ($_POST['opcao'] == 'colaborador') {
            $logs = (isset($isSite)) ? LogAtividadeConsolidado::model()->getLogsMetricas($metrica->programa, $metrica->criterio, 1, $_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], 1)
                : LogAtividadeConsolidado::model()->getLogsMetricas($metrica->programa, $metrica->criterio, $metrica->sufixo, $_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], 0);
        } else {
            $logs = (isset($isSite)) ? LogAtividadeConsolidado::model()->getLogsMetricasEquipe($metrica->programa, $metrica->criterio, 1, $_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], 1)
                : LogAtividadeConsolidado::model()->getLogsMetricasEquipe($metrica->programa, $metrica->criterio, $metrica->sufixo, $_POST['date_from'], $_POST['date_to'], $_POST['selecionado'], 0);
        }


        if (!empty($logs)) {
            $this->GerarRelatorio($logs, $metrica, $_POST['date_from'], $_POST['date_to'], $_POST['opcao'], $_POST['selecionado'], $start);
        } else {
            Yii::app()->user->setFlash('error', Yii::t('smith', 'Não foram encontrados registros dessa métrica.'));
            $this->redirect(array('index'));
        }
    }

    public function GerarRelatorio($logs, $metrica, $data_inicio, $data_fim, $opcao, $selecionado, $start)
    {
        $dataInicio = MetodosGerais::dataAmericana($data_inicio);
        $dataFim = MetodosGerais::dataAmericana($data_fim);
        // Calcular Dias Uteis
        $dias_uteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));

        $serial = LogAtividade::model()->getSerial();
        if ($selecionado != 'todos_colaboradores' && $selecionado != 'todas_equipes' && $opcao == 'colaborador') {
            $responsavel = '<span><b>Colaborador: ' . Colaborador::model()->findByAttributes(array("ad" => $selecionado, "serial_empresa" => $serial))->nome . '</b></span><br>';
            $selecionado = Colaborador::model()->findByAttributes(array("ad" => $selecionado, "serial_empresa" => $serial))->nome;
        }
        if ($selecionado != 'todos_colaboradores' && $selecionado != 'todas_equipes' && $opcao == 'equipe') {
            $responsavel = '<span><b>Equipe: ' . Equipe::model()->findByPk($selecionado)->nome . '</b></span><br>';
            $selecionado = Equipe::model()->findByPk($selecionado)->nome;
        }
        $fk_empresa = MetodosGerais::getEmpresaId();
        $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
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
                        <img class="header_logo_page" src="' . $imagem . '">
                        <div class="header_title">
                            <span>' . Yii::t('smith', 'RELATÓRIO DE ACOMPANHAMENTO DE MÉTRICAS') . '</span><br>
                            <span style="font-size: 10px">' . Yii::t('smith', 'No período de') . ' ' . $data_inicio . ' ' . Yii::t('smith', 'até') . ' ' . $data_fim . ' </span>
                        </div>
                        <span><b>' . Yii::t('smith', 'Métrica') . ': ' . $metrica->titulo . '</b></span><br>
                        <span><b>' . Yii::t('smith', 'Área de atuação') . ': ' . $metrica->atuacao . '</b></span><br>
                        <span><b>' . Yii::t('smith', 'Aplicação Medida') . ': ' . $metrica->programa . '</b></span><br>
                        <span><b>' . Yii::t('smith', 'Critério de filtragem') . ': ' . $criterio . '</b></span><br>

                            ' . $responsaveis . '
                        <div class="header_date">
                        <p>Data:  ' . date("d/m/Y") . '
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

        $ini = str_replace('/', '-', $data_inicio);
        $fim = str_replace('/', '-', $data_fim);

        if ($selecionado == 'todas_equipes' || $selecionado == 'todos_colaboradores')
            $selecionado = explode('_', $selecionado)[0];

        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output('acompanhamentoMetricas_' . $opcao . '_' . $selecionado . '_' . $ini . '_ate_' . $fim . '.pdf');
        LogAcesso::model()->saveAcesso('Métricas', 'Relatório métrica', 'Relatório métrica', MetodosGerais::tempoResposta($start));
    }

    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Métricas");
        $this->pageTitle = Yii::t("smith", "Métricas");

        $model = new Metrica('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Metrica']))
            $model->attributes = $_GET['Metrica'];
        LogAcesso::model()->saveAcesso('Métricas', 'Gerenciar métricas', 'Métricas', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /*
     * Action auxiliar para carregar valores no input de criterio, exibindo os 10 primeiros resultados da pesquisa.
     */
    public function actionGetCriterioDinamico() {
        if (isset($_POST['criterio'])) {
            $isSite = SitePermitido::model()->findByAttributes(array('nome' => $_POST['programa'], 'fk_empresa' => MetodosGerais::getEmpresaId()));
            $criterios = (isset($isSite)) ? LogAtividadeConsolidado::model()->getCriterioDinamico($_POST['programa'], $_POST['criterio'], 1, 1)
                : LogAtividadeConsolidado::model()->getCriterioDinamico($_POST['programa'], $_POST['criterio'], $_POST['sufixo'], 0);
            $html = "";
            if ((count($criterios) > 0)) {
                $html = '<div id="resultados"><p><b>10 ' . Yii::t('smith', 'primeiros resultados') . ':</b></p>';
                $i = 0;
                foreach ($criterios as $criterio) {
                    $html.='<p><a id="p_cliente_' . $i . '" href="#" onclick="carregarCriterio(this.text)">  ' . $criterio['descricao'] . '</a></p>';
                    $i++;
                }
            } else {
                $html.= '<p><i>' . Yii::t('smith', 'Não foram encontrados resultados para:') . ' "' . $_POST['criterio'] . '"<i></p>';
            }
            $html.='</div>';
            echo $html;
        }
    }

    /**
     * Action auxiliar para carregar dados na grid de pre-visualização no create;
     */
    public function actionGetPreVisualizacao() {
        $html = "";
        if (isset($_POST['total']) && $_POST['total'] != 0) {
            $isSite = SitePermitido::model()->findByAttributes(array('nome' => $_POST['programa'], 'fk_empresa' => MetodosGerais::getEmpresaId()));
            $total = (isset($isSite)) ? LogAtividadeConsolidado::model()->getTotalGridPreVisualizar($_POST['programa'], $_POST['criterio'], 1, 1)
                : LogAtividadeConsolidado::model()->getTotalGridPreVisualizar($_POST['programa'], $_POST['criterio'], $_POST['sufixo'], 0);
            $html .= '<input value=' . $total->total . ' type="hidden" id="totalResultados">';
        }
        if (isset($_POST['criterio']) && $_POST['criterio'] != '') {
            $isSite = SitePermitido::model()->findByAttributes(array('nome' => $_POST['programa'], 'fk_empresa' => MetodosGerais::getEmpresaId()));
            $criterios = (isset($isSite)) ? LogAtividadeConsolidado::model()->getPreVisualizacao($_POST['programa'], $_POST['criterio'], 1, 1, $_POST['qtd'])
                : LogAtividadeConsolidado::model()->getPreVisualizacao($_POST['programa'], $_POST['criterio'], $_POST['sufixo'], 0, $_POST['qtd']);
        } elseif (isset($_POST['programa']) && $_POST['programa'] != '') {
            $isSite = SitePermitido::model()->findByAttributes(array('nome' => $_POST['programa'], 'fk_empresa' => MetodosGerais::getEmpresaId()));
            $criterios = (isset($isSite)) ? LogAtividadeConsolidado::model()->getPreVisualizacao($_POST['programa'], '', 1, 1, $_POST['qtd'])
                : LogAtividadeConsolidado::model()->getPreVisualizacao($_POST['programa'], '', 1, 0, $_POST['qtd']);
        }
        if ((count($criterios) > 0)) {
            $html .= '<div id="resultados"><p><b>10 ' . Yii::t('smith', 'primeiros resultados') . ':</b></p>';
            $i = 0;
            foreach ($criterios as $criterio) {
                $html .= '<p><a id="p_criterio_' . $i . '" href="#" onclick="carregarCriterio(this.text)">  ' . $criterio['descricao'] . '</a></p>';
                $html .= '<p><a id="p_usuario_' . $i . '" href="#" onclick="carregarCriterio(this.text)">  ' . $criterio['usuario'] . '</a></p>';
                $html .= '<p><a id="p_duracao_' . $i . '" href="#" onclick="carregarCriterio(this.text)">  ' . $criterio['duracao'] . '</a></p>';
                $html .= '<p><a id="p_data_' . $i . '" href="#" onclick="carregarCriterio(this.text)">  ' . MetodosGerais::dataBrasileira($criterio['data']) . '</a></p>';
                $i++;
            }
            $html .= '<input value=' . $i . ' type="hidden" id="qtdCriterio">';
        } else {
            $html .= '<p><i>' . Yii::t('smith', 'Não foram encontrados resultados para:') . ' "' . $_POST['criterio'] . '"<i></p>';
        }
        $html .= '</div>';
        echo $html;
    }

    /**
     * Carregar os colaboradores e equipes do dropdown de pesquisa do relatório
     */
    public function actionGetColaboradores() {
        $fk_empresa = MetodosGerais::getEmpresaId();
        if ($_POST['opcao'] == 'colaborador') {

            $dados = array();
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa), array("order" => "nome ASC"));

            $dados = CHtml::listData($colaboradores, 'id', 'nome');
            echo CHtml::tag('option', array('value' => 'todos_colaboradores'), CHtml::encode('Todos'), true);
            foreach ($colaboradores as $d) {
                echo CHtml::tag('option', array('value' => $d->ad), CHtml::encode($d->nomeCompleto), true);
            }
        } elseif ($_POST['opcao'] == 'equipe') {
            $dados = array();
            $colaboradores = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa), array("order" => "nome ASC"));

            $dados = CHtml::listData($colaboradores, 'id', 'nome');
            echo CHtml::tag('option', array('value' => 'todas_equipes'), CHtml::encode('Todas'), true);
            foreach ($colaboradores as $d) {
                echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->nome), true);
            }
        }
    }

    public function loadModel($id) {
        $model = Metrica::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /**
     * Action utilizada para exibir as métrica definidas como favorita nos quadros da página inicial.
     */
    public function actionEntradasFavorito() {
        $favorito = Metrica::model()->findAllByAttributes(array('favorito' => 1, 'fk_empresa' => MetodosGerais::getEmpresaId()), array('limit' => 4));
        $arrayFavoritos = array();
        for ($i = 0; $i < 4; $i++) {
            $entradas = (isset($favorito[$i]->id)) ? (isset(MetricaConsolidada::model()->getEntradasMetricas($favorito[$i]->id)->entradas)) ? MetricaConsolidada::model()->getEntradasMetricas($favorito[$i]->id)->entradas : 0 : 0;
            $arrayFavoritos[$i]['titulo'] = (isset($favorito[$i]->titulo)) ? $favorito[$i]->titulo : Yii::t('smith', 'Clique aqui para definir uma métrica');
            $arrayFavoritos[$i]['entradas'] = $entradas;
            $arrayFavoritos[$i]['url'] = (isset($favorito[$i]->id)) ? Yii::app()->createUrl("metrica/detalheMetrica/{$favorito[$i]->id}") : "#favoritarMetricaModal";

        }
        echo json_encode($arrayFavoritos);
    }

    /**
     *  Action auxiliar para carregar os colaboradores que são associados a métrica
     */
    public function actionGetColaboradoresPorEquipe() {
        $fk_empresa = MetodosGerais::getEmpresaId();
        if (isset($_POST['equipe']) && $_POST['equipe'] != '') {
            $condicao = "fk_empresa=$fk_empresa AND id = " . MetodosGerais::getEquipe();
            if (Yii::app()->user->groupName != 'coordenador') {
                $condicao = ' fk_empresa = ' . $fk_empresa;
                foreach ($_POST['equipe'] as $key => $equipe)
                    ($key < 1) ? $condicao .= ' AND fk_equipe = ' . $equipe : $condicao .= ' OR fk_equipe = ' . $equipe;
            }
            $colaboradores = Colaborador::model()->findAll(array('condition' => $condicao, "order" => "nome ASC"));

           foreach ($colaboradores as $d) {
                echo '<option value="'.$d->id.'"> '.$d->getNomeCompleto().' </option>';

            }
        }
        elseif(isset($_POST['metrica'])){
            $colaboradores = ColaboradorHasMetrica::model()->with('colaborador')->findAllByAttributes(array('fk_metrica' => $_POST['metrica']));
            $arrayEquipes = [];

            foreach ($colaboradores as $d) {
                $arrayEquipes[] = $d->colaborador->fk_equipe;
                echo '<option value="'.$d->fk_colaborador.'"> '.MetodosGerais::reduzirNome($d->colaborador->nomeCompleto).' </option>';

            }
            $arrayEquipes = array_unique($arrayEquipes);
            $stringEquipes = '';
            foreach ($arrayEquipes as $key => $value)
                ($key < count($arrayEquipes)) ? $stringEquipes .= $value . ',' : $stringEquipes .= $value;
            echo '|<input type="hidden" value="' . $stringEquipes . '" id="fk_equipe">';
        } else {
            echo 'not';
        }
    }


    function actionModalFavoritar(){
        if(isset($_POST['metrica'])){
            $metrica_id = $_POST['metrica'];
            $model = Metrica::model()->findByPk($metrica_id);
            $model->favorito = 1;
            $model->save(false);
            return true;
        }
        return false;
    }
}
