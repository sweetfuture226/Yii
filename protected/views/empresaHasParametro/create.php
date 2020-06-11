<?php
/* @var $this EmpresaHasParametroController */
/* @var $model EmpresaHasParametro */

$this->breadcrumbs=array(
	Yii::t("smith",'Parâmetros')=>array('index'),

);


?>

<?php
    echo $this->renderPartial('_form', array('model'=>$model));
?>
