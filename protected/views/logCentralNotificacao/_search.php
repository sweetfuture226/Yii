<div class="wide form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'htmlOptions' => array('class' => 'mainForm'),
    )); ?>


    <div class="rowElem noborder three_columns">
        <?php echo $form->label($model, 'id'); ?>
        <div class="formBottom">
            <?php echo $form->textField($model, 'id', array('class' => '')); ?>
        </div>
        <div class="fix"></div>
    </div>


    <div class="rowElem noborder three_columns">
        <?php echo $form->label($model, 'fk_acao'); ?>
        <div class="formBottom">
            <?php echo $form->textField($model, 'fk_acao', array('class' => '')); ?>
        </div>
        <div class="fix"></div>
    </div>


    <div class="rowElem noborder three_columns">
        <?php echo $form->label($model, 'fk_documento_sem_contrato'); ?>
        <div class="formBottom">
            <?php echo $form->textField($model, 'fk_documento_sem_contrato', array('class' => '')); ?>
        </div>
        <div class="fix"></div>
    </div>


    <div class="rowElem noborder three_columns">
        <?php echo $form->label($model, 'descricao'); ?>
        <div class="formBottom">
            <?php echo $form->textField($model, 'descricao', array('class' => '', 'size' => 60, 'maxlength' => 255)); ?>
        </div>
        <div class="fix"></div>
    </div>


    <div class="rowElem noborder three_columns">
        <?php echo $form->label($model, 'tipo'); ?>
        <div class="formBottom">
            <?php echo $form->textField($model, 'tipo', array('class' => '')); ?>
        </div>
        <div class="fix"></div>
    </div>


    <div class="rowElem noborder three_columns">
        <?php echo $form->label($model, 'fk_empresa'); ?>
        <div class="formBottom">
            <?php echo $form->textField($model, 'fk_empresa', array('class' => '')); ?>
        </div>
        <div class="fix"></div>
    </div>

    <div class="buttons">
        <div style="float: left; ">
            <?php echo CHtml::submitButton('Buscar', array('class' => 'greyishBtn submitForm')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->