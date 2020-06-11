<?php

class DisciplinaController extends Controller
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'index', 'delete', 'createAjaxDisciplina', 'compareDisciplina'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->title_action = Yii::t("smith", "Criar Disciplina");
        $this->pageTitle = Yii::t("smith", "Criar Disciplina");
        $model = new Disciplina;

        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Disciplina'])) {
            if (!Disciplina::model()->find(array('condition' => 'codigo = "' . trim($_POST['Disciplina']['codigo']) . '" AND fk_empresa = ' . MetodosGerais::getEmpresaId()))) {
                $start = MetodosGerais::inicioContagem();

                $model->attributes = $_POST['Disciplina'];
                $model->fk_empresa = $fk_empresa;
                $model->codigo = trim($model->codigo);
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Disciplina inserido com sucesso.'));
                    LogAcesso::model()->saveAcesso('Contratos', 'Criar Disciplina', 'Criar', MetodosGerais::tempoResposta($start));
                    $this->redirect(array('index'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('smith', 'Disciplina não pôde ser inserida.'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Disciplina já cadastrada.'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /*
     * Criar disciplina através do modal na tela de cadastro
     * de novo contrato
     */
    public function actionCreateAjaxDisciplina()
    {
        if (!empty($_POST['disciplina'])) {
            $model = new Disciplina();
            $model->codigo = trim($_POST['disciplina']);
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            if ($model->save()) {
                $disciplinas = Disciplina::model()->findAllByAttributes(array("fk_empresa" => $model->fk_empresa));
                echo "<option value=''>".Yii::t('smith', 'Selecione')."</option>";
                foreach ($disciplinas as $value) {
                    echo "<option value='{$value->id}'>{$value->codigo}</option>";
                }
            }
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->title_action = Yii::t("smith", Yii::t('smith', "Atualizar Disciplina"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Atualizar Disciplina"));
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Disciplina'])) {
            $model->attributes = $_POST['Disciplina'];
            $model->codigo = trim($model->codigo);
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Disciplina atualizado com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Disciplina não pôde ser atualizado.'));
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
            Documento::model()->deleteAll('fk_disciplina = ' . $id);
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
    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Disciplinas/Fases");
        $this->pageTitle = Yii::t("smith", "Disciplinas");
        $model = new Disciplina('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Disciplina']))
            $model->attributes = $_GET['Disciplina'];
        LogAcesso::model()->saveAcesso('Contratos', 'Disciplinas/Fases', 'Disciplinas/Fases', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Disciplina::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pro-disciplina-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCompareDisciplina() {
        return (Disciplina::model()->find(array('condition' => 'codigo = "' . trim($_POST['disciplina']) . '" AND fk_empresa = ' . MetodosGerais::getEmpresaId())) || empty($_POST['disciplina'])) ? true : false;
    }
}
