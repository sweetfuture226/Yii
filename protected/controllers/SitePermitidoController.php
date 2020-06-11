<?php

class SitePermitidoController extends Controller
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
        return array('userGroupsAccessControl', // perform access control for CRUD operations
            //'accessControl', // perform access control for CRUD operations
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
                'actions' => array('create', 'delete', 'update', 'index', 'validar'),
                'groups' => array('root', 'empresa', 'demo'),
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
        $this->title_action = Yii::t("smith", Yii::t('smith', "Adicionar Site Permitido"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Adicionar Site Permitido"));

        $model = new SitePermitido;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if ((isset($_POST['sites_selecionados'])) && (isset($_POST['SitePermitido']))) {
            $start = MetodosGerais::inicioContagem();
            Yii::import("application.modules.userGroups.models.UserGroupsUser", true);
            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            if (isset($user->fk_empresa))
                $fk_empresa = $user->fk_empresa;

            $lista_site = $_POST['sites_selecionados'];
            //$fk_empresa = MetodosGerais::getEmpresaId();
            $ultimo_inserir = count($lista_site);
            $flag = 0;
            foreach ($lista_site as $site) {
                $model = new SitePermitido;
                $model->fk_equipe = $_POST['SitePermitido']['fk_equipe'];
                $model->fk_empresa = $fk_empresa;
                $model->nome = $site;
                $model->save();
                $flag++;
                if ($flag == $ultimo_inserir) {
                    LogAcesso::model()->saveAcesso('Configurações', 'Adicionar Site Permitido', 'Adicionar Site Permitido', MetodosGerais::tempoResposta($start));
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Os Sites foram inseridos com sucesso.'));
                    $this->redirect(array('index'));
                }
            }

        }
        if (isset($_POST['enviarEmail'])) {

            $mensagem = "<div>" . Yii::t('smith', 'Olá Administrador') . ",<br><br> " . Yii::t('smith', 'Os seguintes sites foram solicitados para inclusão:') . " <br>" . $_POST['site'] . "</div>";
            // $message = new YiiMailMessage;
            // $message->setBody($mensagem, 'text/html');
            // $message->subject = Yii::t('smith', "Novos Sites Solicitados") . " - " . Yii::app()->user->name;
            // $message->addTo("lucascardoso@vivainovacao.com");
            // $message->from = "lucascardoso@vivainovacao.com";

            // Yii::app()->mail->send($message);

            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), Yii::t('smith', 'Novos Sites Solicitados') .' - ' . Yii::app()->user->name, $mensagem);
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Solicitação enviada'));
            $this->refresh();
        }

        $this->render('create', array('model' => $model,));
    }

    /**
     * Action utilizado no modal de solicitação de novos sites para inclusão.
     */
    public function actionValidar()
    {
        $mensagem = "<div>" . Yii::t('smith', 'Olá Administrador') . ",<br><br> " . Yii::t('smith', 'Os seguintes sites foram solicitados para inclusão:') . " <br>" . $_POST['site'] . "</div>";
        $message = new YiiMailMessage;
        $message->setBody($mensagem, 'text/html');
        $message->subject = Yii::t('smith', "Novos Sites Solicitados") . " - " . Yii::app()->user->name;
        $message->addTo("lucascardoso@vivainovacao.com");
        $message->from = "lucascardoso@vivainovacao.com";
        Yii::app()->mail->send($message);
        echo "OK";
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->title_action = Yii::t("smith", "Atualizar Site Permitido");
        $this->pageTitle = Yii::t("smith", "Atualizar Site Permitido");

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SitePermitido'])) {
            $model->attributes = $_POST['SitePermitido'];

            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Site atualizado com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Site não pôde ser atualizado.'));
            }
        }

        $this->render('update', array('model' => $model,));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $start = MetodosGerais::inicioContagem();
        $this->loadModel($id)->delete();
        LogAcesso::model()->saveAcesso('Configurações', 'Deletar Site Permitido', 'Deletar Site Permitido', MetodosGerais::tempoResposta($start));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Lista de sites permitidos");
        $this->pageTitle = Yii::t("smith", "Lista de sites permitidos");

        $model = new SitePermitido('search');
        $model->unsetAttributes();
        // clear any default values
        if (isset($_GET['SitePermitido']))
            $model->attributes = $_GET['SitePermitido'];
        LogAcesso::model()->saveAcesso('Configurações', 'Sites permitidos', 'Lista de sites permitidos', MetodosGerais::tempoResposta($start));
        $this->render('index', array('model' => $model,));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SitePermitido the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = SitePermitido::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SitePermitido $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'site-permitido-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
