<?php
$this->breadcrumbs=array(
    Yii::t("smith",'Manual do usuário')=>array('index'),
    Yii::t("smith",'Módulo de programas e sites'),
); ?>
<script>
    $("#moduloProgramasSites").children('a').addClass('active');
    $("#moduloProgramasSites").children('ul').css({"overflow": "hidden" , "display": "block"});
</script>
<?php foreach ($model as $obj) { ?>
    <span class="anchorHelp" id="subitem<?= $obj->id ?>"></span>
    <div id="titulo-<?= $obj->id ?>" class="form-group col-lg-12 helpSmith">
        <?= Yii::t("smith", $obj->titulo); ?>
    </div>
    <div id="conteudo-<?= $obj->id ?>" class="form-group col-lg-12 helpSmith">
        <?= Yii::t("smith", $obj->conteudo); ?>
    </div>
<?php } ?>