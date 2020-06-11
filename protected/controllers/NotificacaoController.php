<?php

class NotificacaoController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */

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
            array('allow', // allow all users to perform 'view' action
                'actions' => array('notificacaoLida', 'removerNotificacao'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionNotificacaoLida() {
        $id_notificacao = $_POST['id'];
        $model = $this->loadModel($id_notificacao);

        $model->status = Notificacao::STATUS_LIDA;

        echo $model->save() ? true : false;
    }

    public function actionRemoverNotificacao() {
        $this->loadModel($_POST['notificacao'])->delete();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Notificacao the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Notificacao::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Notificacao $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'notificacao-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
