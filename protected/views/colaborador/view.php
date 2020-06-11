<?php
$this->breadcrumbs=array(
	'Pro Pessoas'=>array('index'),
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'nome',
		'email',
		array(
			'name'=>'salario',
			'value'=>'R$'.MetodosGerais::float2real($model->salario),
		),
		'horas_semana',
		'ad',
	),
)); ?>
