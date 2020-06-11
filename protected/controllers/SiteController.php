<?php

class SiteController extends Controller {

    public $title_action = "";

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('/userGroups'));
        } else {
            switch (Yii::app()->user->groupName) {
                case 'user':
                    $this->redirect(array('/userGroups'));
                    break;
                case 'coordenador':
                    $this->redirect(Yii::app()->baseUrl . '/contrato/');
                    break;
                case 'empresa':
                    $this->redirect(Yii::app()->baseUrl . '/dashboard/');
                    break;
                case 'demo':
                    $this->redirect(Yii::app()->baseUrl . '/dashboard/');
                    break;
                case 'root':
                    $this->redirect(Yii::app()->baseUrl . '/logAcesso/');
                    break;
                case 'tradutor':
                    $this->redirect(Yii::app()->baseUrl . '/painelControle/TraducaoColaborativa');
                    break;
            }
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('/userGroups'));
        } else {
            if ($error = Yii::app()->errorHandler->error) {
                Logger::saveError($error);
                if (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else {
                    if ($error['code'] != '404' && $error['code'] != '403' && $error['code'] != '500')
                        $this->layout = "error";
                    $this->render('error', $error);
                }
            }
        }
    }

    public function actionMaintenance() {
        $this->layout = "error";
        $this->render('error', array('code'=>'0'));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
