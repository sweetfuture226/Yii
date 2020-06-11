<?php

class LogCentralNotificacaoController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';
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
            array('allow', // allow admin user to perform 'index' and 'delete' actions
                'actions' => array('index', 'delete', 'create', 'update', 'Desfazer'),
                'groups' => array('admin', 'empresa'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->title_action = "Criar LogCentralNotificacao";
        $model = new LogCentralNotificacao;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['LogCentralNotificacao'])) {
            $model->attributes = $_POST['LogCentralNotificacao'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'LogCentralNotificacao inserido com sucesso.');
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', 'LogCentralNotificacao não pôde ser inserido.');
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->title_action = "Atualizar LogCentralNotificacao";
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['LogCentralNotificacao'])) {
            $model->attributes = $_POST['LogCentralNotificacao'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'LogCentralNotificacao atualizado com sucesso.');
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', 'LogCentralNotificacao não pôde ser atualizado.');
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Requisição inválida. Por favor não repita esta requisição novamente.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $this->title_action = Yii::t('smith', 'Log de notificações');
        $model = new LogCentralNotificacao('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['LogCentralNotificacao']))
            $model->attributes = $_GET['LogCentralNotificacao'];

        $this->render('index', array(
            'model' => $model,
        ));
    }


    public function actionDesfazer($fk_central, $tipo)
    {
        $model = LogCentralNotificacao::model()->findAllByAttributes(array('fk_documento_sem_contrato' => $fk_central));
        if ($tipo == 1) {
            if (!empty($model)) {
                foreach ($model as $key => $value) {
                    $log = LogAtividadeConsolidado::model()->findByPk($value->fk_acao);
                    $sem_contrato = DocumentoSemContrato::model()->findByPk($value->fk_documento_sem_contrato);
                    if ((!is_null($sem_contrato)) && (!is_null($log))) {
                        $log->descricao = $sem_contrato->documento;
                        $log->save(false);
                        $sem_contrato->flagExcluir = 0;
                        $sem_contrato->save(false);
                        $value->delete();
                    }
                }
            }
        } else {
            foreach ($model as $key => $value) {
                $documento_sem_contrato = DocumentoSemContrato::model()->findByPk($value->fk_documento_sem_contrato);
                $documento = Documento::model()->findByPk($value->fk_acao);
                if ((!is_null($documento_sem_contrato)) && (!is_null($documento))) {
                    $sem_contrato->flagExcluir = 0;
                    $sem_contrato->save(false);
                    $documento->delete();
                    $value->delete();
                }
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = LogCentralNotificacao::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'A página não existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'log-central-notificacao-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
