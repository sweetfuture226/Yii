<a id="btn_modal_confirm_metrica" class="invisible" data-toggle="modal" href="#modal_confirm_metrica">Dialog</a>
<div class="modal fade" id="modal_confirm_metrica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('smith', 'Aviso'); ?></h4>
            </div>
            <div class="modal-body">

                <?php echo Yii::t('smith', 'Ao não selecionar um critério, serão trazidas todas as ocorrências da aplicação escolhida. Proceder?'); ?>

            </div>
            <div class="modal-footer">
                <button id ="btn_fechar_modal_confirm_metrica" data-dismiss="modal" class="btn btn-default" type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>
                <button id="btn_modal_confirm_visualiza_metrica" class="btn btn-success"
                        type="button"><?php echo Yii::t('smith', 'Confirmar'); ?></button>
            </div>
        </div>
    </div>
</div>

