<div class="modal" id="modalNovoContrato" abindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo Yii::t('smith', 'Cadastrar novo contrato') ?></h4>
            </div>
            <div class="modal-body">
                <form action="#">
                    <input type='hidden' id='nome_documento_contrato' value=''><input type='hidden'
                                                                                      id='id_documento_contrato'
                                                                                      value=''>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Nome do projeto') ?> </p>
                        <input class='form-control obg' id='nome_contrato' name='tipoLicenca' type="text"
                               data-codigo="false">
                    </div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Código do projeto') ?> </p>
                        <input class='form-control obg' id='codigo_contrato_' name='codigo' type="text"
                               data-nome="codigo" data-codigo="true">
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Coordenador responsável') ?> </p>
                        <?php echo CHtml::dropdownlist('Coordenador', 'Coordenador', CHtml::listData(UserGroupsUser::model()->findAll(array('order' => 'nome', 'condition' => ' fk_empresa = ' . MetodosGerais::getEmpresaId() . ' AND username not like "%admin%"')), 'id',
                            'nome'), array("class" => "form-control chzn-select obg"));
                        ?>
                    </div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Valor estimado') ?> </p>
                        <input class='form-control obg valor' id='valor_contrato_' name='tipoLicenca' type="text">
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Moeda') ?> </p>
                        <?php echo CHtml::dropDownList('moeda', 'moeda', array(
                            'BRL' => 'Real',
                            'EUR' => 'Euro',
                            'USD' => 'Dólar'
                        ), array("class" => "form-control chzn-select obg", "style" => "width:100%;")); ?>
                    </div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Data Inicial') ?> </p>
                        <input class='date form-control obg' id='data_inicial_contrato' name='tipoLicenca' type="text">
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Data Final') ?> </p>
                        <input class='date form-control obg' id='data_final_contrato' name='tipoLicenca' type="text">
                    </div>
                    <div class="form-group  col-lg-6">
                        <p>
                            <strong><?php echo Yii::t('smith', 'Enviar e-mail para coordenador do projeto com o resumo semanal') ?></strong>
                        </p>
                        <?php echo CHtml::checkBox('receber_email', 'receber_email', array('checked' => 'checked')); ?>
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Nome do documento') ?> </p>
                        <input class='form-control obg' id='documento_com_contrato' name='tipoLicenca' type="text">
                    </div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Disciplina') ?> </p>
                        <?php echo CHtml::dropdownlist('nome_disciplina', 'nome_disciplina', CHtml::listData(Disciplina::model()->findAll(array('order' => 'TRIM(codigo) ASC', 'condition' => 'fk_empresa = :empresa', 'params' => array(':empresa' => MetodosGerais::getEmpresaId()))), 'id', 'codigo'), array("class" => "form-control chzn-select obg"));
                        ?>
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-6">
                        <p> <?php echo Yii::t('smith', 'Tempo previsto') ?> <a class="" title=""><i
                                    class="fa fa-question-circle" style="color: blue" data-toggle="tooltip"
                                    title="Informar tempo planejado para a conclusão deste documento."></i> </a></p>
                        <input class='form-control obg previstoHM' id='tempo_previsto_novo_doc' name='tipoLicenca'
                               type="text">
                    </div>
                    <div style="clear: both"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default cancel"
                        type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>
                <button class="btn btn-success submitForm salvarContrato"
                        type="button"><?= Yii::t('smith', 'Salvar') ?></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#codigo_contrato_').tokenfield({createTokensOnBlur: true});
        $('#codigo_contrato_').on('tokenfield:createtoken', function (event) {
            var existingTokens = $(this).tokenfield('getTokens');
            $.each(existingTokens, function (index, token) {
                if (token.value === event.attrs.value)
                    event.preventDefault();
            });
        });

    });


    $(".salvarContrato").on('click', function () {
        var valido = validarCampos();
        if (valido == true) {
            Salvar();
        } else {
            if (valido == 'data-invalida')
                swal("Atenção!", "<?php echo Yii::t('smith', 'A data inicial deve ser menor que a data final.') ?>", "error");
            else
                swal("Atenção!", "<?php echo Yii::t('smith', 'Todos os campos são obrigatórios.') ?>", "error");
        }
    });


    function validarCampos() {
        var inicio = $('#data_inicial_contrato').val();
        var fim = $('#data_final_contrato').val();

        var flag = true;
        $("#codigo_contrato_").parent().css('border-color', 'green');
        $(".obg").each(function (index) {
            $(this).css('border-color', 'green');
            if (!($(this).is('div'))) {
                if ($(this).val() == "") {
                    $(this).css('border-color', 'red');
                    flag = false;
                    if ($(this).data('nome') == "codigo")
                        $("#codigo_contrato_").parent().css('border-color', 'red');
                }
            }
        });
        if (!checkDateRange(inicio, fim) && inicio != '' && fim != '') {
            flag = 'data-invalida';
        }
        return flag;
    }

    function checkDateRange(start, end) {
        var start2 = start.split("/");
        var end2 = end.split("/");
        var data = new Date();
        var hoje = new Date(data.getFullYear(), data.getMonth(), data.getDate());
        start2 = new Date(start2[2], start2[1] - 1, start2[0]);
        end2 = new Date(end2[2], end2[1] - 1, end2[0]);
        if (isNaN(start2)) {
            return false;
        }
        if (isNaN(end2)) {
            return false;
        }
        if (end2 < start2) {
            return false;
        }
        return true;
    }


    function Salvar() {
        $.ajax({
            method: "POST",
            url: baseUrl + '/Contrato/CreateFromAjax',
            data: {
                nome: $("#nome_contrato").val(),
                codigo: $("#codigo_contrato_").val(),
                coordenador: $("#Coordenador").val(),
                valor: $("#valor_contrato_").val(),
                moeda: $("#moeda").val(),
                data_inicial: $("#data_inicial_contrato").val(),
                data_final: $("#data_final_contrato").val(),
                novoDocId: $("#id_documento_contrato").val(),
                novoDocNome: $("#documento_com_contrato").val(),
                fk_disciplina: $("#nome_disciplina").val(),
                tempo: $("#tempo_previsto_novo_doc").val()
            }
        }).done(function (retorno) {
            if (retorno == "success") {
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Projeto e documento cadastrado.') ?>", "success");
                console.log(objCampo);
                var row = $("#row_documento").val();
                var nRow = $("#table-documentoSemContrato tbody tr:eq(" + row + ")")[0];
                $('#table-documentoSemContrato').dataTable().fnDeleteRow(nRow);
                $('#modalNovoContrato').modal('toggle');
                $('#modalNotificacoes').modal('toggle');
            } else {
                swal("Erro!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar cadastrar Contrato e Documento') ?>", "error");
            }
        });
    }
</script>