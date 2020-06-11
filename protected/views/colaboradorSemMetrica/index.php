<?php
$this->breadcrumbs=array(
	Yii::t('smith','Colaborador sem métrica'),
);

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


    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('colaborador-sem-metrica-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>
<?php echo CHtml::beginForm(); ?>
<p><?= Yii::t("smith", 'Aqui é possível associar um colaborador à métrica.') ?></p><br>
<?php echo CHtml::beginForm(); ?>
<div align="left" style="margin: 0 0 18px 10px; display: block" class="row">
    <div style="float: left; ">
        <?php echo CHtml::button(Yii::t("smith", 'Associar colaborador'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'onclick' => "validaForm();")); ?>
    </div>

</div>

<div align="right" style="float: right; margin: 10px;" class="row">
    <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
                'mGridId' => 'colaborador-sem-metrica-grid', //Gridview id
                'mPageSize' => @$_GET['pageSize'],
                'mDefPageSize' => Yii::app()->params['defaultPageSize'],
                'mPageSizeOptions'=>Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
        ));
    ?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'colaborador-sem-metrica-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'pager' => array('cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css'),
    'cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css',
    'htmlOptions' => array('class' => 'grid-view rounded'),
    'afterAjaxUpdate' => 'afterAjax',
	'columns'=>array(
        array(
            'id' => 'selectedItens',
            'class' => 'CCheckBoxColumn',
            'value' => '$data->fk_metrica."#-#".$data->fk_colaborador',
            'selectableRows' => 2,
        ),
        array(
            'name'=>'fk_colaborador',
            'filter' => CHtml::listData(Colaborador::model()->findAll(array("condition" => "fk_empresa =" . MetodosGerais::getEmpresaId(), "order" => "nome ASC", "distinct" => true)), 'id', 'nomeCompleto'),
            'value' => 'Colaborador::model()->findByPk($data->fk_colaborador)->nomeCompleto'
        ),
        array(
            'name'=>'fk_metrica',
            'filter'=>CHtml::listData(Metrica::model()->findAll(array("condition"=>"fk_empresa =".MetodosGerais::getEmpresaId(),"order"=>"titulo ASC","distinct"=>true)),'id','titulo'),
            'value'=>'Metrica::model()->findByPk($data->fk_metrica)->titulo'
        ),
        array(
            'name' => 'data',
            'header'=>'Data',
            'value' => '$data->data',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'data',
                'language' => 'en',
                //'i18nScriptFile' => 'jquery.ui.datepicker-pt-BR.js',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_data',
                    'size' => '10',
                    'class' => 'date'
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus',
                    'dateFormat' => 'yy-mm-dd',
                    'yearRange' => '1940:',
                    'autoSize' => false,
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => false,
                )
            ), true),
        ),
	),
)); ?>
<?php $this->renderPartial('modalAssociarColaborador'); ?>
<?php echo CHtml::endForm(); ?>


<script>
    function validaForm() {
        var hasItem = $.fn.yiiGridView.getChecked("colaborador-sem-metrica-grid", "selectedItens");
        if (typeof hasItem !== 'undefined' && hasItem.length > 0) {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Você tem certeza que deseja associar todos os colaboradores selecionados?'); ?>";
            $('#btn_modal_confirm_metrica').click();
            document.getElementById("confirma_metrica").style.display = "block";
            document.getElementById("confirma_metrica").style.margin = "0px 0px 0px 370px";
            document.getElementById("confirma_metrica").style.position = "absolute";
        }
        else {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Por favor, selecione ao menos um item'); ?>";
            $('#btn_modal_confirm_metrica').click();
            document.getElementById("confirma_metrica").style.display = "none";
        }
    }
</script>
