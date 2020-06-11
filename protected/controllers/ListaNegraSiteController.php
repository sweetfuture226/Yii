<?php

class ListaNegraSiteController extends Controller
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('index'),
				'groups' => array('coordenador', 'empresa', 'root', 'demo'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$start = MetodosGerais::inicioContagem();
		$this->title_action = Yii::t("smith", "Sites não permitidos");
		$this->pageTitle = Yii::t("smith", "Sites não permitidos");
		$model = new ListaNegraSite('search');
        $model->unsetAttributes();  // clear any default values
		if (isset($_GET['ListaNegraSite']))
			$model->attributes = $_GET['ListaNegraSite'];
        if(!empty($_POST['selectedItens'])){
            foreach($_POST['selectedItens'] as $value){
                $infoSite = explode("#-#",$value);
                $id = $infoSite[0];
                $site = $infoSite[1];
                $modelSite = new SitePermitido();
                $modelSite->nome = $site;
				$modelSite->fk_empresa = MetodosGerais::getEmpresaId();
                if($modelSite->save())
					ListaNegraSite::model()->findByPk($id)->delete();

            }
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Os sites foram validados.'));
            $this->refresh();
        }
		LogAcesso::model()->saveAcesso('Programas e Sites', 'Relatório de sites blacklist', 'Sites Blacklist', MetodosGerais::tempoResposta($start));
        $this->render('index',array(
            'model'=>$model,

        ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ListaNegraSite the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = ListaNegraSite::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ListaNegraSite $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sites-blacklist-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
