<a id="btn_modal_confirm_metrica" class="invisible" data-toggle="modal" href="#modal_confirm_metrica">Dialog</a>
<div class="modal fade" id="modal_confirm_metrica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('smith', 'Confirmar associação dos colaboradores as métricas'); ?></h4>
            </div>
            <div class="modal-body">

                <p id="mensagem"></p>

            </div>
            <div class="modal-footer">
                <div id="confirma_metrica"><button id="btn_modal_confirm_metrica"class="btn btn-success" type="submit"><?php echo Yii::t('smith', 'Confirmar'); ?></button></div>
                <button id ="btn_fechar_modal_confirm_metrica" data-dismiss="modal" class="btn btn-default" type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>

                <input type='hidden' id='id_metrica' value="">
            </div>
        </div>
    </div>
</div>

