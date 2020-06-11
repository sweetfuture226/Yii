<?php
/* @var $this SitePermitidoController */
/* @var $model SitePermitido */

$this->breadcrumbs=array(
	'Sites Permitidos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Atualizar',
);


?>



<?php echo $this->renderPartial('_formUpdate', array('model'=>$model)); ?>