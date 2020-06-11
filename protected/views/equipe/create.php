<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Equipes')=>array('index'),
	Yii::t("smith",'Criar'),
);

?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'model_pessoa' => $model_pessoa, 'historico' =>
    $historico)); ?>