<?php
/* @var $this EmpresaHasParametroController */
/* @var $model EmpresaHasParametro */

$this->breadcrumbs=array(
	Yii::t("smith",'Parâmetros')=>array('index'),

);

$this->menu=array(
	array('label'=>'List Parametros', 'url'=>array('index')),
	array('label'=>'Create Parametros', 'url'=>array('create')),
	array('label'=>'View Parametros', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Parametros', 'url'=>array('admin')),
);
?>



<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
