<h4> <?php echo Yii::t('smith', 'Verificação - Ausência do computador por período superior a 2 horas') ?></h4>
<p style="text-align: justify"><?php echo Yii::t('smith', 'Os usuários abaixo apresentaram ausência do computador (tempo ocioso ou computador desligado) por um período superior a 2 horas.'); ?></p>
<table class="table table-bordered datatable dataTable tableNotificacoes" id="tabela2Horas">
    <thead>
    <tr>
        <th style="width: 31% !important;background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Colaborador'); ?></strong></th>
        <th style="width: 19% !important;background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Data'); ?></strong></th>
        <th style="width: 17% !important;background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Hora Inicial'); ?></strong></th>
        <th style="width: 15% !important;background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Hora Final'); ?></strong></th>
        <th style="width: 18% !important;background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Ações'); ?></strong></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($value as $obj) {
        echo '<tr>
                <td>' . $obj['nome'] . ' ' . $obj['sobrenome'] . '</td>
                <td>' . MetodosGerais::dataBrasileira($obj['data']) . '</td>
                <td>' . $obj['hora_inicial'] . '</td>
                <td>' . $obj['hora_final'] . '</td>
                <td style="width: 13% !important;">
                   <a href="#" class="btn btn-success btnAtividadeExterna" data-fk_colaborador="' . $obj['fk_colaborador'] . '" data-usuario="' . $obj['nome'] . ' ' . $obj['sobrenome'] . '" data-duracao="' . $obj['duracao'] . '" data-hora_inicial="' . $obj['hora_inicial'] . '" data-hora_final="' . $obj['hora_final'] . '" data-data="' . MetodosGerais::dataBrasileira($obj['data']) . '" data-id="' . $obj['fk_log'] . '" data-toggle="tooltip" title="' . Yii::t("smith", "Cadastrar atividade externa") . '">  <i class="fa fa-plus-square" aria-hidden="true"></i> </a> 
                   <a href="#" class="btn btn-orange btnAusencia" data-fk_colaborador="' . $obj['fk_colaborador'] . '" data-usuario="' . $obj['nome'] . ' ' . $obj['sobrenome'] . '" data-duracao="' . $obj['duracao'] . '" data-hora_inicial="' . $obj['hora_inicial'] . '" data-hora_final="' . $obj['hora_final'] . '" data-data="' . MetodosGerais::dataBrasileira($obj['data']) . '" data-id="' . $obj['fk_log'] . '" data-toggle="tooltip" title="' . Yii::t("smith", "Ausência não justificada") . '">  <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> </a>
                </td>
              </tr>';

    } ?>
    </tbody>
</table>


<script type="text/javascript">
    $(document).ready(function () {
        $(".btnAtividadeExterna").on('click', function () {
            var fk_colaborador = $(this).data('fk_colaborador');
            var data = $(this).data('data');
            var ad = $(this).data('usuario');
            var fk_log = $(this).data('id');
            var duracao = $(this).data('duracao');
            var hora_inicial = $(this).data('hora_inicial');
            var hora_final = $(this).data('hora_final');
            var campo = $(this);
            var row = campo.closest('tr').index();
            var column = campo.closest('td').index();
            $("#row_atividade").val(row);
            $("#column_atividade").val(column);
            var info = "<?=  Yii::t('smith', 'Favor informar a descrição da atividade externa do colaborador') ?> <strong>" + ad + "</strong> <?php echo Yii::t('smith', 'para o dia') ?> <strong>" + data + "</strong> com horário de saída às <strong>" + hora_inicial + "</strong> e horário de retorno às <strong>" + hora_final + "</strong>";
            $('.titulo-info').html(info);
            $('#tipo_ausencia').val('2horas');
            $('#modalNotificacoes').modal('toggle');
            $('#modal-atividade-externa').modal('show');
            $('#descricao_atividade').val('');
        });

        $('.btnAusencia').on('click', function () {
            $('#modalNotificacoes').modal('toggle');
            fk_log = $(this).data('id');
            campo = $(this);
            swal({
                title: "Atenção!",
                text: "Essa ação marcará como ausência não justificada. Proceder?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3CB371",
                confirmButtonText: "<?php echo Yii::t('smith', 'Sim') ?>",
                cancelButtonText: "<?php echo Yii::t('smith', 'Não') ?>",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    method: "POST",
                    url: baseUrl + '/LogAtividade/AusenciaNaoJustificada',
                    data: {fk_log: fk_log}
                }).done(function (retorno) {
                    if (retorno == "success") {
                        var row = campo.closest('tr');
                        var nRow = row[0];
                        $('#tabela2Horas').dataTable().fnDeleteRow(nRow);
                        swal("Sucesso!", "Ausência não justificada cadastrada.", "", "success");
                        $('#modalNotificacoes').modal('toggle');
                    }
                    else
                        swal("Erro!", "Ocorreu um erro ao tentar cadastrar uma ausência não justificada", "", "error");
                })
            });
        });
    });
</script>