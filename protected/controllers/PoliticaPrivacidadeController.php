<?php

class PoliticaPrivacidadeController extends Controller
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


    public function actionIndex(){
        $this->layout = "Help";
        $this->pageTitle = Yii::t('smith', "Políticas de privacidade");
        $this->render("index");
    }

}