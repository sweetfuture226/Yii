<!-- MODAL DE JUSTIFICATIVA 2 dias -->
<div class="modal " id="modal-justificativa-2-dias" abindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Justificativa da ausência') ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="row_justificativa_2_dias">
                <input type="hidden" id="column_justificativa_2_dias">
                <input type="hidden" id="tipo_justificativa">
                <p class="titulo-info"></p>
                <p>
                    <?php
                    echo CHtml::label('Selecione o tipo', 'justificativa');
                    echo CHtml::dropDownList('justificativa_2_dias', null, array(), array('empty' => Yii::t('smith', 'Selecione'), "class" => "chzn-select", "style" => "width:100%;"));
                    ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel"
                        data-dismiss="modal"><?= Yii::t('smith', 'Fechar') ?></button>
                <button type="button"
                        class="btn btn-primary saveJustificativa"><?= Yii::t('smith', 'Salvar') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.saveJustificativa').on('click', function () {
        if ($('#justificativa_2_dias').val() != "") {
            var tipo = $('#tipo_justificativa').val();
            (tipo == 'tecnico') ? saveTecnica() : saveLicensa();
        } else {
            swal("Atenção!", "<?php echo Yii::t('smith', 'O campo justificativa é obrigatório.') ?>", "error");
        }
    });

    function saveLicensa() {
        var row = $("#row_justificativa_2_dias").val();
        var column = $("#column_justificativa_2_dias").val();
        var descricao = $("#justificativa_2_dias").val();
        var fk_colaborador = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('id');
        var data_inicial = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_inicial');
        var data_final = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_final');
        if ((descricao == "Férias") || (descricao == "Suspensão")) {
            $.ajax({
                method: "POST",
                url: baseUrl + '/ColaboradorHasFerias/CreateFerias',
                data: {
                    fk_colaborador: fk_colaborador,
                    descricao: descricao,
                    data_inicio: data_inicial,
                    data_fim: data_final
                }
            }).done(function (retorno) {
                if (retorno == "success") {
                    var nRow = $("#table-2dias tbody tr:eq(" + row + ")")[0];
                    $('#table-2dias').dataTable().fnDeleteRow(nRow);
                    $('#modal-justificativa-2-dias').modal('hide');
                    $('#modalNotificacoes').modal('toggle');
                    swal("Sucesso!", "<?php echo Yii::t('smith', 'Licença inserida.') ?>", "success");
                }
                else
                    swal("Error!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar inserir licença.') ?>", "error");
            });
        } else {
            $.ajax({
                method: "POST",
                url: baseUrl + '/Colaborador/Demissao',
                data: {fk_colaborador: fk_colaborador}
            }).done(function (retorno) {
                if (retorno == "success") {
                    var nRow = $("#table-2dias tbody tr:eq(" + row + ")")[0];
                    $('#table-2dias').dataTable().fnDeleteRow(nRow);
                    $('#modal-justificativa-2-dias').modal('hide');
                    $('#modalNotificacoes').modal('toggle');
                    swal("Sucesso!", "<?php echo Yii::t('smith', 'Colaborador inativado.') ?>", "success");
                }

            });
        }
    }


    function saveTecnica() {
        var row = $("#row_justificativa_2_dias").val();
        var column = $("#column_justificativa_2_dias").val();
        var descricao = $("#justificativa_2_dias").val();
        var fk_colaborador = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('id');
        var data_inicial = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_inicial');
        var data_final = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_final');
        $.ajax({
            method: "POST",
            url: baseUrl + '/ColaboradorSemProdutividade/SetJustificativa',
            data: {
                fk_colaborador: fk_colaborador,
                data_inicio: data_inicial,
                data_fim: data_final,
                descricao: descricao
            }
        }).done(function (retorno) {
            if (retorno == "success") {
                var nRow = $("#table-2dias tbody tr:eq(" + row + ")")[0];
                $('#table-2dias').dataTable().fnDeleteRow(nRow);
                $('#modal-justificativa-2-dias').modal('hide');
                $('#modalNotificacoes').modal('toggle');
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Questão técnica inserida.') ?>", "success");
            }
            else
                swal("Error!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar questão técnica') ?>", "error");
        });
    }


</script>