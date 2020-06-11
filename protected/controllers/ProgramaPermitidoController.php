<?php

class ProgramaPermitidoController extends Controller
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
            'userGroupsAccessControl', // perform access control for CRUD operations
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
                'actions' => array('create', 'delete', 'update', 'index', 'validar', 'createAjax'),
                'groups' => array('root', 'empresa', 'demo'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Action utilizado no modal de solicitação de novos programas para inclusão.
     */
    public function actionValidar()
    {
        $mensagem = "<div>".Yii::t('smith', 'Olá Administrador').",<br><br> ".Yii::t('smith', 'O seguinte programa foi solicitado para inclusão:')." <br> ".Yii::t('smith', 'Nome').": " . $_POST['programa'] . "<br>".Yii::t('smith', 'Site do fabricante').": " . $_POST['site'] . "</div>";
        // $message = new YiiMailMessage;
        // $message->setBody($mensagem, 'text/html');
        // $message->subject = Yii::t('smith', "Novo Programa Solicitado")." - " . Yii::app()->user->name;
        // $message->addTo("lucascardoso@vivainovacao.com");
        // $message->from = "lucascardoso@vivainovacao.com";
        // Yii::app()->mail->send($message);

        // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
        SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), Yii::t('smith', 'Novo Programa Solicitado') .' - ' . Yii::app()->user->name, $mensagem);
        echo "OK";

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->title_action = Yii::t("smith", Yii::t('smith', "Adicionar Programas Permitidos"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Adicionar Programas Permitidos"));
        $model = new ProgramaPermitido;
        $fk_empresa = MetodosGerais::getEmpresaId();
        $idUser = Yii::app()->user->id;
        $serial = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $serial->serial_empresa;

        if ((isset($_POST['programas'])) && isset($_POST['ProgramaPermitido'])) {
            $start = MetodosGerais::inicioContagem();

            $lista_programas = $_POST['programas'];
            $fk_empresa = MetodosGerais::getEmpresaId();
            $ultimo_inserir = count($lista_programas);
            $flag = 0;
            foreach ($lista_programas as $programa) {
                $model = new ProgramaPermitido;
                $model->fk_empresa = $fk_empresa;
                $model->serial_empresa = $serial;
                $model->fk_equipe = $_POST['ProgramaPermitido']['fk_equipe'];
                $model->nome = $programa;
                $model->save();
                $flag++;
                if ($flag == $ultimo_inserir) {
                    LogAcesso::model()->saveAcesso('Configurações', 'Adicionar Programas Permitidos', 'Adicionar Programas Permitidos', MetodosGerais::tempoResposta($start));
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Os Programas foram inseridos com sucesso.'));
                    $this->redirect(array('index'));
                }
            }
        }
        if (isset($_POST['enviarEmail'])) {
            $mensagem = "<div>".Yii::t('smith', 'Olá Administrador').",<br><br> ".Yii::t('smith', 'O seguinte programa foi solicitado para inclusão:')." <br> ".Yii::t('smith', 'Nome').": " . $_POST['programa'] . "<br>".Yii::t('smith', 'Site do fabricante').": " . $_POST['site'] . "</div>";
            // $message = new YiiMailMessage;
            // $message->setBody($mensagem, 'text/html');
            // $message->subject = Yii::t('smith', "Novo Programa Solicitado")." - " . Yii::app()->user->name;
            // $message->addTo("lucascardoso@vivainovacao.com");
            // $message->from = "lucascardoso@vivainovacao.com";
            // Yii::app()->mail->send($message);

            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), Yii::t('smith', 'Novo Programa Solicitado') .' - ' . Yii::app()->user->name, $mensagem);
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Solicitação enviada'));
            $this->refresh();
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
        $this->title_action = Yii::t("smith", Yii::t('smith', "Atualizar Programa Permitido"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Adicionar Programa Permitidos"));
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ProgramaPermitido'])) {
            $start = MetodosGerais::inicioContagem();
            $model->attributes = $_POST['ProgramaPermitido'];

            if ($model->save()) {
                LogAcesso::model()->saveAcesso('Configurações', 'Atualizar Programas Permitidos', 'Atualizar Programas Permitidos', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Programa atualizado com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Programa não pôde ser atualizado.'));
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
            $start = MetodosGerais::inicioContagem();
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            LogAcesso::model()->saveAcesso('Configurações', 'Deletar Programas Permitido', 'Deletar Programas Permitido', MetodosGerais::tempoResposta($start));
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
        $this->title_action = Yii::t("smith", "Lista de programas permitidos");
        $this->pageTitle = Yii::t("smith", "Lista de programas permitidos");
        $model = new ProgramaPermitido('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProgramaPermitido']))
            $model->attributes = $_GET['ProgramaPermitido'];
        LogAcesso::model()->saveAcesso('Configurações', 'Programas permitidos', 'Lista de programas permitidos', MetodosGerais::tempoResposta($start));
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
        $model = ProgramaPermitido::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'programa-permitido-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public function actionCreateAjax()
    {
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;
        $serial = LogAtividade::model()->getSerial();
        $flag = "Erro";
        if (isset($_POST['Programa'])) {
            $flag = "Sucesso";
            $lista_programas = $_POST['Programa']['selecionados'];
            $listaExists = ProgramaPermitido::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
            if (isset($listaExists))
                ProgramaPermitido::model()->deleteAllByAttributes(array("fk_empresa" => $fk_empresa));
            foreach ($lista_programas as $programa) {
                $model = new ProgramaPermitido;
                $model->fk_empresa = $fk_empresa;
                $model->serial_empresa = $serial;
                $model->nome = $programa['nome_programa'];
                $model->save();
                $flag = "Sucesso";
            }

        }
        if (isset($_POST['Site'])) {
            $flag = "Sucesso";
            $lista_site = $_POST['Site']['selecionados'];
            $listaExists = SitePermitido::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
            if (isset($listaExists))
                SitePermitido::model()->deleteAllByAttributes(array("fk_empresa" => $fk_empresa));
            foreach ($lista_site as $site) {
                $model = new SitePermitido;
                $model->fk_empresa = $fk_empresa;
                //$model->serial_empresa = $serial;
                $model->nome = $site['nome_site'];
                $model->save();
                $flag = "Sucesso";
            }

        }
        $modelEmpresa = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
        if ($modelEmpresa->passo_wizard <= 2) {
            $modelEmpresa->passo_wizard = 2;
            if ($modelEmpresa->save())
                $flag = "Sucesso";
        }
        echo $flag;
    }
}
