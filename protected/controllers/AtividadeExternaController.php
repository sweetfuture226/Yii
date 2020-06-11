<?php

class AtividadeExternaController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'create', 'delete', 'getPlanilha', 'ImportarPlanilha', 'CreateFromAjax', 'CreateAtividadeExterna'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Atividades externas");
        $this->pageTitle = Yii::t("smith", "Atividades externas");
        $model = new AtividadeExterna('searchExtra');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AtividadeExterna']))
            $model->attributes = $_GET['AtividadeExterna'];
        $idUser = Yii::app()->user->id;
        $usuario = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $usuario->serial_empresa;
        $fk_empresa = $usuario->fk_empresa;

        $condicao = ' fk_empresa = ' . $fk_empresa ;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();
        }
        LogAcesso::model()->saveAcesso('Contratos', 'Atividades externas', 'Atividades externas', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
            'serial' => $serial,
            'fk_empresa' => $fk_empresa,
            'condicao'=>$condicao,
        ));
    }

    /**
     * @param $fk_colaborador
     * @param $descricao
     * @param $codigo_contrato
     * @param $data_inicio
     * @param $data_fim
     * Criar atividade externa a partir do modal de ausencia de 2 dias  da central de notificações
     */
    public function actionCreateAtividadeExterna($fk_colaborador, $descricao, $codigo_contrato, $data_inicio, $data_fim)
    {
        $data_inicio = MetodosGerais::dataAmericana($data_inicio);
        $data_fim = MetodosGerais::dataAmericana($data_fim);
        $dias_sem_produtividade = ColaboradorSemProdutividade::model()->findAll(array("condition" => "fk_colaborador = $fk_colaborador AND data >= '$data_inicio' AND data <= '$data_fim'"));
        $colaborador = Colaborador::model()->findByAttributes(array("id" => $fk_colaborador, "fk_empresa" => MetodosGerais::getEmpresaId()));
        foreach ($dias_sem_produtividade as $key => $value) {
            $model = new AtividadeExterna;
            $model->programa = "Atividade Externa";
            $model->title_completo = "Atividade Externa " . $descricao;
            $model->descricao = $codigo_contrato . " - " . $descricao;
            $model->data = $value->data;
            $model->usuario = $colaborador->ad;
            $model->hora_host = "18:00:00";
            $model->data_hora_servidor = $model->data . " " . MetodosGerais::setHoraServidor($model->hora_host);
            $model->hora_saida = "08:00:00";
            $model->duracao = "08:00:00";
            $model->serial_empresa = LogAtividade::model()->getSerial();
            $model->atividade_extra = 1;
            if ($model->save()) {
                $log = new LogAtividade();
                $log->usuario = $colaborador->ad;
                $log->programa = "Atividade Externa";
                $log->descricao = $codigo_contrato . " - " . $descricao;
                $log->duracao = $model->duracao;
                $log->data = $model->data;
                $log->title_completo = $log->programa . $log->descricao;
                $log->serial_empresa = MetodosGerais::getSerial();
                if ($log->save(false)) {
                    $modelLogConsolidacao = new LogAtividadeConsolidado();
                    $modelLogConsolidacao->usuario = $colaborador->ad;
                    $modelLogConsolidacao->programa = "Atividade Externa";
                    $modelLogConsolidacao->descricao = $codigo_contrato . " - " . $descricao;
                    $modelLogConsolidacao->duracao = $log->duracao;
                    $modelLogConsolidacao->data = MetodosGerais::dataAmericana($value->data);
                    $modelLogConsolidacao->title_completo = $modelLogConsolidacao->programa . $modelLogConsolidacao->descricao;
                    $modelLogConsolidacao->serial_empresa = MetodosGerais::getSerial();
                    $modelLogConsolidacao->num_logs = 1;
                    $modelLogConsolidacao->save(false);

                    /*
                     * Adicionar produtividade na tabela consolidada de produtividade caso a inserção da atividade externa foi de uma atividade
                     * com dia de execução anterior da data de hoje
                     */
                    if (strtotime(date('Y-m-d')) > strtotime($log->data)) {
                        $produtividade = GrfProdutividadeConsolidado::model()->findByAttributes(array("fk_colaborador" => $colaborador->id, 'data' => $log->data));
                        if (isset($produtividade)) {
                            $produtividade->duracao += (MetodosGerais::time_to_seconds($log->duracao) / 3600);
                            $produtividade->save(false);
                        } else {
                            $produtividadeConsolidada = new GrfProdutividadeConsolidado();
                            $produtividadeConsolidada->equipe = Equipe::model()->findByPk($colaborador->fk_equipe)->nome;
                            $produtividadeConsolidada->nome = $colaborador->nome;
                            $produtividadeConsolidada->duracao = (MetodosGerais::time_to_seconds($log->duracao) / 3600);
                            $produtividadeConsolidada->hora_total = (MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) / 5;
                            $produtividadeConsolidada->data = $log->data;
                            $produtividadeConsolidada->fk_colaborador = $colaborador->id;
                            $produtividadeConsolidada->fk_empresa = MetodosGerais::getEmpresaId();
                            $produtividadeConsolidada->save(false);
                        }
                    }
                }


                $value->delete();
                echo "success";
            } else {
                echo "<pre>";
                print_r($model->getErrors());
                echo "</pre>";
            }
        }
    }

    /**
     * @param $data
     * @param $fk_colaborador
     * @param $ad
     * @param $descricao
     * @param $fk_log
     * @param $duracao
     * @param $hora_inicial
     * @param $hora_final
     * @param $codigo_contrato
     *
     * Criar atividade externa a partir do modal de ausencia de 2 horas  da central de notificações
     */
    public function actionCreateFromAjax($data, $fk_colaborador, $ad, $descricao, $fk_log, $duracao, $hora_inicial, $hora_final, $codigo_contrato)
    {
        $fkColaborador = Colaborador::model()->findByAttributes(array("id" => $fk_colaborador, "fk_empresa" => MetodosGerais::getEmpresaId()));
        $log = LogAtividade::model()->findByPk($fk_log);
        $model = new AtividadeExterna;
        $model->usuario = $fkColaborador->ad;
        $model->programa = "Atividade Externa";
        $model->descricao = $codigo_contrato . " - " . $descricao;
        $model->title_completo = "Atividade Externa " . $descricao;
        $model->data = MetodosGerais::dataAmericana($data);
        $model->nome_host = $log->nome_host;
        $model->duracao = $log->duracao;
        $model->hora_host = $hora_final;
        $model->hora_saida = $hora_inicial;
        $model->data_hora_servidor = $model->data . " " . MetodosGerais::setHoraServidor($hora_final);
        $model->serial_empresa = LogAtividade::model()->getSerial();
        $model->atividade_extra = 1;
        if ($model->save()) {
            $log->usuario = $fkColaborador->ad;
            $log->programa = "Atividade Externa";
            $log->descricao = $codigo_contrato . " - " . $descricao;
            $log->duracao = $model->duracao;
            $log->data = $model->data;
            $log->title_completo = $log->programa . $log->descricao;
            $log->serial_empresa = LogAtividade::model()->getSerial();
            if ($log->save(false)) {
                GrfOciosidadeConsolidado::model()->updateAll(array('status' => 1), array('condition' => "fk_log = " . $fk_log));
                $modelLogConsolidacao = new LogAtividadeConsolidado();
                $modelLogConsolidacao->usuario = $fkColaborador->ad;
                $modelLogConsolidacao->programa = "Atividade Externa";
                $modelLogConsolidacao->descricao = $codigo_contrato . " - " . $descricao;
                $modelLogConsolidacao->duracao = $log->duracao;
                $modelLogConsolidacao->data = MetodosGerais::dataAmericana($data);
                $modelLogConsolidacao->title_completo = $modelLogConsolidacao->programa . $modelLogConsolidacao->descricao;
                $modelLogConsolidacao->serial_empresa = MetodosGerais::getSerial();
                $modelLogConsolidacao->num_logs = 1;
                $modelLogConsolidacao->save(false);

                /*
                 * Adicionar produtividade na tabela consolidada de produtividade caso a inserção da atividade externa foi de uma atividade
                 * com dia de execução anterior da data de hoje
                 */
                if (strtotime(date('Y-m-d')) > strtotime($log->data)) {
                    $produtividade = GrfProdutividadeConsolidado::model()->findByAttributes(array("fk_colaborador" => $fkColaborador->id, 'data' => $log->data));
                    if (isset($produtividade)) {
                        $produtividade->duracao += (MetodosGerais::time_to_seconds($log->duracao) / 3600);
                        $produtividade->save(false);
                    } else {
                        $produtividadeConsolidada = new GrfProdutividadeConsolidado();
                        $produtividadeConsolidada->equipe = Equipe::model()->findByPk($fkColaborador->fk_equipe)->nome;
                        $produtividadeConsolidada->nome = $fkColaborador->nome;
                        $produtividadeConsolidada->duracao = (MetodosGerais::time_to_seconds($log->duracao) / 3600);
                        $produtividadeConsolidada->hora_total = (MetodosGerais::time_to_seconds($fkColaborador->horas_semana) / 3600) / 5;
                        $produtividadeConsolidada->data = $log->data;
                        $produtividadeConsolidada->fk_colaborador = $fkColaborador->id;
                        $produtividadeConsolidada->fk_empresa = MetodosGerais::getEmpresaId();
                        $produtividadeConsolidada->save(false);
                    }
                }
            }
            echo "ok";
        } else {
            echo "<pre>";
            print_r($model->getErrors());
            echo "</pre>";
        }
    }

    public function actionCreate() {
        $this->title_action =  Yii::t("smith","Atividades externas");
        $model = new AtividadeExterna;
        $this->pageTitle =  Yii::t("smith","Atividades externas");
        $flag = false;
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa;
        $condicaoCol = ' fk_empresa = ' . $fk_empresa ;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND id in (SELECT fk_contrato FROM usuario_has_contrato WHERE fk_usergroups_user = " . Yii::app()->user->id . ")";
            $condicaoCol = "fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();
        }

        if (isset($_POST['AtividadeExterna'])) {
            $start = MetodosGerais::inicioContagem();
            $parametro = EmpresaHasParametro::model()->findByAttributes(array('fk_empresa' => MetodosGerais::getEmpresaId()));
            $model->attributes = $_POST['AtividadeExterna'];
            $model->usuario = 'teste';
            $model->data = MetodosGerais::dataAmericana($model->data);
           // if($model->save()){
                foreach ($_POST['AtividadeExterna']['usuario'] as $usuario) {
                    $duracaoAux = MetodosGerais::time_to_minutes($_POST['AtividadeExterna']['hora_host'] . ":00") - MetodosGerais::time_to_minutes($_POST['AtividadeExterna']['hora_saida'] . ":00");
                    $duracao = ($duracaoAux > 480) ? 480 : $duracaoAux;
                    $registrosExcluidos = LogAtividade::model()->getRegistrosExcl($usuario, $_POST['AtividadeExterna']);
                    $tmp = $this->PermutarRegistrosLog($registrosExcluidos);
                    $horas = floor($duracao/60);
                    $resto = $duracao%60;
                    for($i=0;$i<$horas;$i++){
                        $logAtividade = new LogAtividade;
                        $logAtividade->attributes = $_POST['AtividadeExterna'];
                        $logAtividade->usuario = $usuario;
                        $logAtividade->programa = "Atividade Externa";
                        $logAtividade->descricao = $_POST['AtividadeExterna']['obra'] . " - " . $_POST['AtividadeExterna']['descricao'];
                        $logAtividade->duracao = '01:00:00';
                        $logAtividade->data = MetodosGerais::dataAmericana($_POST['AtividadeExterna']['data']);
                        $logAtividade->title_completo = $logAtividade->programa . $logAtividade->descricao;
                        $logAtividade->serial_empresa = LogAtividade::model()->getSerial();
                        $logAtividade->fk_empresa = MetodosGerais::getEmpresaId();
                        $logAtividade->atividade_extra = 1;
                        $adicionarHora = '+'.$i.' hour';
                        $horario_servidor = date('H:i:s', strtotime($adicionarHora, strtotime($_POST['AtividadeExterna']['hora_saida'] . ":00")));
                        $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($horario_servidor);
                        $logAtividade->hora_host = $horario_servidor;
                        if (($duracaoAux > 480) && $logAtividade->hora_host >= $parametro->almoco_inicio) {
                            $duracaoAlmoco = (MetodosGerais::time_to_seconds($parametro->almoco_fim) - MetodosGerais::time_to_seconds($parametro->almoco_inicio)) / 3600;
                            $adicionarHora = '+' . (($i + $duracaoAlmoco) * 60) . ' minutes';
                            $horario_servidor = date('H:i:s', strtotime($adicionarHora, strtotime($_POST['AtividadeExterna']['hora_saida'] . ":00")));
                            $logAtividade->hora_host = $horario_servidor;
                            $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($horario_servidor);
                        }
                        $logAtividade->save();
                    }
                    $logAtividade = new LogAtividade;
                    $logAtividade->attributes = $_POST['AtividadeExterna'];
                    $logAtividade->usuario = $usuario;
                    $logAtividade->programa = "Atividade Externa";
                    $logAtividade->descricao = $_POST['AtividadeExterna']['obra'] . " - " . $_POST['AtividadeExterna']['descricao'];
                    $logAtividade->duracao = '00'.$resto.'00';
                    $logAtividade->data = MetodosGerais::dataAmericana($_POST['AtividadeExterna']['data']);
                    $logAtividade->title_completo = $logAtividade->programa . $logAtividade->descricao;
                    $logAtividade->serial_empresa = LogAtividade::model()->getSerial();
                    $logAtividade->fk_empresa = MetodosGerais::getEmpresaId();
                    $logAtividade->atividade_extra = 1;
                    $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($_POST['AtividadeExterna']['hora_host'] . ":00");
                    $logAtividade->hora_host = $_POST['AtividadeExterna']['hora_host'] . ":00";

                    if($logAtividade->save()){
                        $model = new AtividadeExterna;
                        $model->usuario = $usuario;
                        $model->programa = "Atividade Externa";
                        $model->obra = $_POST['AtividadeExterna']['obra'];
                        $model->hora_saida = $_POST['AtividadeExterna']['hora_saida'] . ":00";
                        $model->descricao = $_POST['AtividadeExterna']['obra'] . " - " . $_POST['AtividadeExterna']['descricao'];
                        $duracaoAux = MetodosGerais::time_to_seconds($_POST['AtividadeExterna']['hora_host'] . ":00") - MetodosGerais::time_to_seconds($_POST['AtividadeExterna']['hora_saida'] . ":00");
                        $duracao = ($duracaoAux > 28800) ? gmdate('H:i:s', 28800) : gmdate('H:i:s', $duracaoAux);
                        $model->duracao = $duracao;
                        $model->data = MetodosGerais::dataAmericana($_POST['AtividadeExterna']['data']);
                        $model->data_hora_servidor = $model->data . " " . MetodosGerais::setHoraServidor($_POST['AtividadeExterna']['hora_host'] . ":00");
                        $model->title_completo = $model->programa . $model->descricao;
                        $model->hora_host = $_POST['AtividadeExterna']['hora_host'] . ":00";
                        $model->nome_host = "Atividade Externa - " . Empresa::model()->findByPk(MetodosGerais::getEmpresaId())->nome;
                        $model->host_domain = "Atividade Externa - " . Empresa::model()->findByPk(MetodosGerais::getEmpresaId())->nome;
                        $model->serial_empresa = LogAtividade::model()->getSerial();
                        $model->atividade_extra = 1;
                        $model->fk_log = $logAtividade->id;
                        if($model->save()){
                            $modelLogConsolidacao = new LogAtividadeConsolidado();
                            $modelLogConsolidacao->usuario = $usuario;
                            $modelLogConsolidacao->programa = "Atividade Externa";
                            $modelLogConsolidacao->descricao = $_POST['AtividadeExterna']['obra'] . " - " . $_POST['AtividadeExterna']['descricao'];
                            $duracaoAux = MetodosGerais::time_to_seconds($_POST['AtividadeExterna']['hora_host'] . ":00") - MetodosGerais::time_to_seconds($_POST['AtividadeExterna']['hora_saida'] . ":00");
                            $duracao = ($duracaoAux > 28800) ? gmdate('H:i:s', 28800) : gmdate('H:i:s', $duracaoAux);
                            $modelLogConsolidacao->duracao = $duracao;
                            $modelLogConsolidacao->data = MetodosGerais::dataAmericana($_POST['AtividadeExterna']['data']);
                            $modelLogConsolidacao->title_completo = $modelLogConsolidacao->programa . $modelLogConsolidacao->descricao;
                            $modelLogConsolidacao->serial_empresa = LogAtividade::model()->getSerial();
                            $modelLogConsolidacao->num_logs = 1;
                            $modelLogConsolidacao->save(false);
                            /*
                             * Adicionar produtividade na tabela consolidada de produtividade caso a inserção da atividade externa foi de uma atividade
                             * com dia de execução anterior da data de hoje
                             */
                            if(strtotime(date('Y-m-d'))>strtotime($modelLogConsolidacao->data)){
                                $fkColaborador = Colaborador::model()->findByAttributes(array("ad" => $usuario, "fk_empresa" => MetodosGerais::getEmpresaId()));
                                $produtividade = GrfProdutividadeConsolidado::model()->findByAttributes(array("fk_colaborador"=>$fkColaborador->id,'data'=>$modelLogConsolidacao->data));
                                if(isset($produtividade)){
                                    $produtividade->duracao += (MetodosGerais::time_to_seconds($duracao)/3600);
                                    $produtividade->save(false);
                                }
                                if(isset($produtividade) && $tmp>0){
                                    $produtividade->duracao -= ($tmp/3600);
                                    $produtividade->save(false);
                                }else{
                                    $produtividadeConsolidada = new GrfProdutividadeConsolidado();
                                    $produtividadeConsolidada->equipe = Equipe::model()->findByPk($fkColaborador->fk_equipe)->nome;
                                    $produtividadeConsolidada->nome = $fkColaborador->nome;
                                    $produtividadeConsolidada->duracao =  (MetodosGerais::time_to_seconds($duracao)/3600);
                                    $produtividadeConsolidada->hora_total = (MetodosGerais::time_to_seconds($fkColaborador->horas_semana)/3600)/5;
                                    $produtividadeConsolidada->data = $modelLogConsolidacao->data;
                                    $produtividadeConsolidada->fk_colaborador = $fkColaborador->id;
                                    $produtividadeConsolidada->fk_empresa = MetodosGerais::getEmpresaId();
                                    $produtividadeConsolidada->save(false);
                                }
                            }
                            $flag = true;
                        }
                    }
                }
                if($flag){
                    LogAcesso::model()->saveAcesso('Contratos', 'Atividade Externas', 'Criar', MetodosGerais::tempoResposta($start));
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Atividade externa inserida com sucesso.'));
                    $this->redirect(array('index'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('smith', 'Atividade externa não pôde ser inserida.'));
                }
           // }
        }

        $this->render('create', array(
            'model' => $model,
            'condicao' => $condicao,
            'condicaoCol'=>$condicaoCol,
        ));
    }

    /**
     * @param $registros
     * @return int
     *
     * Metodo auxiliar para salvar os registros da tabela de log_atividade que tiveram conflitos de horário com a atividade externa
     * na tabela de log_historico.
     */
    public function PermutarRegistrosLog($registros){
        $duracao=0;
        foreach($registros as $value){
            $modelTeste = new LogAtividadeHistorico();
            $modelTeste->id = $value->id;
            $modelTeste->usuario = $value->usuario;
            $modelTeste->programa =$value->programa;
            $modelTeste->descricao = $value->descricao;
            $modelTeste->duracao = $value->duracao;
            $modelTeste->data = $value->data;
            $modelTeste->hora_host = $value->data_hora_servidor;
            $modelTeste->serial_empresa = $value->serial_empresa;
            $modelTeste->fk_empresa = $value->fk_empresa;
            if($modelTeste->save()){
                $duracao += MetodosGerais::time_to_seconds($value->duracao);
                $value->delete();
            }
        }
        return $duracao;
    }

    /**
     * @param $data
     * @param $row
     * @return string
     *
     * Metodo auxiliar para converter a hora do servidor para a hora da bahia.
     */
    public function getHoraServidor($data, $row) {
        $dateTime = new DateTime($data->data_hora_servidor, new DateTimeZone('America/New_York'));
        $dateTime->setTimezone(new DateTimeZone('America/Bahia'));
        return $dateTime->format('H:i:s');
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            $log = LogAtividade::model()->findByPk($model->fk_log);
            $model->delete();
            if(isset($log))
                $log->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('smith', 'Requisição inválida. Por favor não repita esta requisição novamente.'));
    }


    public function actionGetPlanilha()
    {
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->getProperties()->setCreator("Smith")
            ->setLastModifiedBy("Smith")
            ->setTitle("Atividades externas");

        $phpExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', Yii::t('smith', 'Contrato'))
            ->setCellValue('B1', Yii::t('smith', 'Colaborador'))
            ->setCellValue('C1', Yii::t('smith', 'Descrição da atividade'))
            ->setCellValue('D1', Yii::t('smith', 'Horário de saída (HH:mm)'))
            ->setCellValue('E1', Yii::t('smith', 'Horário de chegada (HH:mm)'))
            ->setCellValue('F1', Yii::t('smith', 'Data'));
        $phpExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->setTitle('Atividade Externa');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(27);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);


        $fkEmpresa = MetodosGerais::getEmpresaId();
        $colaboradores = Colaborador::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa =' . $fkEmpresa . ' AND ativo = 1 AND status = 1'));
        $contratos = Contrato::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa =' . $fkEmpresa . ' AND ativo = 1'));

        $stringColaborador = '';
        $isPrimeiro = true;
        foreach ($colaboradores as $colaborador) {
            $stringColaborador .= (!$isPrimeiro) ? ',' . $colaborador->nome . ' ' . $colaborador->sobrenome
                : $colaborador->nome . ' ' . $colaborador->sobrenome;
            $isPrimeiro = false;
        }

        $arrayColaborador = explode(",", $stringColaborador);
        $i = 1;
        $newSheet = $phpExcel->createSheet();
        $newSheet->setTitle('Colaboradores');
        foreach ($arrayColaborador as $item) {
            $phpExcel->setActiveSheetIndex(1)
                ->setCellValue('A' . $i, $item);
            $i++;
        }
        $intervalo = 'A1:A' . (count($arrayColaborador));

        $phpExcel->addNamedRange(
            new PHPExcel_NamedRange(
                'colaboradores',
                $phpExcel->setActiveSheetIndex(1),
                $intervalo
            )
        );


        $stringContrato = '';
        $isPrimeiro = true;
        foreach ($contratos as $contrato) {
            $stringContrato .= (!$isPrimeiro) ? ';' . '[' . $contrato->codigo . ']-' . $contrato->nome
                :'[' .$contrato->codigo . ']-' . $contrato->nome;
            $isPrimeiro = false;
        }

        $arrayContrato = explode(";", $stringContrato);
        $i = 1;
        $newSheet = $phpExcel->createSheet();
        $newSheet->setTitle('Contratos');
        foreach ($arrayContrato as $item) {
            $phpExcel->setActiveSheetIndex(2)
                ->setCellValue('A' . $i, $item);
            $i++;
        }
        $intervalo = 'A1:A' . (count($arrayContrato));

        $phpExcel->addNamedRange(
            new PHPExcel_NamedRange(
                'contratos',
                $phpExcel->setActiveSheetIndex(2),
                $intervalo
            )
        );

        for ($i = 2; $i <= 250; $i++) {
            $phpExcel->setActiveSheetIndex(0);
            $phpExcel->getActiveSheet()
                ->getCell('B' . $i)
                ->getDataValidation()
                ->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
                ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError(Yii::t('smith', 'Colaborador não encontra-se cadastrado no sistema.'))
                ->setPrompt(Yii::t('smith', 'Escolha um colaborador'))
                ->setFormula1('=colaboradores');

            $phpExcel->getActiveSheet()
                ->getCell('A' . $i)
                ->getDataValidation()
                ->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
                ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError(Yii::t('smith', 'Contrato não encontra-se cadastrado no sistema.'))
                ->setPrompt(Yii::t('smith', 'Escolha um contrato'))
                ->setFormula1('=contratos');

            $phpExcel->getActiveSheet()->getStyle('F' . $i)
                ->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
        }


        $phpExcel->getActiveSheet()
            ->protectCells('A1:F1', 'PHP');

        try {
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
            $filename = 'atividadeExternaModelo.xlsx';
            $objWriter->save($filename);
        } catch (Exception $e) {
            Logger::saveError($e);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        flush();
        readfile($filename);
        unlink($filename);
    }

    public function actionImportarPlanilha()
    {
        if (!empty($_POST['nameFile'])) {
            $fullPath = Yii::app()->basePath . '/../public/' . $_POST['nameFile'];
            $idEmpresa = $_POST['fk_empresa'];

            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
            require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

            try {
                $fileType = PHPExcel_IOFactory::identify($fullPath);
                $reader = PHPExcel_IOFactory::createReader($fileType);
                $objPHPExcel = $reader->load($fullPath);

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell('D' . $row)->getValue() != "" && $sheet->getCell('E' . $row)->getValue() != "") {
                        $usuario = $sheet->getCell('B' . $row)->getValue();
                        $objColaborador = Colaborador::model()->findColaboradorByNomeCompleto($usuario);
                        $horarioSaida = MetodosCSV::strTempoByTipo($sheet->getCell('D' . $row)->getValue(), $sheet->getCell('D' . $row)->getDataType());
                        $horarioChegada = MetodosCSV::strTempoByTipo($sheet->getCell('E' . $row)->getValue(), $sheet->getCell('E' . $row)->getDataType());
                        $data = (is_float($sheet->getCell('F' . $row)->getValue())) ? gmdate('d/m/Y', ($sheet->getCell('F' . $row)->getValue() - 25569) * 86400) : $sheet->getCell('F' . $row)->getValue();
                        $duracao = MetodosGerais::time_to_minutes($horarioChegada) - MetodosGerais::time_to_minutes($horarioSaida);
                        $dados = array('data' => $data, 'hora_saida' => $horarioSaida, 'hora_host' => $horarioChegada);
                        $registrosExcluidos = LogAtividade::model()->getRegistrosExcl($objColaborador->ad, $dados, true);
                        $tmp = $this->PermutarRegistrosLog($registrosExcluidos);
                        $horas = floor($duracao / 60);
                        $resto = $duracao % 60;
                        preg_match('#\[(.*?)\]#', $sheet->getCell('A' . $row)->getValue(), $codigo_contrato);
                        $codigo_contrato = $codigo_contrato[1];
                        for ($i = 0; $i < $horas; $i++) {
                            $logAtividade = new LogAtividade;
                            $logAtividade->usuario = $objColaborador->ad;
                            $logAtividade->programa = "Atividade Externa";
                            $logAtividade->descricao = $codigo_contrato . " - " . $sheet->getCell('B' . $row)->getValue();
                            $logAtividade->duracao = '01:00:00';
                            $logAtividade->data = MetodosGerais::dataAmericana($data);
                            $logAtividade->title_completo = $logAtividade->programa . $logAtividade->descricao;
                            $logAtividade->serial_empresa = LogAtividade::model()->getSerial();
                            $logAtividade->fk_empresa = MetodosGerais::getEmpresaId();
                            $logAtividade->atividade_extra = 1;
                            $adicionarHora = '+' . $i . ' hour';
                            $horario_servidor = date('H:i:s', strtotime($adicionarHora, strtotime($horarioSaida)));
                            $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($horario_servidor);
                            $logAtividade->hora_host = date('H:i:s', strtotime($adicionarHora, strtotime($horarioSaida)));
                            $logAtividade->save();
                        }
                        $logAtividade = new LogAtividade;
                        $logAtividade->usuario = $objColaborador->ad;
                        $logAtividade->programa = "Atividade Externa";
                        $logAtividade->descricao = $codigo_contrato . " - " . $sheet->getCell('B' . $row)->getValue();
                        $logAtividade->duracao = '00' . $resto . '00';
                        $logAtividade->data = MetodosGerais::dataAmericana($data);
                        $logAtividade->title_completo = $logAtividade->programa . $logAtividade->descricao;
                        $logAtividade->serial_empresa = LogAtividade::model()->getSerial();
                        $logAtividade->fk_empresa = MetodosGerais::getEmpresaId();
                        $logAtividade->atividade_extra = 1;
                        $logAtividade->data_hora_servidor = $logAtividade->data . " " . MetodosGerais::setHoraServidor($horarioChegada);
                        $logAtividade->hora_host = $horarioChegada;

                        if ($logAtividade->save()) {
                            $model = new AtividadeExterna;
                            $model->usuario = $objColaborador->ad;
                            $model->programa = "Atividade Externa";
                            $model->obra = $codigo_contrato;
                            $model->hora_saida = $horarioSaida;
                            $model->descricao = $codigo_contrato . " - " . $sheet->getCell('B' . $row)->getValue();
                            $duracao = gmdate('H:i:s', (MetodosGerais::time_to_seconds($horarioChegada) - MetodosGerais::time_to_seconds($horarioSaida)));
                            $model->duracao = $duracao;
                            $model->data = MetodosGerais::dataAmericana($data);
                            $model->data_hora_servidor = $model->data . " " . MetodosGerais::setHoraServidor($horarioChegada);
                            $model->title_completo = $model->programa . $model->descricao;
                            $model->hora_host = $horarioChegada;
                            $model->serial_empresa = LogAtividade::model()->getSerial();
                            $model->atividade_extra = 1;
                            $model->fk_log = $logAtividade->id;
                            if ($model->save()) {
                                $modelLogConsolidacao = new LogAtividadeConsolidado();
                                $modelLogConsolidacao->usuario = $usuario;
                                $modelLogConsolidacao->programa = "Atividade Externa";
                                $modelLogConsolidacao->descricao = $codigo_contrato . " - " . $sheet->getCell('B' . $row)->getValue();
                                $duracao = gmdate('H:i:s', (MetodosGerais::time_to_seconds($horarioChegada) - MetodosGerais::time_to_seconds($horarioSaida)));
                                $modelLogConsolidacao->duracao = $duracao;
                                $modelLogConsolidacao->data = MetodosGerais::dataAmericana($data);
                                $modelLogConsolidacao->title_completo = $modelLogConsolidacao->programa . $modelLogConsolidacao->descricao;
                                $modelLogConsolidacao->serial_empresa = LogAtividade::model()->getSerial();
                                $modelLogConsolidacao->num_logs = 1;
                                $modelLogConsolidacao->save(false);
                                /*
                                 * Adicionar produtividade na tabela consolidada de produtividade caso a inserção da atividade externa foi de uma atividade
                                 * com dia de execução anterior da data de hoje
                                 */
                                if (strtotime(date('Y-m-d')) > strtotime($modelLogConsolidacao->data)) {
                                    $produtividade = GrfProdutividadeConsolidado::model()->findByAttributes(array("fk_colaborador" => $objColaborador->id, 'data' => $modelLogConsolidacao->data));
                                    if (isset($produtividade)) {
                                        $produtividade->duracao += (MetodosGerais::time_to_seconds($duracao) / 3600);
                                        $produtividade->save(false);
                                    }
                                    if (isset($produtividade) && $tmp > 0) {
                                        $produtividade->duracao -= ($tmp / 3600);
                                        $produtividade->save(false);
                                    } else {
                                        $produtividadeConsolidada = new GrfProdutividadeConsolidado();
                                        $produtividadeConsolidada->equipe = Equipe::model()->findByPk($objColaborador->fk_equipe)->nome;
                                        $produtividadeConsolidada->nome = $objColaborador->nome;
                                        $produtividadeConsolidada->duracao = (MetodosGerais::time_to_seconds($duracao) / 3600);
                                        $produtividadeConsolidada->hora_total = (MetodosGerais::time_to_seconds($objColaborador->horas_semana) / 3600) / 5;
                                        $produtividadeConsolidada->data = $modelLogConsolidacao->data;
                                        $produtividadeConsolidada->fk_colaborador = $objColaborador->id;
                                        $produtividadeConsolidada->fk_empresa = MetodosGerais::getEmpresaId();
                                        $produtividadeConsolidada->save(false);
                                    }
                                }
                                $flag = true;
                            }
                        }
                    } 

                }
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Atividades externa inserida com sucesso.'));
                $this->redirect('index');
            } catch (Exception $e) {
                unlink($fullPath);
                Logger::sendException($e);
            }
        }

    }


    public function loadModel($id) {
        $model = AtividadeExterna::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'log-atividade-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
