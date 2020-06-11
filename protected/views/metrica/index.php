<a id="btn_modal_detalhar" class="invisible" data-toggle="modal" href="#modal_detalhar">Dialog</a>
<form class="form valid" target="_blank" id="metrica-form"
      action="<?= Yii::app()->request->baseUrl ?>/Metrica/relatorioMetrica" method="post">
    <div class="modal fade" id="modal_detalhar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Pesquisar</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group  col-lg-6">
                        <p>
                            <?php echo CHtml::label(Yii::t('smith', 'Selecione uma Opção'), 'opcao'); ?>
                            <?php
                            echo CHtml::dropDownList(
                                'opcao', 'opcao', array('equipe' => 'Equipes', 'colaborador' => 'Colaboradores'), array('class' => 'chzn-select', 'prompt' => 'Selecione ')
                            );
                            ?>
                        </p>
                    </div>


                    <div class="form-group  col-lg-6" id="selec" style="display: none">
                        <p>
                            <?php echo CHtml::label(Yii::t('smith', 'Escolha um(a)'), "colaborador_id"); ?>
                            <?php echo CHtml::dropDownList('selecionado', '', array(), array('class' => 'chzn-select', 'id' => 'selecionado')); ?>
                        </p>
                    </div>

                    <div style="clear: both"></div>
                    <?php $dataIni = '01/' . date('m/Y'); ?>
                    <?php $dataEnd = date('d/m/Y'); ?>
                    <div class="form-group  col-lg-6">
                        <label for="date_from"><?php echo Yii::t("smith", 'Data Inicial') ?></label>

                        <p><?php echo CHtml::textField('date_from', $dataIni, array('class' => 'date form-control ')); ?></p>

                    </div>

                    <div class="form-group  col-lg-6">
                        <label for="date_to"><?php echo Yii::t("smith", 'Data Final') ?></label>

                        <p><?php echo CHtml::textField('date_to', $dataEnd, array('class' => 'date form-control ')); ?></p>

                    </div>

                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <button id="btn_modal_confirm_programa" class="btn btn-success"
                            type="submit"><?= Yii::t('smith', 'Confirmar'); ?></button>
                    <button id="btn_fechar_modal_confirm_programa" data-dismiss="modal" class="btn btn-default"
                            type="button"><?= Yii::t('smith', 'Fechar'); ?></button>

                    <input type='hidden' id='id_metrica' name='id_metrica' value="">
                </div>
            </div>
        </div>
    </div>
</form>
<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Métricas'),
);

Yii::app()->clientScript->registerScript('button_create', '
    $(".title_action").prepend(\'<button onclick= location.href="' . CHtml::normalizeUrl(array("Metrica/create")) . '" class="btn btn-success" style="float: right;"><i class="icon-plus-sign"></i> ' . Yii::t("smith", 'Adicionar') . ' </button>\');
');
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
            $.fn.yiiGridView.update('pro-metrica-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>
<?php echo CHtml::beginForm('favorito', 'post'); ?>

<div class="dataTables_length">
    <label>
    <?php
    $this->widget('application.extensions.PageSize.PageSize', array(
        'mGridId' => 'pro-metrica-grid', //Gridview id
        'mPageSize' => @$_GET['pageSize'],
        'mDefPageSize' => Yii::app()->params['defaultPageSize'],
        'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
    ));
    ?>
    </label>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'pro-metrica-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        array(
            'id' => 'selectedItens',
            'class' => 'CCheckBoxColumn',
            'header' => 'html',
            'headerTemplate' => '<label>Favoritos<span></span></label>',
            'value' => '$data->id',
            'selectableRows' => 2,
            'checked' => '$data->favorito'
        ),
        'titulo',
        array(
            'name' => 'atuacao',
            'filter' => CHtml::listData(Metrica::model()->findAllByAttributes(array("fk_empresa" => MetodosGerais::getEmpresaId()), array('order' => 'atuacao ASC')), 'atuacao', 'atuacao'),
        ),
        array(
            'name' => 'meta',
            'filter' => false,
        ),
        array(
            'name' => 'min_e',
            'filter' => false,
        ),
        array(
            'name' => 'max_e',
            'filter' => false,
        ),
        array(
            'name' => 'min_t',
            'filter' => false,
        ),
        array(
            'name' => 'max_t',
            'filter' => false,
        ),
        array(
            'header' => Yii::t("smith", 'Ações'),
            'class'=>'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width:20%;'),
            'buttons' => array(
                'update' => array(
                    'label' => 'Editar',
                    'options' => array('class' => 'btn btn-orange btn-margin-grid'),
                ),
                'delete' => array(
                    'label' => 'Excluir',
                    'click' => 'js:function(evt){
                                        evt.preventDefault();
                                        /*Your custom JS goes here :) */
                                        }',
                    'options' => array('class' => 'btn btn-danger btn-margin-grid deletar'),
                    'url' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
                ),
                'detalhe' => array(
                    'label' => 'Detalhar', // text label of the button
                    'options' => array('class' => 'btn btn-info btn-margin-grid detalhe', 'target' => '_blank'),
                    'url' => 'Yii::app()->createUrl("metrica/detalheMetrica/$data->id")',
                    'icon' => 'fa fa-info-circle'
                ),
                'relatorio' => array(
                    'label' => 'Relatório', // text label of the button
                    'options' => array('class' => 'btn btn-primary btn-margin-grid relatorio'),
                    'icon' => 'fa fa-file-pdf-o',
                    'url' => '$data->id',
                )
            ),
            "template" => '{detalhe}{relatorio}{update}{delete}',
        ),
    ),
));
?>
<?php echo CHtml::endForm(); ?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/deleteModel.js', CClientScript::POS_END);
?>
<script>
    jQuery('#pro-metrica-grid a.relatorio').live('click', function () {
        $('#id_metrica').val($(this).attr('href'));
        $('#btn_modal_detalhar').click();
        $('#opcao').val('equipe');
        $("#opcao").trigger("change");
        getColaborador('equipe');
        return false;
    });
</script>

<script>
    $("#opcao").change(function () {
        getColaborador($('#opcao').val());
    });

    $(document).on('click', 'table .checkbox-column input', function (e) {
        var sum = 0;
        var selectedItens = [];
        $(this).closest('tbody').find('input').each(function (k, v) {
            if ($(v).prop('checked')) {
                sum++;
                selectedItens[k] = $(this).val();
            }
        });

        if (sum > 4) {
            $('#modal_default .modal-body p').html("<?php echo Yii::t('smith', 'Somente 4 favoritos podem ser selecionados simultaneamente'); ?>");
            $('#modal_default').modal('show');
            e.preventDefault();
        } else {
            $.ajax({
                url: baseUrl+"/metrica/favorito",
                type: 'POST',
                data: {selectedItens: selectedItens}
            }).done(function (res) {
                // ALGUMA RESPOSTA;
            });
        }
    });
</script>

<script>
    function validaForm() {
        var hasItem = $.fn.yiiGridView.getChecked("pro-metrica-grid", "selectedItens");
        if (typeof hasItem !== 'undefined' && hasItem.length > 0) {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Deseja marcar todos os itens selecionados como favoritos?'); ?>";
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

    function getColaborador(value) {
        $.ajax({
            type: 'POST',
            data: {'opcao': value},
            url: baseUrl+"/metrica/getColaboradores",
            success: function (data) {
                $("#selec").show();
                $('#selecionado').find('option').remove();
                if (data) {
                    $('#selecionado').append(data);
                } else {
                    $('#selecionado').append("<option value='0'>Selecione<option>");
                }
                $("#selecionado").trigger("change");
            }
        });
    }
</script>
