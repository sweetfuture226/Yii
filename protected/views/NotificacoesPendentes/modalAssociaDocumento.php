<!-- MODAL DE ASSOCIAÇÃO TEMPO A DOCUMENTO -->
<div class="modal " id="modal-associa-documento" abindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Associar tempo a documento') ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="row_doc_sem_contrato">
                <input type="hidden" id="column_doc_sem_contrato">
                <p class="titulo-info"></p>
                <p>
                    <?php
                    $listData = Contrato::model()->searchContractsWithDocuments(MetodosGerais::getEmpresaId());
                    $data = array();
                    foreach ($listData as $model)
                        $data[$model->id] = $model->nome . ' - ' . $model->codigo;
                    echo CHtml::label(Yii::t("smith", 'Contrato'), 'contrato');
                    echo CHtml::dropDownList('contratosComDocumento', null, $data, array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione um contrato'), "style" => "width:100%;"));
                    ?>
                </p>
                <p>
                    <?= CHtml::label(Yii::t('smith', 'Documento'), 'documento'); ?>
                    <?= CHtml::dropDownList('documentos_escolher', null, array(), array('class' => 'chzn-select')) ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel"
                        data-dismiss="modal"><?= Yii::t('smith', 'Fechar') ?></button>
                <button type="button" class="btn btn-primary salvarDocumento"><?= Yii::t('smith', 'Salvar') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('change', '#contratosComDocumento', function () {
        var fk_contrato = $(this).val();
        $.ajax({
            method: "POST",
            url: baseUrl + '/DocumentoSemContrato/GetDocuments',
            data: {fk_contrato: fk_contrato}
        }).done(function (retorno) {
            $("#documentos_escolher").html("");
            $("#documentos_escolher").append(retorno);
            $("#documentos_escolher").trigger('change');

        });
    });

    $(".salvarDocumento").on('click', function () {
        var row = $("#row_doc_sem_contrato").val();
        var column = $("#column_doc_sem_contrato").val();
        var documento_nome = $("#table-documentoSemContrato tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('nome');
        var documento_id = $("#table-documentoSemContrato tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('id');
        var duracao = $("#table-documentoSemContrato tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('duracao');
        var fk_colaborador = $("#table-documentoSemContrato tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('fk_colaborador');

        if (($("#contratosComDocumento").val() == "") || ($("#documentos_escolher").val() == "")) {
            swal("Erro!", "<?php echo Yii::t('smith', 'Todos os campos são obrigatórios.') ?>", "error");
        }
        else {
            $.ajax({
                method: "POST",
                url: baseUrl + '/DocumentoSemContrato/UpdateDocuments',
                data: {
                    id_documento_sem_contrato: documento_id,
                    nome_documento_sem_contrato: documento_nome,
                    novo_nome_documento: $("#documentos_escolher").val(),
                    duracao: duracao,
                    fk_colaborador: fk_colaborador
                }
            }).done(function (retorno) {
                if (retorno == "success") {
                    swal("Sucesso!", "<?php echo Yii::t('smith', 'Documento registrado.') ?>", "success");
                    var nRow = $("#table-documentoSemContrato tbody tr:eq(" + row + ")")[0];
                    $('#table-documentoSemContrato').dataTable().fnDeleteRow(nRow);
                    $('#modal-associa-documento').modal('hide');
                    $('#modalNotificacoes').modal('toggle');
                }
                else
                    swal("Erro!", "<?php echo Yii::t('smith', 'Ocorreu um erro por favor, contate a equipe de suporte.') ?>", "error");
            });
        }
    });
</script>