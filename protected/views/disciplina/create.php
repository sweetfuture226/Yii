<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Disciplinas')=>array('index'),
	Yii::t("smith",'Criar'),
);

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>