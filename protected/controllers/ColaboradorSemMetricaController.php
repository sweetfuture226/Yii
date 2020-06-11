<?php

class ColaboradorSemMetricaController extends Controller
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
			array('allow', // allow admin user to perform 'index' and 'delete' actions
				'actions'=>array('index'),
				'groups'=>array('admin','empresa'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$this->title_action = Yii::t('smith',"Colaboradores com métricas não associadas");
		$this->pageTitle = Yii::t('smith',"Colaboradores com métricas não associadas");
		$model=new ColaboradorSemMetrica('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ColaboradorSemMetrica']))
			$model->attributes=$_GET['ColaboradorSemMetrica'];

        if(!empty($_POST['selectedItens'])){
            foreach($_POST['selectedItens'] as $value){
                $explode = explode("#-#",$value);
                $fkMetrica = $explode[0];
                $fkColaborador = $explode[1];
                $modelColHasMet = new ColaboradorHasMetrica();
				$modelColHasMet->fk_metrica = $fkMetrica;
				$modelColHasMet->fk_colaborador = $fkColaborador;
                $modelColHasMet->data = date('Y-m-d H:i:s');
                if($modelColHasMet->save())
                    ColaboradorSemMetrica::model()->deleteAllByAttributes(array("fk_metrica"=>$fkMetrica,"fk_colaborador"=>$fkColaborador));

            }
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Os colaboradores foram associados.'));
            $this->refresh();
        }

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ColaboradorSemMetrica::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'A página não existe.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='colaborador-sem-metrica-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
