<?php

class ColaboradorController extends Controller
{

    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'userGroupsAccessControl',
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
                'actions' => array('view', 'index', 'update',
                    'inativar', 'reativar', 'CreateAjax', 'upload', 'verificaDataRelatorioIndDia',
                    'VerificaDataRelatorioIndDia', 'getIdByNomeCompleto', 'getColaboradores', 'getIdByNomeCompletoAjax', 'Demissao', 'IsBlock', 'IsBlockAd'),
                'groups' => array('empresa', 'root', 'demo'),
            ),
            array('allow', // allow all users to perform 'view' action
                'actions' => array('view', 'index',
                      'getColaboradores', 'IsBlock', 'IsBlockAd' ),
                'groups' => array('coordenador'),
            ),
            array('allow', // allow all users to perform 'view' action
                'actions' => array('create', 'delete'),
                'groups' => array('root'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Método auxiliar utilizado pelo ajax para inativar o status do colaborador
     */
    public function actionInativar() {
        $start = MetodosGerais::inicioContagem();
        $model = Colaborador::model()->findByPk($_POST['pessoa']);
        $model->status = 0;
        if($model->save(false))
            ColaboradorHasFalta::model()->deleteAllByAttributes(array('fk_colaborador'=>$_POST['pessoa']));
        LogAcesso::model()->saveAcesso('Configurações', 'Inativar Colaborador', 'Inativar', MetodosGerais::tempoResposta($start));
    }

    /**
     * Método auxiliar utilizado pelo ajax para reativar o status do colaborador
     */
    public function actionReativar() {
        $start = MetodosGerais::inicioContagem();
        $model = Colaborador::model()->findByPk($_POST['pessoa']);
        $model->status = 1;
        $model->save(false);
        LogAcesso::model()->saveAcesso('Configurações', 'Reativar Colaborador', 'Reativar', MetodosGerais::tempoResposta($start));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->title_action = Yii::t('smith', Yii::t('smith', 'Criar Colaborador'));
        $this->pageTitle = Yii::t('smith', Yii::t('smith', 'Criar Colaborador'));
        $model = new Colaborador;
        $modelEquipe = new Equipe;

        if (isset($_POST['Equipe'])) {
            $modelEquipe->attributes = $_POST['Equipe'];
            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            if (isset($user->fk_empresa))
                $modelEquipe->fk_empresa = $user->fk_empresa;

            if ($modelEquipe->save()) {
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Equipe inserida com sucesso.'));
                $this->refresh();
            }
        }

        if (isset($_POST['Colaborador'])) {
            $salario = MetodosGerais::real2float($_POST['Colaborador']['salario']);
            $model->attributes = $_POST['Colaborador'];
            $model->salario = $salario;
            $model->fk_equipe = $_POST['Colaborador']['fk_equipe'];
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->ativo = 1;

            if ($model->save()) {

                Yii::app()->user->setFlash('success', Yii::t('smith', 'Colaborador inserido com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Colaborador não pôde ser inserido.'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'modelEquipe' => $modelEquipe
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->title_action = Yii::t('smith', Yii::t('smith', 'Atualizar Colaborador'));
        $this->pageTitle = Yii::t('smith', Yii::t('smith', 'Atualizar Colaborador'));
        $model = $this->loadModel($id);
        $modelEquipe = new Equipe;
        $modelFerias = new ColaboradorHasFerias;
        $modelEquipes = new ColaboradorHasEquipe;
        $modelSalario = new ColaboradorHasSalario;
        $modelColaboradorHasParametro = ColaboradorHasParametro::model()->findByAttributes(array('fk_colaborador' => $id));
        $modelParametros = EmpresaHasParametro::model()->findByAttributes(array('fk_empresa' => MetodosGerais::getEmpresaId()));

        $horario_entrada = (isset($modelColaboradorHasParametro)) ? $modelColaboradorHasParametro->horario_entrada : $modelParametros->horario_entrada;
        $horario_saida = (isset($modelColaboradorHasParametro)) ? $modelColaboradorHasParametro->horario_saida : $modelParametros->horario_saida;

        if (isset($_POST['Equipe'])) {
            $modelEquipe->attributes = $_POST['Equipe'];
            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            if (isset($user->fk_empresa))
                $modelEquipe->fk_empresa = $user->fk_empresa;

            if ($modelEquipe->save()) {
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Equipe inserida com sucesso.'));
                $this->refresh();
            }
        }

        if (isset($_POST['Colaborador'])) {
            $start = MetodosGerais::inicioContagem();
            $fk_empresa = MetodosGerais::getEmpresaId();
            $salario = MetodosGerais::real2float($_POST['Colaborador']['salario']);
            $valor_hora = MetodosGerais::real2float($_POST['Colaborador']['valor_hora']);

            if($salario != $model->salario){
                $chs = new ColaboradorHasSalario;
                $chs->fk_colaborador = (int)$model->id;
                $chs->fk_empresa = $fk_empresa;
                $chs->data_inicio = date('Y-m-d');
                $chs->valor = $salario;
                $chs->save();
            }
            $flag = $model->fk_equipe == $_POST['Colaborador']['fk_equipe'] ? false : true;
            $model->fk_empresa = $fk_empresa;
            $model->attributes = $_POST['Colaborador'];
            $model->nome = $_POST['Colaborador']['nome'];
            $model->sobrenome = $_POST['Colaborador']['sobrenome'];
            $model->salario = $salario;
            $model->valor_hora = $valor_hora;
            $model->fk_equipe = $_POST['Colaborador']['fk_equipe'];
            $model->ativo = 1;

            if ($model->save()) {
                if ($flag)
                    ColaboradorHasEquipe::model()->saveRelation($id, $model->fk_equipe);
                if (!isset($modelColaboradorHasParametro)) {
                    $modelColaboradorHasParametro = new ColaboradorHasParametro();
                    $modelColaboradorHasParametro->fk_colaborador = $id;
                    $modelColaboradorHasParametro->fk_empresa = MetodosGerais::getEmpresaId();
                    $modelColaboradorHasParametro->horario_entrada = $_POST['horario_entrada'];
                    $modelColaboradorHasParametro->horario_saida = $_POST['horario_saida'];
                    $modelColaboradorHasParametro->save();
                } else {
                    $modelColaboradorHasParametro->horario_entrada = $_POST['horario_entrada'];
                    $modelColaboradorHasParametro->horario_saida = $_POST['horario_saida'];
                    $modelColaboradorHasParametro->save();
                }


                LogAcesso::model()->saveAcesso('Configurações', 'Atualizar Colaborador', 'Atualizar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Colaborador atualizado com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Colaborador não pôde ser atualizado.'));
            }
        }

        $model->salario = MetodosGerais::float2real($model->salario);
        $model->valor_hora = MetodosGerais::float2real($model->valor_hora);

        $this->render('update', array(
            'model' => $model,
            'modelEquipe' => $modelEquipe,
            'modelSalario' => $modelSalario,
            'modelFerias' => $modelFerias,
            'modelEquipes' => $modelEquipes,
            'horario_entrada' => $horario_entrada,
            'horario_saida' => $horario_saida
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
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('smith', 'Requisição inválida. Por favor não repita esta requisição novamente.'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t('smith', 'Colaboradores');
        $this->pageTitle = Yii::t('smith', 'Colaboradores');
        $model = new Colaborador('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Colaborador']))
            $model->attributes = $_GET['Colaborador'];
        LogAcesso::model()->saveAcesso('Configurações', 'Colaboradores', 'Colaboradores', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Colaborador::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pro-pessoa-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCreateAjax() {
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;
        $flag = "";
        if (isset($_POST['documento'])) {
            if ($_POST['documento'] == 'ism') {
                //INSERÇÃO MANUAL
                if (isset($_POST['Colaborador'])) {
                    foreach ($_POST['Colaborador'] as $pessoa) {
                        $model = $this->loadModel($pessoa['id']);
                        if ($pessoa['nome'] != "" && $pessoa['salario'] != "" && $pessoa['horas_semana'] != "" && $pessoa['fk_equipe'] != "")
                            $model->ativo = 1;

                        $model->attributes = $pessoa;
                        $salario = MetodosGerais::real2float($pessoa['salario']);
                        $model->salario = $salario;
                        $model->fk_empresa = $fk_empresa;

                        if ($model->save(false))
                            $flag = "Sucesso";
                        else
                            $flag = "Erro";
                    }
                    $modelEmpresa = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
                    if ($modelEmpresa->passo_wizard < 5) {
                        $modelEmpresa->passo_wizard = 5;
                        $modelEmpresa->save();
                        $flag = "Sucesso";
                    }

                    if ($flag === "Sucesso") {
                        $this->Concluir();
                        echo "Sucesso";
                    } else {
                        return false;
                    }
                }
            } else if ($_POST['documento'] == 'ise') {
                //INSERÇÃO EXCEL
                $flag = "Erro";
                if (isset($_POST['nameFile'])) {
                    $fullPath = Yii::app()->basePath . '/../public/' . $_POST['nameFile'];
                    if ($this->validarXls($fullPath)) {
                        $flag = "Sucesso";
                    }
                }
                if ($flag === "Sucesso") {
                    $this->Concluir();
                    echo "Sucesso";
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Método auxiliar utilizado pelo wizard assim que finalizado.
     */
    public function Concluir() {
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;
        $model = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
        $model->wizard = 1;
        //Colaborador::model()->updateAll(array("ativo"=>"1"),"fk_empresa={$fk_empresa}");
        $model->save();
    }

    /**
     * @throws CException
     * Método utilizado para realizar o upload de parametrização de planilha dos colaboradores
     */
    public function actionUpload() {
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder = Yii::app()->basePath . '/../public/';
        $allowedExtensions = array("xls", "xlsx"); //array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 10 * 1024 * 1024; // maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        $fileSize = filesize($folder . $result['filename']); //GETTING FILE SIZE
        $fileName = $result['filename']; //GETTING FILE NAME
        echo $return; // it's array
    }

    /**
     * @param $fullPath
     * @return bool
     *
     * Método auxiliar para verificar se os dados da planilha estão corretos e salva-los
     * na tabela de colaborador.
     */
    private function validarXls($fullPath) {
        $idEmpresa = MetodosGerais::getEmpresaId();

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
                $ad = $sheet->getCell('A' . $row)->getValue();
                $colaborador = Colaborador::model()->findByAttributes(
                    array('ad' => $ad, 'fk_empresa' => $idEmpresa));
                if (!is_null($colaborador)) {
                    $colaborador->nome = $sheet->getCell('B' . $row)->getValue();
                    $colaborador->sobrenome = $sheet->getCell('C' . $row)->getValue();
                    $colaborador->email = $sheet->getCell('D' . $row)->getValue();

                    $salario = str_replace(",", ".", $sheet->getCell('E' . $row)->getValue());
                    $colaborador->salario = (float) $salario;

                    $cellF = $sheet->getCell('F' . $row)->getValue();
                    $cellFType = $sheet->getCell('F' . $row)->getDataType();
                    $colaborador->horas_semana = MetodosCSV::strTempoByTipo($cellF, $cellFType);

                    $equipe = Equipe::model()->findByAttributes(
                            array('nome' => $sheet->getCell('G' . $row)->getValue(),
                                'fk_empresa' => $idEmpresa));
                    $colaborador->fk_equipe = $equipe->id;

                    $horas = MetodosCSV::getTimeByTipo($cellF, $cellFType);
                    $horaMensal = (float) ($horas) * 4;
                    $total = (float) $colaborador->salario / $horaMensal;
                    $colaborador->valor_hora = round($total, 2);
                    $colaborador->status = 1;
                    if (!$colaborador->save()) {
                        print_r($colaborador->errors);
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            unlink($fullPath);
            Logger::saveError($e);
        }
        unlink($fullPath);
        return true;
    }

    public function actionGetIdByNomeCompleto(){
        $obj = Colaborador::model()->findColaboradorByNomeCompleto($_GET['nome']);
        echo $obj->id;
    }

    public function actionGetIdByNomeCompletoAjax()
    {
        $nome_completo = explode(' ', $_POST['nome']);
        $sobrenome = $nome_completo[count($nome_completo) - 1];

        $criteria = new CDbCriteria;
        $criteria->addCondition('nome REGEXP "^' . $nome_completo[0] . '"');
        $criteria->addCondition('sobrenome REGEXP "' . $sobrenome . '$"');
        $criteria->addCondition('fk_empresa = ' . MetodosGerais::getEmpresaId());
        $criteria->addCondition('ativo = 1 AND status = 1');

        $colaborador = Colaborador::model()->find($criteria);

        echo $colaborador->id;
    }

    /**
     * Método auxiliar utilizado para preencher o filtro de colaboradores das telas de pesquisas
     * dos relatórios via ajax.
     */
    public function actionGetColaboradores() {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = "fk_empresa=$fk_empresa AND fk_equipe = " . MetodosGerais::getEquipe();

        if (Yii::app()->user->groupName != 'coordenador') {
            $condicao = ' fk_empresa = ' . $fk_empresa;
            echo CHtml::tag('option', array('value' => 'todos_colaboradores'), CHtml::encode(Yii::t("smith", "Todos")), true);
        }

        $colaboradores = Colaborador::model()->findAll(array('condition' => $condicao, "order" => "nome"));
        foreach ($colaboradores as $d) {
            echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->nomeCompleto), true);
        }
    }

    /**
     * @throws CHttpException
     * Método auxiliar para verificar se houve produtividade do colaborador em determinada data
     * via ajax antes de realizar a consulta do relatório
     */
    public function actionVerificaDataRelatorioIndDia() {
        $data_relatorio = $_POST['data_relatorio'];
        $id_colaborador = $_POST['id_colaborador'];

        $colaborador = $this->loadModel($id_colaborador);
        echo $colaborador->verificaDataRelatorioIndDia($data_relatorio);
        Yii::app()->end();
    }

    public function actionDemissao($fk_colaborador)
    {
        $model = Colaborador::model()->findByPk($fk_colaborador);
        $model->status = 0;
        if ($model->save(false)) {
            ColaboradorHasFalta::model()->deleteAllByAttributes(array('fk_colaborador' => $fk_colaborador));
            ColaboradorSemProdutividade::model()->deleteAll(array('condition' => "fk_colaborador = " . $fk_colaborador));
            echo "success";
        } else {
            echo "error";
        }
    }


    public function actionIsBlock()
    {
        $option = "<optgroup label='Ativos'>";
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        if($user->group_id == 4){
            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa'=>MetodosGerais::getEmpresaId(), 'fk_equipe'=>$user->fk_equipe), array('order' => 'status DESC, nome ASC'));
        }else {

            $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa'=>MetodosGerais::getEmpresaId()), array('order' => 'status DESC, nome ASC'));
        }
        foreach ($colaboradores as $key => $value)
        {
            if($value->status == 1)
            {
                $option .= "<option value='".$value->id."'>".$value->nomeCompleto."</option>";
            }
        }
        $option .= "</optgroup>";
        $option .= "<optgroup label='Inativos'>";
        foreach ($colaboradores as $key => $value) {
            if ($value->status == 0) {
                $option .= "<option value='" . $value->id . "'>" . $value->nomeCompleto . "</option>";
            }
        }
        $option .= "</optgroup>";
        echo $option;
    }


    public function actionIsBlockAd()
    {
        $option = "<optgroup label='Ativos'>";
        $colaboradores = Colaborador::model()->findAllByAttributes(array('fk_empresa'=>MetodosGerais::getEmpresaId()), array('order' => 'status DESC, nome ASC'));
        foreach ($colaboradores as $key => $value)
        {
            if($value->status == 1)
            {
                $option .= "<option value='".$value->ad."'>".$value->nomeCompleto."</option>";
            }
        }
        $option .= "</optgroup>";
        $option .= "<optgroup label='Inativos'>";
        foreach ($colaboradores as $key => $value) {
            if ($value->status == 0) {
                $option .= "<option value='" . $value->ad . "'>" . $value->nomeCompleto . "</option>";
            }
        }
        $option .= "</optgroup>";
        echo $option;
    }

    
}
