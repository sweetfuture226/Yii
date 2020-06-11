<?php
$this->breadcrumbs=array(
    Yii::t("smith",'Manual do usuário')=>array('index'),
    Yii::t("smith",'Sobre'),
); ?>
<div id="item1" class="form-group col-lg-12 helpSmith">
    <?= Yii::t("smith", $model->titulo); ?>
    <?= Yii::t("smith", $model->conteudo); ?>
</div>