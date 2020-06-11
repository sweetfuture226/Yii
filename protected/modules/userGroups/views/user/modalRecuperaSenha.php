<form class="form valid" id="recupera-form"  method="post">
    <div class="modal fade" id="recuperarSenha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" style="text-align: left"><?= Yii::t('smith', 'Recuperar senha') ?></h4>
                </div>
                <div class="modal-body">
                    <!--Notification Start -->
                    <div class="notifications" style="float: left;">
                        <div  id="notification" class="alert  fade " style="margin-bottom: 0px !important">

                            <span id="mensagem"><strong><i class="icon-ok-sign"></i> </strong></span>
                        </div>
                    </div>
                    <!--Notification End -->
                    <div class="form-group  col-lg-12">
                        <p style="text-align: left; color:#797979 ">
                            <label for="UserGroupsUser_email"><?= Yii::t('smith', 'Email')?></label>
                            <input size="60" maxlength="255" class="form-control valid" name="email" id="email" type="text">
                        </p>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button"><?= Yii::t('smith', 'Fechar')?></button>
                    <input class="btn btn-info submitForm" type="button" name="enviarEmail" id="enviarEmail"  value="<?= Yii::t('smith', 'Enviar')?>">
                </div>
            </div>
        </div>
    </div>
</form>



<script>
    $("#enviarEmail").click(function(){
        var baseurl="<?php print Yii::app()->request->baseUrl;?>";
        $.ajax({
            url: baseurl + '/Usuario/recuperaSenha',
            type: 'POST',
            data: $("#recupera-form").serialize(),
            success: function(data){
                if(data == "ok"){
                    document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith','Senha enviada para o email informado'); ?>";
                    $("#notification").removeClass('in alert-danger');
                    $("#notification").addClass('in alert-success ');
                }
                else{
                    document.getElementById('mensagem').innerHTML = "<?php echo Yii::t('smith','Email não encontrado. Favor informar outro e-mail ou entrar em contato com suporte.'); ?>";
                    $("#notification").removeClass('in alert-success');
                    $("#notification").addClass('in alert-block alert-danger');
                }
            }

        });
    });
</script>
