<?php
$this->breadcrumbs=array(
    'Empresas'=>array('index'),
    'Povoar tabelas',
);


?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'pro-pessoa-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'form valid'),
)); ?>

<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Empresas'),'nome'); ?>
        <?php echo CHtml::dropdownlist('fk_empresa','',CHtml::listData(Empresa::model()->findAll(array('order'=>'nome')), 'id', 'nome'),array("class"=>"chzn-select", 'empty'=>Yii::t("smith",'TODAS') ,"style"=> "width:100%;")); ?>
    </p>
</div>



<div style="clear: both"></div>
<?php $dataIni = '01/' . date('m/Y'); ?>
<?php $dataEnd = date('d/m/Y'); ?>
<div class="form-group  col-lg-4">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Inicial') ?></label>
    <?php echo CHtml::textField('date_from', $dataIni, array('class' => 'date form-control validate[required]')); ?>

</div>

<div class="form-group  col-lg-4">
    <label for="Pagamento_data"><?php echo Yii::t("smith", 'Data Final') ?></label>
    <?php echo CHtml::textField('date_to', $dataEnd, array('class' => 'date form-control validate[required]')); ?>

</div>

<div class="form-group  col-lg-4">
    <p>
        <?php   echo CHtml::label(Yii::t('smith', 'Tabela'),'tipo_empresa');
        echo CHtml::dropdownlist('tabela', '', array('produtividade' => "Produtividade", "programa" => "Programa", "colaborador" => "Colaborador", "projeto" => "Projeto", "programa_blacklist" => "Programas Blacklist", "lista_negra_site" => "Sites Blacklist", "documentos_contrato" => "Documentos sem contrato"), array("class" => "chzn-select", 'empty' => Yii::t("smith", 'TODAS'), "style" => "width:100%;", 'multiple' => 'multiple'));
        ?>
    </p>
</div>




<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
        <?php echo CHtml::submitButton(Yii::t('smith','Atualizar'), array('class'=>'btn btn-info submitForm')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
<!-- form -->
