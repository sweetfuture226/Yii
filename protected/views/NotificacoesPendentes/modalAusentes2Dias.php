<h4> <?php echo Yii::t('smith', 'Verificação - Ausência do computador por período superior a 2 dias.') ?></h4>
<p style="text-align: justify"><?php echo Yii::t('smith', 'Os usuários abaixo apresentaram ausência do computador (tempo ocioso ou computador desligado) por um período superior a 2 dias. Cadastrar como atividade externa [ex: viagem], licença [férias, suspensão, demissão, etc.], questões técnicas [formatação do computador, instalação de novo antivírus, etc.] ou ausência não justificada.') ?></p>
<table id="table-2dias" class="table table-bordered datatable dataTable tableNotificacoes">
    <thead>
    <tr>
        <th style="width: 34% !important; background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Colaborador') ?></strong></th>
        <th style="width: 17% !important; background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Data Inicial') ?></strong></th>
        <th style="width: 17% !important; background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Data Final') ?></strong></th>
        <th style="width: 38% !important; background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Ações') ?></strong></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($value as $obj) {
        foreach ($obj as $item) {
            echo '<tr><td>' . $item['nome'] . '</td><td>' . MetodosGerais::dataBrasileira($item['data_inicial']) . '</td><td>' . MetodosGerais::dataBrasileira($item['data_final']) . '</td><td width="22%"><a href="#" class="btn btn-success btnAtividadeExterna2Dias" 
            data-id="' . $item['id'] . '" data-ad="' . $item['nome'] . '" data-data_inicial="' . MetodosGerais::dataBrasileira($item['data_inicial']) . '" data-data_final="' . MetodosGerais::dataBrasileira($item['data_final']) . '" data-toggle="tooltip" title="' . Yii::t("smith", "Cadastrar atividade externa") . '"> <i class="fa fa-plus-square" aria-hidden="true"></i> 
            </a> <a href="#" class="btn btn-primary btnLicenca" 
            data-id="' . $item['id'] . '" data-ad="' . $item['nome'] . '" data-data_inicial="' . $item['data_inicial'] . '" data-data_final="' . $item['data_final'] . '" data-toggle="tooltip" title="' . Yii::t("smith", "Adicionar uma licença a este colaborador") . '"> <i class="fa fa-hospital-o" aria-hidden="true"></i> </a>
            </a> <a href="#" class="btn btn-danger btnTecnico" 
            data-id="' . $item['id'] . '" data-ad="' . $item['nome'] . '" data-data_inicial="' . $item['data_inicial'] . '" data-data_final="' . $item['data_final'] . '" data-toggle="tooltip" title="' . Yii::t("smith", "Adicionar uma questão técnica") . '"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>
            <a href="#" class="btn btn-orange btnAusenciaNaoJustificada" 
            data-id="' . $item['id'] . '" data-data_inicial="' . MetodosGerais::dataBrasileira($item['data_inicial']) . '" data-data_final="' . MetodosGerais::dataBrasileira($item['data_final']) . '" data-toggle="tooltip" title="' . Yii::t("smith", "Ausência não justificada") . '"> <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> </a>
            </td></tr>';
        }
    } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btnAtividadeExterna2Dias").on('click', function () {
            var fk_usuario = $(this).data('id');
            var ad = $(this).data('ad');
            var data_inicial = $(this).data('data_inicial');
            var data_final = $(this).data('data_final');
            var campo = $(this);
            var row = campo.closest('tr').index();
            var column = campo.closest('td').index();
            $("#row_atividade").val(row);
            $("#column_atividade").val(column);
            var info = "<?=  Yii::t('smith', 'Favor informar a descrição da atividade externa do colaborador') ?> <strong>" + ad + "</strong> com saída no dia <strong>" + data_inicial + "</strong> e retorno no dia <strong>" + data_final + "</strong>";
            $('.titulo-info').html(info);
            $('#tipo_ausencia').val('2dias');
            $('#modalNotificacoes').modal('toggle');
            $('#modal-atividade-externa').modal('show');
            $('#descricao_atividade').val('');
            $('.dinamico-desc').addClass('col-lg-12');
            $('.dinamico-desc').removeClass('col-lg-6');
            $('.visible-2-horas').css('display', 'none');
        });
        $(".btnLicenca").on('click', function () {
            var fk_colaborador = $(this).data('id');
            var campo = $(this);
            var data_inicial = $(this).data('data_inicial');
            var data_final = $(this).data('data_final');
            var row = campo.closest('tr').index();
            var column = campo.closest('td').index();
            $("#row_justificativa_2_dias").val(row);
            $("#column_justificativa_2_dias").val(column);
            $("#tipo_justificativa").val('licenca');
            $('#modalNotificacoes').modal('toggle');

            $('#justificativa_2_dias').html("");
            $('#justificativa_2_dias').append("<option value='Demissão'><?php echo Yii::t('smith', 'Demissão') ?></option>\n\
					 <option value='Férias'><?php echo Yii::t('smith', 'Férias') ?></option>\n\
	                 <option value='Suspensão'><?php echo Yii::t('smith', 'Suspensão') ?></option>");
            $("#justificativa_2_dias").trigger('change');
            //$('#modal-justificativa-2-dias').modal('show');
            $('#modal-justificativa-ausencia').modal('show');
        });
        $(".btnTecnico").on('click', function () {
            var fk_colaborador = $(this).data('id');
            var data_inicial = $(this).data('data_inicial');
            var data_final = $(this).data('data_final');
            var campo = $(this);
            var row = campo.closest('tr').index();
            var column = campo.closest('td').index();
            //$('#justificativa_2_dias').html("");
           // $('#justificativa_2_dias').append("tipo",QuestaoTecnica::model->findAll()->tipo);

            $("#row_questao_tecnica").val(row);
            $("#column_questao_tecnica").val(column);
            $('#modalNotificacoes').modal('toggle');

            $('#modal-questao-tecnica').modal('show');
        });
        $(".btnAusenciaNaoJustificada").on('click', function () {
            var fk_colaborador = $(this).data('id');
            var data_inicial = $(this).data('data_inicial');
            var data_final = $(this).data('data_final');
            var campo = $(this);
            $('#modalNotificacoes').modal('toggle');
            showAusenciaNaoJustificada(fk_colaborador, data_inicial, data_final, campo);
        });
    });

    function showAusenciaNaoJustificada(fk_colaborador, data_inicial, data_final, campo) {
        var descricao = "Ausência não justificada.";
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
                url: baseUrl + '/ColaboradorSemProdutividade/SetJustificativa',
                data: {
                    fk_colaborador: fk_colaborador,
                    data_inicio: data_inicial,
                    data_fim: data_final,
                    descricao: descricao
                }
            }).done(function (retorno) {
                if (retorno == "success") {
                    var row = campo.closest('tr');
                    var nRow = row[0];
                    $('#table-2dias').dataTable().fnDeleteRow(nRow);
                    swal("Sucesso!", "Ausência não justificada cadastrada.", "", "success");
                    $('#modalNotificacoes').modal('toggle');
                }
                else
                    swal("Erro!", "Ocorreu um erro ao tentar cadastrar uma ausência não justificada", "", "error");
            })
        });
    }
</script>