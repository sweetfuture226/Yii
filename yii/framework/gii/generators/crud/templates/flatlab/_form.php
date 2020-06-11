<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>\n"; ?>

	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
<?php $class = 'form-control'; ?>
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
        <div class="form-group  col-lg-4">
	    <p>
            <?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
            <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column,$class)."; ?>\n"; ?>
	    </p>
        </div>

<?php
}
?>
        <div class="buttons">    
            <div style="float: right; ">
               <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Salvar' : 'Atualizar', array('class'=>'btn btn-info submitForm')); ?>\n"; ?>
            </div>
        </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>


