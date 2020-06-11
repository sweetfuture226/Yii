<a id="btn_modal_confirm_feriado" class="invisible" data-toggle="modal" href="#modal_confirm_feriado">Dialog</a>
<div class="modal fade" id="modal_confirm_feriado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('smith', 'Confirmar novo(s) feriado(s)'); ?></h4>
            </div>
            <div class="modal-body">

                <p id="mensagem"></p>

            </div>
            <div class="modal-footer">
                <div id="confirma_feriado"><button id="btn_modal_confirm_feriado"class="btn btn-success" type="submit"><?php echo Yii::t('smith', 'Confirmar'); ?></button></div>
                <button id ="btn_fechar_modal_confirm_feriado" data-dismiss="modal" class="btn btn-default" type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>

                <input type='hidden' id='id_feriado' value="">
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
</div>


<script>
    function validaForm() {
        var hasItem = $.fn.yiiGridView.getChecked("feriado-grid", "selectedItens");
        if (typeof hasItem !== 'undefined' && hasItem.length > 0) {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Você tem certeza que deseja autorizar todos os itens selecionados como feriados?'); ?>";
            $('#btn_modal_confirm_feriado').click();
            document.getElementById("confirma_feriado").style.display = "block";
            document.getElementById("confirma_feriado").style.margin = "0px 0px 0px 370px";
            document.getElementById("confirma_feriado").style.position = "absolute";
        }
        else {
            document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith', 'Por favor, selecione ao menos uma data'); ?>";
            $('#btn_modal_confirm_feriado').click();
            document.getElementById("confirma_feriado").style.display = "none";
        }
    }
</script>