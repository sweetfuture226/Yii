<div class="modal" id="modalNovoDocumento" abindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo Yii::t('smith', 'Cadastrar novo documento') ?></h4>
            </div>
            <div class="modal-body">
                <form action="#">
                    <input type='hidden' id='nome_documento_' value=''><input type='hidden' id='id_documento_' value=''>
                    <input type='hidden' id='row_documento' value=''>
                    <div class="form-group  col-lg-12">
                        <p> <?php echo Yii::t('smith', 'Contrato') ?> </p>
                        <select class='form-control chzn-select chzn-done' id='contratos_' name='tipoLicenca'></select>
                    </div>
                    <div class="form-group  col-lg-12">
                        <p> <?php echo Yii::t('smith', 'Documento') ?> </p>
                        <input class='form-control' id='documento_' name='tipoLicenca' type="text">
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Disciplina') ?>  </p>
                        <?php echo CHtml::dropdownlist('disciplina', 'disciplina', CHtml::listData(Disciplina::model()->findAll(array('order' => 'TRIM(codigo) ASC', 'condition' => 'fk_empresa = :empresa', 'params' => array(':empresa' => MetodosGerais::getEmpresaId()))), 'id', 'codigo'), array("class" => "form-control chzn-select obg"));
                        ?>
                    </div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Tempo previsto') ?> <a class="" title=""><i
                                    class="fa fa-question-circle" style="color: blue" data-toggle="tooltip"
                                    title="Informar tempo planejado para a conclusão deste documento."></i> </a></p>
                        <input class='form-control previstoHHM' id='tempo_previsto_documento' name='tipoLicenca'
                               type="text">
                    </div>

                    <div style="clear: both"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default cancel"
                        type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>
                <button class="btn btn-success submitForm salvarNovoDocumento"
                        type="button"><?= Yii::t('smith', 'Salvar') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".salvarNovoDocumento").on('click', function () {
            if (validarDados()) {
                swal({
                    text: "As informações inseridas demandam a criação do documento <strong>" + $("#documento_").val() + "</strong>, com duração de <strong>" + $("#tempo_previsto_documento").val() + "</strong>, associado ao projeto <strong>" + $("#contratos_ option:selected").text() + "</strong>. Proceder?",
                    title: "Atenção!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#00a651;",
                    cancelButtonText: "Não",
                    confirmButtonText: "Sim",
                    html: true,
                    closeOnConfirm: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + '/Documento/CreateFromAjax',
                            data: {
                                fk_disciplina: $("#disciplina").val(),
                                tempo: $("#tempo_previsto_documento").val(),
                                fk_contrato: $("#contratos_").val(),
                                nome_documento: $("#documento_").val(),
                                documento_sem_contrato_id: $("#id_documento_").val()
                            }
                        }).done(function (retorno) {
                            if (retorno == "success") {
                                var row = $("#row_documento").val();
                                var nRow = $("#table-documentoSemContrato tbody tr:eq(" + row + ")")[0];
                                $('#table-documentoSemContrato').dataTable().fnDeleteRow(nRow);
                                $('#modalNovoDocumento').modal('toggle');
                                $('#modalNotificacoes').modal('toggle');
                                swal("Sucesso!", "<?php echo Yii::t('smith', 'Documento cadastrado.')?>", "success");
                            }
                            else
                                swal("Erro!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar cadastrar documento')?>", "error");
                        });
                    }
                    else
                        $('#modalNotificacoes').modal('toggle');
                });
            }
            else {
                swal("Atenção!", "<?php echo Yii::t('smith', 'Todos os campos são obrigatórios')?>", "error");
            }
        });
    });

    function verificaDocumento() {
        var flag = false;
        $.ajax({
            type: 'POST',
            url: baseUrl + '/Documento/CompareDocumento',
            data: {contrato: $("#contratos_").val(), documento: $("#documento_").val()},
            async: false,
            success: function (data) {
                swal("Atenção!", "<?php echo Yii::t('smith', 'Este documento já existe na LDP do contrato.Por favor, tente com outro nome de documento.')?>", "error");
            }
        }).fail(function (xhr) {
            flag = true;
        });
        return flag;
    }

    function validarDados() {
        var flag = true;
        if ($("#contratos_").val() == '0' || $("#disciplina").val() == '0' || $("#tempo_previsto_documento").val() == "")
            flag = false;
        else
            flag = verificaDocumento();
        return flag;
    }
</script>