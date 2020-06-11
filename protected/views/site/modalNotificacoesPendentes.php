<?php $usuario = NotificacoesPendencias::getNotificacoes(); ?>

<div class="modal" id="modalNotificacoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Central de notificações') . ' (<label id="numerador">1</label> de <label>' . count($ntcPendentes) . '</label>)' ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="qtd" value="<?php echo count($ntcPendentes) ?>">
                <input type="hidden" id="identificador" value="0">
                <div style="clear: both"></div>
                <div class="row" id="o" style="margin-bottom: -6%">

                    <?php $i = 0;
                    foreach ($ntcPendentes as $key => $value) { ?>

                    <?php if ($i == 0) { ?>
                    <div class="form-group  col-lg-12 firts" data-visible="<?= count($value) > 2 ? $value[2] : ""; ?>"
                         id="div_<?php echo $i ?>">
                        <div class="divTeste" style="margin-top: -2%">
                            <div>
                                <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true"
                                        id="fechar"><?= Yii::t('smith', 'Fechar notificações') ?></button>
                                <?php }else{ ?>
                                <div class="form-group  col-lg-12 divs"
                                     data-visible="<?= count($value) > 2 ? $value[2] : ""; ?>"
                                     id="div_<?php echo $i ?>">
                                    <div class="divTeste" style="margin-top: -2%">
                                        <div>
                                            <?php } ?>

                                            <?php $this->renderPartial($value[0], array('value' => $value[1]));
                                            $i++;
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <?php } ?>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success submitForm btnTrocarSenha" type="button"
                                    onclick="alterarSenha();"><?= Yii::t('smith', 'Salvar') ?></button>
                            <button class="btn btn-primary submitForm" type="button" id="btnAnterior"
                                    disabled="disabled"><?= Yii::t('smith', 'Anterior') ?></button>
                            <button class="btn btn-primary submitForm" type="button"
                                    id="btnProximo"><?= Yii::t('smith', 'Próximo') ?></button>
                            <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true"
                                    id="fechar"><?= Yii::t('smith', 'Fechar notificações') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).on('click', ".cancel", function () {
                    $('#modalNotificacoes').modal('toggle');
                });
                $(document).ready(function () {
                    $("#fechar").hide();
                    $(".tableNotificacoes").dataTable({
                        "pageLength": 5,
                        "lengthMenu": [[5, 10, 25], [5, 10, 25]],
                        "bInfo": true,
                        "language": {
                            "info": "Exibindo _START_ até _END_ de _MAX_ resultados"
                        }
                    });
                    $('.tableNotificacoes div.dataTables_info').css('display', 'block');
                    $("#btnProximo").on('click', function () {
                        showDiv();
                    });
                    $("#btnAnterior").on('click', function () {
                        hideDiv();
                    });
                    $(".divs").each(function (index) {
                        $(this).hide();
                    });
                    if ($(".firts").data('visible') == "div_senha")
                        $(".btnTrocarSenha").show();
                    else
                        $(".btnTrocarSenha").hide();
                    $('#modalNotificacoes').modal('toggle');
                });

                function showDiv() {
                    $('div.dataTables_info').css('display', 'block');
                    var identificador = $("#identificador").val();
                    $("#div_" + identificador).hide();
                    identificador++;
                    $("#numerador").html(eval(parseInt(identificador) + 1));
                    $("#identificador").val(identificador);
                    $("#btnAnterior").attr('disabled', false);
                    if (identificador == (parseInt($("#qtd").val()) - 1)) {
                        $("#btnProximo").attr('disabled', true);
                        $("#fechar").show();
                    }
                    if ($("#div_" + identificador).data('visible') == "div_senha")
                        $(".btnTrocarSenha").show();
                    else
                        $(".btnTrocarSenha").hide();

                    $("#div_" + identificador).show();
                }

                function hideDiv() {
                    var identificador = $("#identificador").val();
                    if (identificador < $("#qtd").val())
                        $("#btnProximo").attr('disabled', false);
                    $("#div_" + identificador).hide();
                    identificador--;
                    $("#numerador").html(eval(parseInt(identificador) + 1));
                    $("#identificador").val(identificador);
                    if (identificador == 0)
                        $("#btnAnterior").attr('disabled', true);
                    if ($("#div_" + identificador).data('visible') == "div_senha")
                        $(".btnTrocarSenha").show()
                    else
                        $(".btnTrocarSenha").hide();
                    $("#div_" + identificador).show();
                }

                function alterarSenha() {
                    var valid = false;
                    var password = $('#password').val();
                    var password_again = $('#password_again').val();
                    if ((password == password_again) && (password != "") && (password_again != "")) {

                        $.ajax({
                            url: baseUrl + "/usuario/alterarSenha?senha=" + $("#password").val(),
                            type: 'POST',
                            dataType: "json",
                            success: function (data) {
                                if (data.resposta == "sucesso") {
                                    swal("Senha alterada com sucesso.", "", "success");
                                }
                                else {
                                    $('#password').val('');
                                    $('#password_again').val('');
                                    swal("Atenção", '<?php echo Yii::t('smith', 'A senha digitada não obedece as critérios de seguraça, por favor tente novamente.') ?>', "error");
                                }

                            },
                            error: function (xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");

                            }
                        });
                    }
                    else {
                        swal("<?php echo Yii::t('smith', 'Por favor, verifique se a senha foi digitada corretamente') ?>", "", "warning");
                        valid = false;
                    }
                    return valid;
                }
            </script>