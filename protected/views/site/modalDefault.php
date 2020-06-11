<a id="btn_modal_open" class="invisible" data-toggle="modal" href="#modal_default">Dialog</a>
<div class="modal" id="modal_default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Aviso</h4>
            </div>
            <div class="modal-body">

                <p id="message"></p>

            </div>
            <div class="modal-footer">
                <button id ="btn_close_modal" data-dismiss="modal" class="btn btn-info" type="button"><?php echo Yii::t('smith', 'Ok'); ?></button>
            </div>
        </div>
    </div>
</div>

