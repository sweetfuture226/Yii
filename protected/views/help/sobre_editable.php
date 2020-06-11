<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Manual do usuário') => array('index'),
    Yii::t("smith", 'Sobre'),
); ?>

<div id="item1" class="form-group col-lg-12 helpSmith" contenteditable="true">
    <?= Yii::t("smith", $model->titulo); ?>
</div>
<div id="conteudo1" class="form-group col-lg-12 helpSmith" contenteditable="true">
    <?= Yii::t("smith", $model->conteudo); ?>
</div>


<div class="buttons">
    <div style="float: right; ">
        <?php echo CHtml::button(Yii::t("smith", 'Salvar'), array('class' => 'btn btn-info submitForm', 'onclick' => 'salvar();')); ?>
    </div>
</div>

<script>
    function salvar() {
        var titulo = $('#item1').html();
        var conteudo = $('#conteudo1').html();
        $.ajax({
            'type': 'POST',
            'data': {'id': 1, 'titulo': titulo, 'conteudo': conteudo},
            'url': baseUrl + '/help/Sobre',
            success: function () {
                document.getElementById('message').innerHTML = "Edição realizada com sucesso.";
                $('#btn_modal_open').click();
            }
        });
    }
</script>