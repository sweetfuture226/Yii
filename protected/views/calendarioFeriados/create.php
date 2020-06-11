<?php
$this->breadcrumbs=array(
    Yii::t("smith",'Calendário de feriados')=>array('index'),
    Yii::t("smith",'Criar'),
);

?>

<?php echo $this->renderPartial('_form', array('datas'=>$datas)); ?>