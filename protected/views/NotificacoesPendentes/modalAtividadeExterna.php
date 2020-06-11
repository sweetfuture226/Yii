<!-- MODAL DE NOVA ATIVIDADE EXTERNA -->
<div class="modal " id="modal-atividade-externa" abindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Cadastrar atividade externa') ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="row_atividade">
                <input type="hidden" id="column_atividade">
                <input type="hidden" id="tipo_ausencia">
                <p class="titulo-info"></p>
                <p>
                    <?php
                    $listData = Contrato::model()->findAll(array('order' => 't.nome', 'condition' => 'fk_empresa =' . MetodosGerais::getEmpresaId()));
                    $data = array();
                    foreach ($listData as $model)
                        $data[$model->codigo] = $model->nome . ' - ' . $model->codigo;
                    echo CHtml::label(Yii::t("smith", 'Contrato'), 'Obra');
                    echo CHtml::dropDownList('contrato', null, $data, array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Sem contrato associado'), "style" => "width:100%;"));
                    ?>
                </p>
                <p>
                    <?= CHtml::label(Yii::t('smith', 'Descrição'), 'descricao'); ?>
                    <?= CHtml::textField('descricao_atividade', '', array('class' => 'form-control')); ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel"
                        data-dismiss="modal"><?= Yii::t('smith', 'Fechar') ?></button>
                <button type="button"
                        class="btn btn-primary salvarAtividadeExterna"><?= Yii::t('smith', 'Salvar') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".salvarAtividadeExterna").on('click', function () {
        if ($('#descricao_atividade').val() != "") {
            var tipo = $('#tipo_ausencia').val();
            (tipo == '2horas') ? Salvar2Horas() : Salvar2Dias();
        } else {
            swal("Atenção!", "<?php echo Yii::t('smith', 'O campo descrição é obrigatório.') ?>", "error");
        }
    });

    function Salvar2Dias() {
        var row = $("#row_atividade").val();
        var column = $("#column_atividade").val();
        var fk_usuario = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('id');
        var data_inicial = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_inicial');
        var data_final = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_final');
        var ad = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('ad');

        $.ajax({
            method: "POST",
            url: baseUrl + '/AtividadeExterna/CreateAtividadeExterna',
            data: {
                fk_colaborador: fk_usuario,
                descricao: $('#descricao_atividade').val(),
                codigo_contrato: $("#contrato").val(),
                data_inicio: data_inicial,
                data_fim: data_final
            }
        }).done(function (retorno) {
            if (retorno != "error") {
                $('#descricao_atividade').val();
                $('#modal-atividade-externa').modal('hide');
                $('#modalNotificacoes').modal('toggle');
                var row = $("#row_atividade").val();
                var nRow = $("#table-2dias tbody tr:eq(" + row + ")")[0];
                $('#table-2dias').dataTable().fnDeleteRow(nRow);
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Atividade externa inserida.') ?>", "success");
            }
            else {
                swal("Erro!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar cadastrar atividade externa') ?>, <?php echo Yii::t('smith', 'por favor contate a equipe de suporte.') ?>", "error");
            }
        });
    }

    function Salvar2Horas() {
        var row = $("#row_atividade").val();
        var column = $("#column_atividade").val();
        var fk_colaborador = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('fk_colaborador');
        var data = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data');
        var ad = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('usuario');
        var fk_log = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('id');
        var duracao = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('duracao');
        var hora_inicial = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('hora_inicial');
        var hora_final = $("#tabela2Horas tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('hora_final');

        $.ajax({
            method: "POST",
            url: baseUrl + '/AtividadeExterna/CreateFromAjax',
            data: {
                data: data,
                fk_colaborador: fk_colaborador,
                ad: ad,
                descricao: $('#descricao_atividade').val(),
                fk_log: fk_log,
                duracao: duracao,
                hora_inicial: hora_inicial,
                hora_final: hora_final,
                codigo_contrato: $("#contrato").val()
            }
        }).done(function (retorno) {
            if (retorno == "ok") {
                $('#descricao_atividade').val();
                $('#modal-atividade-externa').modal('hide');
                $('#modalNotificacoes').modal('toggle');
                var row = $("#row_atividade").val();
                var nRow = $("#tabela2Horas tbody tr:eq(" + row + ")")[0];
                $('#tabela2Horas').dataTable().fnDeleteRow(nRow);
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Atividade externa inserida.') ?>", "success");
            }
            else {

                swal("Erro!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar cadastrar atividade externa') ?> , <?php echo Yii::t('smith', 'por favor contate a equipe de suporte.') ?>", "error");
            }
        });
    }

</script>