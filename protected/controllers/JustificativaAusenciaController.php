<?php

class JustificativaAusenciaController extends Controller
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
				'actions'=>array('index','delete','create','update', 'CreateJustificativaAusencia'),
				'groups'=>array('admin','empresa'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
                $this->layout = false;
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
                $this->title_action = "Criar JustificativaAusencia";
		$model=new JustificativaAusencia;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JustificativaAusencia']))
		{
			$model->attributes=$_POST['JustificativaAusencia'];
			if($model->save()){
                            Yii::app()->user->setFlash('success','JustificativaAusencia inserido com sucesso.');
                            $this->redirect(array('index'));
                        }
                        else {
                            Yii::app()->user->setFlash('error','JustificativaAusencia não pôde ser inserido.');
                        }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

    public function actionCreateJustificativaAusencia($tipo)
    {
        $model = new JustificativaAusencia();
        $model->tipo = $tipo;
        if ($model->save()) {
            echo "success";
        } else {
            echo "<pre>";
            print_r($model->getErrors());
            echo "</pre>";
        }

    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
                $this->title_action = "Atualizar JustificativaAusencia";
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JustificativaAusencia']))
		{
			$model->attributes=$_POST['JustificativaAusencia'];
			if($model->save()){
                            Yii::app()->user->setFlash('success','JustificativaAusencia atualizado com sucesso.');
                            $this->redirect(array('index'));
                        }
                        else {
                            Yii::app()->user->setFlash('error','JustificativaAusencia não pôde ser atualizado.');
                        }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Requisição inválida. Por favor não repita esta requisição novamente.');
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
                $this->title_action = "JustificativaAusencias";
		$model=new JustificativaAusencia('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['JustificativaAusencia']))
			$model->attributes=$_GET['JustificativaAusencia'];

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
		$model=JustificativaAusencia::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='justificativa-ausencia-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
