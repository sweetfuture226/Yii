<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Manual do usuário') => array('index'),
    Yii::t("smith", 'Mobile'),
); ?>
<?php foreach ($model as $obj) { ?>
    <span class="anchorHelp" id="subitem<?= $obj->id ?>"></span>
    <div id="titulo-<?= $obj->id ?>" class="form-group col-lg-12 helpSmith" contenteditable="true">
        <?= Yii::t("smith", $obj->titulo); ?>
    </div>
    <div id="conteudo-<?= $obj->id ?>" class="form-group col-lg-12 helpSmith" contenteditable="true">
        <?= Yii::t("smith", $obj->conteudo); ?>
    </div>
<?php } ?>

    <div class="buttons">
        <div style="float: right; ">
            <?php echo CHtml::button(Yii::t("smith", 'Salvar'), array('class' => 'btn btn-info submitForm', 'onclick' => 'salvar();')); ?>
        </div>
    </div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/manualUsuario.js', CClientScript::POS_END);
?>