<?php
/* @var $this SitePermitidoController */
/* @var $model SitePermitido */

$this->breadcrumbs=array(
	Yii::t("smith", 'Métrica') => array('index'),
	
	'Atualizar',
);


?>


<?php echo $this->renderPartial('_form', array('model'=>$model,'modelLogs' => $modelLogs,)); ?>