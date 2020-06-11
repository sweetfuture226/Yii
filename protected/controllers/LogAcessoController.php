<?php

class LogAcessoController extends Controller
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
            array('allow', // allow admin user to perform 'index' and 'delete' actions
                'actions' => array('index'),
                'groups' => array('root'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $this->title_action = "LogAcessos";
        $model = new LogAcesso('searchIndex');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['LogAcesso']))
            $model->attributes = $_GET['LogAcesso'];

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
        $model = LogAcesso::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'log-acesso-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
