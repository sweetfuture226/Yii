<div class="modal" id="modal-implantation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informação</h4>
            </div>
            <div class="modal-body">
                <h4>Olá, tudo bem?</h4>
                <p>Percebemos que você ainda não visualizou a sua área de contratos<br/>
                    Deseja visualizá-la agora?
                </p>
            </div>
            <div class="modal-footer" data-idnotificacao="" data-url="<?= Yii::app()->createUrl("notificacao/notificacaoLida") ?>">
                <button type="button" class="btn btn-default" data-dismiss="modal">Não tenho interesse</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Lembrar mais tarde</button>
                <a href="<?= Yii::app()->createUrl('Contrato') ?>" class="btn btn-primary viewer">Quero visualizar</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->