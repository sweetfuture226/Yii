<?php

class DocumentoSemContratoController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'userGroupsAccessControl',
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
                'actions' => array('index', 'GetContractsWithDocuments', 'GetDocuments', 'UpdateDocuments', 'GetContratos'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Documentos sem contrato associado");
        $this->pageTitle = Yii::t("smith", "Documentos sem contrato associado");
        $model = new DocumentoSemContrato('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['DocumentoSemContrato']))
            $model->attributes = $_GET['DocumentoSemContrato'];
        if (!empty($_POST['selectedItens'])) {
            foreach ($_POST['selectedItens'] as $contrato) {
                $documentoContrato = DocumentoSemContrato::model()->findByPk($contrato);
                $logContratoConsolidado = new GrfProjetoConsolidado;
                $logContratoConsolidado->documento = $documentoContrato->documento;
                $logContratoConsolidado->duracao = $documentoContrato->duracao;
                $logContratoConsolidado->data = $documentoContrato->data;
                $logContratoConsolidado->fk_empresa = $documentoContrato->fk_empresa;
                $logContratoConsolidado->fk_obra = $_POST['Obra'];
                $logContratoConsolidado->fk_colaborador = $documentoContrato->fk_colaborador;
                $logContratoConsolidado->associado = 1;
                if ($logContratoConsolidado->save())
                    $documentoContrato->delete();
            }
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Documentos associados com sucesso.'));
            $this->refresh();

        }

        LogAcesso::model()->saveAcesso('Contratos', 'Documentos sem contrato', 'Documentos sem contrato associado', MetodosGerais::tempoResposta($start));
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $condicao = ' fk_empresa = ' . $fkEmpresa;
        if (Yii::app()->user->groupName == 'coordenador') {
            $condicao = "fk_empresa=$fkEmpresa AND id in (SELECT fk_contrato FROM usuario_has_contrato WHERE fk_usergroups_user = " . Yii::app()->user->id . ")";
        }
        $this->render('index', array(
            'model' => $model,
            'fkEmpresa' => $fkEmpresa,
            'condicao' => $condicao,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DocumentoSemContrato the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = DocumentoSemContrato::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DocumentoSemContrato $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'documentos-sem-contrato-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetContractsWithDocuments()
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $result = Contrato::model()->searchContractsWithDocuments($fk_empresa);
        $string = "";
        foreach ($result as $key => $value) {
            $string .= "<option value='" . $value->id . "'> " . $value->nome . " </option>";
        }
        echo $string;
    }

    public function actionGetContratos()
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $result = Contrato::model()->findAllByAttributes(array('fk_empresa' => $fk_empresa), array('order' => 'nome ASC'));
        $string = "";
        foreach ($result as $key => $value) {
            $string .= "<option value='" . $value->codigo . "'> " . $value->nome . " </option>";
        }
        echo $string;
    }

    public function actionGetDocuments($fk_contrato)
    {
        $string = "";
        if ($fk_contrato != "") {
            $result = Documento::model()->findAll(array('order' => 'TRIM(nome) ASC', 'condition' => 'fk_contrato = ' . $fk_contrato));
            foreach ($result as $key => $value) {
                $string .= "<option value='" . $value->id . "'> " . $value->nome . " </option>";
            }
        } else {
            $string .= "<option value=''> Selecione um contrato ... </option>";
        }
        echo $string;
    }

    public function actionUpdateDocuments($id_documento_sem_contrato, $nome_documento_sem_contrato, $novo_nome_documento,
                                          $duracao, $fk_colaborador)
    {
        $log = LogAtividadeConsolidado::model()->findAll(array('condition' => 'descricao LIKE "%' . $nome_documento_sem_contrato . '%" and fk_empresa = ' . MetodosGerais::getEmpresaId()));
        if (!empty($log)) {
            foreach ($log as $key => $value) {
                $value->descricao = Documento::model()->findByPk($novo_nome_documento)->nome;
                $value->save(false);
                $log2 = new LogCentralNotificacao;
                $log2->fk_empresa = MetodosGerais::getEmpresaId();
                $log2->tipo = 1;
                $log2->descricao = Documento::model()->findByPk($novo_nome_documento)->nome;
                $log2->fk_acao = $value->id;
                $log2->fk_documento_sem_contrato = $id_documento_sem_contrato;
                $log2->save(false);
            }
            $doc = DocumentoSemContrato::model()->findByPk($id_documento_sem_contrato);
            $doc->flagExcluir = 1;
            $doc->save(false);

            echo "success";
        } else {
            $new_log = new LogAtividadeConsolidado;
            $new_log->descricao = Documento::model()->findByPk($novo_nome_documento)->nome;
            $new_log->fk_empresa = MetodosGerais::getEmpresaId();
            $new_log->data = date("Y-m-d");
            $new_log->duracao = MetodosGerais::formataTempo($duracao);
            $new_log->usuario = Colaborador::model()->findByPk($fk_colaborador)->ad;
            $new_log->serial_empresa = MetodosGerais::getSerial();
            if ($new_log->save()) {

                $doc = DocumentoSemContrato::model()->findByPk($id_documento_sem_contrato);
                $doc->flagExcluir = 1;
                $doc->save(false);
                $log = new LogCentralNotificacao;
                $log->fk_empresa = MetodosGerais::getEmpresaId();
                $log->tipo = 1;
                $log->descricao = Documento::model()->findByPk($novo_nome_documento)->nome;
                $log->fk_acao = $new_log->id;
                $log->fk_documento_sem_contrato = $id_documento_sem_contrato;
                $log->save(false);
                echo "success";
            } else {
                echo "<pre>";
                print_r($new_log->getErros());
                echo "</pre>";
            }
        }
    }
}
