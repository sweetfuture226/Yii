<?php

class ColaboradorHasFaltaController extends Controller
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
                'actions'=>array('index'),
                'groups'=>array('admin','empresa'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->title_action = Yii::t('smith','Colaboradores em falta');
        $this->pageTitle = Yii::t('smith','Colaboradores em falta');
        $model=new ColaboradorHasFalta('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['ColaboradorHasFalta']))
        $model->attributes=$_GET['ColaboradorHasFalta'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer the ID of the model to be loaded
    */
    public function loadModel($id)
    {
        $model=ColaboradorHasFalta::model()->findByPk($id);
        if($model===null)
        throw new CHttpException(404,'A página não existe.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param CModel the model to be validated
    */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='colaborador-has-falta-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @param $data
     * @param $row
     * @return string
     *
     * Método auxiliar utilizado para concatenar todas as datas que houve falta do colaborador em
     * uma string.
     */
    public function getDatas($data, $row)
    {
        $aux = "";
        $datas = ColaboradorHasFalta::model()->findAllByAttributes(array("fk_colaborador"=>$data->fk_colaborador));
        foreach($datas as $value){
            $aux .= MetodosGerais::dataBrasileira($value->data) .",";
        }
        return $aux;
    }
}
