<?php
$this->breadcrumbs=array(
	Yii::t('smith','Colaborador')=>array('index'),
	Yii::t('smith','Criar'),
);

?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'modelEquipe'=>$modelEquipe,'modelFerias'=>$modelFerias)); ?>