<?php
$this->pageTitle=Yii::app()->name . ' - Error ' . $code;
$this->breadcrumbs=array(
	Yii::t("smith",'Error'),
);
?>
<?php if($code == 500){ ?>
           <div class="page-error-404">
            
            <div class="error-symbol">
                <i class="entypo-traffic-cone"></i>
            </div>
            
            <div class="error-text">
                <h2>Ops!</h2>
                <p> <?= Yii::t('smith', 'Parece que aconteceu algo errado') ?>.</br>
                <a href="<?php echo Yii::app()->getHomeUrl(); ?>"><?= Yii::t('smith', 'Favor retornar à página inicial') ?></a>
            </div>
            
            <hr />
            
        </div>
<?php }elseif($code == 403){ ?>

            <div class="page-error-404">
            
            
            <div class="error-symbol">
                <i class="entypo-lock"></i>
            </div>
            
            <div class="error-text">
                <h2>403</h2>
                <h2><?= Yii::t('smith','Permissão negada!') ?></h2>
                <p> <?= Yii::t('smith', $message) ?></br></p>
            </div>
            
            <hr />
            
        </div>

<?php }elseif($code == 404){ ?>
            <div class="page-error-404">
            
            
            <div class="error-symbol">
                <i class="entypo-attention"></i>
            </div>
            
            <div class="error-text">
                <h2>404</h2>
                <p><?= Yii::t('smith','Página não encontrada!') ?></p>
                <p> <?= Yii::t('smith', $message) ?></br></p>
            </div>
            
            <hr />
            
        </div>
<?php }?>


