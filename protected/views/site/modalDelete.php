<!--modal deletar -->
<a id="btn_modal_deletar" class="invisible" data-toggle="modal" href="#modal_deletar">Dialog</a>
<div class="modal fade" id="modal_deletar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Excluir</h4>
            </div>
            <div class="modal-body">

                Deseja confirmar a exclusão?

            </div>
            <div class="modal-footer">
                <button id="btn_fechar_modal_deletar" data-dismiss="modal" class="btn btn-default" type="button">
                    Cancelar
                </button>
                <button id="btn_confirmar_modal_deletar" class="btn btn-success" type="button">Confirmar</button>
                <input type='hidden' id='url_deletar' value="">
            </div>
        </div>
    </div>
</div>
<!--fim modal deletar -->