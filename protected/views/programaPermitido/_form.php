<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'programa-permitido-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form valid'),
        ));
?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>-->

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group  col-lg-4">
            <p>
                <?php echo CHtml::hiddenField("indice", 0) ?>
                <?php echo CHtml::label(Yii::t('smith', 'Programas'), "colaborador_id"); ?>
                <?php echo CHtml::dropdownlist('programas', 0, CHtml::listData(ProgramaPermitido::model()->findAll(array("order" => "nome", "condition" => "fk_empresa = 1")), 'nome', 'nome'), array("class" => "chzn-select form-control input-sm m-bot15", "multiple" =>"multiple", "style" => "width:100%;")); ?>
            </p>
        </div>
        <div class="form-group  col-lg-4">
            <p>
                <?php   echo $form->labelEx($model,'fk_equipe');
                        echo $form->dropdownlist($model,'fk_equipe',CHtml::listData(Equipe::model()->findAll(array('order'=>'nome','condition'=>'fk_empresa ='.UserGroupsUser::model()->findByPk(Yii::app()->user->id)->fk_empresa)), 'id', 'nome'),array("class"=>"chzn-select", 'empty'=>Yii::t("smith",'Todas') ,"style"=> "width:100%;"));
                ?>
            </p>
        </div>
        <div style="clear:both"></div>

        <span><?=Yii::t('smith', 'Caso o programa desejado não conste na lista')?>, <a data-toggle="modal" href="#solicitacao"><?=Yii::t('smith', 'clique aqui')?></a> <?=Yii::t('smith', 'para validarmos')?></span>

        <div class="clear"></div>
        <div class="buttons">
            <div style="float: right; margin-bottom: 15px ">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t("smith", 'Salvar') : Yii::t("smith", 'Atualizar'), array('class' => 'btn btn-info submitForm')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
<!-- form -->

<form id="solicita">
<div class="modal fade" id="solicitacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <div style="clear: both"></div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('smith', 'Solicitação de validação de novos programas'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="form-group  col-lg-6">
                    <p>
                        <label><?= Yii::t('smith', 'Programa'); ?></label>
                        <input type="text"  class="form-control" name="programa" id="programa"  /></p>
                </div>
                <div class="form-group  col-lg-6">
                    <p>
                        <label><?= Yii::t('smith', 'Site do fabricante'); ?></label>
                        <input type="text"  class="form-control" name="site" id="site"  /></p>
                </div>



            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <!--Notification Start -->
                <div class="notifications" style="float: left;">
                    <div  id="notification" class="alert alert-success fade " style="margin-bottom: 0px !important">

                    <strong><i class="icon-ok-sign"></i><?= Yii::t('smith', 'Pedido solicitado!'); ?></strong>
                 </div>
                </div>
                <!--Notification End -->
                <button data-dismiss="modal" class="btn btn-default" type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <input class="btn btn-info submitForm" type="button" name="enviarEmail" id="enviarEmail" value="Enviar">                                          </div>
        </div>
    </div>
</div>
</form>

<script>

    function adicionarPrograma() {
        var indice = $("#indice").val();

        var programa_selecionado = $("#programas option:selected");

        var existe = false;

        var programa_nome = programa_selecionado.text();

        var tipo_programa_id = programa_selecionado.val();
        for (i=0 ; i<=indice;i++){
            if(programa_nome == $('#Programa_selecionados_'+i+'_nome_programa').val())
                existe = true;
        }

        if (tipo_programa_id != '') {
            if(!existe){
            var template = '\n\
 <li class="search-choice">\n\
 \
 <input type="hidden" value="' + programa_nome + '" name="Programa[selecionados][' + indice + '][nome_programa]" id="Programa_selecionados_' + indice + '_nome_programa" />\n\
 <span type="text" value="' + programa_nome + '" name="Programa[selecionados][' + indice + '][nome_personalizado]" id="Programa_selecionados_' + indice + '_nome_personalizado" maxlength="255" readonly/>' + programa_nome + '</span>\n\
 <input class="remover_item btn" onclick="removerPrograma(this)" type="button" value="" /></li>\n\
 ';
            $('#programas_selecionados').append(template);
            if (indice % 3 == 0) {
                if (indice != 0)
                    $("#programas_selecionados").height("+=45");
                else
                    $("#programas_selecionados").height("+=25");
            }
            indice++;

            $("#indice").val(indice);
        }
        else{
                document.getElementById('message').innerHTML = "<?= Yii::t('smith', 'Programa já inserido na lista.') ?>";
            $('#btn_modal_open').click();
        }
    }
        else {
            document.getElementById('message').innerHTML = "<?= Yii::t("smith", "Favor selecionar um programa"); ?>";
            $('#btn_modal_open').click();
        }
  }
    function removerPrograma(element) {
        var indice = $("#indice_proximo_programa").val();
        element.parentNode.remove();
        indice--;
        $("#indice_proximo_programa").val(indice);
        if ((indice) % 3 == 0) {
            if (indice != 0)
                $("#programas_selecionados").height("-=45");
            else
                $("#programas_selecionados").height("-=25");
        }
    }



  $("#enviarEmail").click(function(){
                  $.ajax({
                    url: 'validar',
                    type: 'POST',
                    data: $("#solicita").serialize(),
                    success: function(data){
                    $("#programa").val("");
                    $("#site").val("");
                    $("#notification").addClass('in');
                    $('#notification').show().delay(1000).hide('slow');

                    }

                });
  });





</script>
