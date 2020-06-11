<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="wide form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
        'htmlOptions'=>array('class'=>'mainForm'),
)); ?>\n"; ?>

<?php foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
?>
    
        <div class="rowElem noborder three_columns">
            <?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
            <div class="formBottom">
                <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
            </div>
            <div class="fix"></div>            
        </div>

<?php endforeach; ?>
        <div class="buttons">    
            <div style="float: left; ">
               <?php echo "<?php echo CHtml::submitButton('Buscar', array('class'=>'greyishBtn submitForm')); ?>\n"; ?>
            </div>
        </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->