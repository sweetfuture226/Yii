<form action="<?= Yii::app()->createUrl('userGroups') ?>" accept-charset="utf-8" method="post">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" class="form-control" name="UserGroupsUser[username]" placeholder="Login">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
            <input type="password" class="form-control" name="UserGroupsUser[password]" placeholder="<?php echo Yii::t("smith", "Senha"); ?>">
        </div>
    </div>
    <a style="margin-top: 20px" data-toggle="modal" href="#recuperarSenha"> <?= Yii::t('smith', 'Esqueci minha senha')?></a>
    <div class="row">
        <div class="col-xs-6 text-left">
            <div data-toggle="buttons">

            </div>
        </div>
        <div class="col-xs-6 text-right">
            <button class="btn btn-primary" type="submit"> <?php echo Yii::t("smith", "Entrar"); ?></button>
        </div>
    </div>
</form>

 <?php $this->renderPartial('modalRecuperaSenha'); ?>