<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Documentos sem contrato associado'),
);


Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){

        $(".odd td:not(.button-column), .even td:not(.button-column)").click(function(){

            url = $(this).parent().children().children(".view").attr("href");
            Boxy.load(url, {title:"Dados"});
        });
    }');

Yii::app()->clientScript->registerScript('afterAjax', "
    function afterAjax(id, data) {


    }");

Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        //use the same parameters that you had set in your widget else the datepicker will be refreshed by default
        $('#datepicker_for_data').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['en'],{'dateFormat':'yy/mm/dd'}));
    }");

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('documentos-sem-contrato-grid', {
            data: $(this).serialize()
        });
        return false;
    });");
?>
<p><?= Yii::t("smith", 'Aqui é possível associar um ou vários documento a algum contrato existente.') ?></p><br>


    <?php echo CHtml::beginForm(); ?>
    <div align="left" style="margin: 0 0 18px 10px; display: block" class="row">
        <div style="float: left; ">
            <?php echo CHtml::button(Yii::t("smith", 'Associar documento(s)'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'onclick' => "validaForm();")); ?>
        </div>

    </div>
<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'documentos-sem-contrato-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'documentos-sem-contrato-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'afterAjaxUpdate' => 'afterAjax',
        'columns' => array(
            array(
                'id' => 'selectedItens',
                'class' => 'CCheckBoxColumn',
                'value' => '$data->id',
                'selectableRows' => 2,
            ),
            'documento',
            'programa',
            array(
                'name' => 'fk_colaborador',
                'filter' => CHtml::listData(Colaborador::model()->findAllByAttributes(array("fk_empresa" => $fkEmpresa), array("order" => "ad ASC", "distinct" => true)), 'id', 'nomeCompleto'),
                'value' => 'Colaborador::model()->findByPk($data->fk_colaborador)->nomeCompleto'
            ),

            array(
                'name' => 'data',
                'value' => 'date("d/m/Y ",strtotime($data->data))',
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

            array(
                'name' => 'duracao',
                 'value'=>'MetodosGerais::formataTempo($data->duracao)',
                'filter' => false,
            ),
        ),
    ));
    ?>


    <a id="btn_modal_confirm_programa" class="invisible" data-toggle="modal" href="#modal_confirm_programa">Dialog</a>
    <div class="modal fade" id="modal_confirm_programa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo Yii::t('smith', 'Confirmar vinculação de documentos'); ?></h4>
                </div>
                <div class="modal-body">

                    <p id="mensagem"></p>
                    <div  id="dropdownContrato">
                        <p>
                            <?php
                            $listData = Contrato::model()->findAll(array('order' => 'nome', 'condition' => $condicao));
                            $data = array();
                            foreach ($listData as $model)
                                $data[$model->id] = $model->nome . ' - ' . $model->codigo;
                            echo CHtml::label(Yii::t("smith", 'Contrato'),'Obra');
                            echo CHtml::dropDownList('Obra',null,$data,array("class"=>"chzn-select",'empty'=>Yii::t("smith", 'Selecione um contrato'), "style"=> "width:100%;"));
                            ?>
                        </p>
                    </div>

                </div>
                <div class="modal-footer">
                    <div id="confirma_programa"><button id="btn_modal_confirm_programa"class="btn btn-success" type="submit"><?php echo Yii::t('smith', 'Confirmar'); ?></button></div>
                    <button id ="btn_fechar_modal_confirm_programa" data-dismiss="modal" class="btn btn-default" type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>

                    <input type='hidden' id='id_contrato' value="">
                </div>
            </div>
        </div>
    </div>
<?php echo CHtml::endForm(); ?>
</div>


<script>
    function validaForm() {
        var hasItem = $.fn.yiiGridView.getChecked("documentos-sem-contrato-grid", "selectedItens");
        if (typeof hasItem !== 'undefined' && hasItem.length > 0) {
            $("#dropdownContrato").show();
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Selecione um contrato o qual deseja-se vincular a produtividade dos documentos selecionados'); ?>";
            $('#btn_modal_confirm_programa').click();
            document.getElementById("confirma_programa").style.display = "block";
            document.getElementById("confirma_programa").style.margin = "0px 0px 0px 370px";
            document.getElementById("confirma_programa").style.position = "absolute";
        }
        else {
            $("#dropdownContrato").hide();
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Por favor, selecione ao menos um item'); ?>";
            $('#btn_modal_confirm_programa').click();
            document.getElementById("confirma_programa").style.display = "none";
        }
    }
</script>
