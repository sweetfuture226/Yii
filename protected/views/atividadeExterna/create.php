<?php
$this->breadcrumbs=array(
	Yii::t('smith','Atividades Externas')=>array('index'),
	Yii::t('smith','Nova'),
);

?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'condicao'=>$condicao,'condicaoCol'=>$condicaoCol)); ?>
