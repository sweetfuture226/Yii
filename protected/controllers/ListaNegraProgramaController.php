<?php

class ListaNegraProgramaController extends Controller
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
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'view' action
                'actions' => array('index'),
                'groups' => array('coordenador', 'empresa', 'root','demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Autorizar programas marcados como permitidos na grid de listagem
     * dos programas detectados como não autorizados.
     */
    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", "Programas não permitidos");
        $this->pageTitle = Yii::t("smith", "Programas não permitidos");
        $model = new ListaNegraPrograma('searchBlackList');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ListaNegraPrograma']))
            $model->attributes = $_GET['ListaNegraPrograma'];
        $idUser = Yii::app()->user->id;
        $usuario = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $usuario->serial_empresa;
        $idEmpresa = $usuario->fk_empresa;

        if (!empty($_POST['selectedItens'])) {
            foreach ($_POST['selectedItens'] as $programa) {
                $modelPrograma = new ProgramaPermitido;
                $modelPrograma->nome = $programa;
                $modelPrograma->fk_empresa = $idEmpresa;
                $modelPrograma->serial_empresa = $serial;
                $modelPrograma->fk_equipe = NULL;
                $modelPrograma->save();
                ListaNegraPrograma::model()->deleteByNome($programa, $idEmpresa);
            }
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Programas validados.'));
            $this->refresh();
        }
        LogAcesso::model()->saveAcesso('Programas e Sites', 'Relatório de programas blacklist', 'Programas blacklist', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
            'serial' => $serial,
            'fk_empresa' => $idEmpresa,
        ));
    }
}
