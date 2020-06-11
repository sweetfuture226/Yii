<div class="modal" id="modal_preencher_colaboradores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('smith', 'Preencher Colaboradores') ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <p><?= Yii::t('smith', $notificacao->notificacao) ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <a id="fazer-alteracao" class="btn btn-info" href="<?php echo Yii::app()->baseUrl .'/'. $notificacao->action ?>"><?= Yii::t('smith', 'Fazer alterações') ?></a>
                <button class="btn btn-danger" type="button" onclick="$('#modal_preencher_colaboradores').modal('hide')"><?= Yii::t('smith', 'Ver depois') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#modal_preencher_colaboradores').modal();
    });

    $(document).on('click', '#fazer-alteracao', function(){
        $.ajax({
            url: baseUrl + '/Notificacao/removerNotificacao',
            type: 'POST',
            data: {notificacao: <?php echo $notificacao->id ?>},
        })
        .done(function(data) {
            console.log(data);
        });
    });
</script>