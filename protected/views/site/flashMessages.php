<?php if(Yii::app()->user->hasFlash('error')): ?>    
            <div class="notifications" style="float: left; width: 50%; margin: 5px 0 0 40px;">
                <div class="alert alert-block alert-danger fade in" style="margin-bottom: 0px !important">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="icon-exclamation-sign"></i> <?= Yii::t('smith', 'ERRO') ?>:</strong> <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(Yii::app()->user->hasFlash('success')): ?>
            <div class="notifications" style="float: left; width: 50%; margin: 5px 0 0 80px;">
                <div class="alert alert-success fade in" style="margin-bottom: 0px !important">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="icon-ok-sign"></i></strong> <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(Yii::app()->user->hasFlash('warning')): ?>    
            <div class="notifications" style="float: left; width: 50%; margin: 5px 0 0 80px;">
                <div class="alert alert-block alert-warning fade in" style="margin-bottom: 0px !important">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="icon-info-sign"></i> <?php echo Yii::t("smith", 'Info:'); ?></strong> <?php echo Yii::app()->user->getFlash('warning'); ?>
                </div>
            </div>
        <?php endif; ?>

<!--Notification Start -->
<div class="notifications" style="float: left;">
    <div  id="notificationSenha" class="alert alert-success fade " style="display: none; margin-bottom: 0px !important">

        <strong><i class="icon-ok-sign"></i> <?=Yii::t('wizard','Senha alterada com sucesso!')?></strong>
    </div>
</div>
<!--Notification End -->

<!--Notification Start -->
<div class="notifications" style="float: left;">
    <div  id="notificationGeral" class="alert fade" style="margin-bottom: 0px !important">
        <strong></strong>
    </div>
</div>
<!--Notification End -->