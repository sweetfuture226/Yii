<div class="top-nav ">
    <ul class="nav pull-right top-menu">
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="" style="display: none">
                <?php switch (Yii::app()->language) {
                    case 'pt':
                    case 'pt_br':
                        echo '<span class="username"><img src="' . Yii::app()->theme->baseUrl . '/images/br.png" class="lang" alt="br" title="Português" /></span>';
                        break;
                    case 'en':
                        echo '<span class="username"><img src="' . Yii::app()->theme->baseUrl . '/images/en.png" class="lang" alt="en" title="English" /></span>';
                        break;
                    case 'es':
                        echo '<span class="username"><img src="' . Yii::app()->theme->baseUrl . '/images/es.png" class="lang" alt="es" title="Espanhol" /></span>';
                        break;
                } ?>


                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended" style="width: 20px !important">
                <div class="log-arrow-up"></div>
                <li style="width: 100% ; text-align: center"><a
                        href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/pt"><img
                            src="<?php echo Yii::app()->theme->baseUrl; ?>/images/br.png" class="lang" alt="br"
                            title="Português"/></a></li>
                <li style="width: 100% ; text-align: center"><a
                        href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/en"><img
                            src="<?php echo Yii::app()->theme->baseUrl; ?>/images/en.png" class="lang" alt="en"
                            title="English"/></a></li>
                <li style="width: 100% ; text-align: center"><a
                        href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/es"><img
                            src="<?php echo Yii::app()->theme->baseUrl; ?>/images/es.png" class="lang" alt="es"
                            title="Espanhol"/></a></li>
            </ul>
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="" style="display: none">
                <i class="icon-user"></i>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout" style="width: 160px !important">
                <div class="log-arrow-up"></div>
                <li style="width: 100%">
                    <a data-toggle="modal" href="#">
                        <?= Yii::app()->user->name ?>
                    </a>
                </li>
                <li style="width: 100%">
                    <a data-toggle="modal" href="#reportarErro">
                        <i class=" icon-exclamation-sign"></i><?= Yii::t('smith', 'Reportar erro') ?>
                    </a>
                </li>
                <li style="width: 100%">
                    <a href="<?php echo Yii::app()->createUrl('userGroups/user/profile', array('id' => Yii::app()->user->id)); ?>">
                        <i class=" icon-suitcase"></i> <?= Yii::t('smith', 'Perfil') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/logout">
                        <i class="icon-key"></i> <?= Yii::t('smith', 'Sair') ?>
                    </a>
                </li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
</div>