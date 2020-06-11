<div class="form">
    <!--Notification Start -->
    <div class="notificationsPlanilha" style="float: left;display: none">
        <div  id="notificationPlanilha" class="alert alert-success fade " style="margin-bottom: 0px !important;margin-left: 12px">

            <strong><i class="icon-ok-sign"></i><?=Yii::t('wizard','Planilha importada com sucesso!')?></strong>
        </div>
    </div>
    <div style="clear: both"></div>
    <!--Notification End -->
    <p class="note"><?=Yii::t('wizard','Importe o arquivo csv com o nome dos colaboradores e preencha todos os campos.')?></p>
    <p class="note"><?php echo CHtml::link(Yii::t("wizard", 'Clique aqui para baixar a planilha de parametrização dos colaboradores'), array('GetCsv')); ?></p>

    <div class="form-group  col-lg-4">
        <?php echo CHtml::label(Yii::t('smith', 'Arquivo CSV'),'Documento_file'); ?>
        <?php echo CHtml::hiddenField('nameFile', '', array('id'=>'nameFile')); ?>
        <!--<input type="file" name="Documento[file]" id="Documento_file">-->
        <?php $this->widget('ext.EAjaxUpload.EAjaxUpload', array( 'id'=>'uploadFile',
            'config'=>array(
                'action' => Yii::app()->createUrl('colaborador/upload'),
                'allowedExtensions'=>array("xls"),//array("jpg","jpeg","gif","exe","mov" and etc...
                'sizeLimit'=>10*1024*1024,// maximum file size in bytes
                'minSizeLimit'=>1,// minimum file size in bytes
                'onComplete'=>"js:function(id, fileName, responseJSON){"
                    . " $('#nameFile').val(responseJSON.filename);"
                    . "$('#notificationPlanilha').addClass('in');
                       $('.notificationsPlanilha').show(); "."}",
//                'messages'=>array(
//                    'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
//                    'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
//                    'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
//                    'emptyError'=>"{file} is empty, please select files again without it.",
//                    'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
//                ),
//                'showMessage'=>"js:function(message){ alert(message); }"
            )
        )); ?>
    </div>

    <div style="clear: both"></div>
</div>
