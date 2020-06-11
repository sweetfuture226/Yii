<form class="form valid" id="usuario-form" enctype="multipart/form-data">
    <div class="modal" id="reportarErro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><?= Yii::t('smith', 'Contato') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="notifications" style="margin-bottom: 5px; margin-left: 15px; width: 94.5%;">
                        <div class="alert alert-success" style="margin-bottom: 0px !important; display: none;" id="success-reportar">
                            <?= Yii::t('smith', 'Email enviado com sucesso') ?>!
                        </div>
                        <div class="alert alert-danger" style="margin-bottom: 0px !important; display: none;" id="error-reportar">
                            <?= Yii::t('smith', 'Descrição e anexo são obrigatórios') ?>!
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-group  col-lg-12">
                        <p>
                        <textarea rows="4" cols="50"  class="form-control" name="contato" id="contato" type="text" style="resize: none"> </textarea>
                        </p>
                    </div>
                    <div class="form-group  col-lg-4">
                        <?php echo CHtml::hiddenField('nameFile', '', array('id'=>'nameFile')); ?>
                        <?php $this->widget('ext.EAjaxUpload.EAjaxUpload', array( 'id'=>'uploadFile',
                            'config'=>array(
                                'action'=>Yii::app()->createUrl('usuario/contato'),
                                'template'=>'<div class="qq-uploader"><div class="qq-upload-drop-area"><span>Drop files here to upload</span></div><div class="qq-upload-button">Anexar arquivo</div><ul class="qq-upload-list"></ul></div>',
                                'allowedExtensions'=>array("jpg","jpeg","png"),//array("jpg","jpeg","gif","exe","mov" and etc...
                                'sizeLimit'=>10*1024*1024,// maximum file size in bytes
                                'minSizeLimit'=>1,// minimum file size in bytes
                                'onComplete'=>"js:function(id, fileName, responseJSON){"
                                    . " $('#nameFile').val(responseJSON.filename);"
                                    ."}",
                            )
                        )); ?>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <!--<input id="enviarEmail" class="btn btn-info submitForm" type="submit" name="yt1" value="Enviar">-->
                    <button class="btn btn-info submitForm" type="button" onclick="enviarEmail()"><?= Yii::t('smith', 'Enviar') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
