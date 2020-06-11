<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div style="display: block" class="form-login-error ">
        <h3><?= Yii::t('smith', 'Login inválido') ?></h3>
        <p><?= Yii::t('smith', 'Usuário ou senha inválidos') ?></p>
    </div>
<?php endif; ?>
<form action="<?= Yii::app()->createUrl('userGroups') ?>" method="post" role="form" id="form_login">
    <p class="description" style="margin-bottom: 15px;">
        <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/pt" style="margin-right: 10px;"> <img
                src="themes/neon/assets/images/flag-br.png" alt="br" title="Português"/> </a>
        <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/en" style="margin-right: 10px;"><img
                src="themes/neon/assets/images/flag-uk.png" alt="en" title="Inglês"/> </a>
        <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/es"><img
                src="themes/neon/assets/images/flag-es.png" alt="es" title="Espanhol"/> </a>
    </p>
    <div class="form-group">

        <div class="input-group">
            <div class="input-group-addon">
                <i class="entypo-user"></i>
            </div>

            <input type="text" class="form-control" name="UserGroupsUser[username]" id="username" placeholder="Login"
                   autocomplete="off"/>
        </div>

    </div>

    <div class="form-group">

        <div class="input-group">
            <div class="input-group-addon">
                <i class="entypo-key"></i>
            </div>

            <input type="password" class="form-control" name="UserGroupsUser[password]" id="password"
                   placeholder="<?php echo Yii::t("smith", "Senha"); ?>" autocomplete="off"/>
        </div>

    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block btn-login">
            <i class="entypo-login"></i>
            <?php echo Yii::t("smith", "Entrar"); ?>
        </button>
    </div>

</form>

<?php $this->renderPartial('modalRecuperaSenha'); ?>


