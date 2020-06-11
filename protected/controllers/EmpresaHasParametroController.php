<?php

class EmpresaHasParametroController extends Controller
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
                //'accessControl', // perform access control for CRUD operations
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
                'actions' => array('create', 'update', 'instalador', 'CreateAjax', 'index', 'BaixarInstalador'),
                'groups' => array('empresa','root','demo'),
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
        $model = new EmpresaHasParametro;
        $this->title_action = Yii::t('smith', "Parâmetros");
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['EmpresaHasParametro'])) {

            $model->attributes = $_POST['EmpresaHasParametro'];
            $model->almoco_inicio = $_POST['EmpresaHasParametro']['almoco_inicio'];
            $model->almoco_fim = $_POST['EmpresaHasParametro']['almoco_fim'];
            $model->tempo_ocio = $_POST['EmpresaHasParametro']['tempo_ocio'];
            $model->porcentagem = $_POST['EmpresaHasParametro']['porcentagem'];
            $model->moeda = $_POST['EmpresaHasParametro']['moeda'];
            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);

            if (isset($user->fk_empresa))
                $model->fk_empresa = $user->fk_empresa;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Contrato inserido com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Contrato não pôde ser inserido.'));
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
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed

        if (isset($_POST['EmpresaHasParametro'])) {

            $start = MetodosGerais::inicioContagem();
            $model->attributes = $_POST['EmpresaHasParametro'];
            $model->horario_entrada = date('H:i:s', strtotime($_POST['EmpresaHasParametro']['horario_entrada']));
            $model->horario_saida = date('H:i:s', strtotime($_POST['EmpresaHasParametro']['horario_saida']));
            $model->almoco_inicio = date('H:i:s', strtotime($_POST['EmpresaHasParametro']['almoco_inicio']));
            $model->almoco_fim = date('H:i:s', strtotime($_POST['EmpresaHasParametro']['almoco_fim']));
            $model->tempo_ocio = $_POST['EmpresaHasParametro']['tempo_ocio'] * 1000 * 60;
            $model->moeda = $_POST['EmpresaHasParametro']['moeda'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Parâmetros atualizados com sucesso'));
            LogAcesso::model()->saveAcesso('Configurações', 'Parâmetros gerais', 'Parâmetros', MetodosGerais::tempoResposta($start));
        }
        $model->tempo_ocio = $model->tempo_ocio/1000/60;

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->title_action = Yii::t("smith", "Parâmetros");
        $model = new EmpresaHasParametro('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['EmpresaHasParametro']))
            $model->attributes = $_GET['EmpresaHasParametro'];

        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $dados = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $user->fk_empresa));

        if (!empty($dados)) {
            $this->actionUpdate($dados->id);
        } else {
            $this->render('create', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return EmpresaHasParametro the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = EmpresaHasParametro::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param EmpresaHasParametro $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'empresaHasParametro-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionInstalador()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Instalador");
        $this->pageTitle = Yii::t("smith", "Instalador");
        LogAcesso::model()->saveAcesso('Configurações', 'Baixar instalador', 'Instalador', MetodosGerais::tempoResposta($start));
        $this->render('instalador');
    }

    public function actionCreateAjax() {
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;
        $flag = "Erro";
        if (isset($_POST['EmpresaHasParametro']) && !empty($_POST['EmpresaHasParametro']['almoco_inicio']) && !empty($_POST['EmpresaHasParametro']['almoco_fim'])) {
            $parametrosExists = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
            if (isset($parametrosExists))
                EmpresaHasParametro::model()->deleteAllByAttributes(array("fk_empresa" => $fk_empresa));

            $model = new EmpresaHasParametro;
            $model->attributes = $_POST['EmpresaHasParametro'];
            $model->horario_entrada = $_POST['EmpresaHasParametro']['horario_entrada'];
            $model->horario_saida = $_POST['EmpresaHasParametro']['horario_saida'];
            $model->fk_empresa = $fk_empresa;
            if ($model->save()) {
                $flag = "Sucesso";
                $modelEmpresa = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
                if ($modelEmpresa->passo_wizard <= 3) {
                    $modelEmpresa->passo_wizard = 3;
                    $modelEmpresa->save();
                }
            }
        }
        echo $flag;
    }
}
