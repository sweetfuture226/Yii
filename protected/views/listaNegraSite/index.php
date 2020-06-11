<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Sites não permitidos'),
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
        $.fn.yiiGridView.update('site-blacklist-grid', {
            data: $(this).serialize()
        });
        return false;
    });");
?>
<p><?= Yii::t("smith", 'Aqui é possível adicionar um site à lista de autorizados.') ?></p><br>
    <?php echo CHtml::beginForm(); ?>
    <div align="left" style="margin: 0 0 18px 10px; display: block" class="row">
        <div style="float: left; ">
            <?php echo CHtml::button(Yii::t("smith", 'Autorizar site(s)'), array('class' => 'btn btn-info submitForm', 'id' => 'btn_enviar', 'onclick' => "validaForm();")); ?>
        </div>

    </div>

<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'site-blacklist-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'site-blacklist-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'afterAjaxUpdate' => 'afterAjax',
        'columns' => array(
            array(
                'id' => 'selectedItens',
                'class' => 'CCheckBoxColumn',
                'value' => '$data->id."#-#".$data->site',
                'selectableRows' => 2,
            ),
            'programa',
            'site',
            array(
                'name' => 'tempo_absoluto',
                'header'=> Yii::t('smith', 'Tempo Absoluto (horas)'),
                'filter' => false,
            ),

            array(
                'name' => 'porcentagem',
                'filter' => false,
                'value' => '$data->porcentagem ."%"',
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
                    <h4 class="modal-title"><?php echo Yii::t('smith', 'Confirmar autorização de sites'); ?></h4>
                </div>
                <div class="modal-body">

                    <p id="mensagem"></p>

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
        var hasItem = $.fn.yiiGridView.getChecked("site-blacklist-grid", "selectedItens");
        if (typeof hasItem !== 'undefined' && hasItem.length > 0) {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Autorizar todos os itens selecionados como permitidos?'); ?>";
            $('#btn_modal_confirm_programa').click();
            document.getElementById("confirma_programa").style.display = "block";
            document.getElementById("confirma_programa").style.margin = "0px 0px 0px 370px";
            document.getElementById("confirma_programa").style.position = "absolute";
        }
        else {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Por favor, selecione ao menos um item'); ?>";
            $('#btn_modal_confirm_programa').click();
            document.getElementById("confirma_programa").style.display = "none";
        }
    }
</script>