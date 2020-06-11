<?php
$this->breadcrumbs = array(
    Yii::t('smith', 'Empresas'),
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
        $("#datepicker_for_nascimento").datepicker();

    }
');

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
    });
    $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('pro-pessoa-grid', {
                    data: $(this).serialize()
            });
            return false;
    });
");
?>


<div class="dataTables_length">
    <label>
        <?php
        $this->widget('application.extensions.PageSize.PageSize', array(
            'mGridId' => 'empresa-grid', //Gridview id
            'mPageSize' => @$_GET['pageSize'],
            'mDefPageSize' => Yii::app()->params['defaultPageSize'],
            'mPageSizeOptions' => Yii::app()->params['pageSizeOptions'], // Optional, you can use with the widget default
        ));
        ?>
    </label>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'empresa-grid',
    'dataProvider' => $model->searchPoc(),
    'filter' => $model,
    'afterAjaxUpdate' => 'afterAjax',
    'columns' => array(
        'nome',
        'responsavel',
        'email',
        'colaboradores_previstos',
        array(
            'name' => 'duracao',
            'value' => '$data->duracao ." dias"'
        ),
        'nomeContato',
        'emailContato',
        array(
            'header' => Yii::t('smith', 'Ações'),
            'class' => 'booster.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width:10%; text-align: right;'),
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
                'viewInfo' => array(
                    'label' => Yii::t('smith', 'Visualizar info da POC'),
                    'options' => array('class' => 'btn btn-info btn-margin-grid viewInfo', 'data-id' => '$data->id'),
                    'icon' => 'fa fa-info-circle'
                ),
            ),
            'template' => '{viewInfo}{update}',
        ),
    ),
)); ?>


<script>
    $('#empresa-grid a.viewInfo').live('click', function () {
        var obj = $(this).parent().parent();
        var empresa = obj.children(':first-child').text();
        var email = obj.children(':nth-child(3)').text();
        $.ajax({
            type: 'POST',
            data: {id: $(this).data('id')},
            dataType: 'JSON',
            url: baseUrl + '/empresa/viewInfoPoc',
            success: function (data) {
                $("#empresa_nome").html(empresa);
                $("#empresa_email").html(email);
                $("#empresa_responsavel").html(data.empresa_responsavel);
                $("#empresa_telefone").html(data.empresa_telefone);
                $("#empresa_logo").attr('src', data.empresa_logo);
                $("#quantidade_maquinas").html(data.quantidade_maquinas);
                $("#duracao").html(data.duracao);
                $("#revenda_nome").html(data.revenda_nome);
                $("#revenda_responsavel").html(data.revenda_responsavel);
                $("#revenda_email").html(data.revenda_email);
                $("#revenda_telefone").html(data.revenda_telefone);
                $('#infoPoc').modal();
            }

        });
        return false;
    });
</script>
<?php $this->renderPartial('modalViewInfoPoc'); ?>
