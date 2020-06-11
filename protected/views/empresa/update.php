<?php
/* @var $this EmpresaController */
/* @var $model Empresa
 * @var $modelRevenda Revenda
 * @var $modelContato Contato
 * @var $modelRevendaHasPoc RevendaHasPoc
 */
$this->breadcrumbs=array(
	'Empresas'=>array('index'),
	
	'Atualizar',
);


?>


<?php $this->renderPartial('_form', array(
	'modelRevenda' => $modelRevenda,
	'modelRevendaHasPoc' => $modelRevendaHasPoc,
	'modelContato' => $modelContato,
	'model' => $model
)); ?>