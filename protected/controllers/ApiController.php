<?php

class ApiController extends Controller
{
    public $title_action = "";

    /**
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('execute'),
                'groups' => array('*')
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('documentacao'),
                'groups' => array('empresa')
            ),
        );
    }


    public function actionDocumentacao()
    {
        $this->title_action = Yii::t('smith', Yii::t('smith', 'API Viva Smith'));
        $this->pageTitle = Yii::t('smith', Yii::t('smith', 'API Viva Smith'));
        $this->render('documentacao');
    }


    public function actionExecute()
    {
        $array = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array());
        if (!isset($_POST['serial']))
            $array['errors']['message'] = 'Missing serial';
        else {
            $empresa = $this->checkAuth();
            if ($empresa) {
                if (!isset($_POST['action']))
                    $array['errors']['message'] = 'Missing action';
                else {
                    $array = array('status_code' => 200, 'status' => 'OK');
                    switch ($_POST['action']) {
                        case 'updateEmployee':
                            $array = array();
                            $array = $this->updateEmployee($_POST['serial'], $empresa);
                            break;
                        case 'insertExternalActivity':
                            $this->checkExternalActivityRequisites();
                            $array['response'] = $this->insertExternalActivity($_POST['serial'], $empresa);
                            break;
                        case 'getEmployees':
                            $array['response'] = $this->getEmployees($empresa);
                            break;
                        case 'getTeams':
                            $array['response'] = $this->getTeams($empresa);
                            break;
                        case 'getContracts':
                            $array['response'] = $this->getContracts($empresa);
                            break;
                        case 'getTeamMonthlyProductivity':
                            $this->checkParameters(0);
                            $array['response'] = $this->getTeamMonthlyProductivity($empresa);
                            break;
                        case 'getEmployeeProductivity':
                            $this->checkParameters(1, 0);
                            $array['response'] = $this->getEmployeeProductivity($empresa);
                            break;
                        case 'getProductivityCost':
                            $this->checkParameters();
                            $array['response'] = $this->getProductivityCost($empresa);
                            break;
                        case 'getOvertimeProductivity':
                            $this->checkParameters();
                            $array['response'] = $this->getOvertimeProductivity($empresa);
                            break;
                        case 'getAttendanceReport':
                            $this->checkParameters();
                            $array['response'] = $this->getAttendanceReport($empresa);
                            break;
                        case 'getProgramsAndSitesProductivity':
                            $this->checkParameters(1, 0);
                            $array['response'] = $this->getProgramsAndSitesProductivity($empresa);
                            break;
                        case 'getIndividualContractProductivity':
                            $this->checkParameters(0);
                            $array['response'] = $this->getIndividualContractProductivity($empresa);
                            break;
                        case 'getGeneralContractProductivity':
                            $this->checkParameters();
                            $array['response'] = $this->getGeneralContractProductivity($empresa);
                            break;
                        case 'getEmployeesContractProductivity':
                            $this->checkParameters(0);
                            $array['response'] = $this->getEmployeesContractProductivity($empresa);
                            break;
                        case 'getContractConsumption':
                            $this->checkParameters(0);
                            $array['response'] = $this->getContractConsumption($empresa);
                            break;
                        case 'getMetricsReport':
                            $this->checkParameters();
                            $array['response'] = $this->getMetricsReport($empresa);
                            break;
                        default:
                            $array = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array());
                            $array['errors']['message'] = 'Action not found';
                            break;
                    }
                }
            } else {
                $array['status_code'] = '401';
                $array['errors']['message'] = 'Authentication failed';
            }

            if ($empresa && (is_null($array['response']) || empty($array['response']))) {
                $array = array('status_code' => 204, 'status' => 'OK', 'response' => array());
                $array['response'] = 'No results found with these parameters';
            }
        }
        echo json_encode($array);
    }

    private function checkExternalActivityRequisites()
    {
        $alert = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array());

        if (empty($_POST['contract']) || !isset($_POST['contract']))
            array_push($alert['errors'], array('message' => 'Missing contract'));

        if (empty($_POST['employee']) || !isset($_POST['employee']))
            array_push($alert['errors'], array('message' => 'Missing employees list'));

        if (empty($_POST['description']) || !isset($_POST['description']))
            array_push($alert['errors'], array('message' => 'Missing description'));

        if (empty($_POST['departure_time']) || !isset($_POST['departure_time']))
            array_push($alert['errors'], array('message' => 'Missing departure time'));

        if (empty($_POST['time_of_arrival']) || !isset($_POST['time_of_arrival']))
            array_push($alert['errors'], array('message' => 'Missing time of arrival'));

        if (empty($_POST['date']) || !isset($_POST['date']))
            array_push($alert['errors'], array('message' => 'Missing date'));

        if (!empty($alert['errors']))
            exit(json_encode($alert));

    }

    private function insertExternalActivity($serialEmpresa, $empresa)
    {
        $flag = false;
        $parametro = EmpresaHasParametro::model()->findByAttributes(array('fk_empresa' => $empresa));
        $usuarios = explode(',', $_POST['employee']);
        foreach ($usuarios as $usuario) {
            $usuario = Colaborador::model()->findByPk($usuario)->ad;
            $contrato = Contrato::model()->findByPk($_POST['contract']);
            $duracaoAux = MetodosGerais::time_to_minutes($_POST['time_of_arrival'] . ":00") - MetodosGerais::time_to_minutes($_POST['departure_time'] . ":00");
            $duracao = ($duracaoAux > 480) ? 480 : $duracaoAux;
            $registrosExcluidos = $this->getRegistrosExcl($usuario, $_POST, $empresa);
            $tmp = $this->PermutarRegistrosLog($registrosExcluidos);
            $horas = floor($duracao / 60);
            $resto = $duracao % 60;
            for ($i = 0; $i < $horas; $i++) {
                $logAtividade = new LogAtividade;
                $logAtividade->usuario = $usuario;
                $logAtividade->programa = "Atividade Externa";
                $logAtividade->descricao = $contrato->codigo . " - " . $_POST['description'];
                $logAtividade->duracao = '01:00:00';
                $logAtividade->data = $_POST['date'];
                $logAtividade->title_completo = $logAtividade->programa . $logAtividade->descricao;
                $logAtividade->serial_empresa = $serialEmpresa;
                $logAtividade->fk_empresa = $empresa;
                $logAtividade->atividade_extra = 1;
                $adicionarHora = '+' . $i . ' hour';
                $horario_servidor = date('H:i:s', strtotime($adicionarHora, strtotime($_POST['departure_time'] . ":00")));
                $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($horario_servidor);
                $logAtividade->hora_host = $horario_servidor;
                if (($duracaoAux > 480) && $logAtividade->hora_host >= $parametro->almoco_inicio) {
                    $duracaoAlmoco = (MetodosGerais::time_to_seconds($parametro->almoco_fim) - MetodosGerais::time_to_seconds($parametro->almoco_inicio)) / 3600;
                    $adicionarHora = '+' . (($i + $duracaoAlmoco) * 60) . ' minutes';
                    $horario_servidor = date('H:i:s', strtotime($adicionarHora, strtotime($_POST['departure_time'] . ":00")));
                    $logAtividade->hora_host = $horario_servidor;
                    $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($horario_servidor);
                }
                $logAtividade->save();
            }
            $logAtividade = new LogAtividade;
            $logAtividade->usuario = $usuario;
            $logAtividade->programa = "Atividade Externa";
            $logAtividade->descricao = $contrato->codigo . " - " . $_POST['description'];
            $logAtividade->duracao = '00' . $resto . '00';
            $logAtividade->data = $_POST['date'];
            $logAtividade->title_completo = $logAtividade->programa . $logAtividade->descricao;
            $logAtividade->serial_empresa = $serialEmpresa;
            $logAtividade->fk_empresa = $empresa;
            $logAtividade->atividade_extra = 1;
            $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($_POST['time_of_arrival'] . ":00");
            $logAtividade->hora_host = $_POST['time_of_arrival'] . ":00";

            if ($logAtividade->save()) {
                $model = new AtividadeExterna;
                $model->usuario = $usuario;
                $model->programa = "Atividade Externa";
                $model->obra = $contrato->codigo;
                $model->hora_saida = $_POST['departure_time'] . ":00";
                $model->descricao = $contrato->codigo . " - " . $_POST['description'];
                $duracaoAux = MetodosGerais::time_to_seconds($_POST['time_of_arrival'] . ":00") - MetodosGerais::time_to_seconds($_POST['departure_time'] . ":00");
                $duracao = ($duracaoAux > 28800) ? gmdate('H:i:s', 28800) : gmdate('H:i:s', $duracaoAux);
                $model->duracao = $duracao;
                $model->data = $_POST['date'];
                $model->data_hora_servidor = $model->data . " " . MetodosGerais::setHoraServidor($_POST['time_of_arrival'] . ":00");
                $model->title_completo = $model->programa . $model->descricao;
                $model->hora_host = $_POST['time_of_arrival'] . ":00";
                $model->serial_empresa = $serialEmpresa;
                $model->atividade_extra = 1;
                $model->fk_log = $logAtividade->id;
                if ($model->save()) {
                    $modelLogConsolidacao = new LogAtividadeConsolidado();
                    $modelLogConsolidacao->usuario = $usuario;
                    $modelLogConsolidacao->programa = "Atividade Externa";
                    $modelLogConsolidacao->descricao = $contrato->codigo . " - " . $_POST['description'];
                    $duracaoAux = MetodosGerais::time_to_seconds($_POST['time_of_arrival'] . ":00") - MetodosGerais::time_to_seconds($_POST['departure_time'] . ":00");
                    $duracao = ($duracaoAux > 28800) ? gmdate('H:i:s', 28800) : gmdate('H:i:s', $duracaoAux);
                    $modelLogConsolidacao->duracao = $duracao;
                    $modelLogConsolidacao->data = $_POST['date'];
                    $modelLogConsolidacao->title_completo = $modelLogConsolidacao->programa . $modelLogConsolidacao->descricao;
                    $modelLogConsolidacao->serial_empresa = $serialEmpresa;
                    $modelLogConsolidacao->num_logs = 1;
                    $modelLogConsolidacao->save(false);
                    /*
                     * Adicionar produtividade na tabela consolidada de produtividade caso a inserção da atividade externa foi de uma atividade
                     * com dia de execução anterior da data de hoje
                     */
                    if (strtotime(date('Y-m-d')) > strtotime($modelLogConsolidacao->data)) {
                        $fkColaborador = Colaborador::model()->findByAttributes(array("ad" => $usuario, "fk_empresa" => $empresa));
                        $produtividade = GrfProdutividadeConsolidado::model()->findByAttributes(array("fk_colaborador" => $fkColaborador->id, 'data' => $modelLogConsolidacao->data));
                        if (isset($produtividade)) {
                            $produtividade->duracao += (MetodosGerais::time_to_seconds($duracao) / 3600);
                            $produtividade->save(false);
                        }
                        if (isset($produtividade) && $tmp > 0) {
                            $produtividade->duracao -= ($tmp / 3600);
                            $produtividade->save(false);
                        } else {
                            $produtividadeConsolidada = new GrfProdutividadeConsolidado();
                            $produtividadeConsolidada->equipe = Equipe::model()->findByPk($fkColaborador->fk_equipe)->nome;
                            $produtividadeConsolidada->nome = $fkColaborador->nome;
                            $produtividadeConsolidada->duracao = (MetodosGerais::time_to_seconds($duracao) / 3600);
                            $produtividadeConsolidada->hora_total = (MetodosGerais::time_to_seconds($fkColaborador->horas_semana) / 3600) / 5;
                            $produtividadeConsolidada->data = $modelLogConsolidacao->data;
                            $produtividadeConsolidada->fk_colaborador = $fkColaborador->id;
                            $produtividadeConsolidada->fk_empresa = $empresa;
                            $produtividadeConsolidada->save(false);
                        }
                    }
                    $flag = true;
                }
            }
        }
        if ($flag) {
            $array = array('status_code' => 204, 'status' => 'OK', 'response' => array());
            $array['response'] = "Successfully updated employee";
            return $array;
        } else {
            $array = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array('has a internal error'));
            return $array;
        }
    }

    private function getRegistrosExcl($usuario, $dados, $empresa)
    {
        $criteria = new CDbCriteria();
        $data = $dados['date'];
        $criteria->addCondition('fk_empresa=' . $empresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addBetweenCondition('data_hora_servidor', $data . ' ' . MetodosGerais::setHoraServidor($dados['departure_time'] . ":00"), $data . ' ' . MetodosGerais::setHoraServidor($dados['time_of_arrival'] . ":00"));
        $criteria->addCondition('usuario= "' . $usuario . '"');
        $criteria->addCondition('atividade_extra=0');

        return LogAtividade::model()->findAll($criteria);
    }

    private function PermutarRegistrosLog($registros)
    {
        $duracao = 0;
        foreach ($registros as $value) {
            $modelTeste = new LogAtividadeHistorico();
            $modelTeste->id = $value->id;
            $modelTeste->usuario = $value->usuario;
            $modelTeste->programa = $value->programa;
            $modelTeste->descricao = $value->descricao;
            $modelTeste->duracao = $value->duracao;
            $modelTeste->data = $value->data;
            $modelTeste->hora_host = $value->data_hora_servidor;
            $modelTeste->serial_empresa = $value->serial_empresa;
            $modelTeste->fk_empresa = $value->fk_empresa;
            if ($modelTeste->save()) {
                $duracao += MetodosGerais::time_to_seconds($value->duracao);
                $value->delete();
            }
        }
        return $duracao;
    }

    /**
     * @param $serial_empresa
     * @param $fk_empresa
     * @return array|bool
     */
    private function updateEmployee($serial_empresa, $fk_empresa)
    {
        $identificador = (isset($_POST['id'])) ? $_POST['id'] : null;
        $colaborador = Colaborador::model()->findByAttributes(array('serial_empresa' => $serial_empresa, 'id' => $identificador));
        if (isset($colaborador)) {
            $colaborador->nome = (!empty($_POST['name'])) ? $_POST['name'] : $colaborador->nome;
            $colaborador->sobrenome = (!empty($_POST['lastName'])) ? $_POST['lastName'] : $colaborador->sobrenome;
            $colaborador->email = (!empty($_POST['email'])) ? $_POST['email'] : $colaborador->email;
            $this->saveColaboradorHasSalario($colaborador, $fk_empresa);
            $colaborador->salario = (!empty($_POST['salary'])) ? $_POST['salary'] : $colaborador->salario;
            $colaborador->horas_semana = (!empty($_POST['weeklyWorkload'])) ? $_POST['weeklyWorkload'] . ':00:00' : $colaborador->horas_semana;
            $colaborador->valor_hora = (isset($colaborador->salario) && isset($colaborador->horas_semana)) ? round($colaborador->salario / ($colaborador->horas_semana * 4), 2) : 0;
            if (!empty($_POST['team'])) {
                $equipe = Equipe::model()->findByAttributes(array('fk_empresa' => $fk_empresa, 'nome' => $_POST['team']));
                if (isset($equipe))
                    $colaborador->fk_equipe = $equipe->id;
                else {
                    $equipe = new Equipe();
                    $equipe->nome = $_POST['team'];
                    $equipe->meta = 60;
                    $equipe->fk_empresa = $fk_empresa;
                    if ($equipe->save())
                        $colaborador->fk_equipe = $equipe->id;
                }
            }
            $colaborador->fk_empresa = $fk_empresa;
            if ($colaborador->save()) {
                $array = array('status_code' => 204, 'status' => 'OK', 'response' => array());
                $array['response'] = "Successfully updated employee";
                return $array;
            } else {
                $array = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array('it\'s necessary fill some fields to update info employee'));
                $array['errors'] = array_values($colaborador->getErrors());
                return $array;
            }
        } else {
            $array = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array('it\'s necessary fill some fields to update info employee'));
            return $array;
        }
    }

    /**
     * @param $colaborador
     * @param $fk_empresa
     */
    private function saveColaboradorHasSalario($colaborador, $fk_empresa)
    {
        if ((isset($_POST['salary']) && !empty($_POST['salary'])) && $_POST['salary'] != $colaborador->salario) {
            $chs = new ColaboradorHasSalario;
            $chs->fk_colaborador = $colaborador->id;
            $chs->fk_empresa = $fk_empresa;
            $chs->data_inicio = date('Y-m-d');
            $chs->valor = $_POST['salary'];
            $chs->save();
        }
    }


    /**
     * @param $fk_empresa
     * @return array
     */
    private function getEmployees($fk_empresa)
    {
        $colaboradores = Colaborador::model()->findAll(array('condition' => 'fk_empresa = ' . $fk_empresa . ' AND fk_equipe != "" AND ativo = 1 AND status = 1', 'order' => 'nome ASC'));
        $arrayColaborador = array();
        foreach ($colaboradores as $colaborador) {
            $arrayColaborador[$colaborador->nomeCompleto]['identificador'] = $colaborador->id;
        }
        return $arrayColaborador;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getTeams($fk_empresa)
    {
        $equipes = Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
        $arrayEquipe = array();
        foreach ($equipes as $equipe) {
            $arrayEquipe[$equipe->nome]['identificador'] = $equipe->id;
        }
        return $arrayEquipe;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getContracts($fk_empresa)
    {
        $contratos = Contrato::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'ativo' => 1));
        $arrayContrato = array();
        foreach ($contratos as $contrato) {
            $arrayContrato[$contrato->nome]['identificador'] = $contrato->id;
        }
        return $arrayContrato;
    }


    /**
     * @param $fk_empresa
     * @return array
     */
    private function getTeamMonthlyProductivity($fk_empresa)
    {
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $filter = $_POST['filter'];
        $diasUteis = BackupRelatorios::dias_uteis(strtotime($iniDate), strtotime($endDate), $fk_empresa);
        $resultado = array();
        $equipes = ($filter != "all") ? Equipe::model()->findAllByAttributes(array('id' => $filter, 'fk_empresa' => $fk_empresa)) :
            Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
        foreach ($equipes as $equipe) {
            $resultadoEquipe = array();
            $somaProdEq = $somaHoraTt = 0;
            $produtividadeEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($iniDate, $endDate, $fk_empresa, $equipe->id);
            if (!empty($produtividadeEquipe)) {
                foreach ($produtividadeEquipe as $prodEq) {
                    $somaProdEq += $prodEq->duracao;
                    $somaHoraTt += $prodEq->hora_total;
                }
                $resultadoEquipe[$equipe->nome]['info'] = array('done' => round(((float)$somaProdEq * 100) / ((float)$somaHoraTt * $diasUteis), 2) . '%', 'goal' => $equipe->meta . '%');
            }

            $resultado = array_merge($resultado, $resultadoEquipe);
        }

        $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($iniDate, $endDate, $fk_empresa, '');
        if (!empty($resultEquipe)) {
            $equipe = $categorias = $produzido = $produzidoEquipe = $categoriaEquipe = array();

            foreach ($resultEquipe as $value) {
                $objEquipe = Equipe::model()->findByPk($value->fk_equipe);
                if ($value->hora_total > 0 && isset($objEquipe)) {
                    $equipe[$objEquipe->nome][] = $value;
                    if (isset($equipe[$objEquipe->nome]['total']) && isset($equipe[$objEquipe->nome]['total_time'])) {
                        $equipe[$objEquipe->nome]['total'] += (float)$value->duracao;
                        $equipe[$objEquipe->nome]['total_time'] += (float)$value->hora_total;
                    } else {
                        if (isset($value->fk_equipe)) {
                            $equipe[$objEquipe->nome]['total'] = (float)$value->duracao;

                            $categorias[] = $objEquipe->nome;
                            $options[$value->fk_equipe] = $objEquipe->nome;
                            $equipe[$objEquipe->nome]['total_time'] = (float)$value->hora_total;
                        }
                    }
                }
            }
            foreach ($equipe as $key => $value) {
                $idEquipe = Equipe::model()->findByAttributes(array("nome" => $key, "fk_empresa" => $fk_empresa));
                if (isset($idEquipe)) {
                    foreach ($value as $chave => $dadoEquipe) {
                        if (($chave != 'total' && $chave != 'total_time') || $chave == '0') {
                            $colaborador = Colaborador::model()->findByPk($dadoEquipe->fk_colaborador);
                            if (isset($colaborador)) {
                                $ferias = ColaboradorHasFerias::model()->findAllByAttributes(array("fk_colaborador" => $colaborador->id));
                                if (!empty($ferias)) {
                                    $diasUteisFerias = BackupRelatorios::diasUteisColaborador(strtotime($iniDate), strtotime($endDate), $ferias, $fk_empresa);
                                    if ($diasUteisFerias) {
                                        $colaboradorDuracao = $dadoEquipe->duracao;
                                        if ((strtotime($iniDate) > strtotime($ferias[0]->data_inicio) && strtotime($iniDate) < strtotime($ferias[0]->data_fim)) && (strtotime($endDate) > strtotime($ferias[0]->data_fim)))
                                            $colaboradorDuracao = GrfProdutividadeConsolidado::model()->graficoProdutividadeByColaborador($ferias[0]->data_fim, $endDate, $fk_empresa, $dadoEquipe->fk_colaborador)[0]->duracao;
                                        $porcentagemProduzidaColaborador = round(($colaboradorDuracao * 100) / ($dadoEquipe->hora_total * $diasUteisFerias), 2);
                                        $resultado[$key]['employees'][MetodosGerais::reduzirNome($colaborador->nome . ' ' . $colaborador->sobrenome)]['done'] = $porcentagemProduzidaColaborador . '%';
                                    }
                                } else {
                                    $duracao = $dadoEquipe->duracao;
                                    $porcentagemProduzidaColaborador = round(($duracao * 100) / ($dadoEquipe->hora_total * $diasUteis), 2);
                                    $resultado[$key]['employees'][MetodosGerais::reduzirNome($colaborador->nome . ' ' . $colaborador->sobrenome)]['done'] = $porcentagemProduzidaColaborador . '%';
                                }
                            }

                        }
                    }
                }
            }
        }
        return $resultado;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getEmployeeProductivity($fk_empresa)
    {
        $data = $_POST['date'];
        $filter = $_POST['filter'];
        $produtividade = array();
        $colaboradores = ($filter != 'all') ? Colaborador::model()->findAll(array('condition' => 'fk_empresa = ' . $fk_empresa . ' AND id = ' . $filter)) :
            Colaborador::model()->findAll(array('condition' => 'fk_empresa = ' . $fk_empresa . ' AND fk_equipe != "" AND ativo = 1 AND status = 1', 'order' => 'nome ASC'));
        switch ($_POST['type']) {
            case 'day':
                /* Produtividade diária */
                foreach ($colaboradores as $colaborador) {
                    $produtividadeColaborador = array();

                    $produtividadeDia = BackupRelatorios::getProdutividadeDiaria($colaborador->id, $data, $fk_empresa);
                    $mediaEquipe = BackupRelatorios::getMediaProdEquipeDia($colaborador->fk_equipe, $data, $fk_empresa);
                    $meta = MetodosGerais::formataTempo(((60 * $colaborador->equipes->meta) / 100) * 60);

                    for ($i = 7; $i <= 18; $i++) $produtividadeColaborador[$i] = array('done' => (float)0, 'goal' => $meta, 'average' => (float)0);
                    foreach ($produtividadeDia as $value) ($value['duracao'] > 3600) ? $produtividadeColaborador[$value['hora']]['done'] = MetodosGerais::formataTempo(3200) : $produtividadeColaborador[$value['hora']]['done'] = MetodosGerais::formataTempo((float)$value['duracao']);
                    foreach ($mediaEquipe as $value) {
                        $produtividadeColaborador[$value['hora']]['average'] = MetodosGerais::formataTempo((float)$value['duracao']);
                        if (!isset($produtividadeColaborador[$value['hora']]['done'])) $produtividadeColaborador[$value['hora']]['done'] = 0;
                        if (!isset($produtividadeColaborador[$value['hora']]['goal'])) $produtividadeColaborador[$value['hora']]['goal'] = 0;
                    }

                    $produtividadeColaborador = array($colaborador->nomeCompleto => $produtividadeColaborador);
                    $produtividade = array_merge($produtividade, $produtividadeColaborador);
                }
                break;
            case 'month':
                /* Produtividade mensal */
                foreach ($colaboradores as $colaborador) {
                    $produtividadeColaborador = array();

                    $registrosMes = BackupRelatorios::produtividadeDiariaPorMesAno(date('m', strtotime($data)), date('Y', strtotime($data)), $colaborador->id, $fk_empresa);
                    $meta = MetodosGerais::formataTempo(((($colaborador->horas_semana / 5) * $colaborador->equipes->meta) / 100) * 3600);
                    $datasUteis = BackupRelatorios::datas_uteis_mes(date('m', strtotime($data)), date('Y', strtotime($data)), $fk_empresa);
                    $mediaEquipe = BackupRelatorios::getMediaProdEquipeMes($colaborador->fk_equipe, date('m', strtotime($data)), date('Y', strtotime($data)), $fk_empresa);

                    foreach ($datasUteis as $data) $produtividadeColaborador[$data] = array('done' => (float)0, 'goal' => $meta, 'average' => (float)0);
                    foreach ($registrosMes as $value) $produtividadeColaborador[date('d/m', strtotime($value->data))]['done'] = MetodosGerais::formataTempo((float)$value->duracao);
                    foreach ($mediaEquipe as $value) $produtividadeColaborador[date('d/m', strtotime($value->data))]['average'] = MetodosGerais::formataTempo((float)$value->duracao * 3600);

                    $produtividadeColaborador = array($colaborador->nomeCompleto => $produtividadeColaborador);
                    $produtividade = array_merge($produtividade, $produtividadeColaborador);
                }
                break;
            case 'year':
                /* Produtividade anual */
                foreach ($colaboradores as $colaborador) {
                    $produtividadeColaborador = array();

                    $registrosAno = BackupRelatorios::produtividadeDiariaPorAno(date('Y', strtotime($data)), $colaborador->id, $fk_empresa);
                    $meta = MetodosGerais::formataTempo(((($colaborador->horas_semana * 4) * $colaborador->equipes->meta) / 100) * 3600);
                    $mediaEquipe = BackupRelatorios::getMediaProdEquipeAno($colaborador->fk_equipe, date('Y', strtotime($data)), $fk_empresa);

                    for ($i = 1; $i <= 12; $i++) $produtividadeColaborador[$i] = array('done' => (float)0, 'goal' => $meta, 'average' => (float)0);
                    foreach ($registrosAno as $value) $produtividadeColaborador[$value->data]['done'] = MetodosGerais::formataTempo((float)$value->duracao);
                    foreach ($mediaEquipe as $value) $produtividadeColaborador[$value->data]['average'] = MetodosGerais::formataTempo((float)$value->duracao * 3600);

                    $produtividadeColaborador = array($colaborador->nomeCompleto => $produtividadeColaborador);
                    $produtividade = array_merge($produtividade, $produtividadeColaborador);
                }
                break;
        }
        return $produtividade;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getProductivityCost($fk_empresa)
    {
        $filter = $_POST['filter'];
        $type = $_POST['type'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $iniDate2 = strtotime($iniDate);
        $endDate2 = strtotime($endDate);
        $dias_uteis = BackupRelatorios::dias_uteis($iniDate2, $endDate2, $fk_empresa);
        $custoEquipe = $custoColaborador = $resultado = array();


        //////////////////// CUSTO POR EQUIPE ////////////////////
        if ($type == 'team' || $type == 'all') {
            $idEquipe = ($type == 'all' || $filter == 'all') ? 'todas_equipes' : $filter;
            $equipeTrabalho = GrfProdutividadeConsolidado::model()->graficoProdutividadeCustoByEquipe($iniDate, $endDate, $fk_empresa, $idEquipe);
            if (!empty($equipeTrabalho)) {
                foreach ($equipeTrabalho as $value) {
                    $idEquipe = ($idEquipe == 'todas_equipes') ? Colaborador::model()->findByPk($value->fk_colaborador)->fk_equipe : $idEquipe;
                    $dadosEquipe = BackupRelatorios::getSalarioTempoEquipe($dias_uteis, $idEquipe, $fk_empresa);
                    $equipe[$value->equipe]['salario'] = (float)($dadosEquipe[0]['salario_equipe']);
                    $equipe[$value->equipe]['hora_total'] = (float)($dias_uteis * $dadosEquipe[0]['hora_total']);
                    $equipe[$value->equipe]['horas_trabalhadas'] = (float)$value->duracao;
                    $equipe[$value->equipe]['horas_ociosas'] = $equipe[$value->equipe]['hora_total'] - $equipe[$value->equipe]['horas_trabalhadas'];
                }

                foreach ($equipe as $nomeEq => $value) {
                    $resultadoEquipe[$nomeEq] = array(
                        'brl' => array(
                            'floatDoneCost' => round(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 2),
                            'formatDoneCost' => 'R$' . MetodosGerais::float2real(round(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 2)),
                            'floatAbsentCost' => round(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 2),
                            'formatAbsentCost' => 'R$' . MetodosGerais::float2real(round(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 2))
                        ),
                        'usd' => array(
                            'floatDoneCost' => MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 'dolar'),
                            'formatDoneCost' => '$' . MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 'dolar'),
                            'floatAbsentCost' => MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 'dolar'),
                            'formatAbsentCost' => '$' . MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 'dolar')
                        )
                    );
                }

                $custoEquipe = array_merge($custoEquipe, $resultadoEquipe);
            }
            $resultado['teams'] = $custoEquipe;
        }
        //////////////////// CUSTO POR COLABORADOR ////////////////////
        if ($type == 'employee' || $type == 'all') {
            $idColaborador = ($type == 'all' || $filter == 'all') ? 'todos_colaboradores' : $filter;
            $colaboradorTrabalho = BackupRelatorios::getTempoProduzidoColaboradorPorAtributos($iniDate, $endDate, $idColaborador, $fk_empresa);
            if (!empty($colaboradorTrabalho)) {
                $categorias = $ocioso = $produzido = $colaborador = array();
                foreach ($colaboradorTrabalho as $value) {
                    if ($value['hora_total'] > 0) {
                        array_push($categorias, $value['nome']);
                        $ferias = ColaboradorHasFerias::model()->findAllByAttributes(array("fk_colaborador" => $value['fk_colaborador']));
                        if (!empty($ferias)) {
                            $diasUteisFerias = BackupRelatorios::diasUteisColaborador(strtotime($iniDate), strtotime($endDate), $ferias, $fk_empresa);
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

                foreach ($colaborador as $nomeEq => $value) {
                    $resultadoColaborador[$nomeEq] = array(
                        'brl' => array(
                            'floatDoneCost' => round(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 2),
                            'formatDoneCost' => 'R$' . MetodosGerais::float2real(round(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 2)),
                            'floatAbsentCost' => round(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 2),
                            'formatAbsentCost' => 'R$' . MetodosGerais::float2real(round(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 2))
                        ),
                        'usd' => array(
                            'floatDoneCost' => MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 'dolar'),
                            'formatDoneCost' => '$' . MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_trabalhadas']) / $value['hora_total'], 'dolar'),
                            'floatAbsentCost' => MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 'dolar'),
                            'formatAbsentCost' => '$' . MetodosGerais::conversaoReal(((float)$value['salario'] * (float)$value['horas_ociosas']) / $value['hora_total'], 'dolar')
                        )
                    );
                }

                $custoColaborador = array_merge($custoColaborador, $resultadoColaborador);
            }
            $resultado['employees'] = $custoColaborador;
        }
        return $resultado;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getOvertimeProductivity($fk_empresa)
    {
        $filter = $_POST['filter'];
        $type = $_POST['type'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $resultado = $horaExtraColaborador = array();

        //////////////////// HORA EXTRA POR EQUIPE ////////////////////
        if ($type == 'team' || $type == 'all') {
            $idEquipe = ($type == 'all' || $filter == 'all') ? 'todos' : $filter;
            $horaExtras = GrfHoraExtraConsolidado::model()->getHorasExtras($iniDate, $endDate, $fk_empresa, 'equipe', $idEquipe);
            $horaExtraEquipe = array();
            foreach ($horaExtras as $value) {
                $colaborador = $value->fkColaborador;
                if (isset($colaborador->fk_equipe)) {
                    $equipe = $colaborador->equipes;
                    if (empty($horaExtraEquipe[$equipe->nome])) {
                        $horaExtraEquipe[$equipe->nome]['time'] = ((float)$value->duracao * 3600);
                        $horaExtraEquipe[$equipe->nome]['productivity'] = ((float)$value->produtividade * 3600);
                    } else {
                        $horaExtraEquipe[$equipe->nome]['time'] += ((float)$value->duracao * 3600);
                        $horaExtraEquipe[$equipe->nome]['productivity'] += ((float)$value->produtividade * 3600);
                    }
                }
            }
            foreach ($horaExtraEquipe as $key => $item) {
                $horaExtraEquipe[$key]['time'] = MetodosGerais::formataTempo($item['time']);
                $horaExtraEquipe[$key]['productivity'] = MetodosGerais::formataTempo($item['productivity']);
            }
            if ($horaExtraEquipe)
                $resultado['teams'] = $horaExtraEquipe;
        }

        //////////////////// HORA EXTRA POR COLABORADOR ////////////////////

        if ($type == 'employee' || $type == 'all') {
            $idColaborador = ($type == 'all' || $filter == 'all') ? 'todos' : $filter;
            $horaExtras = GrfHoraExtraConsolidado::model()->getHorasExtras($iniDate, $endDate, $fk_empresa, 'colaborador', $idColaborador);
            foreach ($horaExtras as $value) {
                $colaborador = $value->fkColaborador;
                if (isset($colaborador->fk_equipe))
                    $horaExtraColaborador[$colaborador->nomeCompleto] = array('time' => MetodosGerais::formataTempo((float)$value->duracao * 3600), 'productivity' => MetodosGerais::formataTempo((float)$value->produtividade * 3600));
            }
            if ($horaExtraColaborador)
                $resultado['employees'] = $horaExtraColaborador;
        }
        return $resultado;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getAttendanceReport($fk_empresa)
    {
        $filter = $_POST['filter'];
        $type = $_POST['type'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $resultadoPonto = $colaboradores = array();
        $colaboradores = $this->getEmployeeByFilter($fk_empresa, $type, $filter);
        foreach ($colaboradores as $colaborador) {
            $pontos = GrfColaboradorConsolidado::model()->getPontos($iniDate, $endDate, $fk_empresa, $colaborador->id);
            if (!empty($pontos)) {
                $arrayPonto = array();
                foreach ($pontos as $value) {
                    $arrayPonto[$value->data] = array('entry' => $value->hora_entrada, 'departure' => $value->hora_saida);
                }
                $resultadoPonto[$colaborador->equipes->nome][$colaborador->nomeCompleto] = $arrayPonto;
            }
        }
        return $resultadoPonto;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getProgramsAndSitesProductivity($fk_empresa)
    {
        $data = MetodosGerais::dataBrasileira($_POST['date']);
        $filter = $_POST['filter'];
        $type = $_POST['type'];
        $resultadoRelatorio = array();
        $colaboradores = $this->getEmployeeByFilter($fk_empresa, $type, $filter);
        foreach ($colaboradores as $colaborador) {
            $produtivo = ProgramasSites::getTempoTotalProdutividadeProgramasByColaborador($colaborador->id, $_POST['date'], $fk_empresa);
            if (!is_null($produtivo->total)) {
                $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
                $almocoInicio = MetodosGerais::setHoraServidor($parametros->almoco_inicio);
                $almocoFim = MetodosGerais::setHoraServidor($parametros->almoco_fim);
                $improdutivo = ProgramasSites::getTempoTotalProgramasNaoProdutivosByColaborador($colaborador->id, $_POST['date'], $fk_empresa);
                $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));
                $horarios = GrfColaboradorConsolidado::model()->find(array(
                    'condition' => 'fk_colaborador = :fk_colaborador AND data = :data',
                    'params' => array(
                        'fk_colaborador' => $colaborador->id,
                        'data' => $_POST['date']
                    )
                ));
                $horario_entrada = $horarios->hora_entrada;
                $horario_saida = $horarios->hora_saida;

                $duracaoAlmoco = MetodosGerais::time_to_seconds($almocoFim) - MetodosGerais::time_to_seconds($almocoInicio);
                $ociosoAlmoco = BackupRelatorios::getTempoAlmoco($colaborador->id, $data, $almocoFim, $fk_empresa);
                $ociosoAlmoco = BackupRelatorios::calcularOcioAlmoco($ociosoAlmoco, $duracaoAlmoco);

                if (strtotime($parametros->almoco_fim) > strtotime($horario_saida))
                    $duracaoAlmoco = MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($parametros->almoco_inicio);


                $colecaoSites = $colecaoProgramas = $colecaoSitesImprodutivos = array();
                $sites = '';
                foreach ($sitesPermitidos as $value) {
                    $sitesProdutivos = ProgramasSites::getTempoTotalSitesPermitidosByColaborador($colaborador->id, $_POST['date'], $value->nome, $fk_empresa);
                    $listaSitePermitido = ProgramasSites::getListaSitesPermitidosByColaborador($colaborador->id, $_POST['date'], $value->nome, $fk_empresa);
                    if (!is_null($sitesProdutivos->total)) {
                        array_push($colecaoSites, $sitesProdutivos->total);
                    }
                    if (!empty($listaSitePermitido)) {
                        foreach ($listaSitePermitido as $item) {
                            $sites .= '"' . $item->site . '",';
                        }
                    }
                }
                $total_parcial_almoco = 0;
                $total_parcial_sites = array_sum($colecaoSites);
                $sitesImprodutivos = ProgramasSites::getTempoTotalSitesNaoPermitidosByColaborador($colaborador->id, $_POST['date'], rtrim($sites, ","), $fk_empresa);

                $duracaoAlmoco -= $total_parcial_almoco;
                if (($total_parcial_almoco > $duracaoAlmoco) || (strtotime($horario_entrada) > strtotime($parametros->almoco_fim))) $duracaoAlmoco = 0;

                $total_parcial_ativ_externa = ProgramasSites::getTempoTotalProdutividadeProgramasByColaborador($colaborador->id, $_POST['date'], $fk_empresa, 'Atividade Externa');

                array_push($colecaoProgramas, $produtivo->total);
                array_push($colecaoSitesImprodutivos, $sitesImprodutivos->total);

                $tempoOcioso = BackupRelatorios::calculoTempoOciosoRelInd($ociosoAlmoco, $duracaoAlmoco, $colecaoProgramas, $improdutivo->total, $colecaoSitesImprodutivos, (MetodosGerais::time_to_seconds($horario_saida) - MetodosGerais::time_to_seconds($horario_entrada)));

                $relatorioIndividual = array(
                    'allowedPrograms' => MetodosGerais::formataTempo($produtivo->total),
                    'allowedSites' => MetodosGerais::formataTempo($total_parcial_sites),
                    'outdoorActivity' => MetodosGerais::formataTempo($total_parcial_ativ_externa->total),
                    'unallowedPrograms' => MetodosGerais::formataTempo($improdutivo->total),
                    'unallowedSites' => MetodosGerais::formataTempo($sitesImprodutivos->total),
                    'absent' => MetodosGerais::formataTempo($tempoOcioso)
                );
                $resultadoRelatorio[$colaborador->equipes->nome][$colaborador->nomeCompleto] = $relatorioIndividual;
            }
        }
        return $resultadoRelatorio;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getIndividualContractProductivity($fk_empresa)
    {
        $filter = $_POST['filter'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $resultadoContratos = array();
        $contratos = ($filter == 'all') ? Contrato::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'ativo' => 1))
            : Contrato::model()->findAllByAttributes(array('id' => $filter, 'fk_empresa' => $fk_empresa));
        foreach ($contratos as $contrato) {
            $prefixo = $contrato->codigo;
            $colaboradores = BackupRelatorios::getProdutividadeContratoPorColaborador('', $contrato, $iniDate, $endDate, $fk_empresa);
            $tempoTotal = $custoTotal = 0;
            $resultadoColaboradores = array();

            foreach ($colaboradores as $colaborador) {
                if (!is_null($colaborador->equipe) || !empty($colaborador)) {
                    $tempoTotal += $colaborador->duracao;
                    $custoTotal += $colaborador->salario * ($colaborador->duracao / 3600);
                }
            }

            foreach ($colaboradores as $colaborador) {
                if (!is_null($colaborador->equipe) && $tempoTotal > 0) {
                    $equipe = Equipe::model()->findByPk($colaborador->equipe)->nome;
                    $arrayColaborador = array(
                        'team' => $equipe,
                        'doneTime' => MetodosGerais::formataTempo($colaborador->duracao),
                        'participation' => round(($colaborador->duracao * 100) / $tempoTotal, 2) . '%',
                        'brlFloatBudget' => round($colaborador->salario * ($colaborador->duracao / 3600), 2),
                        'brlFormatBudget' => 'R$' . MetodosGerais::float2real(round($colaborador->salario * ($colaborador->duracao / 3600), 2)),
                        'usdFloatBudget' => MetodosGerais::conversaoReal(round($colaborador->salario * ($colaborador->duracao / 3600), 2), 'dolar'),
                        'usdFormatBudget' => '$' . MetodosGerais::conversaoReal(round($colaborador->salario * ($colaborador->duracao / 3600), 2), 'dolar')
                    );

                    $resultadoColaboradores[isset($colaborador->colaborador) ? MetodosGerais::reduzirNome($colaborador->colaborador) : MetodosGerais::reduzirNome($colaborador['nome'] . ' ' . $colaborador['sobrenome'])] = $arrayColaborador;
                    unset($arrayColaborador);
                }
            }
            $relatorioContrato = array(
                'info' => array(
                    'code' => $prefixo,
                    'start' => MetodosGerais::dataBrasileira($iniDate),
                    'estimateConclusion' => ($contrato->data_final == NULL) ? 'undefined' : MetodosGerais::dataBrasileira($contrato->data_final),
                    'timeGoal' => $contrato->tempo_previsto ? $contrato->tempo_previsto : 'undefined',
                    'timeDone' => MetodosGerais::formataTempo($tempoTotal),
                    'costGoal' => ($contrato->valor == NULL) ? 'undefined' : 'R$' . MetodosGerais::float2real($contrato->valor),
                    'costDone' => 'R$' . MetodosGerais::float2real(round($custoTotal, 2))
                ),
                'employees' => $resultadoColaboradores
            );

            $resultadoContratos[$contrato->nome] = $relatorioContrato;
            unset($relatorioContrato);
        }
        return $resultadoContratos;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getGeneralContractProductivity($fk_empresa)
    {
        $filter = $_POST['filter'];
        $type = $_POST['type'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $resultado = array();
        // EQUIPES //
        if ($type == 'team' || $type == 'all') {
            $idEquipe = ($type == 'all' || $filter == 'all') ? 'todas_equipes' : $filter;
            $registros = BackupRelatorios::getProdutividadeProjetosByAtt($idEquipe, 'equipe', $iniDate, $endDate, $fk_empresa);
            if (!empty($registros)) {
                $dados = Contrato::model()->formatDataRelatorioGeralContratoOpEquipe($registros);
                foreach ($dados as $key => $value) {
                    $resultadoEquipes = array();
                    foreach ($value as $chave => $valor) {
                        $registroEquipe[$key][$chave] = array(
                            'code' => $valor['codigo_obra'],
                            'timeGoal' => (isset($valor['tempoPrevisto'])) ? MetodosGerais::formataTempo($valor['tempoPrevisto'] * 3600) : '00:00:00',
                            'timeDone' => MetodosGerais::formataTempo($valor['horas'] * 3600),
                            'brlFloatBudgetGoal' => (isset($valor['valorPrevisto'])) ? (float)$valor['valorPrevisto'] : (float)0,
                            'brlFormatBudgetGoal' => (isset($valor['valorPrevisto'])) ? 'R$' . MetodosGerais::float2real($valor['valorPrevisto']) : 'R$0,00',
                            'usdFloatBudgetGoal' => (isset($valor['valorPrevisto'])) ? MetodosGerais::conversaoReal($valor['valorPrevisto'], 'dolar') : (float)0,
                            'usdFormatBudgetGoal' => (isset($valor['valorPrevisto'])) ? '$' . MetodosGerais::conversaoReal($valor['valorPrevisto'], 'dolar') : '$0,00',
                            'brlFloatBudgetDone' => (isset($valor['valor_horas'])) ? (float)$valor['valor_horas'] : (float)0,
                            'brlFormatBudgetDone' => 'R$' . MetodosGerais::float2real($valor['valor_horas']),
                            'usdFloatBudgetDone' => (isset($valor['valor_horas'])) ? MetodosGerais::conversaoReal($valor['valor_horas'], 'dolar') : (float)0,
                            'usdFormatBudgetDone' => (isset($valor['valor_horas'])) ? '$' . MetodosGerais::conversaoReal($valor['valor_horas'], 'dolar') : '$0,00',
                        );
                        $resultadoEquipes = array_merge($resultadoEquipes, $registroEquipe);
                    }
                }
            }
            $resultado['teams'] = $resultadoEquipes;
        }

        // COLABORADORES //
        if ($type == 'employee' || $type == 'all') {
            $idColaborador = ($type == 'all' || $filter == 'all') ? 'todos_colaboradores' : $filter;
            $registros = BackupRelatorios::getProdutividadeProjetosByAtt($idColaborador, 'colaborador', $iniDate, $endDate, $fk_empresa);
            if (!empty($registros)) {
                $dados = Contrato::model()->formatDataRelatorioGeralContratoOpColaborador($registros);
                foreach ($dados as $key => $value) {
                    $resultadoColaboradores = array();
                    foreach ($value as $chave => $valor) {
                        $registroColaborador[$key][$chave] = array(
                            'code' => $valor['codigo_obra'],
                            'timeGoal' => (isset($valor['tempoPrevisto'])) ? MetodosGerais::formataTempo($valor['tempoPrevisto'] * 3600) : '00:00:00',
                            'timeDone' => MetodosGerais::formataTempo($valor['horas'] * 3600),
                            'brlFloatBudgetGoal' => (isset($valor['valorPrevisto'])) ? (float)$valor['valorPrevisto'] : (float)0,
                            'brlFormatBudgetGoal' => (isset($valor['valorPrevisto'])) ? 'R$' . MetodosGerais::float2real($valor['valorPrevisto']) : 'R$0,00',
                            'usdFloatBudgetGoal' => (isset($valor['valorPrevisto'])) ? MetodosGerais::conversaoReal($valor['valorPrevisto'], 'dolar') : (float)0,
                            'usdFormatBudgetGoal' => (isset($valor['valorPrevisto'])) ? '$' . MetodosGerais::conversaoReal($valor['valorPrevisto'], 'dolar') : '$0,00',
                            'brlFloatBudgetDone' => (isset($valor['valor_horas'])) ? (float)$valor['valor_horas'] : (float)0,
                            'brlFormatBudgetDone' => 'R$' . MetodosGerais::float2real($valor['valor_horas']),
                            'usdFloatBudgetDone' => (isset($valor['valor_horas'])) ? MetodosGerais::conversaoReal($valor['valor_horas'], 'dolar') : (float)0,
                            'usdFormatBudgetDone' => (isset($valor['valor_horas'])) ? '$' . MetodosGerais::conversaoReal($valor['valor_horas'], 'dolar') : '$0,00',
                        );
                        $resultadoColaboradores = array_merge($resultadoColaboradores, $registroColaborador);
                    }
                }
            }
            $resultado['employees'] = $resultadoColaboradores;
        }


        // CONTRATOS //
        if ($type == 'contract' || $type == 'all') {
            $idContrato = ($type == 'all' || $filter == 'all') ? 'todos_contratos' : $filter;
            $registros = BackupRelatorios::getProdutividadeProjetosByAtt($idContrato, 'contrato', $iniDate, $endDate, $fk_empresa);
            if (!empty($registros)) {
                $dados = Contrato::model()->formatDataRelatorioGeralContratoOpContrato($registros);
                foreach ($dados as $key => $value) {
                    $resultadoContratos = array();
                    $registroContrato[$key] = array(
                        'info' => array(
                            'code' => $value['data']['codigo_obra'],
                            'timeGoal' => $value['data']['tempo_previsto'] ? $value['data']['tempo_previsto'] : '00:00:00',
                            'brlFloatBudgetGoal' => (isset($value['data']['valor_previsto'])) ? (float)$value['data']['valor_previsto'] : (float)0,
                            'brlFormatBudgetGoal' => (isset($value['data']['valor_previsto'])) ? 'R$' . MetodosGerais::float2real($value['data']['valor_previsto']) : 'R$0,00',
                            'usdFloatBudgetGoal' => (isset($value['data']['valor_previsto'])) ? MetodosGerais::conversaoReal($value['data']['valor_previsto'], 'dolar') : (float)0,
                            'usdFormatBudgetGoal' => (isset($value['data']['valor_previsto'])) ? '$' . MetodosGerais::conversaoReal($value['data']['valor_previsto'], 'dolar') : '$0,00',
                        ));
                    foreach ($value as $chave => $valor) {
                        if ($chave != 'data') {
                            $registroContrato[$key]['employees'][$chave] = array(
                                'timeDone' => (isset($valor['tempo_trab'])) ? MetodosGerais::formataTempo($valor['tempo_trab'] * 3600) : (float)0,
                                'brlFloatBudgetDone' => (isset($valor['custo'])) ? (float)$valor['custo'] : (float)0,
                                'brlFormatBudgetDone' => (isset($valor['custo'])) ? 'R$' . MetodosGerais::float2real($valor['custo']) : 'R$0,00',
                                'usdFloatBudgetDone' => (isset($valor['custo'])) ? MetodosGerais::conversaoReal($valor['custo'], 'dolar') : (float)0,
                                'usdFormatBudgetDone' => (isset($valor['custo'])) ? '$' . MetodosGerais::conversaoReal($valor['custo'], 'dolar') : '$0,00',
                            );
                            $resultadoContratos = array_merge($resultadoContratos, $registroContrato);
                        }
                    }
                }
            }
            $resultado['contracts'] = $resultadoContratos;
        }
        return $resultado;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getEmployeesContractProductivity($fk_empresa)
    {
        $filter = $_POST['filter'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $resultadoProdutividade = array();
        $colaboradores = $this->getEmployeeByFilter($fk_empresa, 'employee', $filter);
        foreach ($colaboradores as $colaborador) {
            $registros = BackupRelatorios::getProdutividadeColaboradorPorContrato($colaborador->id, $iniDate, $endDate, $fk_empresa);
            $produtividade = array();
            if (!empty($registros)) {
                foreach ($registros as $value) {
                    $produtividade[$colaborador->nomeCompleto][Contrato::model()->findByPk($value->fk_obra)->nome] = MetodosGerais::formataTempo(round((float)$value->duracao / 3600, 2) * 3600);
                    $resultadoProdutividade = array_merge($resultadoProdutividade, $produtividade);
                }
            }
        }
        return $resultadoProdutividade;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getContractConsumption($fk_empresa)
    {
        $filter = $_POST['filter'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
        $tarifa = $parametros->tarifa_energia;
        $resultado = array();

        $registrosConsumo = ($filter == 'all') ? BackupRelatorios::getTempoTotalContrato('', $iniDate, $endDate, $fk_empresa)
            : BackupRelatorios::getTempoTotalContrato($filter, $iniDate, $endDate, $fk_empresa);

        foreach ($registrosConsumo as $item) {
            $potenciaPC = 200;
            $valor = round((($potenciaPC * ($item->duracao / 3600)) / 1000) * $tarifa, 2);
            $contrato = Contrato::model()->findByPk($item->fk_obra);

            $mesString = MetodosGerais::mesString($item->mes);
            $gasto[$contrato->nome][$mesString] = array(
                'brlFloatConsumption' => (float)$valor,
                'brlFormatConsumption' => 'R$' . MetodosGerais::float2real($valor),
                'usdFloatConsumption' => MetodosGerais::conversaoReal($valor, 'dolar'),
                'usdFormatConsumption' => '$' . MetodosGerais::conversaoReal($valor, 'dolar')
            );
            $resultado = array_merge($resultado, $gasto);
        }

        return $resultado;
    }

    /**
     * @param $fk_empresa
     * @return array
     */
    private function getMetricsReport($fk_empresa)
    {
        $filter = $_POST['filter'];
        $type = $_POST['type'];
        $iniDate = $_POST['iniDate'];
        $endDate = $_POST['endDate'];
        $resultado = array();

        $serial = Empresa::model()->findByPK($fk_empresa)->serial;
        $metricas = Metrica::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa));
        foreach ($metricas as $metrica) {
            $isSite = SitePermitido::model()->findByAttributes(array('nome' => $metrica->programa, 'fk_empresa' => $fk_empresa));
            $dias_uteis = BackupRelatorios::dias_uteis(strtotime($iniDate), strtotime($endDate), $fk_empresa);
            $criterio = (!empty($metrica->criterio)) ? $metrica->criterio : $metrica->programa;
            $resultado[$metrica->titulo]['info'] = array(
                'performance' => $metrica->atuacao,
                'application' => $metrica->programa,
                'criterion' => $criterio,
            );
            // EQUIPES //
            if ($type == 'team' || $type == 'all') {
                $equipes = ($type == 'all' || $filter == 'all') ? Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa))
                    : Equipe::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'id' => $filter));
                foreach ($equipes as $equipe) {
                    $logs = (isset($isSite)) ?
                        BackupRelatorios::getLogsMetricasEquipe($metrica->programa, $metrica->criterio, 1, $iniDate, $endDate, $equipe->id, 1, $serial) :
                        BackupRelatorios::getLogsMetricasEquipe($metrica->programa, $metrica->criterio, $metrica->sufixo, $iniDate, $endDate, $equipe->id, 0, $serial);
                    if (!empty($logs)) {
                        $responsavel = (isset($responsavel)) ? $responsavel : 'undefined';
                        $tempoTotal = 0;

                        foreach ($logs as $log) $tempoTotal += MetodosGerais::time_to_seconds($log->duracao);

                        $resultado[$metrica->titulo]['teams'][$equipe->nome] = array(
                            'inputs' => count($logs),
                            'totalTime' => MetodosGerais::formataTempo($tempoTotal),
                            'averageTimePerDay' => MetodosGerais::formataTempo(((($tempoTotal / 60) / $dias_uteis)) * 60)
                        );
                    }
                }
            }


            // COLABORADORES //
            if ($type == 'employee' || $type == 'all') {
                $colaboradores = $this->getEmployeeByFilter($fk_empresa, $type, $filter);
                foreach ($colaboradores as $colaborador) {
                    $logs = (isset($isSite)) ?
                        BackupRelatorios::getLogsMetricas($metrica->programa, $metrica->criterio, 1, $iniDate, $endDate, $colaborador->ad, 1, $serial) :
                        BackupRelatorios::getLogsMetricas($metrica->programa, $metrica->criterio, $metrica->sufixo, $iniDate, $endDate, $colaborador->ad, 0, $serial);
                    if (!empty($logs)) {
                        $tempoTotal = 0;
                        foreach ($logs as $log) $tempoTotal += MetodosGerais::time_to_seconds($log->duracao);

                        $resultado[$metrica->titulo]['employees'][$colaborador->nomeCompleto] = array(
                            'inputs' => count($logs),
                            'totalTime' => MetodosGerais::formataTempo($tempoTotal),
                            'averageTimePerDay' => MetodosGerais::formataTempo(((($tempoTotal / 60) / $dias_uteis)) * 60)
                        );
                    }
                }
            }
            if (count($resultado[$metrica->titulo]) < 2)
                unset($resultado[$metrica->titulo]);
        }
        return $resultado;
    }


    /**
     * @return bool
     */
    private function checkAuth()
    {
        $empresa = Empresa::model()->findByAttributes(array('serial' => $_POST['serial']));
        return ($empresa) ? $empresa->id : false;
    }

    /**
     * @param int $type
     * @param int $iniAndEndDate
     */

    private function checkParameters($type = 1, $iniAndEndDate = 1)
    {
        $alert = array('status_code' => 400, 'status' => 'Error', 'errors' => array(), 'response' => array());

        /*
         * Verificação se foi enviado o filtro ou tipo para consulta.
         */
        if ((!isset($_POST['filter'])) || (empty($_POST['filter'])))
            array_push($alert['errors'], array('message' => 'Missing filter'));
        if (($type) && ((!isset($_POST['type'])) || (empty($_POST['type']))))
            array_push($alert['errors'], array('message' => 'Missing type'));

        /*
         *  Verificação se foi enviado data(s) para consulta.
         */

        if ($iniAndEndDate) {
            if ((!isset($_POST['iniDate']) || empty($_POST['iniDate'])) && (!isset($_POST['endDate']) || empty($_POST['endDate'])))
                array_push($alert['errors'], array('message' => 'Missing iniDate and endDate'));
            else {
                if (!isset($_POST['iniDate']) || empty($_POST['iniDate']))
                    array_push($alert['errors'], array('message' => 'Missing iniDate'));
                if (!isset($_POST['endDate']) || empty($_POST['endDate']))
                    array_push($alert['errors'], array('message' => 'Missing endDate'));
            }
        } else {
            if ((!isset($_POST['date']) || empty($_POST['date'])))
                array_push($alert['errors'], array('message' => 'Missing date'));
        }

        /*
         * Verificação de iniDate > endDate || endDate > today.
         */
        if ((isset($_POST['iniDate']) || !empty($_POST['iniDate'])) && (isset($_POST['endDate']) || !empty($_POST['endDate'])) && !isset($_POST['date'])) {
            if (strtotime($_POST['iniDate']) > strtotime($_POST['endDate']))
                array_push($alert['errors'], array('message' => 'iniDate cannot be bigger than endDate'));
            if (strtotime($_POST['endDate']) > strtotime(date('Y-m-d')))
                array_push($alert['errors'], array('message' => 'endDate cannot be bigger than actual date'));
        }

        if (!empty($alert['errors']))
            exit(json_encode($alert));
    }

    /**
     * @param $fk_empresa
     * @param $type
     * @param $filter
     * @return array|mixed|null
     */
    private function getEmployeeByFilter($fk_empresa, $type, $filter)
    {
        $colaboradores = array();
        if ($type == 'employee' || $type == 'all')
            $colaboradores = ($type == 'all' || $filter == 'all') ? Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'ativo' => 1, 'status' => 1))
                : Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'id' => $filter));
        if ($type == 'team' || $type == 'all')
            $colaboradores = ($type == 'all' || $filter == 'all') ? Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'ativo' => 1, 'status' => 1))
                : Colaborador::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'fk_equipe' => $filter));
        return $colaboradores;
    }
}