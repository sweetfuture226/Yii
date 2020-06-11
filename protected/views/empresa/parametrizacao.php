<?php
$this->breadcrumbs=array(
    'Empresas'=>array('index'),
    'Parametrizar',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'empresa-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'form valid', 'enctype' => 'multipart/form-data'),
)); ?>

<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Empresas'),'nome'); ?>
        <?php echo CHtml::dropdownlist('fk_empresa','',CHtml::listData(Empresa::model()->findAll(array('order'=>'nome')), 'id', 'nome'),array("class"=>"chzn-select" ,"style"=> "width:100%;")); ?>
    </p>
</div>
<div style="clear: both"></div>
<p class="note"><?=Yii::t('wizard','Importe o arquivo csv com o nome dos colaboradores e preencha todos os campos.')?></p>
<p class="note"><?php echo CHtml::link(Yii::t("wizard", 'Clique aqui para baixar a planilha de parametrização dos colaboradores'), array('empresa/GetPlanilha'),array('id'=>'getPlanilha')); ?></p>

<div class="form-group  col-lg-4">
    <?php echo CHtml::label(Yii::t('smith', 'Arquivo CSV'),'Documento_file'); ?>
    <?php echo CHtml::hiddenField('nameFile', '', array('id'=>'file')); ?>
    <?php $this->widget('ext.EAjaxUpload.EAjaxUpload', array( 'id'=>'planilhaPrametrizacao',
        'config'=>array(
            'action' => Yii::app()->createUrl('colaborador/upload'),
            'allowedExtensions'=>array("xls"),//array("jpg","jpeg","gif","exe","mov" and etc...
            'sizeLimit'=>10*1024*1024,// maximum file size in bytes
            'minSizeLimit'=>1,// minimum file size in bytes
            'onComplete'=>"js:function(id, fileName, responseJSON){"
                . " $('#file').val(responseJSON.filename);"
                ."}",
        )
    )); ?>
</div>

<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::submitButton(Yii::t('smith','Salvar'), array('class'=>'btn btn-info submitForm')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
<!-- form -->

<script>
    $( window ).load(function() {
        console.log( "window loaded" );
        var empresa = $("#fk_empresa").val();
        var href = baseUrl + '/empresa/GetPlanilha/';
        document.getElementById("getPlanilha").setAttribute("href",href+empresa);
    });
    $("#fk_empresa").change(function(){
        var empresa = $(this).val();
        var href = baseUrl + '/empresa/GetPlanilha/';
        document.getElementById("getPlanilha").setAttribute("href",href+empresa);
    });

</script>
