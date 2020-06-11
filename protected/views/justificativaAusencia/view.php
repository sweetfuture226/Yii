<?php
$this->breadcrumbs=array(
	'Justificativa Ausencias'=>array('index'),
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tipo',
	),
)); ?>
