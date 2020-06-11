    <div class="sidebar-toggle-box">
        <div title="<?= Yii::t('smith', 'Alternar exibição do menu') ?>" data-placement="right" ><i class="icon-reorder tooltips"></i></div>
    </div>
    <!--logo start-->
    <a href="<?php echo Yii::app()->getHomeUrl(); ?>" class="logo" title="<?= Yii::t('smith', 'Página inicial') ?>" >Viva<span>Smith</span></a>
    <!--logo end-->
    <?php $notificacoes = Notificacao::model()->getNotificacoes(Yii::app()->user->id); ?>
    <div class="nav notify-row" id="top_menu">
        <ul class="nav top-menu">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="icon-bell"></i>
                    <?php if(count($notificacoes) > 0) {?>
                    <span class="badge bg-warning">
                        <?php echo count($notificacoes); ?>
                    </span>
                    <?php }?>
                </a>
                <?php if (count($notificacoes) == 0) { ?>
                <ul class="dropdown-menu extended alert notification" style="width: 500px !important">
                    <div class="notify-arrow notify-arrow-yellow"></div>
                    <li><p class="yellow"><?= Yii::t('smith', 'Notificações') ?></p></li>
                    <li>
                        <a href="#" class="implantation">
                            <span><?= Yii::t('smith', 'Nenhum resultado encontrado.') ?></span>
                        </a>
                    </li>
                </ul>
                <?php }else { ?>

                <ul class="dropdown-menu extended alert notification" style="width: 500px !important">
                    <div class="notify-arrow notify-arrow-yellow"></div>
                    <li><p class="yellow"><?= Yii::t('smith', 'Notificações') ?></p></li>
                    <?php foreach ($notificacoes as $value) { ?>
                    <li>
                        <?php if ($value->tipo == Notificacao::$TP_IMPLANTATION_AFTER_DAYS) { ?>
                            <a href="#" data-id="<?= $value->id ?>" data-url="<?= Yii::app()->createUrl($value->action); ?>" class="implantation">
                            <?php } else { ?>
                                <a href="<?php echo Yii::app()->createUrl($value->action); ?>">
                                <?php } ?>
                                <span class="label label-danger" style="display: inline-block; padding: 0.8em;"><i class="icon-bell"></i></span>
                                <?php echo Yii::t("smith", $value->notificacao) ?>
                                    <span class="small italic"></span>
                            </a>
                    </li>
                    <?php }
                    } ?>
            </ul>
        </li>
    </ul>
</div>

