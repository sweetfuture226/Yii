<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Equipes')=>array('index'),
	Yii::t("smith",'Atualizar'),
);
Yii::app()->clientScript->registerScript('button_create', '
    $("div.block-header").prepend(\'<div class="button_new" style="float: right;margin: 6px 12px 0 0;"><a href="'.CHtml::normalizeUrl(array("Equipe/create")).'"><img src="'.Yii::app()->theme->baseUrl.'/img/icons/create.png" alt="Criar" title="Criar" class="" style="cursor: pointer;" /></a></div>\');
');?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'model_pessoa' => $model_pessoa, 'historico' => $historico)); ?>
