<?php

class DocumentoController extends Controller
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
                'actions' => array('index', 'close', 'getDisciplinaGrid', 'getDisciplinaId', 'compareDocumento', 'createCSV', 'deleteCSV', 'CreateFromAjax'),
                'groups' => array('coordenador', 'empresa', 'root'),
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
        $this->title_action = Yii::t("smith", Yii::t('smith', "Documentos"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Documento"));
        $model = new Documento('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Documento']))
            $model->attributes = $_GET['Documento'];

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
        $model = Documento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /*
     * Exibir o nome da disciplina de cada documento a partir da fk_disciplina.
     */
    public function getNomeDisciplina($data, $row)
    {
        $disciplina = Disciplina::model()->findByPk($data->fk_disciplina);
        return $disciplina->nome;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'documento-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /*
     * Método auxiliar para finalizar um documento a partir do acompanhmento de contrato
     * em formato de árvore.
     */
    public function actionClose()
    {
        $id = $_POST['id'];
        $model = Documento::model()->findByPk($id);
        $model->finalizado = true;
        $model->save(false);
    }

    public function actionGetDisciplinaGrid()
    {
        $disciplina = Disciplina::model()->findByPk($_POST['disciplina'])->codigo;

        echo $disciplina;
    }

    public function actionGetDisciplinaId()
    {
        $disciplina = Disciplina::model()->findByAttributes(array('codigo' => $_POST['disciplina'], 'fk_empresa' => MetodosGerais::getEmpresaId()))->id;

        echo $disciplina;
    }

    public function actionCompareDocumento()
    {
        return (Documento::model()->find(array('condition' => 'nome LIKE "' . trim(str_replace("\\", "\\\\\\\\", $_POST['documento'])) . '" AND fk_empresa = ' . MetodosGerais::getEmpresaId() . ' AND fk_contrato = ' . $_POST['contrato'])) || empty($_POST['documento'])) ? true : false;
    }

    public function actionCreateCSV()
    {
        $src = dirname(Yii::app()->request->scriptFile) . '/public/';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->getProperties()->setCreator("Smith")->setLastModifiedBy("Smith");
        $phpExcel->getActiveSheet()
            ->setCellValue('A1', Yii::t('smith', 'nome'))
            ->setCellValue('B1', Yii::t('smith', 'previsto'))
            ->setCellValue('C1', Yii::t('smith', 'disciplina'));

        $index = 2;
        foreach ($_POST['docs'] as $data) {
            $phpExcel->getActiveSheet()->setCellValue('A' . $index, $data);
            $index++;
        }

        try {
            $filename = 'lista_de_documentos_' . date('dmYHis') . '.csv';
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'CSV');
            $objWriter->save($src . $filename);
            echo $filename;
        } catch (Exception $e) {
            Logger::saveError($e);
        }
    }

    public function actionDeleteCSV()
    {
        try {
            unlink(Yii::app()->basePath . '/../' . $_POST['doc']);
        } catch (Exception $e) {
            Logger::saveError($e);
        }
    }

    public function actionCreateFromAjax($fk_disciplina, $tempo, $fk_contrato, $nome_documento, $documento_sem_contrato_id)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            /*CRIAR NOVO DOCUMENTO*/
            $model = new Documento;
            $model->fk_contrato = $fk_contrato;
            $model->fk_disciplina = $fk_disciplina;
            $model->previsto = $tempo;
            $model->nome = $nome_documento;
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->finalizado = 0;
            $model->save();

            /*DELEÇÂO LOGICA DO DOCUMENTO SEM CONTRATO*/
            $doc = DocumentoSemContrato::model()->findByPk($documento_sem_contrato_id);
            $doc->flagExcluir = 1;
            $doc->save(false);

            /*CONSOLIDAÇÂO DO NOVO DOCUMENTO*/
            $grfProjetoConsolidado = new GrfProjetoConsolidado();
            $grfProjetoConsolidado->attributes = $doc->attributes;
            $grfProjetoConsolidado->associado = 1;
            $grfProjetoConsolidado->fk_obra = $fk_contrato;
            $grfProjetoConsolidado->save(false);

            /*REGISTRO DE LOG DA AÇÂO*/
            $log = new LogCentralNotificacao;
            $log->fk_empresa = MetodosGerais::getEmpresaId();
            $log->tipo = 2;
            $log->descricao = $nome_documento;
            $log->fk_acao = $model->id;
            $log->fk_documento_sem_contrato = $documento_sem_contrato_id;
            $log->save();

            $transaction->commit();
            echo "success";
        } catch (Exception $e) {
            $transaction->rollBack();
            echo "erro";

        }
    }
}
