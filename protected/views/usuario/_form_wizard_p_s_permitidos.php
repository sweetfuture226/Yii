<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'p-s-form',
	'enableAjaxValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,

        ),
        'htmlOptions'=>array('class'=>'form valid'),
)); ?>
<p>
    <?=Yii::t('wizard','Selecione e adicione os programas e sites que podem ser usados pelos funcionários da instituição.')?>
</p>
<fieldset><legend><?=Yii::t('smith','Programas permitidos')?></legend>
<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::hiddenField("indice", 0) ?>
<?php echo CHtml::label(Yii::t('smith','Programas'), "colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('programas', 0, CHtml::listData(ProgramaPermitido::model()->findAll(array("order" => "nome ASC", "condition" => "fk_empresa = 1")), 'id', 'nome'), array("class" => "chzn-select form-control input-sm m-bot15", "prompt" => Yii::t('smith', "Selecione"), "style" => "width:100%;")); ?>
    </p>
</div>
<div class="form-group  col-lg-2" style="margin-top: 20px">
    <input class="btn btn-success"  id="bt_adicionar_ambiente" onclick="adicionarPrograma()" value="<?=Yii::t('smith','Adicionar')?>" type="button">
</div>
<div class="form-group  col-lg-6" style="margin-top: 20px">
    <div class="chzn-container chzn-container-multi" style="width: 100%;" >
        <ul class="chzn-choices" id="programas_selecionados">
            <li class="search-field" style="width: 100%;"><input type="text" value="" class="default" autocomplete="off" style="width: 100%;"></li>
        </ul>
    </div>
</div>
<div style="clear:both"></div>

<p style="margin-left: 10px">
    <?=Yii::t('smith','Se o programa autorizado não se encontra na lista')?>, <a  data-toggle="modal" href="#solicitacaoP" ><?=Yii::t('smith','clique aqui')?></a>
	</p>





</fieldset>

<fieldset><legend><?=Yii::t('smith','Sites permitidos')?></legend>
<div class="form-group  col-lg-4">
    <p>
        <?php echo CHtml::hiddenField("indice", 0) ?>
<?php echo CHtml::label(Yii::t('smith','Sites'), "colaborador_id"); ?>
        <?php echo CHtml::dropdownlist('sites', 0, CHtml::listData(SitePermitido::model()->findAll(array("order" => "nome ASC", "condition" => "fk_empresa = 0")), 'id', 'nome'), array("class" => "chzn-select form-control input-sm m-bot15", "prompt" => Yii::t('smith', "Selecione"), "style" => "width:100%;")); ?>
    </p>
</div>
<div class="form-group  col-lg-2" style="margin-top: 20px">
    <input class="btn btn-success"  id="bt_adicionar_ambiente" onclick="adicionarSite()" value="<?=Yii::t('smith','Adicionar')?>" type="button">
</div>
<div class="form-group  col-lg-6" style="margin-top: 20px">
    <div class="chzn-container chzn-container-multi" style="width: 100%;" >
        <ul class="chzn-choices" id="sites_selecionados">
            <li class="search-field" style="width: 100%;"><input type="text" value="" class="default" autocomplete="off" style="width: 100%;"></li>
        </ul>
    </div>
</div>
	<div style="clear:both"></div>
	<p style="margin-left: 10px">
        <?=Yii::t('smith','Se o site autorizado não se encontra na lista')?>, <a data-toggle="modal" href="#solicitacaoS"><?=Yii::t('smith','clique aqui')?></a>
	</p>
</fieldset>


<?php $this->endWidget(); ?>
<script>

    function adicionarPrograma() {
        var indice = $("#indice").val();

        var programa_selecionado = $("#programas option:selected");



        var programa_nome = programa_selecionado.text();
        var tipo_programa_id = programa_selecionado.val();
        if (tipo_programa_id != '') {
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
        } else {
            document.getElementById('message').innerHTML = "Você deve selecionar um programa!";
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

    function enviarEmailPrograma(){
        $.ajax({
            url: baseUrl +'/ProgramaPermitido/validar',
            type: 'POST',
            data: $("#programa-form").serialize(),
            success: function(data){
                console.log(data);
                $("#programa").val("");
                $("#site").val("");
                $("#notification").addClass('in');
                $('#notification').show().delay(1000).hide('slow');
            }

        });
    }

    function enviarEmailSite(){
        $.ajax({
            url: baseUrl +'/SitePermitido/validar',
            type: 'POST',
            data: $("#site-form").serialize(),
            success: function(data){
                $("#site").val("");
                $("#notification2").addClass('in');
                $('#notification2').show().delay(1000).hide('slow');
            }

        });
    }

</script>
