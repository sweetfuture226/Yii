<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Contratos')=>array('index'),
	Yii::t("smith",'Atualizar'),
);
Yii::app()->clientScript->registerScript('button_create', '
    $("div.block-header").prepend(\'<div class="button_new" style="float: right;margin: 6px 12px 0 0;"><a href="' . CHtml::normalizeUrl(array("Contrato/create")) . '"><img src="' . Yii::app()->theme->baseUrl . '/img/icons/create.png" alt="Criar" title="Criar" class="" style="cursor: pointer;" /></a></div>\');
');?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'condicao'=>$condicao,'documentos'=>$documentos)); ?>

<script>
	$(document).ready(function() {
	    $('.dataTables_info').hide();
	    var index = parseInt($("#next_index_documento").val());

        if ($('#doc-grid tbody tr').find('td')[2]) {
            $('#doc-grid tbody tr').each(function () {
                $.ajax({
                    type: 'post',
                    url: baseUrl + '/Documento/getDisciplinaId/',
                    data: {'disciplina': $(this).find('td')[2].innerHTML},
                    async: false,
                    success: function (data) {
                        disciplinaId = data;
                    }
                });
                $(this).attr('data-index', index);
                $(this).append('<input type="hidden" value="' + $(this).find('td')[0].innerHTML + '" name="Documento[' + index + '][nome]" id="Documento[' + index + '][nome]">\n\
	            <input type="hidden" value="' + $(this).find('td')[1].innerHTML + '" name="Documento[' + index + '][previsto]" id="Documento[' + index + '][previsto]">\n\
	            <input type="hidden" value="' + $(this).find('td')[3].innerHTML + '" name="Documento[' + index + '][finalizado]" id="Documento[' + index + '][finalizado]">\n\
	            <input type="hidden" value="' + disciplinaId + '" name="Documento[' + index + '][fk_disciplina]" id="Documento[' + index + '][fk_disciplina]">');

                index++;
            });

            $("#next_index_documento").val(index);
        }
	});
</script>
