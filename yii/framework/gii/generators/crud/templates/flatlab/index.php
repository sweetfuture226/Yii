<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label',
);\n\n";

echo 'Yii::app()->clientScript->registerScript(\'button_create\', \'
    $(".panel-heading").prepend(\\\'<button class="btn btn-success" style="float: right;" onclick= location.href="\'.CHtml::normalizeUrl(array("'.$this->modelClass.'/create")).\'"><i class="icon-plus-sign"></i> Novo</button>  \\\');
\');'  
        

        
?>

Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){
        $(".odd td:not(.button-column), .even td:not(.button-column)").click(function(){
            url = $(this).parent().children().children(".view").attr("href");
            Boxy.load(url, {title:"Dados"});
        });
    }

');

Yii::app()->clientScript->registerScript('afterAjax', '
    function afterAjax(id, data) {

        boxy_view();
    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>


<div class="dataTables_length">
    <label>
    <?php echo "<?php\n"; ?>
        $this->widget('application.extensions.PageSize.PageSize', array(
                'mGridId' => '<?php echo $this->class2id($this->modelClass); ?>-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions'=>Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        )); 
    ?>
    </label>
</div>

<?php echo "<?php"; ?> $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
                        'header' => 'Ações',
                        'class' => 'CButtonColumn',
                        'viewButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/view.png',
                        'updateButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/edit.png',
                        'deleteButtonImageUrl' => Yii::app()->theme->baseUrl . '/images/icons/trash.png',
		),
	),
)); ?>
