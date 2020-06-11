<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'site-permitido-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
        ));



?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

<?php echo $form->errorSummary($model); ?>

<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::hiddenField("indice", 0) ?>
<?php echo CHtml::label(Yii::t('smith', 'Sites'), "colaborador_id"); ?>
<?php echo CHtml::dropdownlist('sites_selecionados', 0, CHtml::listData(SitePermitido::model()->findAll(array("order" => "nome", "condition" => "fk_empresa = 0")), 'nome', 'nome'), array("class" => "chzn-select form-control input-sm m-bot15", "style" => "width:100%;", "multiple"=>"multiple")); ?>
    </p>
</div>

  <div class="form-group  col-lg-4">
            <p>
                <?php   echo $form->labelEx($model,'fk_equipe');
                        echo $form->dropdownlist($model,'fk_equipe',CHtml::listData(Equipe::model()->findAll(array('order'=>'nome','condition'=>'fk_empresa ='.UserGroupsUser::model()->findByPk(Yii::app()->user->id)->fk_empresa)), 'id', 'nome'),array("class"=>"chzn-select", 'empty'=>Yii::t("smith",'Todas ') ,"style"=> "width:100%;"));
                ?>
            </p>
        </div>
<div style="clear:both"></div>
<span><?=Yii::t('smith', 'Caso o programa desejado não conste na lista')?>, <a href="#" id="solicitar"><?=Yii::t('smith', 'clique aqui')?></a><?=' '. Yii::t('smith', 'para validarmos')?></span>

<div class="buttons">
    <div style="float: right; margin-bottom: 15px">
<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t("smith", 'Salvar') : Yii::t("smith", 'Atualizar'), array('class' => 'btn btn-info submitForm')); ?>
    </div>
</div>

<div class="modal fade" id="solicitacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Solicitação de validação de novos sites</h4>
            </div>
            <div class="modal-body">

                <div class="form-group  col-lg-12">
                    <p>
                        <label>Site</label>
                        <input type="text" title="Para inserir mais de um site na lista, separe-os por vírgulas" class="form-control" name="site" id="site"  /></p>
                </div>

                <div style="clear: both"></div>

            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Fechar</button>
                <input class="btn btn-info submitForm" type="submit" name="enviarEmail" value="Enviar">    
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<!-- form -->
<script>

    $(document).ready(function(){
        $("#solicitar").on('click', function(){
            $("#solicitacao").modal('toggle');
        })
    });

    function adicionarSite() {
        var indice = $("#indice").val();
        var site_selecionado = $("#sites option:selected");
        var site_nome = site_selecionado.text();
        var tipo_site_id = site_selecionado.val();
        if (tipo_site_id != '') {
            var template = '\n\
             <li class="search-choice">\n\
             \
             <input type="hidden" value="' + site_nome + '" name="Site[selecionados][' + indice + '][nome_site]" id="Site_selecionados_' + indice + '_nome_site" />\n\
             <span type="text" value="' + site_nome + '" name="Site[selecionados][' + indice + '][nome_personalizado]" id="Site_selecionados_' + indice + '_nome_personalizado" maxlength="255" readonly/>' + site_nome + '</span>\n\
             <input class="remover_item btn" onclick="removerSite(this)" type="button" value="" /></li>\n\
             ';
            $('#sites_selecionados').append(template);
            if (indice % 3 == 0) {
                if (indice != 0)
                    $("#sites_selecionados").height("+=45");
                else
                    $("#sites_selecionados").height("+=25");
            }
            indice++;

            $("#indice").val(indice);
        } else {
            document.getElementById('message').innerHTML = "Você deve selecionar um site!";
            $('#btn_modal_open').click();
        }
    }

    function removerSite(element) {
        var indice = $("#indice_proximo_site").val();
        element.parentNode.remove();
        indice--;
        $("#indice_proximo_site").val(indice);
        if ((indice) % 3 == 0) {
            if (indice != 0)
                $("#sites_selecionados").height("-=45");
            else
                $("#sites_selecionados").height("-=25");
        }
    }
</script>
