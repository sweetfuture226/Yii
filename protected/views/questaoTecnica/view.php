<?php
$this->breadcrumbs=array(
	'Questao Tecnicas'=>array('index'),
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tipo',
	),
)); ?>
