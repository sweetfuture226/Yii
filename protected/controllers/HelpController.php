<?php

class HelpController extends Controller
{

    public $title_action = "";


    public function filters()
    {
        return array(
            'userGroupsAccessControl'
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
            array('allow', // allow all users to perform 'view' action
                'actions' => array('index', 'sobre', 'VisaoGeral', 'preparacao', 'instalacao', 'conhecendo',
                    'moduloProdutividade', 'moduloProgramasSites', 'moduloMetricas', 'moduloContratos', 'moduloConfiguracoes',
                    'mobile', 'faleConosco', 'duvidas'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('allow',
                'actions' => array('saveManual'),
                'groups' => array('root'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Manual do usuário");
        $this->render("index");
    }

    public function actionSobre()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Sobre");
        if (!empty($_POST)) {
            $model = ManualUsuario::model()->findByPk($_POST['id']);
            $model->attributes = $_POST;
            $model->save();
        } else {
            $model = ManualUsuario::model()->findByPk(1);
            (Yii::app()->user->groupName == 'root') ? $this->render("sobre_editable", array('model' => $model)) :
                $this->render("sobre", array('model' => $model));
        }

    }

    public function actionVisaoGeral()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Visão geral");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '2%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("visaoGeral_editable", array('model' => $model)) :
            $this->render("visaoGeral", array('model' => $model));


    }

    public function actionPreparacao()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Preparando instituição");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '3%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("preparacao_editable", array('model' => $model)) :
            $this->render("preparacao", array('model' => $model));

    }

    public function actionInstalacao()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Instalação");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '4%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("instalacao_editable", array('model' => $model)) :
            $this->render("instalacao", array('model' => $model));

    }

    public function actionConhecendo()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Conhecendo sistema");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '5%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("conhecendo_editable", array('model' => $model)) :
            $this->render("conhecendo", array('model' => $model));

    }

    public function actionModuloProdutividade()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Módulo produtividade");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '6%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("moduloProdutividade_editable", array('model' => $model)) :
            $this->render("moduloProdutividade", array('model' => $model));

    }

    public function actionModuloProgramasSites()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Módulo programas e sites");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '7%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("moduloProgramasSites_editable", array('model' => $model)) :
            $this->render("moduloProgramasSites", array('model' => $model));

    }

    public function actionModuloMetricas()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Módulo métricas");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '8%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("moduloMetricas_editable", array('model' => $model)) :
            $this->render("moduloMetricas", array('model' => $model));
    }

    public function actionModuloContratos()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Módulo contratos");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '9%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("moduloContratos_editable", array('model' => $model)) :
            $this->render("moduloContratos", array('model' => $model));
    }

    public function actionModuloConfiguracoes()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Módulo configurações");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '10%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("moduloConfiguracoes_editable", array('model' => $model)) :
            $this->render("moduloConfiguracoes", array('model' => $model));
    }

    public function actionMobile()
    {
        $this->layout = "Help";
        $this->pageTitle = "Mobile";
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '11%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("mobile_editable", array('model' => $model)) :
            $this->render("mobile", array('model' => $model));
    }

    public function actionDuvidas()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Dúvidas");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '12%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("duvidas_editable", array('model' => $model)) :
            $this->render("duvidas", array('model' => $model));
    }

    public function actionFaleConosco()
    {
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Fale conosco");
        $model = ManualUsuario::model()->findAll(array('condition' => "id like '13%'"));
        (Yii::app()->user->groupName == 'root') ? $this->render("faleconosco_editable", array('model' => $model)) :
            $this->render("faleconosco", array('model' => $model));
    }

    public function actionSaveManual()
    {
        $arrayForm = array();
        if (!empty($_POST)) {
            foreach ($_POST['form'] as $value) {
                $ref = explode('-', $value['id']);
                $arrayForm[$ref[1]][] = $value['html'];
            }
            foreach ($arrayForm as $key => $value) {
                $model = ManualUsuario::model()->find(array('condition' => "id like '$key'"));
                $model->titulo = $value[0];
                $model->conteudo = (isset($value[1])) ? $value[1] : '';
                $model->save();
            }
        }
    }

}