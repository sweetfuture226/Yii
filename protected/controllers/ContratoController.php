<?php

class ContratoController extends Controller
{
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
        $arrayAcessCRUD = (MetodosGerais::checkPermissionAccessContract()) ? array('coordenador', 'empresa', 'root', 'demo') : array('empresa', 'root', 'demo');
        return array(
            array('allow',
                'actions' => array('index', 'AndamentoObra',
                    'close', 'custoEnergia', 'RelatorioIndividual',
                    'GetDataInicioProjeto', 'GetDataFinalizadoProjeto', 'ImportCSV', 'GetDisciplina',
                    'Finalizar', 'RelatorioGeral', 'codigoExiste', 'getObras',
                    'ProdutividadeColaborador', 'relatorioContratoFinalizado', 'RelatorioContratoEmAtraso',
                    'getDocumentos', 'getDocumentoDinamico', 'CreateFromAjax'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('allow',
                'actions' => array('update', 'create', 'delete'),
                'groups' => $arrayAcessCRUD
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->title_action = Yii::t("smith", "Criar Contrato");
        $this->pageTitle = Yii::t("smith", "Criar Contrato");
        $model = new Contrato;

        //verifica se é coordenador ou perfil geral de dono
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa . ' AND username not like "%admin%" ';
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_equipe = " . MetodosGerais::getEquipe();
        }

        //Pre-carregar a moeda dos parametros
        $parametros = EmpresaHasParametro::model()->findByAttributes(array('fk_empresa' => $fk_empresa));
        $model->moeda = $parametros->moeda;

        if (isset($_POST['Contrato'])) {
            $start = MetodosGerais::inicioContagem();
            $model->attributes = $_POST['Contrato'];

            $model->receber_email = 0;
            if ($_POST['Contrato']['receber_email']) $model->receber_email = 1;

            $model->finalizada = (int)$_POST['Contrato']['finalizada'];
            $model->data_inicio = MetodosGerais::dataAmericana($_POST['Contrato']['data_inicio']);
            $model->data_final = MetodosGerais::dataAmericana($_POST['Contrato']['data_final']);
            $model->valor = MetodosGerais::real2float($_POST['Contrato']['valor']);
            $model->serial_empresa = LogAtividade::model()->getSerial();
            $model->moeda = $_POST['Contrato']['moeda'];
            $model->tempo_previsto = "00:00:00";
            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            if (isset($user->fk_empresa))
                $model->fk_empresa = $user->fk_empresa;
            if ($model->save()) {
                if (($_FILES['Documento']['name']['file'] != ""))
                    $this->actionImportCSV($_FILES['Documento'], $model->id);
                elseif (isset($_POST['Documento']))
                    $this->salvaDocumentos($_POST['Documento'], $model->id);
                $usuarioHasObra = new UsuarioHasContrato();
                $usuarioHasObra->fk_contrato = $model->id;
                $usuarioHasObra->fk_usergroups_user = $_POST['Contrato']['coordenador'];
                if ($usuarioHasObra->save()) {
                    if(empty($model->tempo_previsto)){
                        $this->verificaContratoHasDocumento($model->id, $model->codigo);
                    }
                    LogAcesso::model()->saveAcesso('Contratos', 'Criar Contrato', 'Criar', MetodosGerais::tempoResposta($start));
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Contrato inserido com sucesso.'));
                    $this->redirect(array('index'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Contrato não pôde ser inserido.'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'condicao' => $condicao,
        ));
    }


    public function actionCreateFromAjax($nome, $codigo, $coordenador, $valor, $moeda, $data_inicial, $data_final, $novoDocId,
                                         $novoDocNome, $fk_disciplina, $tempo)
    {
        $model = new Contrato;
        $model->nome = $nome;
        $model->codigo = $codigo;
        $model->coordenador = $coordenador;
        $model->valor = MetodosGerais::real2float($valor);
        $model->moeda = $moeda;
        $model->data_inicio = MetodosGerais::dataAmericana($data_inicial);
        $model->data_final = MetodosGerais::dataAmericana($data_final);
        $model->tempo_previsto = MetodosGerais::DataDiffInMinutes($data_inicial, $data_final);
        $model->serial_empresa = MetodosGerais::getSerial();
        $model->receber_email = 0;
        $model->finalizada = 0;
        $model->fk_empresa = MetodosGerais::getEmpresaId();
        if ($model->save()) {
            $documento = new Documento;
            $documento->nome = $novoDocNome;
            $documento->fk_disciplina = $fk_disciplina;
            $documento->fk_contrato = $model->id;
            $documento->finalizado = 0;
            $documento->fk_empresa = MetodosGerais::getEmpresaId();
            $documento->previsto = $tempo;
            if ($documento->save()) {
                DocumentoSemContrato::model()->deleteAllByAttributes(array('id' => $novoDocId));
                echo "success";
                die();
            }
        }
        echo "error";
    }

    /**
     * @param $id
     * @param $codigo
     *
     * Método auxiliar utilizado para verificar se para o novo contrato existe produtividade
     * de documentos que se referenciam a este centro de custo.
     */
    public function verificaContratoHasDocumento($id, $codigo)
    {
        $codigo = explode(',', $codigo);
        $documentos = array();
        foreach ($codigo as $item) {
            $query = DocumentoSemContrato::model()->find(array('condition' => 'fk_empresa =' . MetodosGerais::getEmpresaId() . " AND documento like '%" . trim($item) . "%' "));
            $documentos = (isset($query)) ? array_merge($documentos, $query) : $documentos;
        }

        if (!empty($documentos)) {
            $model = new ContratoHasDocumentoOnCreate();
            $model->fk_contrato = $id;
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->data = date('Y-m-d');
            $model->save();
        }
    }

    /**
     * @param $dados
     * @param $id_obra
     *
     * Método auxiliar para salvar documentos na ação de criar um novo contrato, caso o usuário
     * opte por cadastrar uma LDP de forma manual.
     */
    public function salvaDocumentos($dados, $id_obra) {
        foreach ($dados as $documento) {
            $model = new Documento;
            $model->nome = trim($documento['nome']);
            $model->previsto = $documento['previsto'];
            $model->fk_disciplina = $documento['fk_disciplina'];
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->fk_contrato = $id_obra;
            $status = ($documento['finalizado'] == 'Finalizado') ? 1 : 0;
            $model->finalizado = $status;
            if ($status)
                $model->data_finalizacao = date('Y-m-d');
            $model->save();
        }
    }

    /**
     * Método auxiliar para finalizar um contrato via ajax a partir da grid de contratos.
     */
    public function actionFinalizar() {
        $start = MetodosGerais::inicioContagem();
        $model = Contrato::model()->findByPk($_POST['contrato']);
        $model->finalizada = 1;
        $model->data_finalizacao = date("Y-m-d H:i:s");
        $model->save(false);
        LogAcesso::model()->saveAcesso('Contratos', 'Finalizar contrato', 'Finalizar', MetodosGerais::tempoResposta($start));
    }

    /**
     * @param $ldp
     * @param $id
     * Método auxiliar para salvar documentos na ação de criar um novo contrato, caso o usuário
     * opte por cadastrar uma LDP pela importação de planilha CSV.
     */
    public function actionImportCSV($ldp, $id) {
        $delimiter = ",";
        $textDelimiter = '"';
        $table = 'documento';

        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND id in (SELECT fk_contrato FROM usuario_has_contrato WHERE fk_usergroups_user = " . Yii::app()->user->id . ")";
        }

        if (!empty($ldp)) {
            //pega o arquivo csv

            $file_tmp = CUploadedFile::getInstanceByName('Documento[file]');
            $path = Yii::getPathOfAlias('webroot') . "/public/csv/" . $file_tmp->name;
            $file_tmp->saveAs($path);
            $file = fopen($path, "r");
            $csvFirstLine = fgetcsv($file, 0, $delimiter);
            fclose($file);

            //variaveis para o FOR
            $filecontent = file($path);
            $lengthFile = sizeof($filecontent);

            $transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < $lengthFile; $i++) {
                    if ($i != 0 && $filecontent[$i] != '') {
                        $csvLine = str_getcsv($filecontent[$i], $delimiter);
                        if (count($csvLine) < 2)
                            $csvLine = str_getcsv($filecontent[$i], ';');
                        $doc = new Documento;
                        $doc->nome = $csvLine[0];
                        $doc->previsto = $csvLine[1];
                        $disciplina = Disciplina::model()->findByAttributes(array("codigo" => $csvLine[2], "fk_empresa" => $fk_empresa));
                        if (!$disciplina) {
                            $disciplina = new Disciplina;
                            $disciplina->codigo = utf8_encode($csvLine[2]);
                            $disciplina->nome = (isset($csvLine[3])) ? $csvLine[3] : $csvLine[2];
                            $disciplina->fk_empresa = $fk_empresa;
                            $disciplina->save();
                        }
                        $doc->fk_disciplina = $disciplina->id;
                        $doc->fk_contrato = $id;
                        $doc->fk_empresa = $fk_empresa;
                        $doc->finalizado = 0;
                        if (!$doc->save()) {
                            throw new Exception(Yii::t('smith', 'CSV incorreto, por favor verifique os campos obrigatórios e tente novamente.'));
                        }
                    }
                }
                $transaction->commit();
            } catch (Exception $e) { // exception in transaction
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
        }
    }

    /**
     * Método auxiliar para exibir as disciplinas no dropdown de inserção LDP manualmente na ação
     * de criar um contrato.
     */
    public function actionGetDisciplina() {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $dados = Disciplina::model()->findAll(array("condition" => "fk_empresa=$fk_empresa"));

        foreach ($dados as $value) {
            echo CHtml::tag('option', array('value' => $value->id, 'selected' => 'selected'), $value->codigo, true);
        }
    }

    /**
     * @param $documentos
     * @param $idObra
     *
     * Métodoo auxiliar para assosiação de novos documentos a um contrato
     * na ação de atualizar um contrato.
     */
    public function associarDocumento($documentos, $idObra) {
        $listaDocumento = $documentos['selecionados'];
        foreach ($listaDocumento as $key => $documento) {
            $doc = Documento::model()->findByPk($documento['id_documento']);
            $model = new Documento;
            $model->nome = $doc->nome;
            $model->previsto = $doc->previsto;
            $model->fk_disciplina = $doc->fk_disciplina;
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->fk_contrato = $idObra;
            $model->save();
        }
    }

    public function actionUpdate($id) {
        $this->title_action = Yii::t("smith", "Atualizar Contrato");
        $this->pageTitle = Yii::t("smith", "Atualizar Contrato");
        $model = $this->loadModel($id);
        $fk_empresa = MetodosGerais::getEmpresaId();
        $documentos = Documento::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa, 'fk_contrato' => $id));

        //verifica se é coordenador ou perfil geral de dono
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = "fk_empresa=$fk_empresa AND username NOT LIKE '%admin%'";
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND username NOT LIKE '%admin%' AND id = " . Yii::app()->user->id;
        }
        $model->valor = MetodosGerais::float2real($model->valor);
        if (isset($_POST['Contrato'])) {
            $start = MetodosGerais::inicioContagem();

            $model->attributes = $_POST['Contrato'];

            $model->receber_email = 0;
            if ($_POST['Contrato']['receber_email']) $model->receber_email = 1;

            $model->finalizada = (int)$_POST['Contrato']['finalizada'];
            $model->data_inicio = MetodosGerais::dataAmericana($_POST['Contrato']['data_inicio']);
            $model->data_final = MetodosGerais::dataAmericana($_POST['Contrato']['data_final']);
            $model->valor = MetodosGerais::real2float($_POST['Contrato']['valor']);
            $model->moeda = $_POST['Contrato']['moeda'];

            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            if (isset($user->fk_empresa))
                $model->fk_empresa = $user->fk_empresa;

            if ($model->save()) {
                Documento::model()->deleteAllByAttributes(array("fk_contrato" => $id));
                if (isset($_POST['Documento']))
                    $this->salvaDocumentos($_POST['Documento'], $model->id);

                UsuarioHasContrato::model()->deleteAllByAttributes(array("fk_contrato" => $id));
                $usuarioHasObra = new UsuarioHasContrato();
                $usuarioHasObra->fk_contrato = $id;
                $usuarioHasObra->fk_usergroups_user = $_POST['Contrato']['coordenador'];
                if ($usuarioHasObra->save()) {
                    LogAcesso::model()->saveAcesso('Contratos', 'Atualizar Contrato', 'Atualizar', MetodosGerais::tempoResposta($start));
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Contrato atualizado com sucesso.'));
                    $this->redirect(array('index'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Contrato não pôde ser atualizado.'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'condicao' => $condicao,
            'documentos' => $documentos,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            $model->ativo = 0;
            //$model->save(false);
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('smith', 'Requisição inválida. Por favor, não repita esta requisição novamente.'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $start = MetodosGerais::inicioContagem();
        $permissaoAcesso = MetodosGerais::checkPermissionAccessContract();
        $this->title_action = Yii::t("smith", "Contratos");
        $this->pageTitle = Yii::t("smith", "Contratos");
        $model = new Contrato('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Contrato']))
            $model->attributes = $_GET['Contrato'];
        LogAcesso::model()->saveAcesso('Contratos', 'Gerenciar contratos', 'Contratos', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
            'permissaoAcesso' => $permissaoAcesso,
        ));
    }

    public function actionAndamentoObra()
    {
        $this->title_action = Yii::t("smith", "Acompanhamento dos Contratos");
        $this->pageTitle = Yii::t("smith", "Acompanhamento dos Contratos");
        $this->layout = 'main';
        $model = new Contrato('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Contrato']))
            $model->attributes = $_GET['Contrato'];

        if (isset($_GET['codigo'])) {
            $start = MetodosGerais::inicioContagem();

            $model = new Contrato;
            $fk_empresa = MetodosGerais::getEmpresaId();
            $obra = Contrato::model()->findByAttributes(array('codigo' => $_GET['codigo'], "fk_empresa" => $fk_empresa));
            $parametro = EmpresaHasParametro::model()->find(array("condition" => "fk_empresa=$fk_empresa"));
            if ($parametro->andamento_obra == "prefixo") {
                $prefixo = $_GET['codigo'];
                $logDocumentos = GrfProjetoConsolidado::model()->getDocumentosLogAcompanhamento($obra->id, $fk_empresa);
                $documentosLog = array();

                foreach ($logDocumentos as $log) {
                    $documentosLog[$log->documento]['documento'] = $log->documento;
                    $documentosLog[$log->documento]['logs'][] = $log;
                }
                $this->render('acompanhamentoPrefixo', array(
                    'obra' => $obra,
                    'documentos' => $documentosLog,
                ));
            } //////////////// ECLIPSE /////////
            elseif ($parametro->andamento_obra == "projetosDS") {
                $empresa = MetodosGerais::getEmpresaId();
                $prefixo = $_GET['codigo'];
                if ($empresa == 1) {
                    $prefixo = "SArq." . $_GET['codigo'];
                    $documentos = $this->andamentoSotero($prefixo, $obra); // Implantação do ECLIPSE
                } else
                    $documentos = $this->andamentoEclipse($prefixo, $obra); // Implantação do ECLIPSE

                $documentosLog = array();
                $tam = count($documentos);

                for ($i = 0; $i < $tam; $i++) {
                    $padrao = $documentos[$i];
                    $log = $model->findLogPorPadrao($padrao);
                    array_push($documentosLog, array("documento" => $documentos[$i], 'logs' => $log));
                }


                $this->render('acompanhamentoEclipse', array(
                    'obra' => $obra,
                    'documentos' => $documentosLog,
                ));
            } /////////////////  LDP \\\\\\\\\\\\\\\\\\\\
            else {
                $documentos = $model->getDocumentos($obra->codigo);


                $tam = count($documentos);

                for ($i = 0; $i < $tam; $i++) {
                    $padrao = $documentos[$i]['documento'];
                    $documentos[$i]['logs'][] = GrfProjetoConsolidado::model()->getLogsContrato($obra->id, $padrao);
                }

                LogAcesso::model()->saveAcesso('Contratos', 'Acompanhamento dos Contratos', 'Acompanhamento', MetodosGerais::tempoResposta($start));
                $this->render('acompanhamento', array(
                    'obra' => $obra,
                    'documentos' => $documentos,
                ));
            }
        } else {
            $this->render('andamento', array(
                'model' => $model,
            ));
        }
    }

    /**
     * @param $projeto
     * @param $obra
     * @return array
     *
     * Método auxiliar para trazer registros de documentos que possuem padrão de nomenclatura
     * ulizado no software Eclipse.
     */
    public function andamentoEclipse($projeto, $obra)
    {

        $andamento = Contrato::model()->findLogProjetoEclipse($projeto);
        $tree = $documentos = array();
        foreach ($andamento as $value) {
            $explode = explode("/", $value['descricao']);
            $documento = array_pop($explode);
            $documento = explode("-", $documento);
            $documento = $documento[0];
            if (!in_array($documento, $documentos))
                array_push($documentos, $documento);
        }

        return $documentos;
    }

    /**
     * @param $projeto
     * @return array
     *
     * Método auxiliar para trazer registros de documentos que possuem um prefixo diferente dos outros padrões
     */
    public function andamentoSotero($projeto)
    {

        $andamento = Contrato::model()->findLogProjetoSotero($projeto);
        $tree = $documentos = array();
        foreach ($andamento as $key => $value) {
            if (!in_array($value['documento'], $documentos))
                array_push($documentos, $value['documento']);
        }
        return $documentos;
    }


    public function actionRelatorioIndividual()
    {
        $this->title_action = Yii::t("smith", "Relatório de acompanhamento de contratos");
        $this->pageTitle = Yii::t("smith", "Relatório de acompanhamento de contratos");

        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND id in (SELECT fk_contrato FROM usuario_has_contrato WHERE fk_usergroups_user = " . Yii::app()->user->id . ")";
        }

        $parametro = EmpresaHasParametro::model()->find(array("condition" => "fk_empresa=$fk_empresa"));
        $hasDisciplina = ($parametro->andamento_obra == "prefixo") ? false : true;

        if (!empty($_POST)) {
            $this->setRelatorioIndividual();
        }
        else {
            $disciplina = new Disciplina;
            $this->render("relatorioIndividual", array('condicao' => $condicao, 'hasDisciplina' => $hasDisciplina, 'disciplina' => $disciplina));
        }
    }

