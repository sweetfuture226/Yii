<?php

class DashboardController extends Controller
{
    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
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
                'actions' => array('index'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->title_action = Yii::t("smith", "Página Inicial");
        $this->pageTitle = Yii::t("smith", "Página Inicial");
        $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $start = MetodosGerais::inicioContagem();

        if ($usuario->implantationIsAfterDays(7)) {
            $notificacao = Notificacao::model()->getNotificacoes($usuario->id, Notificacao::$TP_IMPLANTATION_AFTER_DAYS);
            if ($notificacao == null & !Contrato::model()->hasObraByEmpresa($usuario->fk_empresa))
                Notificacao::model()->notifyUser($usuario->id, Notificacao::$TP_IMPLANTATION_AFTER_DAYS);
        }

        $dataInicio = date('Y-m-' . 1);
        $today = date('Y-m-d');

        $dataFim = date('Y-m-d', time() - (3600 * 27));
        if (strtotime($today) == strtotime($dataInicio))
            $dataInicio = date('Y-m-d', strtotime('-1 months'));

        $dias_uteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));

        $logProgramaConsolidado = new LogProgramaConsolidado();
        $logProgramaConsolidado->unsetAttributes();  // clear any default values
        if (isset($_GET['LogProgramaConsolidado']))
            $logProgramaConsolidado->attributes = $_GET['LogProgramaConsolidado'];

        $dadosGraficoEquipe = $this->getProdutividadeEquipeDashboard($dataInicio, $dataFim);
        $resultPrograma = GrfProgramaConsolidado::model()->graficoProdutividade($dataInicio, $dataFim, $usuario->fk_empresa);
        $somaSite = $somaPrograma = $somaNao_identificado = 0;
        $extras = array();
        $countSite = $countPrograma = $countNao_identificado = $outrosSite = $outrosPrograma = $outrosNao_identificado = $outrosAtividadeExtra = 0;

        $atividadesExternas = AtividadeExterna::model()->getTempoAtividadeExterna($dataInicio, $dataFim, "", "");

        $totalPrograma = $programa = array();
        $totalP = 0;

        $empresa = Empresa::model()->findByPk($usuario->fk_empresa);
        $sql = "SELECT nome , SUM(TIME_TO_SEC(horas_semana)/5)/3600 as tempo_total FROM colaborador
                    WHERE serial_empresa like '$empresa->serial' AND ativo = 1";
        $command = Yii::app()->getDb()->createCommand($sql);
        $ocioso = $command->queryAll();

        foreach (array_reverse($resultPrograma) as $value) {
            $variavel = 'soma' . ucfirst($value->categoria);
            $$variavel += $value->duracao;

            $variavel2 = 'count' . ucfirst($value->categoria);
            $$variavel2++;

            $nomeCategoria = $value->categoria == 'nao_identificado' ? 'Não identificado' : ucfirst($value->categoria);

            if ($$variavel2 <= 5) {
                $extras[$nomeCategoria][] = array(
                    'name' => $value->programa,
                    'y' => (float)$value->duracao
                );
            } else {
                $variavel3 = 'outros' . ucfirst($value->categoria);
                $$variavel3 += $value->duracao;

                $extras[$nomeCategoria][5] = array('name' => 'Outros', 'y' => (float)$$variavel3);
            }

            $totalPrograma[$value->programa] = (float)$value->duracao;
            $programa[$value->programa] = $value->categoria;
            $totalP += (float)$value->duracao;
        }

        if (!empty($atividadesExternas)) {
            foreach ($atividadesExternas as $key => $value) {
                if ($key <= 5) {
                    $extras['Atividades externas'][] = array(
                        'name' => $value->descricao,
                        'y' => (float)$value->duracao
                    );
                } else {
                    $outrosAtividadeExtra += $value->duracao;

                    $extras['Atividades externas'][5] = array('name' => 'Outros', 'y' => (float)$outrosAtividadeExtra);
                }
            }
        } else {
            $extras['Atividades externas'][0] = array('name' => 'Atividades Externas', 'y' => (float)0);
        }

        $extras['Ausente do computador'][0] = array('name' => 'Ausente do computador', 'y' => (float)$ocioso[0]['tempo_total']);
        $ociosoTempo = (isset($ocioso[0]['tempo_total'])) ? ($ocioso[0]['tempo_total'] * $dias_uteis) - ($totalP) : 0;
        $tempoAtivExt = (empty($atividadesExternas)) ? 0 : ($atividadesExternas[0]->duracao / 3600);
        $totalP += $ociosoTempo;
        $totalP += $tempoAtivExt;
        $graficoPrograma = "";
        $divisor = (float)($totalP);
        foreach ($programa as $programa => $categoria) {
            $porcentagem = ($divisor == 0) ? 0 : round((((float)($totalPrograma[$programa]) * 100) / $divisor), 2);
            $graficoPrograma .= "\xA" . Yii::t('smith', $this->formatarCategoria($categoria)) . "- {$programa}\t{$porcentagem}%";
        }
        $porcentagem = ($divisor == 0) ? 0 : round((((float)($ociosoTempo) * 100) / $divisor), 2);
        $porcentagemAtivExt = ($divisor == 0) ? 0 : round((((float)($tempoAtivExt) * 100) / $divisor), 2);
        $graficoPrograma .= "\xA" . Yii::t('smith', "Atividades externas") . "- Atividades \t{$porcentagemAtivExt}%";
        /*
         * If requerido para o SENAI
         */
        if ($usuario->fk_empresa == 4)
            $graficoPrograma .= "\xA" . Yii::t('smith', "Atividades externas") . "- Atividades \t{$porcentagem}%";
        else
            $graficoPrograma .= "\xA" . Yii::t('smith', "Ausente do computador") . "- Ausente do computador \t{$porcentagem}%";

        $ranking = new GrfProdutividadeConsolidado();
        $ranking->unsetAttributes();  // clear any default values
        if (isset($_GET['ranking']))
            $ranking->attributes = $_GET['ranking'];

        $grf3 = array(
            'programa' => $somaPrograma,
            'site' => $somaSite,
            'nao_identificado' => $somaNao_identificado,
            'ocioso' => $ociosoTempo,
            'externa' => $tempoAtivExt
        );

        $dadosGraficoContratos = $this->getProdutividadeContratoDashboard($dataInicio, $dataFim);
        LogAcesso::model()->saveAcesso('Dashboard', 'Página Inicial', 'Página Inicial', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'logProgramaConsolidado' => $logProgramaConsolidado,
            'dadosGraficoEquipe' => $dadosGraficoEquipe,
            'graficoPrograma' => $graficoPrograma,
            'ranking' => $ranking,
            'dataProviderContrato' => $dadosGraficoContratos,
            'idEmpresa' => $usuario->fk_empresa,
            'grafico3' => $grf3,
            'extras' => $extras
        ));
    }

    /**
     * @param $dataInicio
     * @param $dataFim
     * @return array|CArrayDataProvider
     *
     * Método auxiliar para calcular tempo produzido dos contratos exibido na grid de
     * top 10 contratos.
     */
    public function getProdutividadeContratoDashboard($dataInicio, $dataFim)
    {
        $contratos = GrfProjetoConsolidado::model()->getContratosTop10($dataInicio, $dataFim);
        $arrayDataProvider = array();
        $i = 0;
        foreach ($contratos as $value) {
            $colaboradores = GrfProjetoConsolidado::model()->getTempoColaboradoresContratos($value->fk_obra, $dataInicio, $dataFim);
            $precoContrato = 0;
            foreach ($colaboradores as $col) {
                $colaborador = Colaborador::model()->findByPk($col->fk_colaborador);
                if ($colaborador->horas_semana != NULL) {
                    $horasSemanal = ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) * 4);
                    $valorHora = ($horasSemanal != 0) ? $colaborador->salario / $horasSemanal : 0;
                    $precoColaborador = round(($col->duracao / 3600) * $valorHora, 2);
                    $precoContrato += $precoColaborador;
                }
            }
            $duracaoTotal = MetodosGerais::formataTempo($value->duracao);
            array_push($arrayDataProvider, array('id' => $i, 'contrato' => $value->nome, 'codigo' => $value->codigo, 'duracao' => $duracaoTotal, 'valorContrato' => MetodosGerais::float2real($precoContrato)));
            $i++;
        }
        $arrayDataProvider = new CArrayDataProvider($arrayDataProvider, array(
            'id' => 'id'
        ));
        return $arrayDataProvider;
    }


    /**
     * @param $dataInicio
     * @param $dataFim
     * @return array
     *
     * Método auxiliar para calcular a produtividade das equipes exibida no relatório de barras.
     */
    public function getProdutividadeEquipeDashboard($dataInicio, $dataFim)
    {
        $empresaId = MetodosGerais::getEmpresaId();
        $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($dataInicio, $dataFim, $empresaId, "");
        if (!empty($resultEquipe)) {
            $options = $categoriaEquipe = $categorias = $produzidoEquipe = $produzido = array();
            foreach ($resultEquipe as $value) {
                $obj = GrfProdutividadeConsolidado::model()->getQuantidadeDiasTrabalhadosPorColaborador($value->fk_colaborador, $dataInicio, $dataFim);
                $colaboradorDuracao = $value->duracao;
                $porcentagemCol = round(($colaboradorDuracao * 100) / ($value->hora_total * $obj->dias_trabalhados), 2);
                $produzidoEquipe[$value->fk_equipe]['data'][] = $porcentagemCol;
                $categoriaEquipe[$value->fk_equipe][] = Colaborador::model()->findByPk($value->fk_colaborador)->nomeCompleto;
            }
            $produzido = array('name' => Yii::t('smith', 'Produtividade'), 'type' => 'bar', 'yAxis' => 0);
            $meta = array("name" => Yii::t('smith', "Meta"), "type" => "spline");
            foreach ($produzidoEquipe as $key => $value) {
                $equipe = Equipe::model()->findByPk($key);
                $options[$key] = $equipe->nome;
                $meta['data'][] = (float)$equipe->meta;
                $categorias[] = $equipe->nome;
                $total = array_sum(array_values($value['data']));
                $produzido['data'][] = round($total / count($value['data']), 2);
                $produzidoEquipe[$key]['name'] = $equipe->nome;
            }

            $dadosGrafico = array($produzido, $categorias, $produzidoEquipe, $categoriaEquipe, $options, $meta);
            return $dadosGrafico;
        }
    }


    /*
     * Método auxiliar para formatar o nome exibido das categorias do gráfico de pizza exibido
     * no dashboard.
     */
    function formatarCategoria($categoria)
    {
        if ($categoria == 'programa') {
            return 'Programa';
        } else if ($categoria == 'nao_identificado') {
            return 'Não identificado';
        } else if ($categoria == 'site') {
            return 'Sites';
        } else {
            return 'None';
        }
    }


}