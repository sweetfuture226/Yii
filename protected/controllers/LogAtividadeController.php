<?php

class LogAtividadeController extends Controller {

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
            array('allow', // allow all users to perform 'view' action
                'actions' => array('index', 'AusenciaNaoJustificada'),
                'groups' => array('coordenador', 'empresa', 'root','demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Registro de Atividades em Tempo Real");
        $this->pageTitle = Yii::t("smith", "Registro de Atividades em Tempo Real");

        $model = new LogAtividade('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['LogAtividade']))
            $model->attributes = $_GET['LogAtividade'];

        $serial = MetodosGerais::getSerial();
        $fk_empresa = MetodosGerais::getEmpresaId();

        LogAcesso::model()->saveAcesso('Tempo Real', 'Atividades em tempo real', 'Registro de Atividades em Tempo Real', MetodosGerais::tempoResposta($start));

        $this->render('index', array(
            'model' => $model,
            'serial' => $serial,
            'fk_empresa' => $fk_empresa,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = AtividadeExterna::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'log-atividade-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @param $data
     * @param $row
     * @return string
     *
     * Método auxiliar para converter a hora do servidor para o horário brasileiro.
     */
    public function getHoraServidor($data, $row) {
        $dateTime = new DateTime($data->data_hora_servidor, new DateTimeZone('America/New_York'));
        $dateTime->setTimezone(new DateTimeZone('America/Bahia'));
        $dateTime->modify('-4 hour');
        return $dateTime->format('H:i:s');
    }

    public function actionAusenciaNaoJustificada($fk_log)
    {
        $log = LogAtividade::model()->findByPk($fk_log);
        $log->atividade_extra = 1;
        if ($log->save(false)) {
            GrfOciosidadeConsolidado::model()->updateAll(array('status' => 1), array('condition' => "fk_log = " . $fk_log));
            echo "success";
        }
    }


}
