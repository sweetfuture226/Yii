<?php

class CalendarioFeriadosController extends Controller
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
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'create', 'feriadoInativo', 'delete', 'importCsv', 'upload'),
                'groups' => array('empresa', 'root', 'demo'),
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
    public function actionCreate()
    {
        $this->title_action = Yii::t("smith", "Novos feriados");
        $this->pageTitle = Yii::t("smith", "Novos feriados");

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model = CalendarioFeriados::model()->findAllByAttributes(array("fk_empresa" => MetodosGerais::getEmpresaId()));
        $datas = '';
        foreach ($model as $value) $datas .= MetodosGerais::dataBrasileira($value->data) . ',';

        if (isset($_POST['CalendarioFeriados'])) {
            $start = MetodosGerais::inicioContagem();

            CalendarioFeriados::model()->deleteAllByAttributes(array("fk_empresa" => MetodosGerais::getEmpresaId()));
            $datas = explode(',', $_POST['CalendarioFeriados']['data']);
            foreach ($datas as $value) {
                $model = new CalendarioFeriados;
                $model->data = MetodosGerais::dataAmericana($value);
                $model->fk_empresa = MetodosGerais::getEmpresaId();
                $model->save();
            }

            if ($_FILES['CalendarioFeriados']['name']['file'] != "") $this->actionImportCsv($_FILES['CalendarioFeriados']);
            LogAcesso::model()->saveAcesso('Configurações', 'Novos feriados', 'Novos feriados', MetodosGerais::tempoResposta($start));
            $this->redirect(array('index'));
        }

        $this->render('create', array(
            'datas' => $datas,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Calendário de feriados");
        $this->pageTitle = Yii::t("smith", "Calendário de feriados");
        $model = new CalendarioFeriados('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CalendarioFeriados']))
            $model->attributes = $_GET['CalendarioFeriados'];
        LogAcesso::model()->saveAcesso('Configurações', 'Calendário de feriados', 'Calendário de feriados', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionFeriadoInativo()
    {
        $this->title_action = Yii::t("smith", "Calendário de feriados");
        $this->pageTitle = Yii::t("smith", "Calendário de feriados");
        $model = new CalendarioFeriados('searchInativos');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CalendarioFeriados']))
            $model->attributes = $_GET['CalendarioFeriados'];
        if(!empty($_POST['selectedItens'])){
            foreach($_POST['selectedItens'] as $data){
                $dataObj = CalendarioFeriados::model()->findByAttributes(array("data"=>$data,"fk_empresa"=>MetodosGerais::getEmpresaId()));
                $dataObj->ativo = 1;
                $dataObj->save();
            }
            Notificacao::model()->deleteAllByAttributes(array('tipo'=>6,'fk_empresa'=>MetodosGerais::getEmpresaId()));
            Yii::app()->user->setFlash('success', Yii::t("smith", "Os novos feriados foram cadastrados."));
            $this->refresh();
        }

        $this->render('feriadoInativo', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CalendarioFeriados the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = CalendarioFeriados::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CalendarioFeriados $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'calendario-feriados-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionImportCSV()
    {
        if (!empty($_POST['nameFile'])) {
            $fullPath = Yii::app()->basePath . '/../public/csv/' . $_POST['nameFile'];
            $fk_empresa = MetodosGerais::getEmpresaId();

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
                    $data = $sheet->getCell('A' . $row)->getValue();

                    $model = new CalendarioFeriados;
                    $model->data = MetodosGerais::dataAmericana($data);
                    $model->fk_empresa = $fk_empresa;
                    $model->save();
                }
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Data(s) inseridas com sucesso.'));
                $this->redirect('index');
            } catch (Exception $e) {
                unlink($fullPath);
                Logger::sendException($e);
            }
        }
    }

    public function actionUpload()
    {
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder = Yii::app()->basePath . '/../public/csv/';
        $allowedExtensions = array("csv"); //array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 10 * 1024 * 1024; // maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        $fileSize = filesize($folder . $result['filename']); //GETTING FILE SIZE
        $fileName = $result['filename']; //GETTING FILE NAME
        echo $return; // it's array
    }
}
