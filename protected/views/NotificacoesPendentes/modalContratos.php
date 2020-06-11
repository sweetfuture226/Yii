<h4>Verificação - Documentos sem contrato associado</h4>
<p style="text-align: justify"><?php echo Yii::t("smith", "Os usuários abaixo tiveram produtividade medida em documentos, mas não foram associados a um contrato.") ?></p>
<table id="table-documentoSemContrato" class="table table-bordered datatable dataTable tableNotificacoes">
    <thead>
    <tr>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Colaborador'); ?></strong>
        </th>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Documento'); ?></strong></th>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Data'); ?></strong></th>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Tempo'); ?></strong></th>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Ações'); ?></strong></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($value as $obj) {
        echo '<tr><td>' . $obj->colaborador->nome . ' ' . $obj->colaborador->sobrenome . '</td><td>' . wordwrap($obj->documento, 15, "\n", true) . '</td><td>' . MetodosGerais::dataBrasileira($obj->data) . '</td><td>' . MetodosGerais::formataTempo($obj->duracao) . '</td><td style="width: 133px;"><a href="#" class="btn btn-success btnContratoAssociado" data-toggle="tooltip" title="Associar este tempo a um documento cadastrado" data-id="' . $obj->id . '" data-nome="' . $obj->documento . '" data-duracao="' . $obj->duracao . '" data-fk_colaborador="' . $obj->fk_colaborador . '"> <i class="fa fa-plus-square" aria-hidden="true"></i> </a>  <a href="#" class="btn btn-orange btnDocumentoInexistente" data-toggle="tooltip" title="Associar este tempo a um novo documento" data-id="' . $obj->id . '" data-nome="' . $obj->documento . '" data-duracao="' . $obj->duracao . '" data-fk_colaborador="' . $obj->fk_colaborador . '"> <i class="fa fa-plus-square" aria-hidden="true"></i> </a>    <a href="#" class="btn btn-danger btnNovoContrato" data-toggle="tooltip" title="Associar este tempo a um documento de um novo projeto" data-id="' . $obj->id . '" data-nome="' . $obj->documento . '" data-duracao="' . $obj->duracao . '" data-fk_colaborador="' . $obj->fk_colaborador . '"> <i class="fa fa-plus-square" aria-hidden="true"></i> </a></td></tr>';
    } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btnContratoAssociado").on('click', function () {
            var documento_nome = $(this).data('nome');
            var documento_id = $(this).data('id');
            var duracao = $(this).data('duracao');
            var fk_colaborador = $(this).data('fk_colaborador');
            var campo = $(this);
            var row = campo.closest('tr').index();
            var column = campo.closest('td').index();
            $("#row_doc_sem_contrato").val(row);
            $("#column_doc_sem_contrato").val(column);
            $('#modalNotificacoes').modal('toggle');
            $('#modal-associa-documento').modal('show');
        });


        $(".btnDocumentoInexistente").on('click', function () {
            var documento_nome = $(this).data('nome');
            var documento_id = $(this).data('id');
            var duracao = $(this).data('duracao');
            var fk_colaborador = $(this).data('fk_colaborador');
            var campo = $(this);
            var row = campo.closest('tr').index();
            $.ajax({
                method: "POST",
                url: baseUrl + '/DocumentoSemContrato/GetContractsWithDocuments',
            }).done(function (retorno) {
                $('#modalNotificacoes').modal('toggle');
                $("#row_documento").val("");
                $("#row_documento").val(row);
                $("#contratos_").html("");
                $("#contratos_").append("<option value='0'> <?php echo Yii::t('smith', 'Selecione uma opção') ?> ... </option>");
                $("#contratos_").append(retorno);
                $("#contratos_").val('0').trigger("change");
                $("#documento_").val(documento_nome);
                $("#id_documento_").val(documento_id);
                objCampo = $(this);
                $('#modalNovoDocumento').modal('toggle');
            });
        });

        $(".btnNovoContrato").on('click', function () {
            var documento_nome = $(this).data('nome');
            var documento_id = $(this).data('id');
            var duracao = $(this).data('duracao');
            var fk_colaborador = $(this).data('fk_colaborador');
            var campo = $(this);
            var row = campo.closest('tr').index();
            $("#row_documento").val("");
            $("#row_documento").val(row);
            $("#nome_contrato").val("");
            $("#codigo_contrato_").tokenfield('setTokens', []);
            $("#valor_contrato_").val("");
            $("#data_inicial_contrato").val("");
            $("#data_final_contrato").val("");
            $("#tempo_previsto_novo_doc").val("");
            removeBorder();
            $('#modalNotificacoes').modal('toggle');
            $("#nome_documento_contrato").val(documento_nome);
            $("#documento_com_contrato").val(documento_nome);
            $("#id_documento_contrato").val(documento_id);
            objCampo = $(this);
            $('#modalNovoContrato').modal('toggle');
        });
    });
    function documentos_change() {
        $(document).on('change', '#contratosComDocumento', function () {
            var fk_contrato = $(this).val();
            $.ajax({
                method: "POST",
                url: baseUrl + '/DocumentoSemContrato/GetDocuments',
                data: {fk_contrato: fk_contrato},
            }).done(function (retorno) {
                $("#documentos_escolher").html("");
                $("#documentos_escolher").append(retorno);
            });
        });
    }

    function removeBorder() {
        var flag = true;
        $("#codigo_contrato_").parent().css('border-color', 'gray');
        $(".obg").each(function (index) {
            $(this).css('border-color', 'gray');
        });
        return flag;
    }
</script>