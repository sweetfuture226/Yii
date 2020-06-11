<?php
$changes = Notificacao::model()->getChangeLog();
?>

<div style="display: none">
    <a id="modalChangeLog" data-toggle="modal" href="#changeLog">
        <i class=" icon-exclamation-sign"></i><?= Yii::t('smith', 'Reportar erro') ?>
    </a>
</div>

<form class="form valid" id="changeLog-form">
    <div class="modal" id="changeLog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><?= Yii::t('smith', 'Novas funcionalidades') ?></h4>
                </div>
                <div class="modal-body">
                    <?php
                    if(!empty($changes))
                        foreach($changes['id'] as $id){
                            echo "<input name='change.$id' type='hidden' value='$id'>";

                        } ?>
<pre>
<?=$changes['texto']?>
</pre>

                    <div style="clear: both"></div>
                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <!--<input id="enviarEmail" class="btn btn-info submitForm" type="submit" name="yt1" value="Enviar">-->
                    <button class="btn btn-info submitForm" type="button"  data-dismiss="modal"><?= Yii::t('smith', 'Ver mais tarde') ?></button>
                    <button class="btn btn-success submitForm" type="button" onclick="lido();">Ok</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
if (!empty($changes) && Yii::app()->controller->id == "dashboard" && Yii::app()->controller->action->id == 'index') {
?>
    <script>
        $(document).ready(function(){

            $('#changeLog').modal('show');

        });

        function lido(){
            $.ajax({
                type: 'POST',
                //dataType: 'JSON',
                data: $('#changeLog-form').serialize(),
                url: baseUrl+'/usuario/changeLido',
                success: function(data){
                    $('#changeLog').modal('hide');

                }
            });
        }
    </script>
<?php } ?>