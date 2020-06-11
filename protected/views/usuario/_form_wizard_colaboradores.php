<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pro-pessoa-form',
	'enableAjaxValidation'=>false,
        'clientOptions'=>array('validateOnSubmit'=>true,),
        'htmlOptions'=>array('class'=>'form valid', 'enctype' => 'multipart/form-data'),
)); ?>
	
<p><?=Yii::t('wizard','Depois de criadas as equipes agora é hora de completar os perfis dos colaboradores pré-cadastrados.
   Escolha uma das seguintes opções.') ?></p>
<div class="form-group  col-lg-4">
    <?php echo CHtml::radioButtonList("documento", 'selecionado',
        array('ism'=>Yii::t("smith", 'Inserção manual'),
            'ise'=>Yii::t("smith", 'Importação planilha' )),
        array('class'=>'fire-toggle')); ?>
</div>

<div class="importManual" style="display: none;">
    <fieldset>
        <legend><?=Yii::t('wizard','Inserção manual de colaboradores')?></legend>
        <p class="note"><?=Yii::t('wizard','Clique sobre do nome de cada usuário para preencher os seus dados.')?></p>
        <br/>
        <div class="panel-group m-bot20" id="accordion">
            <div style="clear: both"></div>
        </div>
    </fieldset>
</div>

<div class="importCSV" style="display: none;">
    <fieldset>
        <legend><?=Yii::t('wizard','Importar Colaboradores')?></legend>
        <?php echo $this->renderPartial("importCSV"); ?>
    </fieldset>
</div>
                          
<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
$(function(){
    $('.fire-toggle').change(function(){
        var current_value = $('input:radio:checked').val() ;
        if (current_value == "ise"){
            $('.importCSV').show();
            $('.importManual').hide();
        }
        if (current_value == "ism"){
            $('.importCSV').hide();
            $('.importManual').show();
        }
    });
});
</script>