    public function actionRelatorioContratoFinalizado()
    {
        $this->title_action = Yii::t("smith", "Relatório de acompanhamento de contratos finalizados");
        $this->pageTitle = Yii::t("smith", "Relatório de acompanhamento de contratos finalizados");

        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa . ' AND finalizada = 1';
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fk_empresa AND id in (SELECT fk_contrato FROM usuario_has_contrato WHERE fk_usergroups_user = " . Yii::app()->user->id . ")";
        }

        $parametro = EmpresaHasParametro::model()->find(array("condition" => "fk_empresa = $fk_empresa"));
        $hasDisciplina = ($parametro->andamento_obra == "prefixo") ? false : true;

        if (!empty($_POST)) {
            $this->setRelatorioIndividual('finalizado');
        } else {
            $disciplina = new Disciplina;
            $this->render("relatorioContratoFinalizado", array('condicao' => $condicao, 'hasDisciplina' => $hasDisciplina, 'disciplina' => $disciplina));
        }
    }

    /**
     * @param $documentos
     * @param $contrato
     * @param $coordenador
     * @param $disciplinas
     * @param $colaboradores
     * @param $prefixo
     *
     * Método auxiliar para gerar o relatório PDF de produtividade individual de contratos que utilizam LDP.
     */
    public function GerarRelatorioDados($documentos, $contrato, $coordenador, $disciplinas, $colaboradores, $prefixo, $docs)
    {
        if (!empty($colaboradores)) {
            $fk_empresa = MetodosGerais::getEmpresaId();
            $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
            $style = MetodosGerais::getStyleTable();
            $rodape = MetodosGerais::getRodapeTable();
            $obj = GrfProjetoConsolidado::model()->findByAttributes(array('fk_obra' => $_POST['Obra']));
            $data = MetodosGerais::dataBrasileira($obj->data);

            $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                <page_header>
                    <div class="header_page">
                        <img class="header_logo_page" src="' . $imagem . '">
                        <div class="header_title">
                            <p>'.Yii::t('smith', 'RELATÓRIO DE ACOMPANHAMENTO').'</p>
                        </div>
                        <span><b>' . Yii::t("smith", 'Contrato') . ': ' . $contrato . ' - ' . Yii::t("smith", 'Código') . ': ' . $prefixo . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Data de inicio') . ': ' . $data . '</b></span>
                        <br>
                        <span><b>'.Yii::t('smith', 'Coordenador').': ' . $coordenador . '</b></span>
                        <div class="header_date">
                            <p>'.Yii::t('smith', 'Data').':  ' . date("d/m/Y") . '
                            <br>'.Yii::t('smith', 'Pág.').' ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                    </div>
                </page_header>
            </page>';

            $html = $header;
            $html .= $rodape;
            $tempoTotal = $custoTotal = 0;
            $corpo = $itens = '';
            foreach ($colaboradores as $colaborador) {
                $tempoTotal += $colaborador['duracao'];
                $custoTotal += $colaborador['custo'];
            }

            foreach ($colaboradores as $key => $value) {
                $corpo .=
                    '<tr>
                        <td style="width: 150px">' . $key . '</td>
                        <td style="width: 178px; text-align: center"">' . $value['equipe'] . ' </td>
                        <td style="width: 105px; text-align: center">' . MetodosGerais::formataTempo($value['duracao']) . ' </td>
                        <td style="width: 105px; text-align: center">' . round(($value['duracao'] * 100) / $tempoTotal, 2) . '% </td>
                        <td style="width: 105px; text-align: center"> R$' . MetodosGerais::float2real($value['custo']) . ' </td>
                    </tr>';
            }

            $html .= '<table  class="table_custom" border="1px">
                <tr>
                    <th colspan="5" style="text-align: left;">' . Yii::t('smith', 'Tempo total gasto') . ': ' . MetodosGerais::formataTempo($tempoTotal) . ' ' . Yii::t('smith', 'horas') . ' </th>
                </tr>
                <tr style="background-color: #CCC; text-decoration: bold;">
                    <th>'.Yii::t('smith', 'Colaborador').'</th>
                    <th>'.Yii::t('smith', 'Equipe').'</th>
                    <th>' . Yii::t('smith', 'Tempo realizado') . '</th>
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
                        <th>' . Yii::t('smith', 'Nome completo do documento') . '</th>
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
                            <td style="width: 235px; text-align: center" >' . wordwrap($documento['documento'], 37, "\n", true) . '</td>
                            <td style="width: 20px; text-align: center">' . $documento['previsto'] . '</td>
                            <td style="width: 40px; text-align: center">' . MetodosGerais::formataTempo($documento['logs'][0]['duracao']) . ' </td>
                            <td style="width: 40px; text-align: center">' . str_replace(".", ",", round(($documento['logs'][0]['duracao'] * 100) / MetodosGerais::time_to_seconds($documento['previsto']), 2)) . '%</td>
                            <td style="width: 40px; text-align: center">R$ ' . MetodosGerais::float2real(round(($horas_realizadas * $custoTotal) / $horas_total, 2)) . '</td>
                            <td style="width: 60px; text-align: center">' . $status . '</td>
                        </tr>';
                    }
                }

                $porcentagem = ($totalPrevistoDisciplina == 0) ? 0 : str_replace(".", ",", round(($totalRealizadosDisciplina * 100) / $totalPrevistoDisciplina, 2));
                $html .= '<tr style="background-color: #CCC; text-decoration: bold;">
                    <th>'.Yii::t('smith', 'Total').'</th>
                    <th>'.Yii::t('smith', 'Tempo previsto').'</th>
                    <th>'.Yii::t('smith', 'Tempo realizado').'</th>
                    <th>'.Yii::t('smith', 'Porcentagem').'</th>
                    <th colspan="2">' . Yii::t('smith', 'Custo') . '</th>
                </tr>
                <tr>
                    <td style="width: 200px" > </td>
                    <td style="text-align: center">' . MetodosGerais::formataTempo($totalPrevistoDisciplina) . ' </td>
                    <td style="text-align: center">' . MetodosGerais::formataTempo($totalRealizadosDisciplina) . ' </td>
                    <td style="text-align: center">' . $porcentagem . '%</td>
                    <td colspan="2" style="text-align: center">R$ ' . MetodosGerais::float2real($custoTotalDisciplina) . '</td>
                </tr>';

                $html .= '</table>';
            }

            if (isset($_POST['docs_nao_cadastrados']) && $docs != '') {
                $html .= "<p style='margin-top: 5px'></p>"
                    . "<table class='table_custom' border='1px'>"
                    . "<tr>"
                    . "<th colspan='3' style='text-align: left;'>" . Yii::t('smith', 'Documentos não cadastrados') . "</th>"
                    . "</tr>"
                    . "<tr style='background-color: #CCC; text-decoration: bold;'>
                        <th>" . Yii::t('smith', 'Documento') . "</th>
                        <th>" . Yii::t('smith', 'Tempo total realizado') . "</th>
                        <th>" . Yii::t('smith', 'Custo') . "</th>
                    </tr>";

                $somaDocDuracao = 0;
                foreach ($docs as $doc) {
                    $html .= "<tr><td style='width: 409px;'>" . $doc->documento . "</td>"
                        . "<td style='text-align: center; width: 135px;'>" . MetodosGerais::formataTempo($doc->duracao) . "</td>"
                        . "<td style='text-align: center; width: 135px;'>R$ " . MetodosGerais::float2real(round(((($doc->duracao) / 3600) * $custoTotal) / $horas_total, 2)) . "</td></tr>";

                    $somaDocDuracao += $doc->duracao;
                }
                $html .= '<tr style="background-color: #CCC; text-decoration: bold;">
                    <th>' . Yii::t('smith', 'Total') . '</th>
                    <th>' . Yii::t('smith', 'Tempo total realizado') . '</th>
                    <th>' . Yii::t('smith', 'Custo') . '</th>
                </tr><tr>
                    <td></td>
                    <td style="text-align: center;">' . MetodosGerais::formataTempo($somaDocDuracao) . '</td>
                    <td>R$ ' . MetodosGerais::float2real(round(((($somaDocDuracao) / 3600) * $custoTotal) / $horas_total, 2)) . '</td>
                </tr>';
                $html .= "</table>";
            } else {
                $html .= "<p style='margin-top: 5px'></p>"
                    . "<table class='table_custom' border='1px'>"
                    . "<tr style='background-color: #CCC; text-decoration: bold;'>
                    <th></th>
                    <th>".Yii::t('smith', 'Tempo total realizado')."</th>
                    <th>".Yii::t('smith', 'Custo')."</th>
                </tr>
                <tr>"
                . "<td><b>Não cadastrado na lista de documentos</b></td>"
                    . "<td>" . MetodosGerais::formataTempo($tempoTotal - $total_documentos_realizados) . "</td>"
                    . "<td>R$ " . MetodosGerais::float2real(round(((($tempoTotal - $total_documentos_realizados) / 3600) * $custoTotal) / $horas_total, 2)) . "</td>"
                    . "</tr></table>";
            }

            $html .= "<p style='margin-top: 15px'></p>"
                    . "<table class='table_custom' border='1px'>"
                    . "<tr style='background-color: #CCC; text-decoration: bold;'>
                        <th></th>
                        <th>".Yii::t('smith', 'Tempo total previsto')."</th>
                        <th>".Yii::t('smith', 'Tempo total realizado')."</th>
                        <th>".Yii::t('smith', 'Custo total')."</th>
                    </tr>
                    <tr>"
                . "<td><b>" . Yii::t('smith', 'Total consolidado') . "</b></td>"
                    . "<td>" . MetodosGerais::formataTempo($total_previsto) . "</td>"
                    . "<td>" . MetodosGerais::formataTempo($total_documentos_realizados + ($tempoTotal - $total_documentos_realizados)) . "</td>"
                    . "<td>R$ " . MetodosGerais::float2real($custo_total_documentos + round(((($tempoTotal - $total_documentos_realizados) / 3600) * $custoTotal) / $horas_total, 2)) . "</td></tr></table>";
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);
            $html2pdf->Output();
        }
        else {
            Yii::app()->user->setFlash('warning', Yii::t('smith', 'Por favor, verifique se os dados de todos colaboradores foram atualizados.'));
            $this->redirect(array('index'));
        }
    }

    /**
     * @param $documentos
     * @param $contrato
     * @param $coordenador
     * @param $colaboradores
     * @param $prefixo
     * @param $data_inicio
     * @param $data_fim
     * @param $previsto
     * @param $tempo_previsto
     * @param $start
     *
     * Método auxiliar para gerar o relatório PDF de produtividade individual de contratos que utilizam o código
     * como parametro de escanear a produtividade dos contratos.
     */
    public function GerarRelatorioDadosPrefixo($documentos, $contrato, $coordenador, $colaboradores, $prefixo, $data_inicio, $data_fim, $previsto, $tempo_previsto, $start, $estimativaData)
    {
        if (!empty($colaboradores)) {
            $tempo_previsto = ($tempo_previsto) ? $tempo_previsto . ' ' . Yii::t('smith', 'horas') : Yii::t('smith', 'Não informado');
            $fk_empresa = MetodosGerais::getEmpresaId();
            $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
            $style = MetodosGerais::getStyleTable();
            $rodape = MetodosGerais::getRodapeTable();

            $obj = GrfProjetoConsolidado::model()->findByAttributes(array('fk_obra' => $_POST['Obra']));
            $data = MetodosGerais::dataBrasileira($obj->data);
            $estimativaData = ($estimativaData == NULL) ? Yii::t('smith', 'Não informado') : MetodosGerais::dataBrasileira($estimativaData);
            $previsto = ($previsto == NULL) ? Yii::t('smith', 'Não informado') : 'R$' . MetodosGerais::float2real($previsto);

            $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                <page_header>
                    <div class="header_page">
                        <img class="header_logo_page" src="' . $imagem . '">
                        <div class="header_title">
                            <span>'.Yii::t("smith", 'RELATÓRIO DE ACOMPANHAMENTO').'</span><br>
                            <span style="font-size: 10px">'.Yii::t("smith", 'No período de').' ' . $data_inicio . ' '.Yii::t("smith", 'até').' ' . $data_fim . ' </span>
                        </div>
                        <span><b>' . Yii::t("smith", 'Contrato') . ': ' . $contrato . ' - ' . Yii::t("smith", 'Código') . ': ' . $prefixo . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Data de inicio') . ': ' . $data . '</b></span>
                        <span><b>- ' . Yii::t("smith", 'Estimativa de conclusão') . ': ' . $estimativaData . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Orçamento previsto') . ': ' . $previsto . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Coordenador') . ': ' . $coordenador . '</b></span>
                        <div class="header_date">
                            <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
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
                $tempoTotal += $colaborador['duracao'];
                $custoTotal += $colaborador['custo'];
            }

            foreach ($colaboradores as $key => $value) {
                $corpo .=
                    '<tr>
                        <td style="width: 150px">' . $key . '</td>
                        <td style="width: 178px">' . $value['equipe'] . ' </td>
                        <td style="width: 83px; text-align: center">' . MetodosGerais::formataTempo($value['duracao']) . ' </td>
                        <td style="width: 83px; text-align: center">' . round(($value['duracao'] * 100) / $tempoTotal, 2) . '% </td>
                        <td style="width: 75px; text-align: center"> R$' . MetodosGerais::float2real($value['custo']) . ' </td>
                    </tr>';
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

            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);
            $inicio = str_replace('/', '-', $data_inicio);
            $fim = str_replace('/', '-', $data_fim);
            LogAcesso::model()->saveAcesso('Contratos', 'Relatório individual', 'Relatório de acompanhamento de contratos', MetodosGerais::tempoResposta($start));
            $html2pdf->Output(Yii::t('smith', 'relatorioAcompanhamento') . '_' . $contrato . '_' . $inicio . '_' . Yii::t("smith", 'ate') . '_' . $fim . '.pdf');
        } else {
            Yii::app()->user->setFlash('warning', Yii::t('smith', 'Por favor, verifique se os dados de todos colaboradores foram atualizados.'));
            $this->redirect(array('index'));
        }
    }

    public function actionRelatorioGeral()
    {
        $this->title_action = Yii::t("smith", "Relatório de acompanhamento de produtividade dos contratos");
        $this->pageTitle = Yii::t("smith", "Relatório de acompanhamento de produtividade dos contratos");

        if (!empty($_POST)) {
            $start = MetodosGerais::inicioContagem();
            $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
            $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);
            $dados = array();
            $registros = GrfProjetoConsolidado::model()->getProdutividadeProjetosByAtt(
                $_POST['selecionado'], $_POST['opcao'], $dataInicio, $dataFim
            );

            if (!empty($registros)) {
                if ($_POST['opcao'] == 'equipe') {
                    $dados = Contrato::model()->formatDataRelatorioGeralContratoOpEquipe($registros, $dataInicio, $dataFim);
                } elseif ($_POST['opcao'] == 'colaborador') {
                    $dados = Contrato::model()->formatDataRelatorioGeralContratoOpColaborador($registros, $dataInicio, $dataFim);
                } elseif ($_POST['opcao'] == 'contrato') {
                    $dados = Contrato::model()->formatDataRelatorioGeralContratoOpContrato($registros, $dataInicio, $dataFim);
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t("smith", 'Por favor, selecione uma opção.'));
                    $this->render('relatorioGeral');
                }

                $diasUteis = MetodosGerais::dias_uteis(strtotime($dataInicio), strtotime($dataFim));
                if (!empty($_POST['button'])) {
                    MetodosCSV::ExportCSVRelGeralContrato($dados, $_POST['date_from'], $_POST['date_to'], $_POST['opcao'], $_POST['button']);
                } else
                    $this->RelatorioAcompanhamentoProjetos($dados, $diasUteis, $_POST['date_from'], $_POST['date_to'], $_POST['opcao'], $start);
            } else {
                Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não existe dados no período solicitado.'));
                $this->render('relatorioGeral');
            }
        } else
            $this->render('relatorioGeral');
    }

    /**
     * @param $dados
     * @param $diasUteis
     * @param $dataInicio
     * @param $dataFim
     * @param $opcao
     * @param $start
     *
     * Método auxiliar para gerar o PDF do relatório geral de contratos.
     */
    public function RelatorioAcompanhamentoProjetos($dados, $diasUteis, $dataInicio, $dataFim, $opcao, $start)
    {
        $empresaId = MetodosGerais::getEmpresaId();
        $imagem = Empresa::model()->findByPK($empresaId)->logo;
        $style = MetodosGerais::getStyleTable();
        $rodape = MetodosGerais::getRodapeTable();
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                        <page_header>
                        <div class="header_page">
                        <img class="header_logo_page" src="' . $imagem . '">
                        <div class="header_title">
                            <span>'.Yii::t("smith", 'RELATÓRIO DE ACOMPANHAMENTO DE CONTRATOS').'</span><br>
                            <span style="font-size: 10px">'.Yii::t("smith", 'No período de').' ' . $dataInicio . ' '.Yii::t('smith', 'até').' ' . $dataFim . ' </span>
                        </div>
                        <div class="header_date">
                        <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
                            <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                        </div>
                        </page_header>
                        </page>';
        $html = $header;
        $corpo = "";
        $html .= $rodape;

        if($opcao == "contrato"){
            $html .= Contrato::model()->geraHtmlRelatorioGeralContratoOpContrato($dados);
        }else{
            $html .= Contrato::model()->geraHtmlRelatorioGeralContrato($dados, $opcao);
        }

        $aux = '';
        if (count($dados) > 1) $aux = 'todos';
        if (count($dados) == 1 && $opcao == 'colaborador') {
            $aux = MetodosGerais::reduzirNome(key($dados));
            $aux = explode(' ', $aux);
            $aux = $aux[0] . $aux[1];
        }
        if (count($dados) == 1 && $opcao == 'equipe') {
            $aux = ucfirst(strtolower(key($dados)));
        }
        if (count($dados) == 1 && $opcao == 'contrato') {
            $aux = ucfirst(strtolower(key($dados)));
        }
        $inicio = str_replace('/', '-', $dataInicio);
        $fim = str_replace('/', '-', $dataFim);
        LogAcesso::model()->saveAcesso('Contratos', 'Relatório geral', 'Relatório de acompanhamento de produtividade dos contratoss', MetodosGerais::tempoResposta($start));
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output(Yii::t('smith', 'relatorioAcompanhamentoContratos') . '_' . Yii::t("smith", $opcao) . '_' . $aux . '_' . $inicio . '_'.Yii::t("smith", 'ate'). '_' . $fim . '.pdf');
    }

    public function actionGetDataInicioProjeto() {
        $obj = GrfProjetoConsolidado::model()->findByAttributes(array('fk_obra' => $_POST['Obra']), array('order' => 'data ASC'));
        $data = MetodosGerais::dataBrasileira($obj->data);
        echo $data;
    }

    public function actionGetDataFinalizadoProjeto()
    {
        $data = Contrato::model()->findByPk($_POST['Obra'])->data_finalizacao;
        $data = MetodosGerais::dataBrasileira(date('Y-m-d', strtotime($data)));
        echo $data;
    }

    public function actionCodigoExiste() {
        $contrato = Contrato::model()->getObraByPrefixo($_POST['codigo']);
        return ($contrato != NULL) ? true : false;
    }

    public function actionCustoEnergia() {
        $this->title_action = Yii::t("smith", "Custo de energia por contrato");
        $this->pageTitle = Yii::t("smith", "Custo de energia por contrato");
        $this->layout = 'main';

        $fk_empresa = MetodosGerais::getEmpresaId();
        $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
        $tarifa = MetodosGerais::float2real($parametros->tarifa_energia);

        if (isset($_POST['Obra'])) {
            $start = MetodosGerais::inicioContagem();
            $tarifa = str_replace(',', '.', $_POST['tarifa']);
            if ($_POST['tarifa'] != $tarifa)
                EmpresaHasParametro::model()->updateByPk($parametros->id, array("tarifa_energia" => $tarifa));

            $tempoGasto = GrfProjetoConsolidado::model()->getTempoTotalContrato($_POST['Obra'], $_POST['date_from'], $_POST['date_to']);
            if (!empty($tempoGasto)) {
                $total_gasto = 0;
                $categorias = $produzido = array();
                $potenciaPC = 200;

                foreach ($tempoGasto as $value) {
                    $contratoNome = Contrato::model()->findByPk($value->fk_obra);
                    if (isset($contratoNome)) {
                        $categorias[] = MetodosGerais::mesString($value->mes) . "-" . $value->ano;
                        $produzido['name'] = $contratoNome->nome;
                        $produzido['data'][] = round((($potenciaPC * ($value->duracao / 3600)) / 1000) * $tarifa, 2);
                        $total_gasto += $value->duracao;
                    }
                }

                $valorTotalGasto = round((($potenciaPC * ($total_gasto / 3600)) / 1000) * $tarifa, 2);
                $valorTotalGasto = str_replace('.', ',', $valorTotalGasto);

                $contrato = $contratoNome->nome . "-" . $contratoNome->codigo;
                LogAcesso::model()->saveAcesso('Contratos', 'Relatório de consumo de energia', 'Custo de energia por contrato', MetodosGerais::tempoResposta($start));
                if (!empty($_POST['button'])) {
                    $registros = array($produzido, $categorias);
                    $colunas = array('65' => 'Mês-Ano', '66' => 'Custo consumo (R$)');
                    $titulo = Yii::t('smith', 'Cosumo de energia') . ' ' . $contrato;
                    $filename = 'RelatorioCustoEnergia.' . $_POST['button'];
                    MetodosCSV::ExportToCsv($registros, $colunas, $titulo, $filename, 'relCustoEnergia',
                        $_POST['button']);
                } else {
                    $this->render('grfCustoEnergia', array(
                        'produzido' => $produzido,
                        'categorias' => $categorias,
                        'valor_total' => $valorTotalGasto,
                        'contrato' => $contrato,
                    ));
                }
            }
            else{
                Yii::app()->user->setFlash('warning', Yii::t('smith', 'Não há dados de consumo de energia deste contrato no intervalo de datas escolhido.'));
                $this->refresh();
            }
        } else {
            $this->render('custoEnergia', array('tarifa' => $tarifa));
        }
    }

    /**
     * @param $id
     * @return mixed
     *
     * Método auxiliar para finalizar um contrato através do acompanhamento de produtividade
     * em formato de árvore.
     */
    public function actionClose($id) {
        $model = Contrato::model()->findByPk($id);
        $model->finalizada = true;
        if ($model->save())
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Contrato finalizado.'));
        else
            Yii::app()->user->setFlash('error', Yii::t('smith', 'Contrato não pôde ser finalizado.'));
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * @param $data
     * @param $row
     * @return string
     *
     * Método auxiliar para retornar o tempo realizado de cada contrato a partir da grid.
     */
    public function getDuracao($data, $row) {
        $duracao = GrfProjetoConsolidado::model()->getDuracaoProjeto($data);
        (isset($duracao)) ? $tempo = MetodosGerais::formataTempo($duracao->duracao) : $tempo = MetodosGerais::formataTempo(0);
        return $tempo;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Contrato::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pro-obra-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetObras() {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;

        $criteria->addCondition("fk_empresa = :fk_empresa");
        $criteria->params = array(":fk_empresa" => $fk_empresa);
        $criteria->order = "nome ASC";

        if (Yii::app()->user->groupName == 'coordenador') {
            $criteria->addCondition("coordenador = :user");
            $criteria->params[':user'] = Yii::app()->user->id;
        }else{
            echo CHtml::tag('option', array('value' => 'todos_contratos'), CHtml::encode(Yii::t("smith", "Todos")), true);
        }

        $obras = Contrato::model()->findAll($criteria);

        foreach ($obras as $obra) {
            echo CHtml::tag('option', array('value' => $obra->id), CHtml::encode($obra->nome), true);
        }
    }

    public function actionProdutividadeColaborador()
    {
        $this->pageTitle = Yii::t("smith", "Produtividade dos colaboradores em contratos");
        $this->title_action = Yii::t("smith", "Produtividade dos colaboradores em contratos");
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador')
            $condicao = "fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();

        if (!empty($_POST)) {
            $start = MetodosGerais::inicioContagem();
            $registros = GrfProjetoConsolidado::model()->getProdutividadeColaboradorPorContrato($_POST['colaborador'], $_POST['date_from'], $_POST['date_to']);
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
                $colaborador = Colaborador::model()->findByPk($_POST['colaborador'])->nomeCompleto;
                LogAcesso::model()->saveAcesso('Contratos', 'Relatório de produtividade do colaborador', 'Produtividade dos colaboradores em contratos', MetodosGerais::tempoResposta($start));
                // Export CSV
                if (!empty($_POST['button'])) {
                    $registrosCsv = array($produzido, $categorias);
                    $colunas = array('65' => 'Contrato', '66' => 'Duração');
                    $titulo = Yii::t('smith', 'Produtividade de') . ' ' . $colaborador . ' ' . Yii::t('smith', 'nos contratos entre') . ' ' . $_POST['date_from'] . Yii::t('smith', ' e ') . $_POST['date_to'];
                    $filename = 'RelatorioProdutividadeColaboradorContratos.' . $_POST['button'];
                    MetodosCSV::ExportToCsv($registrosCsv, $colunas, $titulo, $filename, 'relPrdColContrato',
                        $_POST['button']);
                } // Gráfico
                else {
                    $this->render('grfProdutividadeColaborador',
                        array('categorias' => $categorias, 'produzido' => $produzido, 'colaborador' => $colaborador,
                            'data_inicio' => $_POST['date_from'], 'data_fim' => $_POST['date_to']));
                }
            } else {
                Yii::app()->user->setFlash('warning', Yii::t("smith", 'Não existe dados no período solicitado.'));
                $this->render('produtividadeColaborador', array('condicao' => $condicao));
            }
        } else {
            $this->render('produtividadeColaborador', array('condicao' => $condicao));
        }
    }

    public function actionRelatorioContratoEmAtraso()
    {
        $this->pageTitle = Yii::t("smith", "Relatório de contratos em atraso");
        $this->title_action = Yii::t("smith", "Relatório de contratos em atraso");
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = ' t.fk_empresa = ' . $fk_empresa;
        if (Yii::app()->user->groupName == 'coordenador')
            $condicao = "t.fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();
        if (!empty($_POST)) {
            $documentosLog = array();
            $documentos = Contrato::model()->getDocumentos($_POST['idContrato']);
            $tam = count($documentos);
            for ($i = 0; $i < $tam; $i++) {
                $padrao = $documentos[$i]['documento'];
                $documentos[$i]['logs'] = GrfProjetoConsolidado::model()->getDuracaoDocumentoContrato($padrao, $fk_empresa);
            }
            $duracaoTotal = 0;
            foreach ($documentos as $value) {
                $estouroPrazo = $value['logs'][0]['duracao'] - MetodosGerais::time_to_seconds($value['previsto']);
                $duracaoTotal += MetodosGerais::time_to_seconds($value['previsto']);
                if ($value['logs'][0]['duracao'] != NULL && $estouroPrazo > 0) {
                    $porcentagemEstourado = round(($estouroPrazo * 100) / MetodosGerais::time_to_seconds($value['previsto']), 2);
                    $documentosLog[$value['disciplina']][] = array('disciplina' => $value['disciplina'], 'documento' => $value['documento'], 'previsto' => $value['previsto'],
                        'duracao' => MetodosGerais::formataTempo($value['logs'][0]['duracao']), 'estouroPrazo' => MetodosGerais::formataTempo($estouroPrazo),
                        'porcentagemDuracao' => $porcentagemEstourado);
                }
            }
            if (!empty($documentosLog)) {
                $contrato = Contrato::model()->findByPk($_POST['idContrato']);
                $custoHoraContrato = round(($contrato->valor) / ($duracaoTotal / 3600), 2);
                $this->getRelatorioContratoEmAtraso($contrato, $documentosLog, $custoHoraContrato);
            } else {
                Yii::app()->user->setFlash('warning', Yii::t("smith", 'Este contrato não possui documentos em atraso.'));
                $this->render('relatorioContratoEmAtraso', array('condicao' => $condicao));
            }


        } else
            $this->render('relatorioContratoEmAtraso', array('condicao' => $condicao));
    }

    public function getRelatorioContratoEmAtraso($contrato, $documentos, $custoHoraContrato)
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $imagem = Empresa::model()->findByPK($fk_empresa)->logo;
        $style = MetodosGerais::getStyleTable();
        $rodape = MetodosGerais::getRodapeTable();
        $header = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                <page_header>
                    <div class="header_page">
                        <img class="header_logo_page" src="' . $imagem . '">
                        <div class="header_title">
                            <span>' . Yii::t("smith", 'RELATÓRIO DE ACOMPANHAMENTO DE CONTRATOS EM ATRASO') . '</span><br>
                        </div>
                        <span><b>' . Yii::t("smith", 'Contrato') . ': ' . $contrato->nome . ' - ' . Yii::t("smith", 'Código') . ': ' . $contrato->codigo . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Data de inicio') . ': ' . MetodosGerais::dataBrasileira($contrato->data_inicio) . '</b></span>
                        <span><b>- ' . Yii::t("smith", 'Estimativa de conclusão') . ': ' . $contrato->data_inicio . '</b></span>
                        <br>
                        <span><b>' . Yii::t("smith", 'Orçamento previsto') . ': R$' . MetodosGerais::float2real($contrato->valor) . '</b></span>
                        <div class="header_date">
                            <p>' . Yii::t("smith", 'Data') . ':  ' . date("d/m/Y") . '
                            <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                    </div>
                </page_header>
            </page>';
        $html = $header;
        $html .= $rodape;

        foreach ($documentos as $disciplina => $doc) {
            $html .= '<p style="margin-top: 5px"></p>';
            $html .= '<table class="table_custom" border="1px">';
            $html .= '<tr >';
            $html .= '<th style="text-align: left;" colspan="5">' . Yii::t('smith', 'Disciplina') . ': ' . $disciplina . '</th>';
            $html .= '</tr>';
            $html .= '<tr style="background-color: #CCC; text-decoration: bold;">';
            $html .= '<th>' . Yii::t("smith", 'Documento') . '</th>';
            $html .= '<th>' . Yii::t("smith", 'Horas excedidas') . '</th>';
            $html .= '<th>' . Yii::t("smith", '%') . '</th>';
            $html .= '<th>' . Yii::t("smith", 'Custo excedido') . '</th>';
            $html .= '<th>' . Yii::t("smith", '%') . '</th>';
            $html .= '</tr>';

            foreach ($doc as $value) {
                $custoExcedido = MetodosGerais::float2real(round((MetodosGerais::time_to_seconds($value['estouroPrazo']) / 3600) * $custoHoraContrato, 2));
                $porcentagemCusto = round(($custoExcedido * 100) / ((MetodosGerais::time_to_seconds($value['previsto']) / 3600) * $custoHoraContrato), 2);
                $html .= '<tr>';
                $html .= '<td style="text-align: center; width: 270px">' . $value['documento'] . '</td>';
                $html .= '<td style="text-align: center; width: 90px">' . $value['estouroPrazo'] . '</td>';
                $html .= '<td style="text-align: center; width: 90px">' . $value['porcentagemDuracao'] . '%</td>';
                $html .= '<td style="text-align: center; width: 90px">R$' . $custoExcedido . '</td>';
                $html .= '<td style="text-align: center; width: 90px">' . $porcentagemCusto . '%</td>';
                $html .= '</tr>';

            }
            $html .= '</table>';
        }


        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output();
    }

    /**
     * @param string $tipo
     */
    private function setRelatorioIndividual($tipo = '')
    {
        $start = MetodosGerais::inicioContagem();
        $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
        $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);
        $fk_empresa = MetodosGerais::getEmpresaId();
        $contrato = Contrato::model()->findByPk($_POST['Obra']);

        $colaboradores = GrfProjetoConsolidado::model()->getProdutividadeContratoPorColaborador($tipo, $contrato, $dataInicio, $dataFim);
        foreach ($colaboradores as $colaborador) {
            if ($colaborador->salario != null) {
                $equipe = Equipe::model()->findByPk($colaborador->equipe);
                $arrayColaborador[$colaborador->colaborador]['equipe'] = (isset($equipe)) ? $equipe->nome : Yii::t('smith', 'Equipe indefinida');
                $arrayColaborador[$colaborador->colaborador]['duracao'] = $colaborador->duracao;
                $arrayColaborador[$colaborador->colaborador]['custo'] = Contrato::getCalculoCustoColaboradorByData($colaborador->fk_colaborador, $contrato, $dataInicio, $dataFim, $colaborador->duracao, $tipo);
            }
        }
        if (empty($contrato->documento)) {
            $prefixo = $contrato->codigo;
            $documentosLog = GrfProjetoConsolidado::model()->getDocumentosRelatorio($tipo, $contrato, $dataInicio, $dataFim, $fk_empresa);
        } else {
            $documentos = Contrato::model()->getDocumentos($contrato->id);
            $prefixo = $contrato->codigo;
            $tam = count($documentos);

            for ($i = 0; $i < $tam; $i++) {
                $padrao = $documentos[$i]['documento'];
                $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
                $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);
                $documentos[$i]['logs'] = GrfProjetoConsolidado::model()->findProjetosByPrefixo($padrao, $dataInicio, $dataFim, $fk_empresa);
            }
            $docs = (isset($_POST['docs_nao_cadastrados'])) ? GrfProjetoConsolidado::model()->getDocumentosRelatorio($tipo, $contrato, $dataInicio, $dataFim, $fk_empresa) : '';
            $disciplinas = Disciplina::model()->getDisciplinasByContrato($contrato->id);
        }
        $coordenador = (isset(UserGroupsUser::model()->findByPk($contrato->coordenador)->nome)) ? UserGroupsUser::model()->findByPk($contrato->coordenador)->nome : 'Não definido';

        if (empty($contrato->documento)) {
            if (!empty($_POST['button'])) {

                $registros = array($colaboradores, $documentosLog);
                MetodosCSV::ExportCSvRelIndividualContrato($registros, $contrato, $coordenador, $_POST['date_from'], $_POST['date_to'], $_POST['button']);
            } else
                $this->GerarRelatorioDadosPrefixo($documentosLog, $contrato->nome, $coordenador, $arrayColaborador, $prefixo, $_POST['date_from'], $_POST['date_to'], $contrato->valor, $contrato->tempo_previsto, $start, $contrato->data_final);
        } else {

            $this->GerarRelatorioDados($documentos, $contrato->nome, $coordenador, $disciplinas, $arrayColaborador, $prefixo, $docs);
        }
    }

    public function actionGetDocumentos()
    {
        return (Documento::model()->findByAttributes(array('fk_contrato' => $_POST['Obra']))) ? true : false;
    }

    public function actionGetDocumentoDinamico()
    {
        if (isset($_POST['documento'])) {
            $documento = $_POST['documento'];
            $inArray = (isset($_POST['nomesGrid'])) ? " AND nome NOT IN ('" . implode($_POST['nomesGrid'], "', '") . "')" : "";
            $html = "";

            if ($_POST['elemento'] == 'Documento_nome') {
                if ($_POST['action'] == 'create')
                    $documentos = Documento::model()->findAll(array('condition' => "nome LIKE '%$documento%' AND fk_empresa = " . MetodosGerais::getEmpresaId() . $inArray, 'limit' => 10, 'order' => 'nome', 'group' => 'nome'));
                else
                    $documentos = Documento::model()->findAll(array('condition' => "nome LIKE '%$documento%' AND fk_empresa = " . MetodosGerais::getEmpresaId() . " AND fk_contrato != " . $_POST['action'] . $inArray, 'limit' => 10, 'order' => 'nome', 'group' => 'nome'));

                if ((count($documentos) > 0)) {
                    $html = '<div id="resultados"><p><b>10 ' . Yii::t('smith', 'primeiros resultados') . ':</b></p>';
                    foreach ($documentos as $key => $documento) $html .= '<p><a id="p_cliente_' . $key . '" href="#" onclick="carregarDocumento(this.text)">  ' . $documento['nome'] . '</a></p>';
                } else
                    $html .= '<p><i>' . Yii::t('smith', 'Não foram encontrados resultados para:') . ' "' . $_POST['documento'] . '"</i>. É um novo documento? <span id="newDoc" style="cursor: pointer; color: #667fa0;">Sim</span></p>';
            } else {
                $documentos = DocumentoSemContrato::model()->findAll(array('condition' => 'fk_empresa =' . MetodosGerais::getEmpresaId() . " AND documento like '%" . trim($documento) . "%' "));
                if ((count($documentos) > 0))
                    foreach ($documentos as $documento) $html .= '<p id="' . $documento->id . '" class="csvDocs">' . $documento->documento . '</p>';
                else
                    $html .= '<p><i>' . Yii::t('smith', 'Não foram encontrados resultados para:') . ' "' . $_POST['documento'] . '"</i>.</p>';
            }
            $html .= '</div>';
            echo $html;
        }
    }


}
