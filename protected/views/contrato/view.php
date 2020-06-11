<?php
$this->breadcrumbs=array(
	'Pro Obras'=>array('index'),
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'nome',
		'codigo',
	),
)); ?>
